<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Branch extends CI_Controller {
class Branch extends MY_Controller {	
	
	public function index()
	{
		$this->manage();
	}
	
	public function manage()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if( !$this->has_method_access())
		{
			redirect('/main/','location');
		}
		////date_default_timezone_set('Asia/Kolkata');
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		$query = "SELECT * FROM `branches` order by id DESC";
        //echo $query;
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/branch/manage";
		
		$this->load->view("$theme/header",$data);
		//$data['sidebar'] = 'sidebar-left';		

		
	}
	
	public function add_new()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		$this->load->library('sms');
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		//////date_default_timezone_set('Asia/Kolkata');
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		/*$this->load->library('breadcrumbs');*/
		//print_r($_POST); exit;
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', "</div>");
			
				
			$this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
			$this->form_validation->set_rules('branch_code', 'Branch Code', 'trim|required');
			
			if ($this->form_validation->run() != FALSE)
			{
				//$post_slug = $page_title; 
				//$post_type = "page";					
				extract($_POST,EXTR_OVERWRITE);
				
				if(empty($user_id) || $user_id == 0)
				{	
					$_SESSION['msg'] = '<p class="error_msg">Session Expired.</p>';
					$this->session->set_userdata('logged_in', false);
					$_SESSION['logged_in'] = false;	
					redirect('/logins','location');
				}
				
				$entry_time = time();
				
				$datai = array( 
						'branch_name' => $branch_name,	
						'branch_code' => $branch_code,	
						'branch_address' => $branch_address,	
						'user_id' => $user_id,
						'entry_date' => $entry_time,
						);
				
				$branch_id=$this->Common_model->commonInsert('branches',$datai);
				
				$_SESSION['msg'] = '<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Branch Added Successfully.
							</div>
							';
				redirect('/branch/manage','location');	
			
			
			}
		}
		
		$data['theme']=$theme;
		$data['content'] = "$theme/branch/add_new";
		$this->load->view("$theme/header",$data);
		//$data['sidebar'] = 'sidebar-left';		

		
	}
	
	public function uri_check()
	{
		
		$str=uri_string();
		$strs=explode("/",$str);
//		print_r($strs);
		$data['class']='main';
		//echo $strs[1];
		if( isset($strs[1]))   
		{
			$data['func']=$strs[1]; 
			switch ($strs[1])
			{
				case 'home': 
				$data['class']='home';break;
				
			}	
		}
		else {
			$data['func']='home';
			$data['class']='home';
		}	
			
		
		
		//$data1=$this->getSEOfields();
		//$data = array_merge($data,$data1);
		
		return $data;
	}
	
	public function delete($rowid)
	{
		
		$CI =& get_instance();
		$this->load->library('global');
		
		$this->load->model('Common_model');
			
		$tbl='branches';
		$pid='id';
		$url='/branch/manage/';	 	
		$fld='Branch';
		
		$rows= $this->Common_model->commonDelete($tbl,$rowid,$pid );			
		
		$_SESSION['msg'] = '<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								'.$rows.' '.$fld.' Deleted Successfully.
							</div>
							';
		redirect($url,'location','301');	
	}
	
	public function user_name_check($val)
	{
		$this->load->model('Common_model');
		//echo $val;
		$options=array('where'=>array('user_name'=>$val));
		$user_exsist=$this->Common_model->commonSelect('users',$options);
		//echo $user_exsist->num_rows();die;
		if($user_exsist->num_rows() > 0 )
		{
			$this->form_validation->set_message('user_name_check', ' %s already exsist');
			return FALSE;
		}	
		else
		{	return TRUE;		}
	}
		
	public function isLogin()	{
		$site_url = site_url();		
		/*if(
		isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == TRUE 
		&& isset($_SESSION['site_url']) 
		&& $_SESSION['site_url'] == $site_url 
		)*/
		$logged_in = $this->session->userdata('logged_in');
		$sess_site_url = $this->session->userdata('site_url');
		if(isset($logged_in) && $logged_in == TRUE 
		&& isset($sess_site_url) && $sess_site_url == $site_url)
		{
			return true;		

		}		
		else
		{
			$_SESSION['msg'] = '<p class="error_msg">You have to login first to proceed.</p>';
			return false;
		}
	}
	
	
}
