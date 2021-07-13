<?php

namespace App\Http\Controllers\Hrm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HrmDocumentType;
use App\HrmDocument;
use Carbon\carbon;
use App\Custom;
use Session;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organization_id=session::get('organization_id');

        $documents=HrmDocument::select('hrm_documents.id','hrm_documents.name','hrm_document_types.name as document_type','hrm_documents.valid_from','hrm_documents.status','hrm_documents.created_at')->where('organization_id',$organization_id)->leftjoin('hrm_document_types','hrm_document_types.id','=','hrm_documents.document_type_id')->get(); 
        //dd($documents);
        $types=HrmDocumentType::pluck('name','id');
        $types->prepend('Choose a Document Type','');

        return view('hrm.hrm_document',compact('types','documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types=HrmDocumentType::pluck('name','id');
        $types->prepend('Choose a Document Type','');

        return view('hrm.hrm_document_create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request,[
            'name' =>'required',
            'document_type'=> 'required',
            'myfile.*' => 'mimes:doc,docx,pdf,xls,xlsx'
        ]);
        $organization_id=session::get('organization_id');


        if($request->hasfile('myfile'))
       {
           if($request->file('myfile'))
            {
                $text = $request->file('myfile')->getClientOriginalname();
                $request->file('myfile')->move(public_path().'/files/',$text);
             
            }

       }

        $document= new HrmDocument;
        $document->name=$request->input('name');
        $document->document_type_id=$request->input('document_type');
        $document->summary=$request->input('summary');
        if($request->input('from') != null)
        {
        $document->valid_from=($request->input('from') != null)? carbon::parse($request->input('from'))->format('Y-m-d'): null;
        }
        if($request->input('to') != null)
        {
        $document->valid_to=($request->input('to') != null)? carbon::parse($request->input('to'))->format('Y-m-d'): null;
        }
        
        $document->organization_id=$organization_id;
        $document->save();

       $upload_at=HrmDocument::select('created_at')->where('organization_id',$organization_id);

        $documenttype=HrmDocumentType::findorfail( $document->document_type_id)->name;
        Custom::userby($document, true);

        return response()->json(['status'=>0 ,'message' =>'document'.config('constants.flash.added'), 'data' =>['id' => $document->id ,'name' => $document->name, 'document_type' => $documenttype ,'uploaded_on' => $upload_at,'valid_from' => $document->valid_from]]);


    }
    public function document_status(Request $request)
    {
        $vacancy_status=HrmDocument::where('id',$request->input('id'))
        ->update(['status'=>$request->input('status')]);
        //dd($vacancy_status);
        return response()->json(['status'=>$request->input('status')]);
    }

    public function document_type_search(Request $request)
    {
        //dd($request->all());
        $type_id=$request->input('id');

        //$type_name=HrmDocumentType::findorfail($type_id)->name;

        //dd($type_name);

        $type=HrmDocument::select('hrm_documents.id','hrm_documents.name','hrm_document_types.name as type_name','hrm_documents.valid_from','hrm_documents.updated_at')->leftjoin('hrm_document_types','hrm_document_types.id','=','hrm_documents.document_type_id')->where('document_type_id','LIKE',$type_id)->get();
       //dd($type);

        if(count($type)>0)
        {
            return response()->json(['type' => $type]);
        }
        else
        {
            $message="No details found.Search for again";
            return response()->json(['message' => $message ]);
        }


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
        $organization_id=session::get('organization_id');
        $documents=HrmDocument::where('id',$id)->where('organization_id',$organization_id)->first();
        $types=HrmDocumentType::pluck('name','id');
        $types->prepend('Choose a Document Type','');

        return view('hrm.hrm_document_edit',compact('types','documents'));
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
        $this->validate($request,[
            'name' =>'required',
            'document_type'=> 'required'
        ]);
        $organization_id=session::get('organization_id');

        $document = HrmDocument::findOrFail($request->input('id'));

        $document->name=$request->input('name');
        $document->document_type_id=$request->input('document_type');
        $document->summary=$request->input('summary');
        if($request->input('from') != null)
        {
        $document->valid_from=($request->input('from') != null)? carbon::parse($request->input('from'))->format('Y-m-d'): null;
        }
        if($request->input('to') != null)
        {
        $document->valid_to=($request->input('to') != null)? carbon::parse($request->input('to'))->format('Y-m-d'): null;
        }
        $document->organization_id=$organization_id;
        $document->save();

        Custom::userby($document, true);
        return response()->json(['status'=>0 ,'message' =>'document'.config('constants.flash.added'), 'data' =>['id' => $document->id ,'name' => $document->name, 'document_type' => $document->document_type_id ,'uploaded_on' => $document->created_at,'valid_from' => $document->valid_from]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       $document=HrmDocument::where('id',$request->id)->delete();
       return response()->json(['status'=>1, 'message' =>'document'.config('constants.flash.added'),'data' =>[]]);
    }
}
