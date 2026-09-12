import Auth from './Auth'
import RegisterStore from './RegisterStore'
import Billing from './Billing'
import Domains from './Domains'
import ManageServices from './ManageServices'
import OnboardingPlan from './OnboardingPlan'
import ReferralProgram from './ReferralProgram'
import StoreActivityLogs from './StoreActivityLogs'
import StoreSettings from './StoreSettings'
import StoreWallet from './StoreWallet'
const Pages = {
    Auth: Object.assign(Auth, Auth),
RegisterStore: Object.assign(RegisterStore, RegisterStore),
Billing: Object.assign(Billing, Billing),
Domains: Object.assign(Domains, Domains),
ManageServices: Object.assign(ManageServices, ManageServices),
OnboardingPlan: Object.assign(OnboardingPlan, OnboardingPlan),
ReferralProgram: Object.assign(ReferralProgram, ReferralProgram),
StoreActivityLogs: Object.assign(StoreActivityLogs, StoreActivityLogs),
StoreSettings: Object.assign(StoreSettings, StoreSettings),
StoreWallet: Object.assign(StoreWallet, StoreWallet),
}

export default Pages