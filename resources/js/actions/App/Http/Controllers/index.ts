import Webhook from './Webhook'
import VirtualAccountController from './VirtualAccountController'
import DataController from './DataController'
import Settings from './Settings'
const Controllers = {
    Webhook: Object.assign(Webhook, Webhook),
VirtualAccountController: Object.assign(VirtualAccountController, VirtualAccountController),
DataController: Object.assign(DataController, DataController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers