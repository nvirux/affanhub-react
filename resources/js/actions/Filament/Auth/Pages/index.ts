import Login from './Login'
import EditProfile from './EditProfile'
import EmailVerification from './EmailVerification'
const Pages = {
    Login: Object.assign(Login, Login),
EditProfile: Object.assign(EditProfile, EditProfile),
EmailVerification: Object.assign(EmailVerification, EmailVerification),
}

export default Pages