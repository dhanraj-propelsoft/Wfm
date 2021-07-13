<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use File;

class Business extends Model
{
    protected $appends = ['image'];

   	protected $guarded = ['mobile','image'];

   	public function getImageAttribute()
	{
		$image_path = File::glob(public_path()."/organizations/".$this->attributes['business_name']."/logos/business_".$this->attributes['id']."*.jpg");
		$real_path = "";
		if($image_path) {
			usort($image_path, create_function('$b,$a', 'return filemtime($a) - filemtime($b);'));
			$real_path =  explode( "/", str_replace('\\','/',$image_path[0]));
			$real_path = end($real_path) ;
		} else {
			$real_path = "no_image.jpg";
		}
		
			return $real_path ;
	}
	public function address(){
		return $this->hasOne('App\BusinessCommunicationAddress','business_id');
	}
	public function businessOrgAssociation(){
		return $this->hasMany('App\People','business_id');
	}
}
