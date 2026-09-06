import Login from './Login'
import EditProfile from './EditProfile'
import Register from './Register'
const Pages = {
    Login: Object.assign(Login, Login),
EditProfile: Object.assign(EditProfile, EditProfile),
Register: Object.assign(Register, Register),
}

export default Pages