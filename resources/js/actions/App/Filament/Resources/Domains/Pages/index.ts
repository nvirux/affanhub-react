import ListDomains from './ListDomains'
import CreateDomain from './CreateDomain'
import EditDomain from './EditDomain'
const Pages = {
    ListDomains: Object.assign(ListDomains, ListDomains),
CreateDomain: Object.assign(CreateDomain, CreateDomain),
EditDomain: Object.assign(EditDomain, EditDomain),
}

export default Pages