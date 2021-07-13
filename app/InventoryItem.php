<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use File;
use URL;

class InventoryItem extends Model
{
	protected $appends = ['image'];

    public function getImageAttribute()
	{
		$business_id = Organization::findOrFail(Session::get('organization_id'))->business_id;
		$business_name = Business::findOrFail($business_id)->business_name;

		$image_path = URL::to('/').'/public/organizations/'.$business_name.'/items/'.$this->attributes['id'].'.jpg';

		if (!file_exists(public_path('/organizations/'.$business_name.'/items/'.$this->attributes['id'].'.jpg'))) {
			$image_path =  URL::to('/').'/public/image_not_available.png';
		}
		
		
		return $image_path;
		
	}
}
