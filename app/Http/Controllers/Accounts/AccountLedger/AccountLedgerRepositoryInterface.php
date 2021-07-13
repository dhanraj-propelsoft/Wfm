<?php

namespace App\Http\Controllers\Accounts\AccountLedger;


interface AccountLedgerRepositoryInterface
{

    public function findByOrgIdAndType($type);
}
