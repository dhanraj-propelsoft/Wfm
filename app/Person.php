<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class Person extends Model
{
    protected $table = "persons";

    protected $appends = ['image', 'sign'];
	
    protected $guarded = ['_token', 'image', 'sign', 'city', 'mobile'];

    public function organizations() {
        return $this->belongsToMany('App\Organization', 'organization_person', 'person_id', 'organization_id');
    }


    public function getImageAttribute()
	{
		$image_path = File::glob(public_path()."/users/images/user_".$this->attributes['id']."*.jpg");
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
	
	public function getSignAttribute()
	{
		//$file = Storage::get($this->picture);
		//$type = Storage::mimeType($this->picture);
		$image_path = File::glob(public_path()."/users/sign/sign_".$this->attributes['id']."*.jpg");
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

	public static function user_image($id) {

		$image_path = File::glob(public_path()."/users/images/user_".$id."*.jpg");
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
	
	/**
	 * Convert to ProperCase.
	 *
	 * @return string
	 */
	public function getFirstNameAttribute($value)
	{
		$value = strtolower($value);
		return ucwords($value);
	}

	/**
	 * Convert to ProperCase.
	 *
	 * @return string
	 */
	public function getLastNameAttribute($value)
	{
		$value = strtolower($value);
		return ucwords($value);
	}
	/**
	 * Get the user's full name.
	 *
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		 $first_name = strtolower($this->first_name);
		 $last_name = strtolower($this->last_name);
		 return ucwords($first_name." ".$last_name);
	}

	 /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

	 /**
     * Set the user's last name.
     *
     * @param  string  $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
	}
	
	public function address(){
		return $this->hasOne('App\PersonCommunicationAddress','person_id');
	}

	public function personOrgAssoication(){
        return $this->hasMany('App\People','person_id','id');
    }
}
