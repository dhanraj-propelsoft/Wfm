<?php

namespace App\Http\Controllers\Accounts\AccountLedger;
use App\Http\Controllers\Accounts\AccountLedger\AccountVoucherRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\AccountLedger;
use Session;

class AccountLedgerRepository implements AccountLedgerRepositoryInterface
{

    public function findByOrgIdAndType ($type) {

        $orgId  =   Session::get('organization_id');
        Log::info('AccountLedgerRepository->findByOrgIdAndType:- Inside org if.... '.$orgId.$type);
       
        Log::info('AccountLedgerRepository->findByOrgIdAndType:- Inside .... '.$orgId.$type);
        $data = AccountLedger::select('account_ledgers.id', 'account_ledgers.display_name AS name', 'account_groups.name AS group')->leftJoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')
        ->where('account_groups.name', $type)
        ->where('account_ledgers.organization_id', $orgId)
        ->where('account_ledgers.approval_status', '1')
        ->where('account_ledgers.status', '1')
        ->orderby('account_ledgers.id', 'asc')
        ->get();
        Log::info('AccountLedgerRepository->findByOrgIdAndType:- Return'.json_encode($data));
        return $data;
    }

  
    
}
