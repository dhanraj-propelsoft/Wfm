<?php
namespace App\Http\Controllers\Notification\Repository;

use App\Organization;
use App\Http\Controllers\Notification\Model\SmsLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SmsLedgerRepository implements SmsLedgerRepositoryInterface
{

    public function findAll()
    {
        $smsLedgers = SmsLedger::with('smsNotification')
            ->leftjoin('organizations', 'organizations.id', '=', 'sms_ledgers.organization_id')
            ->orderBy('sms_ledgers.updated_at', 'asc')
            ->where('sms_ledgers.status', 5)
            ->get();

        return $smsLedgers;
    }
    public function findById($id)
    {
        return SmsLedger::where('id', $id)->first();
    }
    public function findOrganizationData()
    {
        $organization_data = Organization::where('status', 1)->pluck('name', 'id');

        $organization_data->prepend('Select Organization', '');
        return $organization_data;
    }

    public function findAllDataByOrganizationId($orgId)
    {

        $smsLedgers = SmsLedger::with('smsNotification')
        ->where('sms_ledgers.organization_id', $orgId)
        ->orderBy('id', 'DESC')
        ->get();


        //dd($smsLedgers);
        return $smsLedgers;
    }
    public function findSmsLedgerRemaining()
    {
        $smsLedgers = SmsLedger::where('organization_id', Session::get('organization_id'))
        ->orderBy('id', 'desc')->first();
       return $smsLedgers;
        
    }
     public function save(SmsLedger $model)
    {
        // ToDo Donated By Data
        Log::info('SmsLedgerRepository->save:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
            Log::info('SmsLedgerRepository->save:-Return Try');
            return $result;
        } catch (\Exception $e) {
            Log::info('SmsLedgerRepository->save:-Return Catch');
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

}
