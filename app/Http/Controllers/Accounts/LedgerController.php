<?php
namespace App\Http\Controllers\Accounts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountLedgerCommunication;
use App\AccountLedgerCreditInfo;
use App\BusinessProfessionalism;
use App\AccountFinancialYear;
use App\AccountPersonType;
use App\AccountLedgerType;
use App\AccountChequeBook;
use App\AccountVoucher;
use App\BankAccountType;
use App\CustomerGroping;
use App\BusinessNature;
use App\PaymentMethod;
use App\AccountLedger;
use App\Organization;
use App\AccountGroup;
use App\AccountHead;
use App\PeopleTitle;
use Carbon\Carbon;
use App\Business;
use App\Country;
use App\Setting;
use App\Custom;
use App\Person;
use App\People;
use Validator;
use App\State;
use App\Nbfc;
use App\Term;
use App\Bank;
use stdClass;
use Session;
use DB;

class LedgerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		$financialyear = AccountFinancialYear::select(DB::raw('DATE_FORMAT(financial_start_year, "%d-%m-%Y") AS financial_start_year'), DB::raw('DATE_FORMAT(financial_end_year, "%d-%m-%Y") AS financial_end_year'))->where('organization_id', Session::get('organization_id'))->where('status', '1')->first();

		$account_ledgers = $this->get_all_ledgers();

		$settings = Setting::select('id', 'status')->where('name', 'ledger_approval')->where('organization_id', Session::get('organization_id'))->first();


		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',$organization_id);
        $group_name->prepend('Select Group Name','');

		return view('accounts.ledger',compact('account_ledgers', 'settings', 'financialyear', 'title', 'payment', 'terms', 'state','group_name'));
	}

	public function statement()
	{
		$financialyear = AccountFinancialYear::select(DB::raw('DATE_FORMAT(financial_start_year, "%d-%m-%Y") AS financial_start_year'), DB::raw('DATE_FORMAT(financial_end_year, "%d-%m-%Y") AS financial_end_year'))->where('organization_id', Session::get('organization_id'))->where('status', '1')->first();

		$account_ledgers = $this->get_all_ledgers(true);

		$settings = Setting::select('id', 'status')->where('name', 'ledger_approval')->where('organization_id', Session::get('organization_id'))->first();


		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id');
		$state->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');

		$group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',Session::get('organization_id'));
        $group_name->prepend('Select Group Name','');

		return view('accounts.statement',compact('account_ledgers', 'settings', 'financialyear', 'title', 'payment', 'terms', 'state','group_name'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($ledger_type=false)
	{
		$organization_id = Session::get('organization_id');

		$groups = AccountGroup::where('organization_id',$organization_id)
				->pluck('display_name','id')
				->prepend('Select Ledger Group','');

		$ledger_types = AccountLedgerType::all();
		
		$bank = Bank::distinct()->get(['bank'])->pluck('bank', 'bank')->prepend('Select Bank','');
		
		$account_type = BankAccountType::pluck('display_name', 'display_name')->prepend('Select Account Type', '');
		
		$nbfc   = DB::table('nbfcs')->where('status', '1')->pluck('name', 'name')->prepend('Select NBFC', '');
	
		$person_types = AccountPersonType::where('status', '1')->get();

		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('user_type', 0)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id')->prepend('Select Person', '');
	

		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('user_type', 1)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id')->prepend('Select Company', '');

		$country = Country::where('name', 'India')->first();

		$state = State::where('country_id', $country->id)->pluck('name', 'id')->prepend('Select State', '');

		$title = PeopleTitle::pluck('display_name','id')->prepend('Title','');
		
		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id')->prepend('Select Title','');
		

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id')->prepend('Select Terms','');
	
		$group_name = CustomerGroping::pluck('display_name','id')->where('organization_id',$organization_id)->prepend('Select Group Name','');


        if($ledger_type==1){
        		$ledger_types = AccountLedgerType::where('name','=','impersonal')->first()->id;
        	  	$ledger_group_type_id=AccountGroup::where('organization_id',$organization_id)
						->where('display_name','=','Direct Expenses')
						->first()->id;

		return view('accounts.ledger_create_popup', compact('people', 'business', 'state', 'title', 'payment', 'terms', 'groups', 'ledger_types', 'bank', 'account_type', 'nbfc', 'person_types','group_name','ledger_group_type_id'));	
			
        }
        else
        {
        	return view('accounts.ledger_create', compact('people', 'business', 'state', 'title', 'payment', 'terms', 'groups', 'ledger_types', 'bank', 'account_type', 'nbfc', 'person_types','group_name'));	
        }

	}

	public function check_ledgers(Request $request) {

		$organization_id = Session::get('organization_id');

		$ledgers = AccountLedger::where('name', $request->ledger_name);
		if($request->id != null) {
			$ledgers->where('id', '!=', $request->id);
		}
		$ledgers->where('organization_id', $organization_id);

		$ledger = $ledgers->first();

		if(!empty($ledger->id)) {
			echo 'false';
		} else {
			echo 'true';
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$person_type = $request->input('person_type');
		$person = null;
		$organization = Organization::findOrFail(Session::get('organization_id'));	  	

		$name = ($request->input('ledger_name') != "") ? $request->input('ledger_name') : null;
		//$account_type = $organization;
		$display_name = $request->input('display_name');
		$ledger_type = $request->input('ledger_type');
		$person_id = ($request->input('person_id') != "") ? $request->input('person_id') : null;
		$business_id = ($request->input('business_id') != "") ? $request->input('business_id') : null;
		$group_id = ($request->input('group_id') != "") ? $request->input('group_id') : null;
		
		$opening_date = ($request->input('opening_balance_date')) != null ? Carbon::parse($request->input('opening_balance_date'))->format('Y-m-d') : date('Y-m-d');
		$opening_type = $request->input('opening_balance_type');
		$opening_balance = $request->input('opening_balance');
		$id = Session::get('organization_id');
		$bank_account_type = ($request->input('account_type') != "") ? $request->input('account_type') : null;
		$account_no = ($request->input('account_no') != "")  ? $request->input('account_no') : null;
		$bank_name = ($request->input('bank_name') != "") ? $request->input('bank_name') : null;
		$bank_branch = ($request->input('bank_branch') != "") ? $request->input('bank_branch') : null;
		$ifsc = ($request->input('ifsc') != "") ? $request->input('ifsc') : null;
		$micr = ($request->input('micr') != "") ? $request->input('micr') : null;
		$nbfc_name = ($request->input('nbfc_name') != "") ? $request->input('nbfc_name') : null;
		$nbfc_branch = ($request->input('nbfc_branch') != "") ? $request->input('nbfc_branch') : null;

		$group_name = AccountGroup::findorFail($group_id)->display_name;
		//return $group_name;

		$ledger_id =  Custom::create_ledger($display_name, $organization, $display_name, $ledger_type, $person_id, $business_id, $group_id, $opening_date, $opening_type, $opening_balance, Session::get('ledger_approval'), '1', $id, false, $bank_account_type, $account_no, $bank_name, $bank_branch, $ifsc, $micr, $nbfc_name, $nbfc_branch);

		if($ledger_id == 0) {
			return response()->json(['status' => 0, 'message' => 'Ledger'.config('constants.flash.exist'), 'data' => []]);
		}

		if($request->input('person_id') != "") {
			$name = Person::findOrFail($request->input('person_id'));
			$person = $name->first_name;

		}elseif($request->input('business_id') != "") {
			$name = Business::findOrFail($request->input('business_id'));
			$person = $name->business_name;
		}
		if($ledger_id) { 

			for($i=0;$i<count($person_type);$i++) {
				DB::table('ledger_person_types')->insert(['ledger_id' => $ledger_id, 'person_type_id' => $person_type[$i]]);
			} 

			if($request->input('credit_period') != null){

				$account_ledger_creditinfo = AccountLedgerCreditInfo::findOrFail($ledger_id);

				$account_ledger_creditinfo->credit_period = $request->input('credit_period');
				if($request->input('min_debit_limit') != "") {
					$account_ledger_creditinfo->min_debit_limit =$request->input('min_debit_limit');
				}
				if($request->input('max_debit_limit') != "") {
					$account_ledger_creditinfo->max_debit_limit = $request->input('max_debit_limit');
				}
				if($request->input('min_credit_limit') != "") {
					$account_ledger_creditinfo->min_credit_limit =$request->input('min_credit_limit');
				}
				if($request->input('max_credit_limit') != "") {
					$account_ledger_creditinfo->max_credit_limit=$request->input('max_credit_limit');
				}
				$account_ledger_creditinfo->warning_status  = $request->input('warning_status ');
				$account_ledger_creditinfo->id=$ledger_id;
				$account_ledger_creditinfo->save();

				Custom::userby($account_ledger_creditinfo, true);  
			}                

			if($request->input('cheque_book') != null)
			{

				$chequebook = AccountChequeBook::where('ledger_id', $ledger_id)->first();
				if($chequebook != null) {
					$chequebook->status = 0;
					$chequebook->save();
				}
				

				$account_chequebook = new AccountChequeBook; 
				$account_chequebook->ledger_id = $ledger_id;
				$account_chequebook->book_no = $request->input('book_no');
				$account_chequebook->no_of_leaves = $request->input('no_of_leaves');
				$account_chequebook->cheque_no_from = $request->input('cheque_no_from');
				$account_chequebook->cheque_no_to = $request->input('cheque_no_to');
				$account_chequebook->next_book_warning = $request->input('next_book_warning');
				$account_chequebook->status = 1;
				$account_chequebook->save();
				Custom::userby($account_chequebook, true);
				Custom::add_addon('records');
			}            

			if($request->input('nbfc_name') != null) {
				$nbfc_master = new Nbfc;
				$nbfc_master->name = ($request->input('nbfc_name') != "") ? $request->input('nbfc_name') : "";
				$nbfc_master->branch = ($request->input('nbfc_branch') != "") ? $request->input('nbfc_branch') : "";
				$nbfc_master->status = 0;
				$nbfc_master->save();
				Custom::userby($nbfc_master, true);
			}
		}

		Custom::add_addon('records');

		$ledger = AccountLedger::findorFail($ledger_id);

		return response()->json(['status' => 1, 'message' => 'Ledger'.config('constants.flash.added'), 'data' => ['id' => $ledger->id, 'name' => $ledger->display_name, 'display_name' => $group_name, 'opening_balance' => $ledger->opening_balance, 'opening_balance_type' => ($ledger->opening_balance_type == 'debit') ? ' Dr' : ' Cr', 'delete_status' => $ledger->delete_status, 'approval_status' => $ledger->approval_status]]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$account_ledgers = AccountLedger::select('account_ledgers.id','account_ledgers.display_name', 'account_ledgers.approval_status', 'account_groups.display_name AS ledger_group_name')
		->leftJoin('account_groups', 'account_ledgers.group_id', '=' , 'account_groups.id')
		->where('account_ledgers.id', '=', $id)->get();

		//return $account_ledgers;

		return view('accounts.ledger_show',compact('account_ledgers'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$organization_id = Session::get('organization_id');
		$query = AccountLedger::select('account_ledgers.*', 'account_ledgers.id as ledger_id', 'account_groups.name AS ledger_group_name', 'account_ledger_credit_infos.*', 'account_ledger_types.id as ledger_type', 'account_ledger_types.name as ledger_type_name', 'account_ledger_types.id as ledger_type_id', 'account_ledger_types.id as ledger_group_type_id', 'account_ledgers.group_id', 'account_cheque_books.id AS cheque_book_id', 'account_cheque_books.book_no', 'account_cheque_books.no_of_leaves', 'account_cheque_books.cheque_no_from', 'account_cheque_books.cheque_no_to', 'account_cheque_books.next_book_warning', 'account_ledgers.group_id');
		$query->leftJoin('account_ledger_types', 'account_ledgers.ledger_type', '=', 'account_ledger_types.id');
		$query->leftJoin('account_groups', 'account_ledgers.group_id', '=' , 'account_groups.id');
		$query->leftJoin('account_ledger_credit_infos', 'account_ledgers.id' , '=' , 'account_ledger_credit_infos.id');
		$query->leftJoin('persons', 'persons.id', '=', 'account_ledgers.person_id');
		$query->leftJoin('businesses', 'account_ledgers.business_id', '=', 'businesses.id');
		$query->leftJoin('account_cheque_books', 'account_ledgers.id', '=', 'account_cheque_books.id');
		$query->where('account_ledgers.id', '=', $id);

		$ledger = $query->first();

		$chequebook = AccountChequeBook::where('id', $id)->first(); 

		$person_types = AccountPersonType::where('status', '1')->get();

		$nbfc_name = DB::table('nbfcs')->where('status', '1')->orWhere('name', $ledger->nbfc_name)->pluck('name', 'name');

		$nbfc_branch = DB::table('nbfcs')->orWhere('name', $ledger->nbfc_name)->pluck('branch', 'branch');

	   /* $account_ledger_groups = AccountGroup::where('organization_id',$organization_id)->pluck('display_name','id');*/

		$account_ledger_groups = AccountGroup::where('id','!=', $id)->where('organization_id',$organization_id)->pluck('display_name', 'id');
		$account_ledger_groups->prepend('Select Ledger Group', '');

		$ledger_head = AccountGroup::select('account_heads.display_name')->leftJoin('account_heads', 'account_heads.id', '=' , 'account_groups.account_head')->where('account_groups.id', $ledger->group_id)->first()->display_name;

		$ledger_types = AccountLedgerType::all();

		//$businessprofessionalism =  BusinessProfessionalism::pluck('name', 'id');
		$bank = Bank::distinct()->pluck('bank', 'bank');
		$bank->prepend('Select Bank', '');
		
		$account_type = BankAccountType::pluck('display_name', 'display_name');
		$account_type->prepend('Select Account Type', '');

		$nbfc = DB::table('nbfcs')->where('status', '1')->pluck('name', 'name');
		$nbfc->prepend('Select NBFC', '');

		$branches = Bank::distinct()->where('ifsc', '=', $ledger->ifsc)->pluck('branch', 'branch');
		$branch = Bank::distinct()->where('ifsc', '=', $ledger->ifsc)->first();

		$state = State::where('status', '1')->orderBy('name')->pluck('name', 'id');
		$state->prepend('Select State', '');

		$people = People::select('person_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'person_id')->where('person_id', $ledger->person_id)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');

	   
		$business = People::select('business_id AS id', DB::raw('IF(mobile_no, CONCAT(display_name, " - " , mobile_no), display_name) AS name'), 'business_id')->where('business_id', $ledger->business_id)->where('organization_id', Session::get('organization_id'))->pluck('name', 'id');


		$title = PeopleTitle::pluck('display_name','id');
		$title->prepend('Title','');

		$payment = PaymentMethod::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$payment->prepend('Select Title','');

		$terms = Term::where('organization_id', Session::get('organization_id'))->pluck('display_name','id');
		$terms->prepend('Select Terms','');


		$states = [];
		$cities = [];
		$selected_list = null;

		if($ledger->bank_name != "") {
			$cities  = Bank::distinct()->where('bank', '=', $ledger->bank_name)->where('city', '=', $branch->city)->orderBy('city', 'asc')->pluck('city', 'city');      
	  
			$city  = Bank::distinct()->where('bank', '=', $ledger->bank_name)->where('state', '=', $branch->city)->orderBy('city', 'asc')->pluck('city', 'city');
	 
			$states  = Bank::distinct()->where('bank', '=', $ledger->bank_name)->orderBy('state', 'asc')->pluck('state', 'state');

			$selected_list = Bank::select('state', 'city')->where('bank', '=', $ledger->bank_name)->where('ifsc', '=', $ledger->ifsc)->first();
		}

		$selected_person  = [];

		/*foreach($ledger->person_type as $person) {
			$selected_person[] = $person->id;
		}*/
		//return $ledger;
		return view('accounts.ledger_edit', compact('ledger', 'person_types','nbfc_name', 'people', 'business', 'nbfc_branch', 'title', 'payment', 'terms', 'ledger_types','account_type','bank','nbfc', 'account_ledger_groups', 'ledger_head','states', 'branches', 'cities', 'state', 'selected_list', 'branch', 'chequebook', 'selected_person'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		//dd($request->all());

		$person_type = $request->input('person_type');

		$settings = Setting::select('status')->where('name', 'ledger_approval')->where('organization_id', Session::get('organization_id'))->first();
		$organization = Organization::findOrFail(Session::get('organization_id'));
		$id = $request->input('id');
		/*$person_types = AccountPersonType::where('status', '1')->get();
		DB::table('ledger_person_type')->where('ledger_id', $id)->delete();
		for($i=0;$i<count($person_type);$i++) {
				DB::table('ledger_person_type')->insert(['ledger_id' => $id, 'person_type_id' => $person_type[$i]]);
			}*/
		$group_id = ($request->input('group_id') != "") ? $request->input('group_id') : null;
		$group_name = AccountGroup::findorFail($group_id)->display_name;

		$account_ledger =  AccountLedger::findOrFail($id);

		if($request->input('name') != "") {
			$account_ledger->name = $request->input('name');
			$account_ledger->display_name = $request->input('display_name');
		} else {
			$account_ledger->name = null;
			$account_ledger->display_name = null;
		}

		if($request->input('crm_id') != "") {
			$account_ledger->crm_id = $request->input('crm_id');
		}
		 
		if($request->input('bcrm_id') != "") {
			$account_ledger->bcrm_id = $request->input('bcrm_id');
		}

		if($request->input('group_id') != "") {
			$account_ledger->group_id = $request->input('group_id');
		} else {
			$account_ledger->group_id = null;
		}
		
		//$account_ledger->parent_group_id = $request->input('account_ledger_type');        

		if($request->input('opening_balance_date') != "") {
			$account_ledger->opening_balance_date = Carbon::parse($request->input('opening_balance_date'))->format('Y-m-d');
		}

		if($request->input('opening_balance') != "") {
			$account_ledger->opening_balance=$request->input('opening_balance');
		}

		if($request->input('opening_balance_type') != "") {
			$account_ledger->opening_balance_type=$request->input('opening_balance_type');
		}

		$account_ledger->account_type = $request->input('account_type');
		$account_ledger->account_no=$request->input('account_no');
		$account_ledger->bank_name =$request->input('bank_name');
		$account_ledger->bank_branch = $request->input('bank_branch');
		$account_ledger->ifsc = $request->input('ifsc');
		$account_ledger->micr = $request->input('micr');

		$account_chequebook =  AccountChequeBook::where('id', $id)->get();
		//dd($id);                       
			
		if($request->input('cheque_book') != "")
		{
			$book = $request->input('book_no');
			$no_of_leaves = $request->input('no_of_leaves');
			$cheque_no_from = $request->input('cheque_no_from');
			$cheque_no_to = $request->input('cheque_no_to');
			$next_book_warning = $request->input('next_book_warning');

			$chequebook = AccountChequeBook::where('ledger_id', $ledger_id)->first();
			if($chequebook != null) {
				$chequebook->status = 0;
				$chequebook->save();
			}

			$account_chequebook = AccountChequeBook::updateOrCreate(
				['ledger_id'=>$id],
				['book_no'=>$book,
				'no_of_leaves'=>$no_of_leaves,
				'cheque_no_from'=>$cheque_no_from,
				'cheque_no_to'=>$cheque_no_to,
				'status' => 1,
				'next_book_warning'=>$next_book_warning]
			);

			$account_chequebook->save();
			Custom::userby($account_chequebook, false);
		}       
 
		if($request->input('nbfc_name_master') != "" || $request->input('nbfc_name') != "") 
		{
			$nbfc_master = new Nbfc; 
			$nbfc_master->name = ($request->input('nbfc_name_master') != "") ? $request->input('nbfc_name_master') : $request->input('nbfc_name');
			$nbfc_master->branch = ($request->input('nbfc_branch_master') != "") ? $request->input('nbfc_branch_master') : $request->input('nbfc_branch');
			$nbfc_master->status = 0;
			$nbfc_master->save();
			Custom::userby($nbfc_master, true);
		}        

		$account_ledger->save();
		Custom::userby($account_ledger, false);

		$creditinfo =  AccountLedgerCreditInfo::where('id', $id)->get(); 

		if (!$creditinfo->isEmpty()) {

			$account_ledger_creditinfo =  AccountLedgerCreditInfo::findOrFail($id); 

			$account_ledger_creditinfo->credit_period   = $request->input('credit_period');
			$account_ledger_creditinfo->min_debit_limit =$request->input('min_debit_limit');
			$account_ledger_creditinfo->max_debit_limit = $request->input('max_debit_limit');
			$account_ledger_creditinfo->min_credit_limit=$request->input('min_credit_limit');
			$account_ledger_creditinfo->max_credit_limit=$request->input('max_credit_limit');
			$account_ledger_creditinfo->warning_status  = $request->input('warning_status ');
			$account_ledger_creditinfo->save();
			Custom::userby($account_ledger_creditinfo, false);
		}

		return response()->json(['status' => 1, 'message' => 'Ledger'.config('constants.flash.updated'), 'data' => ['id' => $account_ledger->id, 'name' => $account_ledger->display_name, 'display_name' => $group_name, 'opening_balance' => $account_ledger->opening_balance, 'opening_balance_type' => ($account_ledger->opening_balance_type =='debit') ? 'Dr' : 'Cr', 'delete_status' => $account_ledger->delete_status, 'approval_status' => $account_ledger->approval_status]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{	
		$debit_ledger = AccountTransaction::where('debit_ledger_id', $request->id)->get();

		if(count($debit_ledger) > 0)
		{
			return response()->json(['status' => 0, 'message' => 'Ledger has transactions, can not be deleted.', 'data' => []]);

		}else{

			$ledger = AccountLedger::findOrFail($request->id);
			$ledger->delete();
			Custom::delete_addon('records');

			return response()->json(['status' => 1, 'message' => 'Ledger'.config('constants.flash.deleted'), 'data' => []]);
		}		
	}

	public function status(Request $request)
	{
		AccountLedger::where('id', $request->input('id'))->update(['status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function approval_status(Request $request)
	{
		AccountLedger::where('id', $request->input('id'))->update(['approval_status' => $request->input('status')]);

		return response()->json(array('result' => "success"));
	}

	public function multidestroy(Request $request)
	{
		$ledgers = explode(',', $request->id);
		$ledger_list = [];

		foreach ($ledgers as $ledger_id) {
			$ledger = AccountLedger::findOrFail($ledger_id);
			$ledger->delete();
			$ledger_list[] = $ledger_id;
			Custom::delete_addon('records');
		}

		return response()->json(['status'=>1, 'message'=>'Ledger'.config('constants.flash.deleted'),'data'=>['list' => $ledger_list]]);
	}

	public function multiapprove(Request $request)
	{
		$ledgers = explode(',', $request->id);

		$ledger_list = [];

		foreach ($ledgers as $ledger_id) {
			AccountLedger::where('id', $ledger_id)->update(['status' => $request->input('status')]);;
			$ledger_list[] = $ledger_id;
		}

		return response()->json(['status'=>1, 'message'=>'Ledger'.config('constants.flash.updated'),'data'=>['list' => $ledger_list]]);
	}	

	public function get_ledger_group(Request $request)
	{
		$ledger_group = [];
		$ledger_parents = AccountGroup::select(DB::raw('DISTINCT(account_groups.id) AS id'), 'account_groups.name', 'account_groups.display_name', 'account_groups.opening_type', DB::raw('COALESCE(account_groups.parent_id, 0) AS parent'))
		->leftJoin('account_ledgertype_group', 'account_groups.id', '=', 'account_ledgertype_group.group_id')
		->where('organization_id',  Session::get('organization_id'))
		->where('status', 1)
		->where('account_groups.approval_status', 1)
		->where('account_ledgertype_group.ledger_type_id', $request->id)->get();

		foreach ($ledger_parents as $ledger_parent) {
		   $ledger_group[] = ["id" => $ledger_parent->id,"name" => $ledger_parent->name, "display_name" => $ledger_parent->display_name, "opening_type" => $ledger_parent->opening_type, "parent" => $ledger_parent->parent];
		}

		//dd($this->get_ledger_child_group($ledger_parent, $ledger_parents, $request->id));

		//return response()->json(array('result_ledger' => $ledger_parents));

		return $this->tree($ledger_group);
	}

	public function tree($elements, $parentId = 0, $i = 0) {

		$html = "";
		$space = '';
		if(!empty(array_filter($elements))) {
			$parent = null;
			foreach ($elements as $element) {
				if ($element['parent'] == $parentId) {                    

				
					$html .= "<option class='opt_group_list' value='".$element['id']."' data-type='".$element['opening_type']."'>".$space.$element['display_name']."</option>";

					$children = $this->tree($elements, $element['id'], $i++);
					if ($children) {
						$html .= $children;
					}
				}
				if($parent != $parentId) {
					$parent = $parentId; 
					$space .= '&nbsp;&nbsp;&nbsp;'; 
				}
			}
		}
		return $html;
	}

	public function bankState(Request $request)
	{
		$bank = $request->bank;
	   
		$state = Bank::distinct()
					->where('bank', '=', $bank)
					->orderBy('state', 'asc')
					->get(['state']);

		$myarray = array();
		$myarray['state'] = $state;

		return response()->json($myarray);
	}

	public function bankCity(Request $request)
	{
		$state = $request->state;
		$bank = $request->bank;
	   
		$city = Bank::distinct()
					->where('bank', '=', $bank)
					->where('state', '=', $state)
					->orderBy('city', 'asc')
					->get(['city']);
	 
		 $myarray = array();
		 $myarray['city'] = $city;

	   return response()->json($myarray);
	}

	public function bankBranch(Request $request)
	{
		$city = $request->city;
		$state = $request->state;
		$bank = $request->bank;
	   
		$branch = Bank::distinct()
					->where('city', '=', $city)
					->where('bank', '=', $bank)
					->where('state', '=', $state)
					->orderBy('branch', 'asc')
					->get(['branch']);

	   $myarray = array();
	   $myarray['branch'] = $branch;
   
	   return response()->json($myarray);
	}

	public function bankCode(Request $request)
	{
		$branch = $request->branch;
		$city = $request->city;
		$state = $request->state;
		$bank = $request->bank;
	   
		$code = Bank::select('ifsc', 'micr')
					->where('branch', '=', $branch)
					->where('city', '=', $city)
					->where('bank', '=', $bank)
					->where('state', '=', $state)
					->first();
	
	   return response()->json($code);
	}

	public function nbfcBranch(Request $request)
	{
		$cat_id = $request->cat_id;
	   
		$branch = Nbfc::distinct()
					->where('name', '=', $cat_id)
					->orderBy('branch', 'asc')
					->get(['branch']);

	   $myarray = array();
	   $myarray['branch'] = $branch;
   
	   return response()->json($myarray);
	}


	public function get_all_ledgers($statement = null)
	{
		$financialyear = AccountFinancialYear::select('financial_start_year','financial_end_year')->where('organization_id', Session::get('organization_id'))->where('status', '1')->first();

		if($statement != null) {
			$statement = " AND account_ledgers.approval_status = 1 AND account_ledgers.approval_status = 1 ";
		}
		

		$account_ledgers = DB::select("SELECT 
  account_ledgers.id,
  account_ledgers.display_name AS ledger,
  account_groups.display_name AS ledger_group_name,
  account_groups.id AS parent,
  IF(
	opening_balance_type = 'Debit',
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) - opening_balance,
	(
	  COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	) + opening_balance
  ) AS closing_balance,
  opening_balance,
  opening_balance_type,
  IF(
	IF(
	  opening_balance_type = 'Debit',
	  (
		COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	  ) - opening_balance,
	  (
		COALESCE(credit_account.credit, 0) - COALESCE(debit_account.debit, 0)
	  ) + opening_balance
	) > 0,
	'Cr',
	'Dr'
  ) AS balance_type,
  account_ledgers.status,account_ledgers.approval_status,account_ledgers.delete_status
FROM
  account_ledgers 
  
  LEFT JOIN account_transactions 
	ON account_transactions.debit_ledger_id = account_ledgers.id 
  LEFT JOIN account_groups
	ON account_ledgers.group_id = account_groups.id
  LEFT JOIN 
	(SELECT 
	  account_transactions.credit_ledger_id AS cr,
	  MIN(account_entries.date) AS cr_date,
	  SUM(
		account_transactions.amount
	  ) AS credit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.organization_id = ".Session::get('organization_id')."
		 AND account_entries.status = 1
	GROUP BY cr) AS credit_account 
	ON credit_account.cr = account_ledgers.id 
  LEFT JOIN 
	(SELECT 
	  account_transactions.debit_ledger_id AS dr,
	  MIN(account_entries.date) AS dr_date,
	  SUM(
		account_transactions.amount
	  ) AS debit 
	FROM
	  account_transactions 
	  LEFT JOIN account_entries 
		ON account_transactions.entry_id = account_entries.id 
		WHERE (account_entries.date BETWEEN '".$financialyear->financial_start_year."' AND '".$financialyear->financial_end_year."') AND account_entries.organization_id = ".Session::get('organization_id')."
		AND account_entries.status = 1
	GROUP BY dr) AS debit_account 
	ON debit_account.dr = account_ledgers.id 
	WHERE account_ledgers.organization_id = ".Session::get('organization_id')."
	$statement
	GROUP BY account_ledgers.id 
	ORDER BY account_groups.display_name
	");

		return $account_ledgers;
	}
}
