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
		$client->username = "sachin123";
		$client->password = $post['password'];
		
		if($client->save()){
		 	$client->add('roles', ORM::factory('Role', array('name' => 'login')));
		 	$this->response->body('You are successfully registered');
		}
	}
	public function action_login(){

		// Handled from a form with inputs with names email / password
		$post = $this->request->post();
		print_r($post);
		$success= Auth::instance()->login($post['email'],$post['password'],false);
		
		// echo Auth::instance()->hash_password($post['password'])."\n";
		// echo Auth::instance()->hash_password("sachin123");
		 
		if ($success)
		{
		    $this->response->body('login successfull');
		}
		else
		{
		    $this->response->body('Login Failed');
		}
		 // $this->response->body('Login Failed');
	}

	public function action_check_login(){

		if (Auth::instance()->logged_in())
		{
		 	
		 	$user = Auth::instance()->get_user();
		 	 
		 	if ($user !== null)
		 	{
		    	$this->response->body($user);	 	    
		 	}
		 	else
		 	{
		    	$this->response->body('User not found');
		 	}   
		}
		else
		{
		     $this->action_login();
		}
	}
	public function action_logout(){
		Auth::instance()->logout();
		$this->response->body('Logged out successfully');
				
	}
} 
