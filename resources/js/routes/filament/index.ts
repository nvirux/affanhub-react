import admin from './admin'
import merchant from './merchant'
import exports from './exports'
import imports from './imports'
const filament = {
    admin: Object.assign(admin, admin),
merchant: Object.assign(merchant, merchant),
exports: Object.assign(exports, exports),
imports: Object.assign(imports, imports),
}

export default filament