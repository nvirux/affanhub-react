import customers from './customers'
import settlementAccounts from './settlement-accounts'
import staff from './staff'
import storeAirtimeDiscounts from './store-airtime-discounts'
import storeDataPlans from './store-data-plans'
import manageServices from './manage-services'
import storeSlips from './store-slips'
import transactions from './transactions'
import walletTransactions from './wallet-transactions'
import withdrawals from './withdrawals'
const resources = {
    customers: Object.assign(customers, customers),
settlementAccounts: Object.assign(settlementAccounts, settlementAccounts),
staff: Object.assign(staff, staff),
storeAirtimeDiscounts: Object.assign(storeAirtimeDiscounts, storeAirtimeDiscounts),
storeDataPlans: Object.assign(storeDataPlans, storeDataPlans),
manageServices: Object.assign(manageServices, manageServices),
storeSlips: Object.assign(storeSlips, storeSlips),
transactions: Object.assign(transactions, transactions),
walletTransactions: Object.assign(walletTransactions, walletTransactions),
withdrawals: Object.assign(withdrawals, withdrawals),
}

export default resources