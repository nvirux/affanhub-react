import Auth from './Auth'
import RegisterStore from './RegisterStore'
import Billing from './Billing'
import Dashboard from './Dashboard'
import Domains from './Domains'
import HelpFaq from './HelpFaq'
import MobileAppManager from './MobileAppManager'
import OnboardingPlan from './OnboardingPlan'
import PushNotifications from './PushNotifications'
import ReferralProgram from './ReferralProgram'
import StoreActivityLogs from './StoreActivityLogs'
import StoreSettings from './StoreSettings'
import StoreWallet from './StoreWallet'
import WholesaleRates from './WholesaleRates'
const Pages = {
    Auth: Object.assign(Auth, Auth),
RegisterStore: Object.assign(RegisterStore, RegisterStore),
Billing: Object.assign(Billing, Billing),
Dashboard: Object.assign(Dashboard, Dashboard),
Domains: Object.assign(Domains, Domains),
HelpFaq: Object.assign(HelpFaq, HelpFaq),
MobileAppManager: Object.assign(MobileAppManager, MobileAppManager),
OnboardingPlan: Object.assign(OnboardingPlan, OnboardingPlan),
PushNotifications: Object.assign(PushNotifications, PushNotifications),
ReferralProgram: Object.assign(ReferralProgram, ReferralProgram),
StoreActivityLogs: Object.assign(StoreActivityLogs, StoreActivityLogs),
StoreSettings: Object.assign(StoreSettings, StoreSettings),
StoreWallet: Object.assign(StoreWallet, StoreWallet),
WholesaleRates: Object.assign(WholesaleRates, WholesaleRates),
}

export default Pages