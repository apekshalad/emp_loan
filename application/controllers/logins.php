<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logins extends CI_Controller {

	public function index()
	{
		$this->login();
	}
	
	
	public function login()
	{
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		$site_url = site_url();	
		$this->load->library('global');
		$this->load->library('login');
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		if(isset($_POST['submit']))
		{
			
			$username=$_POST['username'];
			$userpass=$_POST['userpass'];
			$this->load->model('Common_model');
			$sql="select * from users where user_name='".$username."' and user_pass = '".md5($userpass)."'"; 
			$detail = $this->Common_model->commonQuery($sql);
                        $cdate = date("m/d/Y");
			$sql="select COUNT(*) as cnt from options where option_key='day_off' and option_value like '%$cdate%'"; 
			$offday = $this->Common_model->commonQuery($sql)->row();
			if($detail->num_rows()>0)
			{
				$data=$detail->row();
				$newdata = array(  
					'username'  => $username, 
					'user_name'     => $data->user_name,
					'user_email'     => $data->user_email, 
					'user_id'     => $data->user_id, 
					'user_type'     => $data->user_type, 
					'user_status'     => $data->user_status, 
					'logged_in' => TRUE,
					'site_url' => $site_url,
                                        'offday' => $offday->cnt
					);
				foreach($newdata as $k=>$v)
				{
					$_SESSION[$k] = $v;
				}
				$this->session->set_userdata($newdata);
				redirect('/main/','location');
			}
			else
			{	
				 $_SESSION['msg'] = '<p class="error_msg">Username/Password Mismatch.</p>';
				 $data['theme'] = $theme;
				 $this->load->view($theme . '/login',$data);
			}

		}
		else{
			
			$data['theme'] = $theme;
			$this->load->view($theme . '/login', $data);
			 
		}
	}
	
	public function logout()
	{
		
		$newdata = array(  'username', 
						'user_name',
						'user_email', 
						'user_id', 
						'user_type', 
						'user_status', 
						//'logged_in',
						'site_url'
						);
		foreach($newdata as $k=>$v)
		{
			unset($_SESSION[$v]);
		}
		$this->session->unset_userdata($newdata);
		$this->session->set_userdata('logged_in', false);
		$_SESSION['logged_in'] = false;		
		$_SESSION['msg'] = '<p class="success_msg">Logged Out Successfully.</p>';
		redirect('/logins','location');	
		
	}
}