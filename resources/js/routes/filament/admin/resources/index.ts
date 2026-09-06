import activityLogs from './activity-logs'
import admins from './admins'
import dataPlans from './data-plans'
import dataTypes from './data-types'
import domains from './domains'
import features from './features'
import networks from './networks'
import owners from './owners'
import planDataPrices from './plan-data-prices'
import plans from './plans'
import services from './services'
import settlementAccounts from './settlement-accounts'
import stores from './stores'
import subscriptions from './subscriptions'
import transactions from './transactions'
import users from './users'
import virtualAccounts from './virtual-accounts'
import walletTransactions from './wallet-transactions'
import withdrawals from './withdrawals'
const resources = {
    activityLogs: Object.assign(activityLogs, activityLogs),
admins: Object.assign(admins, admins),
dataPlans: Object.assign(dataPlans, dataPlans),
dataTypes: Object.assign(dataTypes, dataTypes),
domains: Object.assign(domains, domains),
features: Object.assign(features, features),
networks: Object.assign(networks, networks),
owners: Object.assign(owners, owners),
planDataPrices: Object.assign(planDataPrices, planDataPrices),
plans: Object.assign(plans, plans),
services: Object.assign(services, services),
settlementAccounts: Object.assign(settlementAccounts, settlementAccounts),
stores: Object.assign(stores, stores),
subscriptions: Object.assign(subscriptions, subscriptions),
transactions: Object.assign(transactions, transactions),
users: Object.assign(users, users),
virtualAccounts: Object.assign(virtualAccounts, virtualAccounts),
walletTransactions: Object.assign(walletTransactions, walletTransactions),
withdrawals: Object.assign(withdrawals, withdrawals),
}

export default resources