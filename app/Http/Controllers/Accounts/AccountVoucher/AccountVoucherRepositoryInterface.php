<?php

namespace App\Http\Controllers\Accounts\AccountVoucher;


interface AccountVoucherRepositoryInterface
{

    public function findByOrgIdAndType($orgId,$type);
}
