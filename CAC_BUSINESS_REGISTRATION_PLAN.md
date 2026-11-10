# CAC Business Registration Service — Implementation Plan & Architecture

> **Document Version:** 1.0  
> **Status:** Architecture Approved / Future Implementation Roadmap  
> **Target Module:** AffanHub Identity Services (`Identity Services` navigation group)

---

## 1. Executive Summary

Corporate Affairs Commission (CAC) business registration is one of the highest-demand legal/identity services for Nigerian entrepreneurs, SMEs, kiosks, and digital business owners.

By adding CAC Registration to AffanHub, merchants can offer official business registrations to their storefront customers. This plan introduces a **Dual-Fulfillment Architecture**:
1. **Fulfilled by AffanHub (Default / Recommended):** AffanHub's accredited CAC agents handle all portal filing, documentation, and query resolution. The merchant simply sets their profit markup and earns passive revenue.
2. **Self-Fulfilled by Merchant:** Merchants who are already accredited CAC agents (or have their own filing partners) can handle applications themselves, keeping 100% of customer payments.

---

## 2. Dual-Fulfillment Business & Financial Model

### Model A: Fulfilled by AffanHub (Wholesale + Merchant Markup)
* **Target Merchant:** Merchants with no CAC accredited agent license who want hands-off profits.
* **Pricing Flow:**
  - **Super Admin** sets the **Wholesale Base Cost** (e.g. ₦16,000 for Business Name).
  - **Merchant** sets their **Customer Selling Price** (e.g. ₦22,000). Minimum price is enforced (cannot be below base cost).
  - When customer pays ₦22,000:
    - ₦16,000 covers the platform filing cost.
    - **₦6,000 instant profit** is credited to the Merchant's store wallet.
* **Order Routing:** The order routes to the **Super Admin Panel** queue (`Admin ➔ CAC Orders`). AffanHub's team processes and uploads the certificate.

### Model B: Self-Fulfilled by Merchant (Direct Fulfillment)
* **Target Merchant:** Merchants who are licensed CAC agents or have an in-house filing agent.
* **Pricing Flow:**
  - Merchant sets 100% of their selling price (e.g. ₦25,000).
  - Customer pays ₦25,000 directly into the Merchant's store revenue.
  - Zero base fee is deducted by AffanHub.
* **Order Routing:** The order appears **only in the Merchant Panel** (`Merchant ➔ CAC Orders`). AffanHub Admin does not process this order. The merchant handles CAC filing, queries, and uploads the certificate.

---

## 3. Supported CAC Packages

| Package Code | Package Name | Target Audience | Typical Platform Base | Default Turnaround |
| :--- | :--- | :--- | :--- | :--- |
| `business_name` | **Business Name (Sole Proprietorship / Enterprise)** | Retailers, freelancers, POS agents, e-commerce stores | ~₦16,000 | 2 – 5 Business Days |
| `private_ltd` | **Company Registration (Private Limited Company - LTD)** | Startups, corporations, tech companies, contracts | ~₦40,000 | 5 – 7 Business Days |
| `incorporated_trustees` | **Incorporated Trustees (NGO / Church / Mosque / Club)** | Non-profits, religious bodies, associations, charities | ~₦65,000 | 1 – 2 Weeks |
| `annual_returns` | **Annual Returns Filing & Status Report** | Existing registered businesses maintaining active status | ~₦7,500 | 1 – 3 Business Days |

---

## 4. Required Customer Inputs & Document Uploads

### Form Fields:
1. **Proposed Names:**
   - Option 1 (Primary Name Choice, e.g. *"Apex Digital Services"*)
   - Option 2 (Alternative Name Choice, e.g. *"Apex Digital Ventures"*)
2. **Business Details:**
   - Nature of Business / Activity Category (e.g., General Contracts, ICT & Telecoms, Agriculture)
   - Detailed Description of Business Operations
   - Official Business Physical Address (Street, LGA, State)
   - Business Email & Phone Number
3. **Proprietor / Director Information:**
   - Full Legal Name (Must match government ID)
   - Date of Birth & Gender
   - National Identification Number (NIN)
   - Phone Number & Email Address
   - Residential Address (Street, LGA, State)
   - Occupation
   - *(For LTD Companies)*: Share Capital allotment & Secondary Director details.

### Required Document Uploads:
- **Valid Government ID:** Clear photo or PDF of NIN Slip, International Passport, Driver's License, or Voter's Card.
- **Passport Photograph:** Clear white background headshot.
- **Official Signature:** Signature drawn on a clean white sheet of paper and photographed/scanned.
- *(For Annual Returns)*: Existing CAC Certificate copy or BN/RC Number.

---

## 5. Database Schema & Architecture

### Table 1: `cac_services` (Global Packages managed by Super Admin)
```sql
CREATE TABLE `cac_services` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(100) UNIQUE NOT NULL,
    `description` TEXT NULL,
    `base_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `min_selling_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `default_processing_time` VARCHAR(100) NOT NULL DEFAULT '2 - 5 Business Days',
    `fields_schema` JSON NULL,
    `required_documents` JSON NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
```

### Table 2: `store_cac_services` (Store-Specific Pricing & Fulfillment Mode)
```sql
CREATE TABLE `store_cac_services` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `store_id` BIGINT UNSIGNED NOT NULL,
    `cac_service_id` BIGINT UNSIGNED NOT NULL,
    `fulfillment_type` ENUM('affanhub', 'self') NOT NULL DEFAULT 'affanhub',
    `selling_price` DECIMAL(12, 2) NOT NULL,
    `processing_time` VARCHAR(100) NOT NULL DEFAULT '2 - 5 Business Days',
    `instructions` TEXT NULL,
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`store_id`) REFERENCES `stores`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cac_service_id`) REFERENCES `cac_services`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `store_cac_unique` (`store_id`, `cac_service_id`)
);
```

### Table 3: `cac_requests` (Customer Application Orders)
```sql
CREATE TABLE `cac_requests` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `reference` VARCHAR(50) UNIQUE NOT NULL,
    `store_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `cac_service_id` BIGINT UNSIGNED NOT NULL,
    `store_cac_service_id` BIGINT UNSIGNED NULL,
    `fulfillment_type` ENUM('affanhub', 'self') NOT NULL DEFAULT 'affanhub',
    
    -- Business Information
    `proposed_name_1` VARCHAR(255) NOT NULL,
    `proposed_name_2` VARCHAR(255) NOT NULL,
    `approved_name` VARCHAR(255) NULL,
    `nature_of_business` VARCHAR(255) NOT NULL,
    `business_description` TEXT NULL,
    `business_address` TEXT NOT NULL,
    `business_email` VARCHAR(255) NOT NULL,
    `business_phone` VARCHAR(50) NOT NULL,
    
    -- Director / Proprietor Details (stored as JSON for single/multiple directors)
    `directors_data` JSON NOT NULL,
    
    -- File Upload Paths (stored on private disk)
    `uploaded_documents` JSON NOT NULL,
    
    -- Financials
    `amount_paid` DECIMAL(12, 2) NOT NULL,
    `platform_cost` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `merchant_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    
    -- Workflow Status
    `status` ENUM('pending', 'processing', 'queried', 'completed', 'rejected') NOT NULL DEFAULT 'pending',
    `query_reason` TEXT NULL,
    `admin_notes` TEXT NULL,
    
    -- Output Deliverables
    `certificate_pdf_path` VARCHAR(255) NULL,
    `status_report_pdf_path` VARCHAR(255) NULL,
    `rc_bn_number` VARCHAR(50) NULL,
    `tin_number` VARCHAR(50) NULL,
    
    `completed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`store_id`) REFERENCES `stores`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cac_service_id`) REFERENCES `cac_services`(`id`) ON DELETE CASCADE
);
```

---

## 6. Filament Panel Design

### Merchant Panel (`app/Filament/Merchant/Resources`)

1. **`StoreCacServiceResource.php` (`Identity Services ➔ CAC Pricing`):**
   - **Fulfillment Preference:** Radio button between `Fulfilled by AffanHub` and `Self-Fulfilled`.
   - **Dynamic Profit Calculator:**
     - When *Fulfilled by AffanHub* is chosen: Displays `AffanHub Base (₦16,000) + Your Price (₦22,000) = Your Profit (₦6,000)`.
     - Validates that Selling Price $\ge$ Base Cost.
     - When *Self-Fulfilled* is chosen: Displays `You keep 100% of selling price`.
   - Toggle to turn individual packages on/off.

2. **`CacRequestResource.php` (`Identity Services ➔ CAC Orders`):**
   - Shows badge count of active orders (`pending` / `processing`).
   - Table columns: Reference, Customer, Package, Proposed Name, Paid Amount, Fulfillment Mode, Status.
   - **Conditional Actions:**
     - **If AffanHub Fulfilled:** Read-only tracking modal showing filing status and download buttons for certificate once completed by AffanHub.
     - **If Self-Fulfilled:** Full action suite for merchant:
       - *"Download Client Files ZIP"*
       - *"Mark as In Progress"*
       - *"Send Query to Customer"*
       - *"Complete Registration"* (Upload Certificate PDF, Status Report PDF, BN/RC & TIN).

---

### Super Admin Panel (`app/Filament/Resources`)

1. **`CacServiceResource.php` (`Admin ➔ CAC Services`):**
   - Manage global packages, descriptions, documents required, base prices, and minimum retail pricing.

2. **`AdminCacRequestResource.php` (`Admin ➔ CAC Fulfillment Queue`):**
   - Filtered view showing **only orders where `fulfillment_type = 'affanhub'`**.
   - Tabbed queues:
     - `Pending Filing`
     - `Processing at CAC`
     - `Queried`
     - `Completed`
   - **Filing Agent Actions:**
     - Single-click **ZIP export** containing all uploaded IDs, passport photos, signature, and formatted text summary for quick filing on the CAC portal.
     - **"Issue Query" Action:** Sends query reason to customer with SMS/Email notification.
     - **"Complete & Upload Certificate" Action:** Uploads official CAC Certificate PDF, Status Report PDF, enters RC/BN and TIN, marks order completed, and notifies customer.

---

## 7. Customer Storefront Journey

1. **Storefront Landing Page (`/cac-registration`):**
   - Clean, professional landing section with package cards, transparent turnaround times, and prerequisites.
2. **Interactive Application Wizard:**
   - Step 1: Package Selection & Name Options (Primary & Alternate).
   - Step 2: Business Category & Contact Address.
   - Step 3: Proprietor / Director Bio-data.
   - Step 4: Secure Document Uploads (with instant image preview & format validation).
   - Step 5: Checkout & Payment Confirmation.
3. **Customer Account Portal (`/account/cac-applications`):**
   - Order history timeline.
   - Responsive status indicator: `Submitted` ➔ `Filing in Progress` ➔ `Completed`.
   - Query response form (if CAC requests an alternate name).
   - One-click downloads for official **CAC Certificate PDF** and **Status Report PDF**.

---

## 8. Implementation Steps (When Ready to Build)

When you are ready to activate this feature, instruct the assistant:
> *"Implement the CAC Business Registration Service following CAC_BUSINESS_REGISTRATION_PLAN.md"*

The assistant will execute the following sequentially:
1. Generate migrations for `cac_services`, `store_cac_services`, and `cac_requests`.
2. Seed standard CAC packages and auto-generate default store configurations.
3. Create `StoreCacServiceResource` and `CacRequestResource` in `app/Filament/Merchant`.
4. Create `AdminCacRequestResource` in `app/Filament/Resources` for the platform agent queue.
5. Build the customer storefront form and document upload handlers.
6. Write Pest automated feature tests for pricing validation, fulfillment routing, and document delivery.
