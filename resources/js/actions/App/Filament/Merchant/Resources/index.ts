import CustomerResource from './CustomerResource'
import SettlementAccountResource from './SettlementAccountResource'
import StaffResource from './StaffResource'
import StoreAirtimeDiscountResource from './StoreAirtimeDiscountResource'
import StoreDataPlanResource from './StoreDataPlanResource'
import TransactionResource from './TransactionResource'
import WalletTransactionResource from './WalletTransactionResource'
import WithdrawalResource from './WithdrawalResource'
const Resources = {
    CustomerResource: Object.assign(CustomerResource, CustomerResource),
SettlementAccountResource: Object.assign(SettlementAccountResource, SettlementAccountResource),
StaffResource: Object.assign(StaffResource, StaffResource),
StoreAirtimeDiscountResource: Object.assign(StoreAirtimeDiscountResource, StoreAirtimeDiscountResource),
StoreDataPlanResource: Object.assign(StoreDataPlanResource, StoreDataPlanResource),
TransactionResource: Object.assign(TransactionResource, TransactionResource),
WalletTransactionResource: Object.assign(WalletTransactionResource, WalletTransactionResource),
WithdrawalResource: Object.assign(WithdrawalResource, WithdrawalResource),
}

export default Resources