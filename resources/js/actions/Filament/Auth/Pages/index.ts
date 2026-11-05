import Login from './Login'
import EditProfile from './EditProfile'
import PasswordReset from './PasswordReset'
const Pages = {
    Login: Object.assign(Login, Login),
EditProfile: Object.assign(EditProfile, EditProfile),
PasswordReset: Object.assign(PasswordReset, PasswordReset),
}

export default Pages