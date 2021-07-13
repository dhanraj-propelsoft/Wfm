<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmRoaster;
use App\Custom;
use Session;

class RoasterController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organization_id = Session::get('organization_id');

		$roasters = HrmRoaster::where('organization_id', $organization_id)->pluck('name', 'id');
		return view('hrm.roaster', compact('roasters'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('hrm.roaster_create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
            'name' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);

        $organization_id = Session::get('organization_id');

        $roaster = new HrmRoaster;
        $roaster->name = $request->input('name');
        $roaster->from_date = Carbon::parse($request->input('from_date'))->format('Y-m-d');
        $roaster->to_date = Carbon::parse($request->input('to_date'))->format('Y-m-d');
        $roaster->description = $request->input('description');
        $roaster->organization_id = $organization_id;
        $roaster->save();
        Custom::userby($department, true);

		return response()->json(['status' => 1, 'message' => 'Roaster'.config('constants.flash.added'), 'data' => ['id' => $department->id, 'name' => $roaster->name, 'parent_department' => $parent_name, 'description' => ($department->description != null) ? $department->description : ""]]);
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
