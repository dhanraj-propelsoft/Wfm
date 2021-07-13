<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AccountChequeBook;
use App\Http\Requests;
use App\Custom;
use Validator;
use Session;

class ChequeBookController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');
		
		$chequebook = AccountChequeBook::select('account_ledgers.id', 'account_ledgers.name', 'account_ledgers.account_type', 'account_ledgers.account_no', 'account_ledgers.bank_name', 'account_ledgers.bank_branch', 'account_cheque_books.book_no', 'account_cheque_books.no_of_leaves', 'account_cheque_books.cheque_no_from', 'account_cheque_books.cheque_no_to', 'account_cheque_books.next_book_warning');
		$chequebook->leftJoin('account_ledgers', 'account_ledgers.id', '=', 'account_cheque_books.ledger_id');
		$chequebook->where('account_ledgers.organization_id', $organization_id);
		$chequebooks = $chequebook->get();

		return view('accounts.cheque_books', compact('chequebooks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$chequebook = AccountChequeBook::select('account_ledgers.id', 'account_ledgers.name', 'account_ledgers.account_type', 'account_ledgers.account_no', 'account_ledgers.bank_name', 'account_ledgers.bank_branch', 'account_ledgers.micr', 'account_ledgers.ifsc', 'account_cheque_books.book_no', 'account_cheque_books.no_of_leaves', 'account_cheque_books.cheque_no_from', 'account_cheque_books.cheque_no_to', 'account_cheque_books.next_book_warning');
		$chequebook->leftJoin('account_ledgers', 'account_ledgers.id', '=', 'account_cheque_books.ledger_id');
		$chequebook->where('account_cheque_books.ledger_id', $id);
		$chequebook->where('account_cheque_books.status', 1);
		$chequebooks = $chequebook->first();

		return view('accounts.cheque_books_edit', compact('chequebooks'));
	}

	public function continue_edit($id)
	{
		$chequebook = AccountChequeBook::select('account_ledgers.id', 'account_ledgers.name', 'account_ledgers.account_type', 'account_ledgers.account_no', 'account_ledgers.bank_name', 'account_ledgers.bank_branch', 'account_ledgers.micr', 'account_ledgers.ifsc', 'account_cheque_books.book_no', 'account_cheque_books.no_of_leaves', 'account_cheque_books.cheque_no_from', 'account_cheque_books.cheque_no_to', 'account_cheque_books.next_book_warning');
		$chequebook->leftJoin('account_ledgers', 'account_ledgers.id', '=', 'account_cheque_books.ledger_id');
		$chequebook->where('account_cheque_books.ledger_id', $id);
		$chequebook->where('account_cheque_books.status', 1);
		$chequebooks = $chequebook->first();

		return view('accounts.cheque_books_edit', compact('chequebooks', 'id'));
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
		if($request->input('continue')=='1') {
			
			$cheque_book = AccountChequeBook::where('account_cheque_books.ledger_id', $request->input('id'))->where('account_cheque_books.status', 1)->first();
			$cheque_book->no_of_leaves = (($cheque_book->no_of_leaves) + ($request->input('no_of_leaves')));
			$cheque_book->cheque_no_from = ($cheque_book->cheque_no_from+1);       
			$cheque_book->cheque_no_to = ($request->input('no_of_leaves')+$request->input('cheque_no_from')+1);
			$cheque_book->next_book_warning = $request->input('next_book_warning');
			$cheque_book->save();
			Custom::userby($cheque_book, false);
		} else {
			
			$cheque_book = AccountChequeBook::where('account_cheque_books.ledger_id', $request->input('id'))->where('account_cheque_books.status', 1)->first();
			$cheque_book->book_no = $request->input('book_no');
			$cheque_book->no_of_leaves = $request->input('no_of_leaves');            
			$cheque_book->cheque_no_from = $request->input('cheque_no_from');
			$cheque_book->cheque_no_to = ($request->input('no_of_leaves')+$request->input('cheque_no_from')-1);
			$cheque_book->next_book_warning = $request->input('next_book_warning');
			$cheque_book->save();

			$cheque_book->save();
			Custom::userby($cheque_book, false);
		}

		return response()->json(['status' => 1, 'message' => 'Cheque Book'.config('constants.flash.updated'), 'data' => ['id' => $cheque_book->id, 'book_no' => $cheque_book->book_no, 'no_of_leaves' => $cheque_book->no_of_leaves, 'cheque_no_from' => $cheque_book->cheque_no_from, 'cheque_no_to' => $cheque_book->cheque_no_to, 'next_book_warning' => ($cheque_book->next_book_warning != null) ? $cheque_book->next_book_warning : ""]]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request)
	{
		$cheque_book = AccountChequeBook::findOrFail($request->input('id'));
		$cheque_book->delete();

		Custom::delete_addon('records');

		return response()->json(['status' => 1, 'message' => 'Cheque Book'.config('constants.flash.deleted'), 'data' => []]);
	}
}
