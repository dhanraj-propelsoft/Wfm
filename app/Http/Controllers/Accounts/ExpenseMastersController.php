<?php
namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Expense;
use App\Custom;
use Validator;
use Session;
use App\AccountGroup;
use Auth;
use App\Module;
use App\Country;
use App\Setting;
use App\Person;
use App\People;
use App\State;
use App\City;
use App\Nbfc;
use App\Term;
use App\Bank;
use stdClass;
use App\PeopleTitle;
use Carbon\Carbon;
use DB;
use App\PaymentMethod;
use App\CustomerGroping;
use App\AccountLedger;

class ExpenseMastersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_name = Session::get('module_name');

        $organization_id = session::get('organization_id');
        $expenses = Expense::where('organization_id', $organization_id)->get();
        $country = Country::where('name', 'India')->first();

        $state = State::where('country_id', $country->id)->pluck('name', 'id');
        $state->prepend('Select State', '');

        $title = PeopleTitle::pluck('display_name', 'id');
        $title->prepend('Title', '');

        $payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $payment->prepend('Select Title', '');

        $terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name', 'id');
        $terms->prepend('Select Terms', '');

        $group_name = CustomerGroping::pluck('display_name', 'id')->where('organization_id', $organization_id);
        $group_name->prepend('Select Group Name', '');
        return view('accounts.expense_master', compact('expenses', 'module_name', 'title', 'payment', 'terms', 'state', 'group_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_id = session::get('organization_id');

        // For Get Expense related ledgers
        $expensesLedgers = AccountLedger::leftjoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')->leftjoin('account_heads', 'account_heads.id', '=', 'account_groups.account_head')
            ->where('account_heads.name', '=', "expense")
            ->where('account_groups.organization_id', $organization_id)
            ->whereNotNull('account_ledgers.id')
            ->pluck('account_ledgers.display_name AS ledger_name', 'account_ledgers.id')
            ->prepend('Select Expenses', '');

        // For Get Daily Expenses id For default Purpose.

        $dailyExpenseId = AccountLedger::where('display_name', '=', 'Daily Expenses')->first()->id;

        return view('accounts.expense_master_create', compact('expensesLedgers', 'dailyExpenseId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createModel($id, $data)
    {
        $data = (object) $data;

        Log::info("ExpenseMastersController->createModel :- Inside");
        $organization_id = Session::get('organization_id');
        if ($id) {
            $model = Expense::findOrFail($id);
        } else {
            $model = new Expense();
        }

        $model->name = $data->name;
        $model->display_name = $data->name;
        $model->description = $data->description;
        $model->ledger_id = $data->expense_ledger;
        $model->organization_id = $organization_id;
        $model->created_by = Auth::user()->id;

        Log::info("ExpenseMastersController->createModel :- Return" . json_encode($model));

        return $model;
    }

    public function saveToModel($model)
    {
        Log::info('ExpenseMastersController->saveToModel:-Inside Try');
        try {
            $result = DB::transaction(function () use ($model) {
                $model->save();
                return [
                    'message' => pStatusSuccess(),
                    'data' => $model
                ];
            });
            Log::info('ExpenseMastersController->saveToModel:-Return Try');
            return $result;
        } catch (\Exception $e) {
            Log::info('ExpenseMastersController->saveToModel:-Return Catch');
            return [
                'message' => pStatusFailed(),
                'data' => $e
            ];
        }
    }

    // expense Data to store
    public function store(Request $request, $id = false)
    {
        Log::info("ExpenseMastersController->store :- Inside");

        $this->validate($request, [
            'name' => 'required'
        ]);
        $datas = $request->all();
        $model = $this->createModel($id, $datas);
        $response = $this->saveToModel($model);
        Log::info('ExpenseMastersController->store get response data:-' . json_encode($response));
        if ($response['message'] == pStatusSuccess()) {
            $responseData = $response['data'];
            Custom::userby($response['data'], true);
            Custom::add_addon('records');
            $message = ($id) ? config('constants.flash.updated') : config('constants.flash.added');
            Log::info('ExpenseMastersController->store:-Return');
            return response()->json([
                'status' => 1,
                'message' => 'Expenses' . $message,
                'data' => [
                    'id' => $responseData->id,
                    'name' => $responseData->name,
                    'display_name' => $responseData->display_name,
                    'description' => ($responseData->description != null) ? $responseData->description : "",
                    'status' => $responseData->status
                ]
            ]);
        } else {
            Log::info('ExpenseMastersController->store:-Return');
            return response()->json([
                'status' => 0,
                'message' => 'Something Went Wrong With Store Expenses'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info('ExpenseMastersController->edit:-inside');
        $organization_id = Session::get('organization_id');

        // For Get Expense related ledgers
        $expensesLedgers = AccountLedger::leftjoin('account_groups', 'account_groups.id', '=', 'account_ledgers.group_id')->leftjoin('account_heads', 'account_heads.id', '=', 'account_groups.account_head')
            ->where('account_heads.name', '=', "expense")
            ->where('account_groups.organization_id', $organization_id)
            ->whereNotNull('account_ledgers.id')
            ->pluck('account_ledgers.display_name AS ledger_name', 'account_ledgers.id')
            ->prepend('Select Expenses', '');

        $expenses = Expense::where('id', $id)->first();

        if (! $expenses)
            abort(403);
        Log::info('ExpenseMastersController->store:-Return');
        return view('accounts.expense_master_edit', compact('expenses', 'expensesLedgers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $expenses = Expense::findOrFail($request->input('id'));
        $expenses->delete();
        Custom::delete_addon('records');

        return response()->json([
            'status' => 1,
            'message' => 'Expenses' . config('constants.flash.deleted'),
            'data' => []
        ]);
    }

    public function expense_masters_approval(Request $request)
    {
        Expense::where('id', $request->input('id'))->update([
            'status' => $request->input('status')
        ]);

        return response()->json([
            "status" => $request->input('status')
        ]);
    }

    public function multidestroy(Request $request)
    {
        $expenses = explode(',', $request->id);
        $expense_list = [];

        foreach ($expenses as $expense) {
            $expense_delete = Expense::findOrFail($expense);
            $expense_delete->delete();
            $expense_list[] = $expense;
            Custom::delete_addon('records');
        }

        return response()->json([
            'status' => 1,
            'message' => 'Expenses' . config('constants.flash.deleted'),
            'data' => [
                'list' => $expense_list
            ]
        ]);
    }

    public function multiapprove(Request $request)
    {
        $expenses = explode(',', $request->id);
        $expense_list = [];

        foreach ($expenses as $expense) {
            Expense::where('id', $expense)->update([
                'status' => $request->input('status')
            ]);
            ;
            $expense_list[] = $expense;
        }

        return response()->json([
            'status' => 1,
            'message' => 'Expenses' . config('constants.flash.updated'),
            'data' => [
                'list' => $expense_list
            ]
        ]);
    }

    public function expense_name(Request $request)
    {
        $organization_id = session::get('organization_id');
        $expenses = Expense::where('name', $request->name)->where('organization_id', $organization_id)
            ->where('id', '!=', $request->id)
            ->first();
        if (! empty($expenses->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
