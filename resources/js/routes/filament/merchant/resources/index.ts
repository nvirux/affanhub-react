import airtimeCashRequests from './airtime-cash-requests'
import customers from './customers'
import modificationRequests from './modification-requests'
import settlementAccounts from './settlement-accounts'
import staff from './staff'
import storeAirtimeCashSettings from './store-airtime-cash-settings'
import storeAirtimeDiscounts from './store-airtime-discounts'
import storeAnnouncements from './store-announcements'
import storeDataPlans from './store-data-plans'
import storeModificationServices from './store-modification-services'
import manageServices from './manage-services'
import storeSlips from './store-slips'
import transactions from './transactions'
import walletTransactions from './wallet-transactions'
import withdrawals from './withdrawals'
const resources = {
    airtimeCashRequests: Object.assign(airtimeCashRequests, airtimeCashRequests),
customers: Object.assign(customers, customers),
modificationRequests: Object.assign(modificationRequests, modificationRequests),
settlementAccounts: Object.assign(settlementAccounts, settlementAccounts),
staff: Object.assign(staff, staff),
storeAirtimeCashSettings: Object.assign(storeAirtimeCashSettings, storeAirtimeCashSettings),
storeAirtimeDiscounts: Object.assign(storeAirtimeDiscounts, storeAirtimeDiscounts),
storeAnnouncements: Object.assign(storeAnnouncements, storeAnnouncements),
storeDataPlans: Object.assign(storeDataPlans, storeDataPlans),
storeModificationServices: Object.assign(storeModificationServices, storeModificationServices),
manageServices: Object.assign(manageServices, manageServices),
storeSlips: Object.assign(storeSlips, storeSlips),
transactions: Object.assign(transactions, transactions),
walletTransactions: Object.assign(walletTransactions, walletTransactions),
withdrawals: Object.assign(withdrawals, withdrawals),
}

export default resources