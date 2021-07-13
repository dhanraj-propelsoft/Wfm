<?php
namespace App\Http\Controllers\Tradewms\Jobcard;

use App\Transaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Tradewms\Jobcard\JobCardRepositoryInterface;
use Illuminate\Support\Carbon;
use App\VehicleChecklist;
use App\WmsTransaction;
use DB;
use Session;
use App\InventoryItem;
use App\TransactionItem;
use App\WmsChecklist;
use App\WmsAttachment;
use App\Custom;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCard;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardDetail;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardAttachment;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardChecklist;
use App\Http\Controllers\Tradewms\Jobcard\Model\JobCardItem;
use App\VehicleJobcardStatus;
use App\VehicleJobItemStatus;
use App\ShipmentMode;
use App\PaymentMode;

class JobCardRepository implements JobCardRepositoryInterface
{

    public function findAll($request, $isApi = false)
    {
        Log::info('JobCardRepository->findAll:-  Inside');
        $request = (object) $request;
        Log::info('JobCardRepository->findAll:-  Inside' . json_encode($request));
        $query = JobCard::with('accountVoucher', 'referencedIn', 'referencedIn.accountVoucher', 'jobCardDetail', 'jobCardDetail.jobCardStatus', 'jobCardDetail.assignedToEmployee', 'jobCardDetail.vehicleDetail', 'person', 'business');

        if ($isApi) {} else {
            $query->where('job_cards.organization_id', $request->org_id);
            $query->where('job_cards.transaction_type_id', $request->transaction_type_id);
            $query->whereNull('job_cards.deleted_at');

            $search_text = trim($request->search_text);
            $fromDate = $request->qfrom_date;
            $toDate = $request->qto_date;
            // $query->whereBetween('updated_at', [$fromDate,$toDate]);

            Log::info('JobCardRepository->findAll:-  serach text  ' . $search_text);
            if (! $search_text || strlen($search_text) == 0) {
                $query->whereHas('jobCardDetail', function ($query) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $query->whereDate('updated_at', '>=', $fromDate)
                            ->whereDate('updated_at', '<=', $toDate);
                    }
                });

                /* Filter By JobCardStatus */
                if ($request->jobcard_status && $request->jobcard_status != 'ALL') {

                    $jobStatusId = $request->jobcard_status;
                    $query->whereHas('jobCardDetail', function ($query) use ($jobStatusId) {
                        $query->where('jobcard_status_id', $jobStatusId);
                    });
                } else {
                    $query->whereHas('jobCardDetail', function ($query) {
                        $query->where('jobcard_status_id', '!=', 8);
                    });
                }
            } else {
                Log::info('JobCardRepository->findAll:-  search by text JC # or Registration #');
                $query->where(function ($query) use ($search_text) {
                    $query->Where('order_no', 'LIKE', '%' . $search_text . '%')
                        ->orWhereHas('jobCardDetail.vehicleDetail', function ($query) use ($search_text) {
                        $query->Where('registration_no', 'LIKE', '%' . $search_text . '%');
                    });
                });
                // $query->orWhere('jobCardDetail.vehicleDetail.registration_no', 'LIKE', '%' . $search_text . '%');
            }
            $query->groupby('job_cards.id');
            $query->orderBy('job_cards.updated_at', 'desc');

            Log::debug('JobCardRepository->findAll:-  SQL - ' . $query->toSql());

            Log::debug('JobCardRepository->findAll:- SQLBinding - ' . json_encode($query->getBindings()));
            $result = $query->get();

            Log::info('JobCardRepository->findAll:-  Return');
            return $result;
        }
    }

    public function findAll_API($request)
    {
        Log::info('JobCardRepository->findAll_API:-  Inside');

        $request = (object) $request;

        Log::info('JobCardRepository->findAll_API:-  data - ' . json_encode($request));

        $organization_id = $request->org_id;
        $offset = $request->page;
        $limit = $request->per_page;

        $transaction = JobCard::select('job_cards.order_no', 'job_cards.id', 'job_cards.transaction_type_id', DB::raw("IF(people.display_name IS NULL, business.display_name, people.display_name) as customer"), 'vehicle_register_details.registration_no', 'hrm_employees.first_name AS assigned_to', 'vehicle_jobcard_statuses.name as jobcard_status', 'job_card_details.job_date', 'job_card_details.registration_id as vehicle_id', 'job_card_details.job_due_date', 'job_card_details.job_completed_date', 'job_cards.organization_id');

        $transaction->leftJoin('people', function ($join) use ($organization_id) {
            $join->on('people.person_id', '=', 'job_cards.people_id')
                ->where('people.organization_id', $organization_id)
                ->where('job_cards.user_type', '0');
        });
        $transaction->leftJoin('people AS business', function ($join) use ($organization_id) {
            $join->on('business.business_id', '=', 'job_cards.people_id')
                ->where('business.organization_id', $organization_id)
                ->where('job_cards.user_type', '1');
        });

        // $transaction->leftjoin('transactions AS reference_transactions', 'transactions.reference_id', '=', 'reference_transactions.id');

        // $transaction->leftJoin('account_vouchers AS reference_vouchers', 'reference_vouchers.id', '=', 'reference_transactions.transaction_type_id');

        $transaction->leftJoin('job_card_details', 'job_cards.id', '=', 'job_card_details.job_card_id');

        $transaction->leftJoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'job_card_details.jobcard_status_id');

        // $transaction->leftJoin('service_types', 'service_types.id', '=', 'wms_transactions.service_type');

        $transaction->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'job_card_details.registration_id');

        $transaction->leftJoin('hrm_employees', 'hrm_employees.id', '=', 'job_card_details.assigned_to');

        $transaction->where('job_cards.organization_id', $organization_id);
        $transaction->where('job_cards.transaction_type_id', $request->transaction_type_id);
        $transaction->whereNull('job_cards.deleted_at');
        $transaction->where('job_cards.notification_status', '!=', 2);
        $transaction->groupby('job_cards.id');
        $transaction->orderBy('job_cards.updated_at', 'desc');
        $transaction->skip($offset * $limit);
        $transaction->take($limit);

        Log::info('JobCardRepository->findAll_API:-  data  SQL - ' . json_encode($transaction->toSql()));
        Log::info('JobCardRepository->findAll_API:-  data  SQL Binding - ' . json_encode($transaction->getBindings()));
        /*
         * Search by customer name, jobstatus,jobcard number
         *
         * Code By Manimaran - 18-6-2019
         */
        // Search column

        if (! isset($request->jobcard_no) && ! isset($request->customer_name) && ! ! isset($request->job_status)) {

            $transactions = $transaction->get();
        } else {

            $columnsToSearch = [
                'job_cards.order_no',
                'vehicle_jobcard_statuses.name'
            ];

            $jobcard_no_query = ($request->jobcard_no) ? $request->jobcard_no : '';

            $customer_name_query = ($request->customer_name) ? $request->customer_name : '';

            $job_status_query = ($request->job_status) ? $request->job_status : '';

            $searchQuery = [
                $jobcard_no_query,
                $job_status_query
            ];

            // dd($searchQuery);

            $transaction->Where(function ($query) use ($columnsToSearch, $searchQuery) {

                foreach ($columnsToSearch as $key => $column) {

                    if ($searchQuery[$key] != null) {

                        $query->Where($column, 'LIKE', '%' . $searchQuery[$key] . '%');
                    }
                }
            });

            $SearchCustomer = [
                $customer_name_query,
                $customer_name_query
            ];
            $columnsToSearch_Cust = [
                'business.display_name',
                'people.display_name'
            ];

            $transaction->Where(function ($query) use ($columnsToSearch_Cust, $SearchCustomer) {

                foreach ($columnsToSearch_Cust as $key => $column) {

                    if ($SearchCustomer[$key] != null) {

                        $query->orWhere($column, 'LIKE', '%' . $SearchCustomer[$key] . '%');
                    }
                }
            });

            $transactions = $transaction->get();
        }

        return $transactions;
    }

    public function findAllPaymentMode()
    {
        Log::info('JobCardRepository->findAllPaymentMode:- Inside');
        $query = PaymentMode::where('status', 1)->select('display_name', 'id')->get();
        Log::info('JobCardRepository->findAllPaymentMode:- Return');
        return $query;
    }

    public function findByTransactionId($id)
    {
        Log::info('JobCardRepository->findByTransactionId:- Inside');
        $query = Transaction::find($id);
        Log::info('JobCardRepository->findByTransactionId:- Return');
        return $query;
    }

    public function findJobCardById($id)
    {
        Log::info('JobCardRepository->findJobCardById:- Inside');
        $query = JobCard::find($id);
        Log::info('JobCardRepository->findJobCardById:- Return');
        return $query;
    }

    public function findJobCardWithDetailById($id)
    {
        Log::info('JobCardRepository->findJobCardWithDetailById:- Inside');
        $query = JobCard::with('jobCardDetail')->where('id', $id)->first();
        ;
        Log::info('JobCardRepository->findJobCardWithDetailById:- Return');
        return $query;
    }

    public function findPreviousJobcards($regId, $typeId, $orgId)
    {
        Log::info('JobCardRepository->findPreviousJobcards:- Inside');
        $query = JobCard::select('vehicle_register_details.registration_no', 'job_cards.order_no', 'job_cards.id', DB::raw("DATE_FORMAT(job_card_details.job_date, '%d-%m-%Y') as job_date"))->leftjoin('job_card_details', 'job_cards.id', '=', 'job_card_details.job_card_id')
            ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'job_card_details.registration_id')
            ->where('vehicle_register_details.id', $regId)
            ->where('job_cards.transaction_type_id', $typeId)
            ->where('job_cards.organization_id', $orgId)
            ->get();
        Log::info('JobCardRepository->findPreviousJobcards:- Return');
        return $query;
    }

    public function findJobCardTransactionById($id)
    {
        $orgId = Session::get('organization_id');
        Log::info('JobCardRepository->findJobCardTransactionById:- Inside');
        $query = Transaction::select('transactions.order_no', 'transactions.id', 'user_type', 'total')->where('transactions.organization_id', $orgId)
            ->where('transactions.id', $id)
            ->first();
        Log::info('JobCardRepository->findJobCardTransactionById:- Return');
        return $query;
    }

    public function findPersonTransactionByOrderNo($orderNo)
    {
        $orgId = Session::get('organization_id');
        Log::info('JobCardRepository->findPersonTransactionByOrderNo:- Inside');
        $query = Transaction::select('transactions.id', 'transactions.total', 'people.display_name', 'people.person_id', 'transactions.user_type')->leftjoin('people', 'people.person_id', '=', 'transactions.people_id')
            ->where('transactions.order_no', $orderNo)
            ->where('transactions.organization_id', $orgId)
            ->whereNull('transactions.deleted_at')
            ->first();
        Log::info('JobCardRepository->findPersonTransactionByOrderNo:- Return');
        return $query;
    }

    public function findBusinessTransactionByOrderNo($orderNo)
    {
        $orgId = Session::get('organization_id');
        Log::info('JobCardRepository->findBusinessTransactionByOrderNo:- Inside');
        // $query = Transaction::select('transactions.id', 'transactions.total', 'people.display_name', 'people.person_id', 'transactions.user_type')->leftjoin('people', 'people.person_id', '=', 'transactions.people_id')
        $query = Transaction::select('transactions.id', 'transactions.total', 'people.display_name', 'people.business_id', 'transactions.user_type')->leftjoin('people', 'people.business_id', '=', 'transactions.people_id')
            ->where('transactions.order_no', $orderNo)
            ->where('transactions.organization_id', $orgId)
            ->whereNull('transactions.deleted_at')
            ->first();
        Log::info('JobCardRepository->findBusinessTransactionByOrderNo:- Return');
        return $query;
    }

    public function findJobcardOrgById($id)
    {
        Log::info('JobCardRepository->findJobcardOrgById:- Inside');
        $query = JobCard::select('job_cards.organization_id AS org_id', 'organizations.name AS org_name', 'business_communication_addresses.address AS org_address', 'business_communication_addresses.mobile_no AS org_ph', 'businesses.gst AS org_gst', 'business_communication_addresses.city_id', 'business_communication_addresses.pin as org_pin', 'cities.name as city_name', 'states.name as state_name')->leftjoin('organizations', 'organizations.id', '=', 'job_cards.organization_id')
            ->leftjoin('businesses', 'businesses.id', '=', 'organizations.business_id')
            ->leftjoin('business_communication_addresses', 'business_communication_addresses.business_id', '=', 'businesses.id')
            ->leftjoin('cities', 'business_communication_addresses.city_id', '=', 'cities.id')
            ->leftjoin('states', 'cities.state_id', '=', 'states.id')
            ->where('job_cards.id', $id)
            ->first();
        Log::info('JobCardRepository->findJobcardOrgById:- Return');
        return $query;
    }

    public function findJobcardCustomerDetailAssoById($id)
    {
        Log::info('JobCardRepository->findJobcardCustomerDetailById:- Inside');
        $query = JobCard::select('job_cards.id', 'job_cards.order_no AS jobcard_no', 'job_cards.name AS customer_name', 'job_cards.mobile AS customer_mobile', 'vehicle_register_details.registration_no', DB::raw('CONCAT(vehicle_makes.name, " - ",vehicle_models.name," - ",vehicle_variants.name) AS make_model_variant'), 'job_card_details.id AS job_card_detail_id', 'vehicle_jobcard_statuses.display_name AS current_status', DB::raw("DATE_FORMAT(job_card_details.job_date, '%d-%m-%Y') as last_updated"), 'job_card_details.vehicle_complaints AS complaints', 'job_card_details.vehicle_note')->leftjoin('job_card_details', 'job_cards.id', '=', 'job_card_details.job_card_id')
            ->leftjoin('vehicle_jobcard_statuses', 'vehicle_jobcard_statuses.id', '=', 'job_card_details.jobcard_status_id')
            ->leftjoin('vehicle_register_details', 'vehicle_register_details.id', '=', 'job_card_details.registration_id')
            ->leftjoin('vehicle_variants', 'vehicle_variants.id', '=', 'vehicle_register_details.vehicle_variant_id')
            ->leftjoin('vehicle_models', 'vehicle_models.id', '=', 'vehicle_variants.vehicle_model_id')
            ->leftjoin('vehicle_makes', 'vehicle_makes.id', '=', 'vehicle_register_details.vehicle_make_id')
            ->where('job_cards.id', $id)
            ->first();
        Log::info('JobCardRepository->findJobcardCustomerDetailById:- Return');
        return $query;
    }

    public function findJobCardDetail($id, $typeId, $orgId)
    {
        Log::info('JobCardRepository->findJobcardDetail:-  Inside');
        $query = JobCard::with('jobCardDetail.vehicleDetail', 'referencedIn', 'referencedIn.accountVoucher')->where([
            'id' => $id,
            'transaction_type_id' => $typeId,
            'organization_id' => $orgId
        ]);
        Log::info('JobCardRepository->findJobcardDetail:-  Query - ' . $query->toSql());
        Log::info('JobCardRepository->findJobcardDetail:-  QueryBinding ' . json_encode($query->getBindings()));
        Log::info('JobCardRepository->findJobcardDetail:-  return');
        return $query->first();
    }

    public function findLastJCImageSequence($jobCardId, $category)
    {
        Log::info('JobCardRepository->findLastJCImageSequence:-  Inside');
        $query = JobCardAttachment::where([
            'job_card_id' => $jobCardId,
            'image_category' => $category
        ])->orderBy('id', 'desc');
        Log::info('JobCardRepository->findLastJCImageSequence:-  return');
        return $query->first();
    }

    public function findLastJobcard($vehicleId, $id = false)
    {
        Log::info('JobCardRepository->findLastJobcard:-  Inside');

        $orgId = Session::get('organization_id');
        $type_id = Session::get('jc_type_id');

        if ($orgId && $type_id) {
            $query = JobCardDetail::select('job_cards.id', 'job_card_details.job_date', 'job_cards.order_no');
            $query->leftjoin('job_cards', 'job_cards.id', '=', 'job_card_details.job_card_id');
            $query->where(function ($subquery) {
                $subquery->where('job_card_details.jobcard_status_id', '!=', "8")
                    ->orWhere('job_card_details.jobcard_status_id', '=', null);
            });
            $query->where('job_cards.organization_id', $orgId);
            $query->where('job_card_details.registration_id', $vehicleId);
            $query->where('job_cards.transaction_type_id', $type_id);
            $query->orderBy('job_cards.id', "DESC");

            if ($id) {
                $query->where('job_cards.id', '!=', $id);
            }

            Log::info('JobCardRepository->findLastJobcard:-  Query ' . $query->toSql());
            Log::info('JobCardRepository->findLastJobcard:-  QueryBinding ' . json_encode($query->getBindings()));
            Log::info('JobCardRepository->findLastJobcard:-  Return ');
            return $query->first();
        } else {
            return null;
        }
    }

    public function findAllCheckList($id = false)
    {
        Log::info('JobCardRepository->findAllCheckListByTransactionId:-  Inside');

        $query = VehicleChecklist::query()->select('name', 'id');
        if ($id) {
            Log::info('JobCardRepository->findAllCheckListByTransactionId:-  with jobCardCheckList' . $id);
            $query->with([
                'jobCardChecklist' => function ($query) use ($id) {
                    $query->where('job_card_id', $id);
                    $query->addSelect('*');
                }
            ]);
        }
        Log::info('JobCardRepository->findAllCheckListByTransactionId:-  Query ' . $query->toSql());
        Log::info('JobCardRepository->findAllCheckListByTransactionId:-  QueryBinding ' . json_encode($query->getBindings()));
        $result = $query->get();
        // dd( $result);
        Log::info('JobCardRepository->findAllCheckListByTransactionId:-  Return ');
        return $result;
    }

    public function findCheckListByJobCardId($id)
    {
        Log::info('JobCardRepository->findAllCheckListByJobCardId :- Inside');
        $query = Jobcard::select('job_card_checklists.id', 'vehicle_checklists.name', 'job_card_checklists.checklist_notes')->leftjoin('job_card_checklists', 'job_cards.id', '=', 'job_card_checklists.job_card_id')
            ->leftjoin('vehicle_checklists', 'vehicle_checklists.id', '=', 'job_card_checklists.checklist_id')
            ->where('job_cards.id', $id)
            ->get();
        Log::info('JobCardRepository->findAllCheckListByJobCardId :- Return');
        return $query;
    }

    public function findUnCheckedCLData($dataArray, $jobCardId)
    {
        Log::info('JobCardRepository->findUnCheckedCLData :- Inside');
        $data = JobCardChecklist::whereNotIn('checklist_id', $dataArray)->where('job_card_id', $jobCardId)->get();
        Log::info('JobCardRepository->findUnCheckedCLData :- Return');
        return $data;
    }

    public function findJobcardItemsByJobcardId($id)
    {
        Log::info('JobCardRepository->findJobcardItemByJobcardId :- Inside');
        $items_query = JobCardItem::select('job_card_items.*', 'vehicle_job_item_statuses.display_name as item_status_name', 'inventory_item_stocks.in_stock as in_stock', 'inventory_items.name AS item_name', 'inventory_item_batches.quantity AS batch_stock', 'global_item_categories.display_name AS category', 'global_item_main_categories.category_type_id AS category_type_id', 'global_item_makes.name AS make')->leftjoin('inventory_items', 'inventory_items.id', '=', 'job_card_items.item_id')
            ->leftjoin('vehicle_job_item_statuses', 'vehicle_job_item_statuses.id', '=', 'job_card_items.job_item_status')
            ->leftjoin('inventory_item_batches', 'inventory_item_batches.id', '=', 'job_card_items.batch_id')
            ->leftjoin('inventory_item_stocks', 'inventory_item_stocks.id', '=', 'inventory_items.id')
            ->leftjoin('global_item_models', 'global_item_models.id', '=', 'inventory_items.global_item_model_id')
            ->leftjoin('global_item_makes', 'global_item_makes.id', '=', 'global_item_models.make_id')
            ->leftjoin('global_item_categories', 'global_item_categories.id', '=', 'global_item_models.category_id')
            ->leftjoin('global_item_main_categories', 'global_item_main_categories.id', '=', 'global_item_categories.main_category_id')
            ->where('job_card_items.job_card_id', $id);
        $transaction_items = $items_query->get();
        Log::info('JobCardRepository->findJobcardItemByJobcardId :- Return');
        return $transaction_items;
    }

    public function findUnselectedItems($dataArray, $transactionId, $isQuery = false)
    {
        // query only using for delete purpose
        Log::info('JobCardRepository->findUnCheckedCLData :- Inside');
        $data = JobCardItem::where('job_card_id', $transactionId)->whereNotIn('item_id', $dataArray);
        Log::info('JobCardRepository->findUnCheckedCLData :- Return');
        return $data;
    }

    public function findJobcardAttachmentByIdAndType($id, $typeId)
    {
        Log::info('JobCardRepository->findJobcardAttachmentByIdAndType :- Inside');
        //$orgId = Session::get('organization_id');
        $data = JobCardAttachment::select('id', 'origional_file')->where('job_card_id', $id)
            ->where('image_category', $typeId)
            //->where('organization_id', $orgId)
            ->get();
        Log::info('JobCardRepository->findJobcardAttachmentByIdAndType :- Inside');
        return $data;
    }

    public function findJobcardImageById($id)
    {
        Log::info('JobCardRepository->findJobcardImageById :- Inside');
        $data = JobCardAttachment::where('id', $id)->first();
        Log::info('JobCardRepository->findJobcardImageById :- Return');
        return $data;
    }

    public function findAllJobCardStatuses($isPluck = false)
    {
        Log::info('JobCardRepository->findAllJobCardStatuses:- Inside');
        $query = VehicleJobcardStatus::get();
        if ($isPluck) {

            $query = $query->pluck('display_name', 'id');
        }
        Log::info('JobCardRepository->findAllJobCardStatuses:- Return');
        return $query;
    }

    public function findAllJobItemStatuses($isPluck = false)
    {
        Log::info('JobCardRepository->findAllJobItemStatuses:- Inside');
        $query = VehicleJobItemStatus::get();
        if ($isPluck) {

            $query = $query->pluck('display_name', 'id');
        }
        Log::info('JobCardRepository->findAllJobItemStatuses:- Return');
        return $query;
    }

    public function findAllShipmentMode($isPluck = false)
    {
        Log::info('JobCardRepository->findAllJobItemStatuses:- Inside');
        $organization_id = Session::get('organization_id');
        $query = ShipmentMode::where('organization_id', $organization_id)->get();
        if ($isPluck) {

            $query = $query->pluck('name', 'id');
        }
        Log::info('JobCardRepository->findAllJobItemStatuses:- Return');
        return $query;
    }

    public function saveJobCard($model, $detailModel, $transactionModel)
    {
        Log::info('JobCardRepository->saveJobCard :- Inside');

        try {

            $result = DB::transaction(function () use ($model, $detailModel, $transactionModel) {

                $model->save();
                // Log::info('JobCardRepository->saveWmsTransaction :- Model - '.json_encode($model));
                $model->jobCardDetail()->save($detailModel);

                $model->referencedIn()->save($transactionModel);

                // Update created By and Last modified fields
                Custom::userby($model, true);
                Custom::userby($model->jobCardDetail, true);
                Custom::userby($transactionModel, true);

                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
            Log::info('JobCardRepository->saveJobCard :- Return try - ');
            return $result;
        } catch (\Exception $e) {
            Log::error('JobCardRepository->saveJobCard :- Return catch Error ' . json_encode($e));
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function updateJobCardOrderNumber($model, $transactionModel, $accountVoucher)
    {
        Log::info('JobCardRepository->updateJobCardOrderNumber :- Inside');

        try {

            $result = DB::transaction(function () use ($model, $transactionModel, $accountVoucher) {

                $model->save();
                $model->referencedIn()->save($transactionModel);

                $accountVoucher->save();

                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
            Log::info('JobCardRepository->updateJobCardOrderNumber :- Return try - ');
            return $result;
        } catch (\Exception $e) {
            Log::error('JobCardRepository->updateJobCardOrderNumber :- Return catch Error ' . json_encode($e));
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function saveJobCardItem($models, $jobCardId)
    {
        Log::info('JobCardRepository->saveJobCardItem :- Inside');

        try {
            $result = DB::transaction(function () use ($models, $jobCardId) {

                // TransactionItem::insert($models);
                foreach ($models as $key => $modelArray) {

                    // Log::info('JobCardRepository->saveJobCardItem :- Save Item3 - ');

                    // start_time convert to date time format
                    if (array_key_exists("start_time", $modelArray)) {
                        // Log::info('JobCardRepository->saveJobCardItem :- Start Time - '.$modelArray['start_time']);
                        // if field is datetime return timestamp else false
                        if ($modelArray['start_time']) {
                            $modelArray['start_time'] = Carbon::parse($modelArray['start_time'])->format('y-m-d h:m:s');
                            // Log::info('JobCardRepository->saveJobCardItem :- Start Time converted- '.$modelArray['start_time']);
                        } else {
                            $modelArray['start_time'] = null;
                        }
                    }
                    /* */
                    // Log::info('JobCardRepository->saveJobCardItem :- Save Array - '.json_encode($modelArray ));

                    $response = JobCardItem::updateOrCreate([
                        'job_card_id' => $jobCardId,
                        'item_id' => $modelArray['item_id']
                    ], $modelArray // convert to array
                    );

                    // Log::info('JobCardRepository->saveJobCardItem :- Save Item2 - '.json_encode($response));
                }
                return [
                    'message' => pStatusSuccess(),
                    'data' => ""
                ];
            });
            Log::info('JobCardRepository->saveJobCardItem :- Return try - ');
            return $result;
        } catch (\Exception $e) {
            Log::error('JobCardRepository->saveJobCardItem :- Return catch Error ' . json_encode($e));
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    public function saveAttachementImages($models)
    {
        Log::info('JobCardRepository->saveAttachementImages :- Inside');
        try {

            $result = DB::transaction(function () use ($models) {
                $model = JobCardAttachment::insert($models);
                return [
                    'message' => pStatusSuccess(),
                    'data' => ""
                ];
            });
            Log::info('JobCardRepository->saveAttachementImages :- Return try - ');
            return $result;
        } catch (\Exception $e) {
            Log::error('JobCardRepository->saveAttachementImages :- Return catch Error ' . json_encode($e));
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    /**
     * * Destroy**
     */
    public function destroyCheckListData($model)
    {
        // $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id', $transactionId)->delete();
        Log::info('JobCardRepository->destroyCheckListData:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->delete();
                return [
                    'message' => pStatusSuccess()
                ];
            });
            Log::info('JobCardRepository->destroyCheckListData:-Return Try');
            return $result;
        } catch (\Exception $e) {

            Log::error('JobCardRepository->destroyCheckListData:-Return catch Error : ' . json_encode($e));
            return [
                'message' => pStatusFailed()
            ];
        }
    }

    public function destroyUnSelectedJobCardItems($jobCardId, $dataArray = false)
    {
        Log::info('JobCardRepository->destroyUnSelectedJobCardItems:-Inside Try');
        try {
            $result = DB::transaction(function () use ($jobCardId, $dataArray) {

                $query = JobCardItem::where('job_card_id', $jobCardId);

                // unselected item array
                if ($dataArray) {
                    $query->whereNotIn('item_id', $dataArray);
                }

                $query->delete();
                return [
                    'message' => pStatusSuccess()
                ];
            });
            Log::info('JobCardRepository->destroyUnSelectedJobCardItems:-Return Try');
            return $result;
        } catch (\Exception $e) {

            Log::error('JobCardRepository->destroyUnSelectedJobCardItems:-Return catch Error : ' . json_encode($e));
            return [
                'message' => pStatusFailed()
            ];
        }
    }

    public function destroyJobCardItem($jobCardItem)
    {
        Log::info('JobCardRepository->destroyJobCardItem:-Inside Try');
        try {
            $result = DB::transaction(function () use ($jobCardItem) {
                $jobCardItem->delete();
                return [
                    'message' => pStatusSuccess()
                ];
            });
            Log::info('JobCardRepository->destroyJobCardItem:-Return Try');
            return $result;
        } catch (\Exception $e) {

            Log::error('JobCardRepository->destroyJobCardItem:-Return catch Error : ' . json_encode($e));
            return [
                'message' => pStatusFailed()
            ];
        }
    }

    /**
     * * Destroy**
     */
    public function destroyAttachment($model)
    {
        // $existing_items = DB::table('transaction_items')->where('transaction_items.transaction_id', $transactionId)->delete();
        Log::info('JobCardRepository->destroyAttachment:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->delete();
                return [
                    'message' => pStatusSuccess()
                ];
            });
            Log::info('JobCardRepository->destroyAttachment:-Return Try');
            return $result;
        } catch (\Exception $e) {

            Log::error('JobCardRepository->destroyAttachment:-Return catch Error : ' . json_encode($e));
            return [
                'message' => pStatusFailed()
            ];
        }
    }

    /**
     * * Destroy**
     */
    public function destroy($model)
    {
        Log::info('JobCardRepository->destroy:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->delete();
                return [
                    'message' => pStatusSuccess()
                ];
            });
            Log::info('JobCardRepository->destroy:-Return Try');
            return $result;
        } catch (\Exception $e) {

            Log::error('JobCardRepository->destroy:-Return catch Error : ' . json_encode($e));
            return [
                'message' => pStatusFailed()
            ];
        }
    }
}
