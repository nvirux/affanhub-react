import Register from './Register'
import EditProfile from './EditProfile'
import EmailVerificationPrompt from './EmailVerificationPrompt'
const Auth = {
    Register: Object.assign(Register, Register),
EditProfile: Object.assign(EditProfile, EditProfile),
EmailVerificationPrompt: Object.assign(EmailVerificationPrompt, EmailVerificationPrompt),
}

export default Auth