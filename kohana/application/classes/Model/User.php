<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User {
	public function create_profile($value)
	{
		$query = DB::select('*')->from('profile')->where('id','=',$value[0]);
		$result = $query->execute()->as_array();
		if(count($result)>0){
			$arr[0]= false;
		}
		else{
				$query = DB::insert('profile', array('id', 'address'))->values(array($value[0], $value[1]));
				$result = $query->execute();
				if($result[1]==1){
					$arr[0]= true;
				}else{
					$arr[0]= false;
				}
			}
		return $arr;
	}

	public function get_profile($value){
		$query = DB::select('*')->from('profile')->where('id', '=', $value);
		$result = $query->execute()->as_array();
		if(count($result)>0)
		return $result;
		else
		return false;
	}
}

?>