import Register from './Register'
import EmailVerificationPrompt from './EmailVerificationPrompt'
const Auth = {
    Register: Object.assign(Register, Register),
EmailVerificationPrompt: Object.assign(EmailVerificationPrompt, EmailVerificationPrompt),
}

export default Auth