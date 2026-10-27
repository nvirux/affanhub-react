import airtimeCashRequests from './airtime-cash-requests'
import customers from './customers'
import modificationRequests from './modification-requests'
import staff from './staff'
import airtimeCashSettings from './airtime-cash-settings'
import airtimeDiscounts from './airtime-discounts'
import announcements from './announcements'
import dataPlans from './data-plans'
import modificationServices from './modification-services'
import manageServices from './manage-services'
import slips from './slips'
import transactions from './transactions'
import walletTransactions from './wallet-transactions'
const resources = {
    airtimeCashRequests: Object.assign(airtimeCashRequests, airtimeCashRequests),
customers: Object.assign(customers, customers),
modificationRequests: Object.assign(modificationRequests, modificationRequests),
staff: Object.assign(staff, staff),
airtimeCashSettings: Object.assign(airtimeCashSettings, airtimeCashSettings),
airtimeDiscounts: Object.assign(airtimeDiscounts, airtimeDiscounts),
announcements: Object.assign(announcements, announcements),
dataPlans: Object.assign(dataPlans, dataPlans),
modificationServices: Object.assign(modificationServices, modificationServices),
manageServices: Object.assign(manageServices, manageServices),
slips: Object.assign(slips, slips),
transactions: Object.assign(transactions, transactions),
walletTransactions: Object.assign(walletTransactions, walletTransactions),
}

export default resources