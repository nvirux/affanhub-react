import billing from './billing'
import mobileApp from './mobile-app'
import invitation from './invitation'
import google from './google'
import phone from './phone'
const merchant = {
    billing: Object.assign(billing, billing),
mobileApp: Object.assign(mobileApp, mobileApp),
invitation: Object.assign(invitation, invitation),
google: Object.assign(google, google),
phone: Object.assign(phone, phone),
}

export default merchant