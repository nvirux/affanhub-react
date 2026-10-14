import AirtimeCashRequestResource from './AirtimeCashRequestResource'
import CustomerResource from './CustomerResource'
import SettlementAccountResource from './SettlementAccountResource'
import StaffResource from './StaffResource'
import StoreAirtimeCashSettingResource from './StoreAirtimeCashSettingResource'
import StoreAirtimeDiscountResource from './StoreAirtimeDiscountResource'
import StoreDataPlanResource from './StoreDataPlanResource'
import StoreServiceResource from './StoreServiceResource'
import StoreSlipResource from './StoreSlipResource'
import TransactionResource from './TransactionResource'
import WalletTransactionResource from './WalletTransactionResource'
import WithdrawalResource from './WithdrawalResource'
const Resources = {
    AirtimeCashRequestResource: Object.assign(AirtimeCashRequestResource, AirtimeCashRequestResource),
CustomerResource: Object.assign(CustomerResource, CustomerResource),
SettlementAccountResource: Object.assign(SettlementAccountResource, SettlementAccountResource),
StaffResource: Object.assign(StaffResource, StaffResource),
StoreAirtimeCashSettingResource: Object.assign(StoreAirtimeCashSettingResource, StoreAirtimeCashSettingResource),
StoreAirtimeDiscountResource: Object.assign(StoreAirtimeDiscountResource, StoreAirtimeDiscountResource),
StoreDataPlanResource: Object.assign(StoreDataPlanResource, StoreDataPlanResource),
StoreServiceResource: Object.assign(StoreServiceResource, StoreServiceResource),
StoreSlipResource: Object.assign(StoreSlipResource, StoreSlipResource),
TransactionResource: Object.assign(TransactionResource, TransactionResource),
WalletTransactionResource: Object.assign(WalletTransactionResource, WalletTransactionResource),
WithdrawalResource: Object.assign(WithdrawalResource, WithdrawalResource),
}

export default Resources