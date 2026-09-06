import Auth from './Auth'
import Pages from './Pages'
import Http from './Http'
import Actions from './Actions'
const Filament = {
    Auth: Object.assign(Auth, Auth),
Pages: Object.assign(Pages, Pages),
Http: Object.assign(Http, Http),
Actions: Object.assign(Actions, Actions),
}

export default Filament