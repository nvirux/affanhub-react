import HomeController from './HomeController'
import ImpersonationController from './ImpersonationController'
import BillingCheckoutCallbackController from './BillingCheckoutCallbackController'
import Merchant from './Merchant'
import Webhook from './Webhook'
import Auth from './Auth'
import DashboardController from './DashboardController'
import VirtualAccountController from './VirtualAccountController'
import EarnController from './EarnController'
import WalletController from './WalletController'
import TransactionController from './TransactionController'
import AirtimeController from './AirtimeController'
import DataController from './DataController'
import Settings from './Settings'
const Controllers = {
    HomeController: Object.assign(HomeController, HomeController),
ImpersonationController: Object.assign(ImpersonationController, ImpersonationController),
BillingCheckoutCallbackController: Object.assign(BillingCheckoutCallbackController, BillingCheckoutCallbackController),
Merchant: Object.assign(Merchant, Merchant),
Webhook: Object.assign(Webhook, Webhook),
Auth: Object.assign(Auth, Auth),
DashboardController: Object.assign(DashboardController, DashboardController),
VirtualAccountController: Object.assign(VirtualAccountController, VirtualAccountController),
EarnController: Object.assign(EarnController, EarnController),
WalletController: Object.assign(WalletController, WalletController),
TransactionController: Object.assign(TransactionController, TransactionController),
AirtimeController: Object.assign(AirtimeController, AirtimeController),
DataController: Object.assign(DataController, DataController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers