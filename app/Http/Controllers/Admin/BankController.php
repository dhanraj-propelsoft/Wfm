<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bank;

class BankController extends Controller
{
    public function index()
	{
			$banks = Bank::paginate(10);

			$last_edited = Bank::select('created_at')->take(1)->orderBy('id', 'DESC')->first();

			$last_edited_date = "";

			if($last_edited) {
					$last_edited_date = $last_edited->created_at->format('M j, Y');
			}

			return view('admin.banks', compact('banks', 'last_edited_date'));
	}

	public function store(Request $request)
		{
				$this->validate($request, [
						'bank' => 'required'
				]);

			 ini_set('max_execution_time', 1500);


			 Excel::filter('chunk')->load(Input::file('bank'))->chunk(250, function ($reader) {

			 $has_sheets = false;
					 
					 foreach ($reader->toArray() as $rows) {
								foreach ($rows as $row) {
										is_array($row) ? $has_sheets = true : $has_sheets = false;
								}
					 }

				
				if($has_sheets == true) {
								$reader->each(function($sheet) {
												foreach ($sheet->toArray() as $row) {
														//print_r($row);
													 $bank = new Bank;
													 $bank->bank = $row['bank'];
													 $bank->ifsc = $row['ifsc'];
													 $bank->micr = $row['micr_code'];
													 $bank->branch = $row['branch'];
													 $bank->address = $row['address'];
													 $bank->contact = $row['contact'];
													 $bank->city = $row['city'];
													 $bank->district = $row['district'];
													 $bank->state = $row['state'];
													 $bank->status = '1';
													 $bank->save();
												}  
								});
						} 

						else {
								foreach ($reader->toArray() as $row) {
												//print_r($row);
												$bank = new Bank;
												$bank->bank = $row['bank'];
												$bank->ifsc = $row['ifsc'];
												$bank->micr = $row['micr_code'];
												$bank->branch = $row['branch'];
												$bank->address = $row['address'];
												$bank->contact = $row['contact'];
												$bank->city = $row['city'];
												$bank->district = $row['district'];
												$bank->state = $row['state'];
												$bank->status = '1';
												$bank->save();
										}

				}

				}, false);
				
				Session::flash('flash_message', 'Bank list successfully added!');

				return redirect()->back();
		}
}
