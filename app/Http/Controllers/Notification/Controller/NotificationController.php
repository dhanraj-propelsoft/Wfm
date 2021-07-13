<?php
namespace App\Http\Controllers\Notification\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Notification\Service\SmsLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function __construct(SmsLedgerService $smsLedgerService)
    {
        
        $this->smsLedgerService = $smsLedgerService;
    }

    public function index($module_name)
    {
        Log::info('NotificationController->index:-Inside');
        $smsLedgers = $this->smsLedgerService->findAll($module_name);

        $organizationData = $this->smsLedgerService->findOrganizationData();
        Log::info('NotificationController->index:-Return');

        return view('admin.sms_allocation', compact('smsLedgers', 'module_name', 'organizationData'));
    }

    public function getOrganizationSms(Request $request)
    {
        Log::info('NotificationController->getOrganizationSms:-Inside');
        $smsLedgers = $this->smsLedgerService->findAllDataByOrganizationId($request->org_no);

        Log::info('NotificationController->getOrganizationSms:-Return');

        return response()->json([
            'status' => 1,
            'data' => $smsLedgers
        ]);
    }

    public function create()
    {
        Log::info('NotificationController->create:-Inside');
        $organization_data = $this->smsLedgerService->create();
        Log::info('NotificationController->create:-Return');
        return view('admin.sms_allocation_create', compact('organization_data'));
    }

    public function store(Request $request)
    {
        Log::info('NotificationController->store:-Inside');
        $response = $this->smsLedgerService->save(Input::all());

        Log::info('NotificationController->store:-Return');
        return $response;
    }
}
