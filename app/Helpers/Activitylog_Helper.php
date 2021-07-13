<?php


namespace App\Helpers;
use File;
use Illuminate\Http\Request;
use Storage;
use App\Helpers\Helper;
class LogActivity
{


    public static function addToLog($org_id,$pro_id,$task_id,$user_name,$subject,$action,$file="")
    {
        //dd($org_id"".$pro_id.",".$task_id.",".$user_id.",".$subject.",".$action);
        
    	if(! File::exists(public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/task_".$task_id.".txt"))
        {
            File::makeDirectory(public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/", $mode = 0777, true, true);
            fopen(public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/task_".$task_id.".txt","a");
            $flatfile=public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/task_".$task_id.".txt";
            $arr=file_get_contents("$flatfile");
        //  dd( $flatfile);
            if($arr==Null)
            {
                $value= [['DataType'=>1,'Time' =>date('Y-m-d H:i:s') ,  'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject]];
              
            }

            else
            {
                $value= json_decode($arr);
                $value[]= ['DataType'=>1,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject];

            }

            $res=(json_encode($value));

            $handle=fopen($flatfile,'w');
            fwrite($handle,$res);


        }else{
             fopen(public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/task_".$task_id.".txt","a");
            $flatfile=public_path()."/activity_log/org_".$org_id."/pro_".$pro_id."/task_".$task_id.".txt";
            $arr=file_get_contents("$flatfile");
            if($arr==Null)
            {
                $value= [['DataType'=>1,'Time' =>date('Y-m-d H:i:s') ,  'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject]];
              
            }

            else
            {
                $value= json_decode($arr);
                $value[]= ['DataType'=>1,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject];

            }

            $res=(json_encode($value));

            $handle=fopen($flatfile,'w');
            fwrite($handle,$res);


        }
    }


    public static function updateActivityLog($data_type,$file,$org_id,$user_name,$subject,$action,$upload_file="")
    {
        
        

        if( File::exists(public_path()."/activity_log".$file))
        {
          
            fopen(public_path()."/activity_log".$file,"a");
            $flatfile=public_path()."/activity_log".$file;
            $arr=file_get_contents("$flatfile");
            if($arr==Null)
            {
                $value= [LogActivity::AppendLogData($data_type,$org_id,$user_name,$subject,$action,$upload_file)];
            }

            else
            {
                $value= json_decode($arr);
                $value[]= LogActivity::AppendLogData($data_type,$org_id,$user_name,$subject,$action,$upload_file);
               // $return_data=LogActivity::AppendLogData($data_type,$org_id,$user_id,$subject,$action,$upload_file);
            }
               $return_data=LogActivity::AppendLogData($data_type,$org_id,$user_name,$subject,$action,$upload_file);

            $res=(json_encode($value));

            $handle=fopen($flatfile,'w');
            fwrite($handle,$res);
            return $return_data;

        }else{
            dd("Not Exist");
        }
       
    }
     public static function AppendLogData($datatype,$org_id,$user_name,$subject,$action="",$uploadfile=""){

            if($datatype)
            {
        /*        if($action)
                {

               return ['DataType'=>1,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject];
                }
            }
            if($datatype==2)
            {
                return ['DataType'=>2,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'Comment' =>$subject];
            }

            if($datatype==3)
            {
                if($file){

                return ['DataType'=>3,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'file' =>$uploadfile];
                }
            }
              if($datatype==4)
            {*/
              

                 return ['DataType'=>$datatype,'Time' =>date('Y-m-d H:i:s') , 'User' =>$user_name, 'Action' =>$action,'Subject'=>$subject];
             
            }
     }



}