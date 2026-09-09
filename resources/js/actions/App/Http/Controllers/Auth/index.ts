import TenantAuthController from './TenantAuthController'
import ForgotPinController from './ForgotPinController'
import TenantPinSetupController from './TenantPinSetupController'
import TransactionPinController from './TransactionPinController'
const Auth = {
    TenantAuthController: Object.assign(TenantAuthController, TenantAuthController),
ForgotPinController: Object.assign(ForgotPinController, ForgotPinController),
TenantPinSetupController: Object.assign(TenantPinSetupController, TenantPinSetupController),
TransactionPinController: Object.assign(TransactionPinController, TransactionPinController),
}

export default Auth