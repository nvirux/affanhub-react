import Webhook from './Webhook'
import Auth from './Auth'
import VirtualAccountController from './VirtualAccountController'
import EarnController from './EarnController'
import WalletController from './WalletController'
import AirtimeController from './AirtimeController'
import DataController from './DataController'
import Settings from './Settings'
const Controllers = {
    Webhook: Object.assign(Webhook, Webhook),
Auth: Object.assign(Auth, Auth),
VirtualAccountController: Object.assign(VirtualAccountController, VirtualAccountController),
EarnController: Object.assign(EarnController, EarnController),
WalletController: Object.assign(WalletController, WalletController),
AirtimeController: Object.assign(AirtimeController, AirtimeController),
DataController: Object.assign(DataController, DataController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers