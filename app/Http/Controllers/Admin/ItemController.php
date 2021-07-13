<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GlobalItemMainCategory;
use App\GlobalItemCategory;
use App\GlobalItemModel;
use App\GlobalItemMake;
use App\GlobalItemType;
use App\GlobalItemCategoryType;
use App\User;
use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    

    public function main_category()
    {
        $item_main_categories = GlobalItemMainCategory::select('global_item_main_categories.status','global_item_main_categories.id', 'global_item_main_categories.name','users.name As user_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_main_categories.created_at, "%d %M, %Y") AS start_date'))->leftjoin('users','users.id','=','global_item_main_categories.created_by','organizations.business_id')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')
            
                ->where('global_item_main_categories.status', 1)
        ->orWhere(function ($query) {
            $query->where('global_item_main_categories.status', '=', 0);
        })
        ->groupBy('global_item_main_categories.id')
         //->orderBy('global_item_main_categories.id')
            //->orderBy('id', 'ASC')
         ->get();

        return view('admin.main_category', compact('item_main_categories'));
    }
    public function main_category_status(Request $request){

        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        GlobalItemMainCategory::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
                     

    // $UpdateData=['status' => $request->input('status')];
    //GlobalItemMainCategory::where('status', $request->input('status'))->update($UpdateData);
    //  return response()->json(array('result' => "success"));
       //  $status= GlobalItemMainCategory::findOrFail($id);
       // $status->status = $request->status;
    
       // $status->save();

    }
    public function main_category_create()
    {
    $inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();


         return view('admin.main_category_create', compact('inventory_types'));
    }
     public function main_category_store(Request $request)
    {
           //dd($request->all());   
        $category= new \App\GlobalItemMainCategory;
        $category->name=$request->get('categoryname');
        $category->display_name=$request->get('categoryname');
        $category->category_type_id=$request->get('types');
        $category->status=1;
          
                       $category->created_by=Auth::user()->id;
         $category->last_modified_by=Auth::user()->id;
        $category->save();


          $created_by =($category->created_by != null) ? User::findorFail($category->created_by)->name : "";
         
       return response()->json(['status' => 1, 'message' => 'Main Category'.config('constants.flash.added'), 'data' =>['id'=>$category->id,'name'=>$category->name,'created_by'=>$created_by,'created_at'=> $category->created_at->format('d F, Y'),'status'=>$category->status]]);
             
    }
    public function main_category_edit($id)
    {

    $main_edit_type= GlobalItemCategoryType::select('id', 'name','display_name')->where('status', 1)
    ->get();
    //dd($main_edit_type);
         // return view('admin.main_category_edit', compact('main_edit'));
        $main_edit_category = GlobalItemMainCategory::select('id', 'name','category_type_id')

    
        ->where('id',$id)->first();
      //dd($main_edit_category);
         return view('admin.main_category_edit', compact('main_edit_category','main_edit_type'));
       

    }
      //check main categoryname dublicate        
    
     public function check_categoryname_insert(Request $request) {
    //dd($request->all());     
        $categoryname = GlobalItemMainCategory::where('category_type_id', $request->types)
        ->where('name',$request->categoryname)
        ->first();
        if(!empty($categoryname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function check_categoryname_edit(Request $request) {
    // dd($request->all());     
        $categoryname = GlobalItemMainCategory::where('id','!=', $request->id)
        ->where('name', $request->categoryname)
        ->where('category_type_id', $request->types)
        ->first();
        if(!empty($categoryname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
      //update the maincategory

    public function main_category_update(Request $request){
       
    //dd($request->all());

        $main_category = GlobalItemMainCategory::findOrFail($request->input('id'));
        $main_category->name = $request->input('categoryname');
         $main_category->display_name=$request->input('categoryname');
         $main_category->category_type_id=$request->input('types');

     
        
        $main_category->save();
        //dd($main_category);
        
         $created_by =($main_category->created_by != null) ? User::findorFail($main_category->created_by)->name : "";
        
        $created_at=($main_category->created_at)?$main_category->created_at->format('d F, Y'): "";

          return response()->json([ 'message' => 'Main Category'.config('constants.flash.updated'), 'data' =>['id'=>$main_category->id,'name'=>$main_category->name,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$main_category->status]]);
             

        }
    //main categories add the function
   
 //<-------------------------------------------------------------------------->

    
     public function category()
    {
        
        $item_categories = GlobalItemCategory::select('global_item_categories.status','global_item_categories.id', 'global_item_categories.name','users.name As user_name','global_item_main_categories.name AS main_category_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_categories.created_at, "%d %M, %Y") AS start_date'))->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')->leftjoin('users','users.id','=','global_item_categories.created_by')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')

             ->where('global_item_categories.status', 1)
        ->orWhere(function ($query) {
            $query->where('global_item_categories.status', '=', 0);
        })
         ->groupBy('global_item_categories.id')
         ->get();
        // dd($item_categories);
        return view('admin.category', compact('item_categories'));
    }
     
     public function category_status(Request $request){
 // var_dump($request->all());
 // die("ok");
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        GlobalItemCategory::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }

 public function category_create(){
    
    $main_category=GlobalItemMainCategory::where('status','1')->pluck('display_name', 'id');
     
    $main_category->prepend('Select Main Category Type', '');
    //dd($main_category);

    return view('admin.category_create', compact('main_category'));

 }
 public function category_store(Request $request)
    {
           //dd($request->all());   

        $category= new \App\GlobalItemCategory;
        $category->name=$request->get('categoryname');
        $category->display_name=$request->get('categoryname');
        $category->main_category_id=$request->get('main_categoryname');
        $category->status=1;
         $category->created_by=Auth::user()->id;
         $category->last_modified_by=Auth::user()->id;
        
        $category->save();
             $maincategory= ($request->input('main_categoryname') != null) ? GlobalItemMainCategory::findorFail($category->main_category_id)->name : "";

             $created_by =($category->created_by != null) ? User::findorFail($category->created_by)->name : "";

      return response()->json(['status' => 1, 'message' => ' Category'.config('constants.flash.added'), 'data' =>['id'=>$category->id,'name'=>$category->name,'main_category'=>$maincategory,'created_by'=>$created_by,'created_at'=> $category->created_at->format('d F, Y'),'status'=>$category->status]]);
        
        //dd($response);
      
    }
    public function category_edit($id)
    {
      // var_dump($id);
      // die("ok");
      //dd($id);

 $main_category= GlobalItemMainCategory::select('id','display_name')->where('status', 1)
 ->pluck('display_name','id');

         // return view('admin.main_category_edit', compact('main_edit'));
        $category = GlobalItemCategory::select('id', 'name','main_category_id')

    
        ->where('id',$id)->first();
    //dd($category);
         return view('admin.category_edit', compact('main_category','category'));
       

    }
    public function itemcheck_categorynamecreate(Request $request) {
// dd($request->all());     
        $categoryname = GlobalItemCategory::where('main_category_id', $request->main_categoryname)
        ->where('name', $request->categoryname)
          ->first();
          //dd($categoryname);
        if(!empty($categoryname->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function edittimecheck_categoryname(Request $request) {
    //dd($request->all());     
        
        $categoryname = GlobalItemCategory::where('id','!=', $request->id)  
        ->where('main_category_id', $request->main_categoryname)
         ->where('name', $request->categoryname)
          ->first();
  //dd($categoryname);
        if(!empty($categoryname->id)&&!empty($categoryname->main_category_id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function itemcategory_update(Request $request){
       
  // dd($request->all());

        $category = GlobalItemCategory::findOrFail($request->input('id'));
        $category->name = $request->input('categoryname');
         $category->display_name=$request->input('categoryname');
         $category->main_category_id=$request->input('main_categoryname');

         
        $category->save();

        $maincategory= ($request->input('main_categoryname') != null) ? GlobalItemMainCategory::findorFail($category->main_category_id)->name : "";

             $created_by =($category->created_by != null) ? User::findorFail($category->created_by)->name : "";
      
      $created_at=($category->created_at)?$category->created_at->format('d F, Y'): "";

          return response()->json([ 'message' => ' Category'.config('constants.flash.updated'), 'data' =>['id'=>$category->id,'name'=>$category->name,'main_categoryname'=> $maincategory,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$category->status]]) ;
        

        }
        //<-------------------------------------------------------------------------->

    public function type()
    {
        $item_types = GlobalItemType::select('global_item_types.status','global_item_types.id', 'global_item_types.name','users.name As user_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_types.created_at, "%d %M, %Y") AS start_date'))->leftjoin('users','users.id','=','global_item_types.created_by')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')
            ->where('global_item_types.status', 1)
        ->orWhere(function ($query) {
            $query->where('global_item_types.status', '=', 0);
        })
        ->groupBy('global_item_types.id')
         ->get();
       //dd( $item_types );
        return view('admin.type', compact('item_types'));
    }
 public function type_status(Request $request){
 // var_dump($request->all());
 // die("ok");
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        GlobalItemType::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }

    public function type_create(){
        $itemtype=GlobalItemCategory:: //select('id','display_name','name')
        where('status','1')
        //->get();
    ->pluck('display_name', 'id');
        $itemtype->prepend('Select Category ', '');
     //select('id','name','display_name')->first();
       
       // dd($itemtype);
     return view('admin.type_create', compact('itemtype'));

    }
   
    public function typename_checkcreate(Request $request){
      // dd($request->all());     
        $checktype = GlobalItemType::where('category_id', $request->categoryname)
        ->where('name', $request->item)
          ->first();
        if(!empty($checktype->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function type_store(Request $request)
    {
           //dd($request->all());   

        $type= new \App\GlobalItemType;
        $type->name=$request->get('item');
        $type->display_name=$request->get('item');
        $type->category_id=$request->get('categoryname');
        $type->status=1;
         $type->created_by=Auth::user()->id;
         $type->last_modified_by=Auth::user()->id;
        $type->save();

        $created_by =($type->created_by != null) ? User::findorFail($type->created_by)->name : "";

            
        return response()->json(['status' => 1, 'message' => ' Type'.config('constants.flash.added'), 'data' =>['id'=>$type->id,'name'=>$type->name,'created_by'=>$created_by,'created_at'=> $type->created_at->format('d F, Y'),'status'=>$type->status]]) ;
    
       
        //dd($response);
     
    }
    public function type_edit($id){
        $categorys=GlobalItemCategory::select('id','display_name')->where('status', 1)
 ->pluck('display_name','id');

        $itemtype = GlobalItemType::select('id', 'name','category_id')
              ->where('id',$id)->first();
  //dd($categorys, $itemtype );
         return view('admin.type_edit', compact('categorys','itemtype'));
       


    }
    public function editcheck_categoryname(Request $request){
       //dd($request->all());     
        $type = GlobalItemType::where('id','!=', $request->itemid)
        ->where('category_id', $request->categoryname)
        ->where('name', $request->item)
          ->first();
        if(!empty($type->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }
    public function type_update(Request $request){
        // dd($request->all());

        $type = GlobalItemType::findOrFail($request->input('itemid'));
        $type->name = $request->input('item');
         $type->display_name=$request->input('item');
         $type->category_id=$request->input('categoryname');

         
        $type->save();
        $created_by =($type->created_by != null) ? User::findorFail($type->created_by)->name : "";

         $created_at=($type->created_at)?$type->created_at->format('d F, Y'): "";


             return response()->json([ 'message' => ' Type'.config('constants.flash.updated'), 'data' =>['id'=>$type->id,'name'=>$type->name,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$type->status]]) ;
    

    }
    //<------------------------------------------------------------------------------>
    public function make()
    {
        $item_makes = GlobalItemMake::select('global_item_makes.status','global_item_makes.id', 'global_item_makes.name','users.name As user_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_makes.created_at, "%d %M, %Y") AS start_date'))->leftjoin('users','users.id','=','global_item_makes.created_by')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')
             ->where('global_item_makes.status', 1)
        ->orWhere(function ($query) {
            $query->where('global_item_makes.status', '=', 0);
        })
          ->groupBy('global_item_makes.id')
         ->get();
        return view('admin.make', compact('item_makes'));
    }
    public function make_create(){

 return view('admin.make_create');
    }
    public function make_status(Request $request){
 // var_dump($request->all());
 // die("ok");
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        GlobalItemMake::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }
public function makename_checkcreate(Request $request){
     //dd($request->all());     
        $make = GlobalItemMake::where('name', $request->itemmake)
       
          ->first();
        if(!empty($make->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
     

public function make_store(Request $request){
    $make= new \App\GlobalItemMake;
        $make->name=$request->get('itemmake');
        $make->display_name=$request->get('itemmake');
               $make->status=1;
          $make->created_by=Auth::user()->id;
         $make->last_modified_by=Auth::user()->id;
        
        $make->save();

          $created_by =($make->created_by != null) ? User::findorFail($make->created_by)->name : "";
return response()->json(['status' => 1, 'message' => ' Type'.config('constants.flash.added'), 'data' =>['id'=>$make->id,'name'=>$make->name,'created_by'=> $created_by,'created_at'=> $make->created_at->format('d F, Y'),'status'=>$make->status]]);
        //dd($response);
      

}
public function make_edit($id){
      
        $itemmake= GlobalItemMake::select('id', 'name')
              ->where('id',$id)->first();
  //dd($itemmake);
         return view('admin.make_edit', compact('itemmake'));
       


    }
    public function editcheck_makename(Request $request){
  // dd($request->all());     
        $type = GlobalItemMake::where('id','!=', $request->makeid)
       
        ->where('name', $request->makename)
          ->first();
        if(!empty($type->id)) {
            echo 'false';
        } else {
            echo 'true';
        }

    }
     public function make_update(Request $request){
     

        $make = GlobalItemMake::findOrFail($request->input('makeid'));
        $make->name = $request->input('makename');
        $make->display_name=$request->input('makename');
         //dd($make);
        $make->save();
             $created_by =($make->created_by != null) ? User::findorFail($make->created_by)->name : "";
              
              $created_at=($make->created_at)?$make->created_at->format('d F, Y'): "";

       return response()->json([ 'message' => ' Type'.config('constants.flash.updated'), 'data' =>['id'=>$make->id,'name'=>$make->name,'created_by'=> $created_by,'created_at'=> 
        $created_at,'status'=>$make->status]]);

    }
    // <-------------------------------------------------------------------->
    public function model()
    {


        $items = GlobalItemModel::select(
          'global_item_category_types.id','global_item_category_types.name as category_type_name','global_item_models.status','global_item_models.id', 'global_item_models.name','global_item_main_categories.name AS main_category_name','users.name As user_name','global_item_types.name AS type_name','global_item_categories.name AS category_name','global_item_makes.name AS make_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_models.created_at, "%d %M, %Y") AS start_date'))
       
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
         ->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')
        ->leftjoin('global_item_types','global_item_types.id','=','global_item_models.type_id')
        ->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')
        ->leftjoin('users','users.id','=','global_item_models.created_by')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')
             ->groupBy('global_item_models.id')
           
         ->get();

         Log::info(' Super-Admin Items'.json_encode($items->count()));
    		//dd($items);

         $main_category = GlobalItemMainCategory::select('id',  'display_name')->pluck('display_name', 'id');
         $main_category->prepend('Select maincategory name', '');
         //dd($main_category);
        return view('admin.model', compact('items','main_category'));
    }
      public function model_status(Request $request){
 // var_dump($request->all());
 // die("ok");
        if($request->input('status')==="1")
        {
            $UpdateData=['status' => $request->input('status')];
        }else{
            $UpdateData=['status' => $request->input('status')];
        }
        GlobalItemModel::where('id', $request->input('id'))->update($UpdateData);
        return response()->json(array('result' => "success",'status'=>$UpdateData));
    }
    public function model_create(){
       $inventory_types = GlobalItemCategoryType::select('id', 'name', 'display_name')->where('status', 1)->get();


       // dd($category);
          return view('admin.model_create', compact('inventory_types'));
           
    }
  
    public function getcategorytypelist($id){
        $categorytype = DB::table("global_item_main_categories")
                    ->where("category_type_id",$id)
                    ->where('status',1)
                    ->pluck("display_name","id");
                 
        return response()->json($categorytype);
    }
    public function getcategorylist($id){
        $category = DB::table("global_item_categories")
                    ->where("main_category_id",$id)
                     ->where('status',1)
                    ->pluck("display_name","id");
                
        return response()->json($category);
    }
    public function gettypelist($id){
        $type = DB::table("global_item_types")
         ->where('status',1)
                    ->pluck("display_name","id");
               
        return response()->json($type);
    }
    public function getmakelist($id)
    {
        $makes = DB::table("global_item_makes")
         ->where('status',1)
                    ->pluck("display_name","id");
                   
        return response()->json($makes);
    }
    public function model_check(Request $request){
//dd($request->all());     
        $make = GlobalItemModel::where('name', $request->model)
        ->where('category_id',$request->categoryname)
        ->where('type_id',$request->type)
        ->where('make_id',$request->make)
                 ->first();
                
        if(!empty($make->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function model_store(Request $request){
      //dd($request->all()); 
    $model= new \App\GlobalItemModel;
        $model->name=$request->get('model');
        $model->display_name=$request->get('model');
        $model->category_id=$request->get('categoryname');
         $model->type_id=$request->get('type');
        $model->make_id=$request->get('make');
            
               $model->status=1;
       $model->created_by=Auth::user()->id;
         $model->last_modified_by=Auth::user()->id;
       //dd($model);
        $model->save();


                 //$main=$request->get('maincategoryname1');
           $main_cat= new \App\GlobalItemMainCategory;
           $main = ($request->input('maincategoryname') != null) ? GlobalItemMainCategory::findorFail($request->input('maincategoryname'))->name : "";
     //dd($main); 

        $type_cat= new \App\GlobalItemCategoryType;
        $categorytype = ($request->input('categorytype') != null) ? GlobalItemCategoryType::findorFail($request->input('categorytype'))->name : "";


         $type = ($request->input('type') != null) ? GlobalItemType::findorFail($model->type_id)->name : "";
 $category = ($request->input('categoryname') != null) ? GlobalItemCategory::findorFail($model->category_id)->name : "";

        $vehicle_make_id = ($request->input('make') != null) ? GlobalItemMake::findorFail($model->make_id)->name : "";



          $created_by =($model->created_by != null) ? User::findorFail($model->created_by)->name : "";
         
        return response()->json(['status' => 1, 'message' => ' Model'.config('constants.flash.added'), 'data' =>['id'=>$model->id,'categorytype'=>$categorytype,'name'=>$model->name,'main'=>$main,'category'=>$category,'type'=>$type,'make'=>$vehicle_make_id,'created_by'=>$created_by,'created_at'=> $model->created_at->format('d F, Y'),'status'=>$model->status]]);
     
}
    public function model_edit($id){

        $model=GlobalItemModel::select(
            'global_item_category_types.id as item_id',
            'global_item_category_types.name as item_name',
            'global_item_main_categories.name AS main_category_name',
            'global_item_main_categories.id AS main_category_id',
            'global_item_categories.id AS category_id',
             'global_item_categories.name AS category_name',
            'global_item_types.id AS typeid',
            'global_item_types.name AS typename',
            'global_item_models.id as modelid',
            'global_item_models.name as modelname',
            'global_item_makes.id AS makeid',
            'global_item_makes.name AS make_name')
       
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')

         ->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')

         ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')

        ->leftjoin('global_item_types','global_item_types.id','=','global_item_models.type_id')

        ->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')

       
            ->where('global_item_models.id',$id)->first();


         $inventory_types = GlobalItemCategoryType::select('id', 'display_name','name')->get();

         $main_category = GlobalItemMainCategory::select('id',  'display_name')->pluck('display_name', 'id');
        
      $category=GlobalItemCategory::select('id','display_name')
    ->pluck('display_name','id');

      $type=GlobalItemType::select('id','display_name') 
        ->pluck('display_name', 'id');
        $type->prepend('Select type name', '');
    

      $make=GlobalItemMake::select('id','display_name')
      ->pluck('display_name','id');
      $make->prepend('Select make name', '');
      $item = GlobalItemModel::select('id', 'name','category_id','type_id','make_id')
              ->where('id',$id)->first();

         return view('admin.model_edit', compact('category','item','type','make','item_type','main_category','inventory_types','model'));
       


    } 

    public function model_edit_check(Request $request){
    //dd($request->all());     
        $make = GlobalItemModel::where('id', '!=',$request->itemid)
        ->where('name', $request->model)
        ->where('category_id',$request->categoryname)
        ->where('type_id',$request->type)
        ->where('make_id',$request->make)
        ->first();
       //dd($make);
        if(!empty($make->id)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

  public function model_update(Request $request){
       
    //dd($request->all());

        $model = GlobalItemModel::findOrFail($request->input('itemid'));
        $model->name = $request->input('model');
         $model->display_name=$request->input('model');
         $model->category_id=$request->input('categoryname');
          $model->type_id=$request->input('type');
           $model->make_id=$request->input('make');


      // dd($model);
        $model->save();

        $maincategory=GlobalItemCategory::findorFail($request->input('maincategoryid'));
        $maincategory->main_category_id=$request->input('maincategoryname');
       $maincategory->save();

        $itemtype=GlobalItemMainCategory::findorFail($request->input('maincategoryid'));
        $itemtype->category_type_id=$request->input('types');
       //dd($itemtype);
       $itemtype->save();
      //dd($itemtype);
 

        $type_cat= new \App\GlobalItemCategoryType;
        $categorytype = ($request->input('types') != null) ? GlobalItemCategoryType::findorFail($request->input('types'))->name : "";

         $main_cat= new \App\GlobalItemMainCategory;
        $main = ($request->input('maincategoryname') != null) ? GlobalItemMainCategory::findorFail($request->input('maincategoryname'))->name : "";
        //dd($main); 

         $type = ($request->input('type') != null) ? GlobalItemType::findorFail($model->type_id)->name : "";
        $category = ($request->input('categoryname') != null) ? GlobalItemCategory::findorFail($model->category_id)->name : "";

        $vehicle_make_id = ($request->input('make') != null) ? GlobalItemMake::findorFail($model->make_id)->name : "";



          $created_by =($model->created_by != null) ? User::findorFail($model->created_by)->name : "";
          $created_at=($model->created_at)?$model->created_at->format('d F, Y'): "";
              
              return response()->json(['status' => 1, 'message' => ' Model'.config('constants.flash.updated'), 'data' =>['id'=>$model->id,'name'=>$model->name,'itemtype'=>$categorytype,'main'=>$main,'category'=>$category,'type'=>$type,'make'=>$vehicle_make_id,'created_by'=>$created_by,'created_at'=> $created_at,'status'=>$model->status]]);



        }
         public function  get_maincategory_name_list($id){
         // dd($id);
        $model = DB::table("global_item_main_categories")
                    ->where("category_type_id",$id)
                    ->where('status',1)
                    ->pluck("display_name","id");
                // dd($model);
                 
        return response()->json($model);
    }
    public function  get_category_name_list($id){
          //dd($id);
        $Category = DB::table("global_item_categories")
                    ->where("main_category_id",$id)
                    ->where('status',1)
                    ->pluck("display_name","id");
                //dd($Category);
                 
        return response()->json($Category);
    }
    

public function select_maincategory( Request $request)
    {
  //dd($request->all());
       
       $organization_id = Session::get('organization_id');
       $category = DB::table('global_item_categories')
       
       ->where('global_item_categories.main_category_id', $request->input('maincategory'))->pluck('global_item_categories.id','global_item_categories.name');
       // $array = (array)$object;
        //dd($object);
       // $category=GlobalItemCategory::select('global_item_categories.id')->where('global_item_categories.main_category_id', $request->input('maincategory')) 
       // ->get();
       //dd($category);
$items = GlobalItemModel::select(
          'global_item_category_types.id as maincategorytype_id','global_item_category_types.name as category_type_name','global_item_models.status','global_item_models.id as model_id', 'global_item_models.name','global_item_main_categories.name AS main_category_name','users.name As user_name','global_item_types.name AS type_name','global_item_categories.name AS category_name','global_item_makes.name AS make_name','organizations.name AS companyname',DB::raw('DATE_FORMAT(global_item_models.created_at, "%d %M, %Y") AS start_date'),'global_item_models.created_by')
       
        ->leftjoin('global_item_categories','global_item_categories.id','=','global_item_models.category_id')
         ->leftjoin('global_item_main_categories','global_item_main_categories.id','=','global_item_categories.main_category_id')
        ->leftjoin('global_item_types','global_item_types.id','=','global_item_models.type_id')
        ->leftjoin('global_item_makes','global_item_makes.id','=','global_item_models.make_id')
        ->leftjoin('users','users.id','=','global_item_models.created_by')
         ->leftjoin('persons','persons.id','=','users.person_id')
            ->leftjoin('organization_person','organization_person.person_id','=','persons.id')
            ->leftjoin('global_item_category_types','global_item_category_types.id','=','global_item_main_categories.category_type_id')
            ->leftjoin('organizations','organizations.id','=','organization_person.organization_id')
            ->whereIn('global_item_models.category_id', $category)

         ->get();


         $main_category = GlobalItemMainCategory::select('id',  'display_name')->pluck('display_name', 'id');

         $main_category->prepend('Select maincategory name', '');

         $main=GlobalItemMainCategory::select( 'display_name')
         ->where('global_item_main_categories.status',1)
         ->get();
        // dd($main);

         
       
     
       
     
   if(count($items) > 0 )
        {
            return response()->json(['status' => 1 ,'data' => $items]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => "No data available."]);
        }
  
      }

}
