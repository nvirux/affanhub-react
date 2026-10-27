import AirtimeCashRequestResource from './AirtimeCashRequestResource'
import CustomerResource from './CustomerResource'
import ModificationRequestResource from './ModificationRequestResource'
import StaffResource from './StaffResource'
import StoreAirtimeCashSettingResource from './StoreAirtimeCashSettingResource'
import StoreAirtimeDiscountResource from './StoreAirtimeDiscountResource'
import StoreAnnouncementResource from './StoreAnnouncementResource'
import StoreDataPlanResource from './StoreDataPlanResource'
import StoreModificationServiceResource from './StoreModificationServiceResource'
import StoreServiceResource from './StoreServiceResource'
import StoreSlipResource from './StoreSlipResource'
import TransactionResource from './TransactionResource'
import WalletTransactionResource from './WalletTransactionResource'
const Resources = {
    AirtimeCashRequestResource: Object.assign(AirtimeCashRequestResource, AirtimeCashRequestResource),
CustomerResource: Object.assign(CustomerResource, CustomerResource),
ModificationRequestResource: Object.assign(ModificationRequestResource, ModificationRequestResource),
StaffResource: Object.assign(StaffResource, StaffResource),
StoreAirtimeCashSettingResource: Object.assign(StoreAirtimeCashSettingResource, StoreAirtimeCashSettingResource),
StoreAirtimeDiscountResource: Object.assign(StoreAirtimeDiscountResource, StoreAirtimeDiscountResource),
StoreAnnouncementResource: Object.assign(StoreAnnouncementResource, StoreAnnouncementResource),
StoreDataPlanResource: Object.assign(StoreDataPlanResource, StoreDataPlanResource),
StoreModificationServiceResource: Object.assign(StoreModificationServiceResource, StoreModificationServiceResource),
StoreServiceResource: Object.assign(StoreServiceResource, StoreServiceResource),
StoreSlipResource: Object.assign(StoreSlipResource, StoreSlipResource),
TransactionResource: Object.assign(TransactionResource, TransactionResource),
WalletTransactionResource: Object.assign(WalletTransactionResource, WalletTransactionResource),
}

export default Resources