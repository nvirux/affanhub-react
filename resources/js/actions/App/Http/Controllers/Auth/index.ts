import TenantAuthController from './TenantAuthController'
import TenantPinSetupController from './TenantPinSetupController'
const Auth = {
    TenantAuthController: Object.assign(TenantAuthController, TenantAuthController),
TenantPinSetupController: Object.assign(TenantPinSetupController, TenantPinSetupController),
}

export default Auth