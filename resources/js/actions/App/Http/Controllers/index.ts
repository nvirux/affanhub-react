import HomeController from './HomeController'
import ImpersonationController from './ImpersonationController'
import BillingCheckoutCallbackController from './BillingCheckoutCallbackController'
import MobileAppDownloadController from './MobileAppDownloadController'
import Merchant from './Merchant'
import Auth from './Auth'
import BlogController from './BlogController'
import SitemapController from './SitemapController'
import Webhook from './Webhook'
import DashboardController from './DashboardController'
import ServicesController from './ServicesController'
import VirtualAccountController from './VirtualAccountController'
import EarnController from './EarnController'
import WalletController from './WalletController'
import TransactionController from './TransactionController'
import AirtimeController from './AirtimeController'
import DataController from './DataController'
import AirtimeCashController from './AirtimeCashController'
import NinVerificationController from './NinVerificationController'
import BvnVerificationController from './BvnVerificationController'
import ModificationController from './ModificationController'
import Settings from './Settings'
const Controllers = {
    HomeController: Object.assign(HomeController, HomeController),
ImpersonationController: Object.assign(ImpersonationController, ImpersonationController),
BillingCheckoutCallbackController: Object.assign(BillingCheckoutCallbackController, BillingCheckoutCallbackController),
MobileAppDownloadController: Object.assign(MobileAppDownloadController, MobileAppDownloadController),
Merchant: Object.assign(Merchant, Merchant),
Auth: Object.assign(Auth, Auth),
BlogController: Object.assign(BlogController, BlogController),
SitemapController: Object.assign(SitemapController, SitemapController),
Webhook: Object.assign(Webhook, Webhook),
DashboardController: Object.assign(DashboardController, DashboardController),
ServicesController: Object.assign(ServicesController, ServicesController),
VirtualAccountController: Object.assign(VirtualAccountController, VirtualAccountController),
EarnController: Object.assign(EarnController, EarnController),
WalletController: Object.assign(WalletController, WalletController),
TransactionController: Object.assign(TransactionController, TransactionController),
AirtimeController: Object.assign(AirtimeController, AirtimeController),
DataController: Object.assign(DataController, DataController),
AirtimeCashController: Object.assign(AirtimeCashController, AirtimeCashController),
NinVerificationController: Object.assign(NinVerificationController, NinVerificationController),
BvnVerificationController: Object.assign(BvnVerificationController, BvnVerificationController),
ModificationController: Object.assign(ModificationController, ModificationController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers