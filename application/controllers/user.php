<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class User extends CI_Controller {
class User extends MY_Controller {	
	
	public function index()
	{
		if(!$this->isLogin())
		{
			
			redirect('/logins','location');
		}
		
		redirect('/user/manage','location');
		
	}
	
	public function manage()
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
		
		$data = $this->global->uri_check();
		date_default_timezone_set('Asia/Kolkata');
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		
		$data['query'] = $this->Common_model->commonQuery("
				select * from users 
				where user_status = 'Y' and (user_type != 'admin')
				order by user_id  DESC
				");	
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/user/manage";
		
		$this->load->view("$theme/header",$data);
		
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
		
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		
		if(isset($_POST['submit']) || isset($_POST['draft']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>', "</div>");
			
				
			$this->form_validation->set_rules('user_meta[first_name]', 'First Name', 'trim|required');
			$this->form_validation->set_rules('user_meta[last_name]', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('user_meta[mobile_no]', 'Mobile No.', 'trim|required');
			$this->form_validation->set_rules('UserType', 'User Type', 'trim|required');
			$this->form_validation->set_rules('UserName', 'User Name', 'trim|required');
			$this->form_validation->set_rules('Password', 'Password', 'trim|required');
                        $this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
			$this->form_validation->set_rules('RepeatPassword', 'Repeat Password', 'trim|required|matches[Password]');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				 
				 
				if(empty($user_id) || $user_id == 0)
				{	
					$_SESSION['msg'] = '<p class="error_msg">Session Expired.</p>';
					$_SESSION['logged_in'] = false;	
					$this->session->set_userdata('logged_in', false);
					redirect('/logins','location');
				}
				
				if(isset($_POST['submit']))
					$page_status = 'publish';
				else if(isset($_POST['draft']))
					$page_status = 'draft';
				else
					$page_status = 'draft';
				
				$cur_time = time();
				$datai = array( 
								'user_name' => trim($UserName),	
								'user_pass' => md5(trim($Password)),
								'user_email' => trim($UserEmail),
								'user_type' => trim($UserType),
                                                                'designation_id' => $_POST['designation_id'],
								'user_registered_date' => $cur_time,	
								'user_update_date' => $cur_time,
								'user_verified' => 'Y',
								'user_status' => 'Y',
								); 
				$user_id=$this->Common_model->commonInsert('users',$datai);
				
				foreach($user_meta as $key=>$val)
				{
					$datai = array( 
								'meta_key' => trim($key),	
								'meta_value' => trim($val),
								'user_id' => $user_id
								);
					$this->Common_model->commonInsert('user_meta',$datai);
				}
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								User added Successfully.
							</div>
							';
				redirect('/user/manage','location');	
			
			
			}
		}
		$data['designation'] = $this->Common_model->commonQuery("
				select *
				from designation 
				order by id DESC
				");	
		$data['theme']=$theme;
		
		$data['content'] = "$theme/user/add_new";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function edit($c_id = NULL)
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
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		
		if(isset($_POST['submit']) || isset($_POST['draft']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters("<div class='notification note-error'>	
			<a href='#' class='close' title='Close'>
			<span>close</span></a> 	<span class='icon'></span>	<p><strong>Error :</strong>", "</p></div>");
			
				
			$this->form_validation->set_rules('user_meta[first_name]', 'First Name', 'trim|required');
			$this->form_validation->set_rules('user_meta[last_name]', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('user_meta[mobile_no]', 'Mobile No.', 'trim|required');
			
			
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if(isset($_POST['submit']))
					$page_status = 'publish';
				else if(isset($_POST['draft']))
					$page_status = 'draft';
				else
					$page_status = 'draft';
				 
				$cId = $this->DecryptClientId($user_id);
				
				$cur_time = time();
				
				if(isset($Password) && !empty($Password))
				{
					
					$datai = array( 
								'user_pass' => md5(trim($Password)),
								'user_email' => trim($UserEmail),
								'user_update_date' => $cur_time,
                                                                'designation_id' => $_POST['designation_id'],
								);
				}
				else
				{
					$datai = array( 
								'user_email' => trim($UserEmail),
								'user_update_date' => $cur_time,
                                                                'designation_id' => $_POST['designation_id'],
								);
				}
				$post_id = $this->Common_model->commonUpdate('users',$datai,'user_id',$cId);
				
				$this->Common_model->commonDelete('user_meta',$cId,'user_id' );
				
				foreach($user_meta as $key=>$val)
				{
					$datai = array( 
								'meta_key' => trim($key),	
								'meta_value' => trim($val),
								'user_id' => $cId
								);
					$this->Common_model->commonInsert('user_meta',$datai);
				}
				
				
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								User Updated Successfully.
						  </div>
							';
				redirect('/user/manage','location');	
			
			
			}
		}
		
		$data['user_id'] = $c_id;
		$decId = $this->DecryptClientId($c_id);
		$data['query'] = $this->Common_model->commonQuery("
				select * from users where user_id = $decId");	
		$data['designation'] = $this->Common_model->commonQuery("
                    select * from designation order by id DESC
				");	
		$data['theme']=$theme;
		
		$data['content'] = "$theme/user/edit";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	public function view($c_id = NULL)
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
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		$data['theme']=$theme;
		$decId = $this->DecryptClientId($c_id);
		
		$data['client_loan_detail'] = $this->Common_model->commonQuery("
					select * from loans 
					inner join clients cst on cst.client_ID=loans.client_ID  
					where cst.client_ID = $decId");	
		
		$data['content'] = "$theme/client/view";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	public function EncryptClientId($id)
	{
		return substr(md5($id), 0, 8).dechex($id);
	}

	public function DecryptClientId($id)
	{
		$md5_8 = substr($id, 0, 8);
		$real_id = hexdec(substr($id, 8));
		return ($md5_8==substr(md5($real_id), 0, 8)) ? $real_id : 0;
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
		if(!is_array($rowid))
			$rowid	= $this->global->DecryptClientId($rowid);
		$this->load->model('Common_model');
			
		$tbl='users';
		$pid='user_id';
		$url='/user/manage/';	 	
		$fld='User';
		
		if($this->global->get_user_meta($rowid,'photo_url'))
		{
			$photo_name = $this->global->get_user_meta($rowid,'photo_url');
			if(isset($photo_name) && !empty($photo_name))
				unlink('uploads/user/'.$photo_name);
		}
		if($this->global->get_user_meta($rowid,'id_url'))
		{
			$id_name = $this->global->get_user_meta($rowid,'id_url');
			if(isset($id_name) && !empty($id_name))
				unlink('uploads/user/'.$id_name);
		}
		
		$this->Common_model->commonDelete('user_meta',$rowid,$pid );
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
	
	public function user_email_check($val)
	{
		$this->load->model('Common_model');
		//echo $val;
		$options=array('where'=>array('user_email'=>$val));
		$user_exsist=$this->Common_model->commonSelect('users',$options);
		//echo $user_exsist->num_rows();die;
		if($user_exsist->num_rows() > 0 )
		{
			$this->form_validation->set_message('user_email_check', ' %s already exsist');
			return FALSE;
		}	
		else
		{	return TRUE;		}
	}	
	
	public function isLogin()	{
		$site_url = site_url();		
		/*if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == TRUE 
		&& isset($_SESSION['site_url']) && $_SESSION['site_url'] == $site_url 
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
