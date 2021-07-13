<?php
namespace App\Http\Controllers\Notification\Repository;

use App\Http\Controllers\Notification\Model\SmsLedger;

interface SmsLedgerRepositoryInterface
{

    public function findAll();
    public function findById($id);

    public function findOrganizationData();

    public function findAllDataByOrganizationId($orgId);
    public function findSmsLedgerRemaining();
    public function save(SmsLedger $model);
}