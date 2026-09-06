import ListStores from './ListStores'
import CreateStore from './CreateStore'
import EditStore from './EditStore'
const Pages = {
    ListStores: Object.assign(ListStores, ListStores),
CreateStore: Object.assign(CreateStore, CreateStore),
EditStore: Object.assign(EditStore, EditStore),
}

export default Pages