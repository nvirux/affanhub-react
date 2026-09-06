import customers from './customers'
import settlementAccounts from './settlement-accounts'
import staff from './staff'
import storeDataPlans from './store-data-plans'
import walletTransactions from './wallet-transactions'
import withdrawals from './withdrawals'
const resources = {
    customers: Object.assign(customers, customers),
settlementAccounts: Object.assign(settlementAccounts, settlementAccounts),
staff: Object.assign(staff, staff),
storeDataPlans: Object.assign(storeDataPlans, storeDataPlans),
walletTransactions: Object.assign(walletTransactions, walletTransactions),
withdrawals: Object.assign(withdrawals, withdrawals),
}

export default resources