import billing from './billing'
import mobileApp from './mobile-app'
import invitation from './invitation'
const merchant = {
    billing: Object.assign(billing, billing),
mobileApp: Object.assign(mobileApp, mobileApp),
invitation: Object.assign(invitation, invitation),
}

export default merchant