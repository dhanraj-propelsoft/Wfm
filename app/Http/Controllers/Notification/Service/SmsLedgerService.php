<?php
namespace App\Http\Controllers\Notification\Service;

use App\Http\Controllers\Notification\Model\SmsLedger;
use App\Http\Controllers\Notification\Model\SmsLedgerVO;
use App\Http\Controllers\Notification\Repository\SmsLedgerRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;

class SmsLedgerService
{

    public function __construct(SmsLedgerRepository $repo)
    {
      
        $this->repo = $repo;
    }

    public function findOrganizationData()
    {
        Log::info('SmsLedgerService->findOrganizationData:- Inside');
        $datas = $this->repo->findOrganizationData();
        Log::info('SmsLedgerService->findOrganizationData:- Return');
        return $datas;
    }

    public function findAll($module_name)
    {
        Log::info('SmsLedgerService->findAll:- Inside');
        if ($module_name == "admin") {
            $entities = $this->repo->findAll();
        } else {
            $orgId = Session::get('organization_id');
            $models = $this->repo->findAllDataByOrganizationId($orgId);

            $entities = collect($models)->map(function ($model) {

                return $this->convertToVO($model);
            });
        }
        Log::info('SmsLedgerService->findAll:- Return');
        return $entities;
    }
     public function findById($id)
        {
            Log::info('SmsLedgerService->findById:- Inside');

                $model = $this->repo->findById($id);

                $entity = $this->convertToVO($model);

            Log::info('SmsLedgerService->findById:- Return');
            return $entity;
        }

    public function convertToVO($model = false)
    {

        Log::info('SmsLedgerService->convertToVO:-Inside');
        $vo = new SmsLedgerVO($model);

        Log::info('SmsLedgerService->convertToVO:-Return');
        return $vo;
    }

    public function findAllDataByOrganizationId($orgId)
    {
        Log::info('SmsLedgerService->findAllDataByOrganizationId:- Inside');
        $models = $this->repo->findAllDataByOrganizationId($orgId);

        $entities = collect($models)->map(function ($model) {

            return $this->convertToVO($model);
        });
            Log::info('SmsLedgerService->findAllDataByOrganizationId:- Return');
        return $entities;
    }

    public function create()
    {
        Log::info('SmsLedgerService->create:- Inside');
        $datas = $this->findOrganizationData();
        Log::info('SmsLedgerService->create:- Return');
        return $datas;
    }

    public function convertToModel($datas, $balance)
    {
        Log::info('SmsLedgerService->convertToModel:- Inside');
        $datas = (object) $datas;


        $model = new SmsLedger();
        $model->organization_id = $datas->organization;
        $model->payment_date = Carbon::parse($datas->payment_date)->format('Y-m-d');

        if ($datas->sms_ledger_type == "credit") {
            $model->payment_reference_id = $datas->payment_reference_id;
            $model->payment = $datas->payment;
            $model->credit = $datas->sms_limit;
            $model->status = 1;
        } elseif ($datas->sms_ledger_type == "debit") {
            $model->debit = $balance;
            $model->sms_notification_id = $datas->sms_notification_id;
            $model->status = 2;
        }
        $model->balance = $balance;

        Log::info('SmsLedgerService->convertToModel:- return' . json_encode($model));
        return $model;
    }
    public function save($datas)
    {
        Log::info('SmsLedgerService->save:- Inside');
        $datas = (object) $datas;


        if ($datas->sms_ledger_type == "credit") {
            $credit_count = ($datas->sms_limit) ? $datas->sms_limit : "";
            $latest_record = SmsLedger::where('organization_id', $datas->organization)->latest()->first();
            if ($latest_record) {
                $balance = $latest_record->balance + $credit_count;
            } else {
                $balance = 0 + $credit_count;
            }
        } elseif ($datas->sms_ledger_type == "debit") {
            $latest_record = SmsLedger::where('organization_id', $datas->organization)->latest()->first();
            if ($latest_record) {
                $balance = $latest_record->balance - 1;
            }
        }
        $model = $this->convertToModel($datas, $balance);
        $response = $this->repo->save($model);
        if($response['message'] == pStatusSuccess())
        {
             $savedData = $response['data'];
             $currentData = $this->findById($savedData->id);
             $response=['message' => pStatusSuccess(),'data' => $currentData];
        }
        Log::info('SmsLedgerService->save:- return' . json_encode($response));
        return $response;
    }
    public function findSmsLedgerRemaining() 
    {
        Log::info('SmsLedgerService->findSmsLedgerRemaining:- Inside');
        $remainingSmsLedger = $this->repo->findSmsLedgerRemaining();
        $smsRemaining = false;
        if($remainingSmsLedger)
        {
            $smsRemaining =($remainingSmsLedger->balance>0)?true:false;
        }
        Log::info('SmsLedgerService->findSmsLedgerRemaining:- Return');
        return $smsRemaining;
        
        
    }

}