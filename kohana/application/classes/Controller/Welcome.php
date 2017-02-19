<?php defined('SYSPATH') or die('No direct script access.');
error_reporting(E_ALL ^ E_DEPRECATED);

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$this->response->body('hello, world!');
		
	}
	public function action_register()
	{
		
		$post = $this->request->post();
		$client = ORM::factory('User');
		$client->email = $post['email'];
		$client->username = $post['email'];
		$client->password = $post['password'];
		
		if($client->save()){
		 	$client->add('roles', ORM::factory('Role', array('name' => 'login')));
		 	$this->response->body('You are successfully registered');
		}
	}
	public function action_login(){

		// Handled from a form with inputs with names email / password
		$post = $this->request->post();
		$session = Session::instance();
		if($post){
			print_r($post);
			$success= Auth::instance()->login($post['email'],$post['password'],false);
			$session->set('id', Auth::instance()->get_user()); 
			if ($success)
			{
			    $this->response->body('login successfull');
			}
			else
			{
			    $this->response->body('Login Failed');
			}
		}
		 // $this->response->body('Login Failed');
	}

	public function action_check_login(){

		if (Auth::instance()->logged_in())
		{
		 	
		 	$user = Auth::instance()->get_user();
		 	 
		 	if ($user !== null)
		 	{
		    	$this->response->body($user->username);	 	    
		 	}
		 	else
		 	{
		    	$this->response->body('User not found');
		 	}   
		}
		else
		{
		    $this->response->body('not Logged in');
		}
	}
	public function action_logout(){
		Auth::instance()->logout();
		$session = Session::instance();
		$session->delete('id');
		$this->response->body('Logged out successfully');
	}

	public function action_add_profile(){
		if (Auth::instance()->logged_in()){
			$post = $this->request->post();
			$client = ORM::factory('User');
			$session = Session::instance();
			// print_r($client->create_profile($post));
			$arr[0]=$session->get('id');
			$arr[1]=$post['address'];
			$result=$client->create_profile($arr);
			if($result[0]){
				$this->response->body('Profile Created successfully');
			}else{
				$this->response->body('Profile Cannot be created.');
			}
		}else{
			$this->response->body('You are not logged in please log in');
		}

	}

	public function action_get_profile(){

		if (Auth::instance()->logged_in()){
			$client = ORM::factory('User');
			$user = Auth::instance()->get_user();
			$session = Session::instance();
			$result=$client->get_profile($session->get('id'));
			if($result){
				$this->response->body("user_id=>".$session->get('id')." <br>username=>".$user->username."  <br> address=>".$result[0]['address']);
			}else
				$this->response->body("NO profile data entered");
			
		}else{
			$this->response->body('You are not logged in please log in');
		}
		
	}
} 
