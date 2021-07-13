<?php

namespace App\Http\Controllers\Accounts\AccountVoucher;
use App\Http\Controllers\Accounts\AccountVoucher\AccountVoucherRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\AccountVoucher;
use Session;

class AccountVoucherRepository implements AccountVoucherRepositoryInterface
{

    public function findByOrgIdAndType($orgId,$type){
        if(!$orgId){
            $orgId  =   Session::get('organization_id');
            Log::info('AccountVoucherRepository->findByOrgIdAndType:- Inside org if.... '.$orgId.$type);
        }
        Log::info('AccountVoucherRepository->findByOrgIdAndType:- Inside .... '.$orgId.$type);
        $query = AccountVoucher::Where(['organization_id' => $orgId, 'name' => $type])->first();
        Log::info('AccountVoucherRepository->findByOrgIdAndType:- Return'.json_encode($query));
        return $query;
    }

    public function findById($id){
        Log::info('AccountVoucherRepository->findById:- Inside');
        $query = AccountVoucher::find($id);
        Log::info('AccountVoucherRepository->findById:- Return');
        return $query;
    }
    
}
