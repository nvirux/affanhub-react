import Webhook from './Webhook'
import VirtualAccountController from './VirtualAccountController'
import EarnController from './EarnController'
import AirtimeController from './AirtimeController'
import DataController from './DataController'
import Settings from './Settings'
const Controllers = {
    Webhook: Object.assign(Webhook, Webhook),
VirtualAccountController: Object.assign(VirtualAccountController, VirtualAccountController),
EarnController: Object.assign(EarnController, EarnController),
AirtimeController: Object.assign(AirtimeController, AirtimeController),
DataController: Object.assign(DataController, DataController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers