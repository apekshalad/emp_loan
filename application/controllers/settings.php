<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Settings extends CI_Controller { 
class Settings extends MY_Controller {	
	
	public function index()
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
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/home_page";
		
		$this->load->view("$theme/header",$data);
		//$data['sidebar'] = 'sidebar-left';		

		
	}
	
	public function general_settings()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('options[company_title]', 'Company Title', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if($this->session->userdata('user_id') != 1)
				{	
					redirect('/','location');
				}
				
				foreach($options as $key=>$value)
				{
					$query = $this->Common_model->commonQuery("select * from options where option_key = '$key' ");			
					if($query->num_rows() > 0)
					{
						$row = $query->row();
						$options_id = $row->option_id;
						$datai = array('option_value' => $value);
						$this->Common_model->commonUpdate('options',$datai,'option_id',$options_id);			
					}
					else
					{
						$datai = array( 'option_key' => $key,	'option_value' => $value);
						$this->Common_model->commonInsert('options',$datai);
					}
				}
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								General Settings Update Successfully.
							</div>
							';
				redirect('/settings/general_settings','location');	
			
			
			}
		
		}
		
		$data['options_list'] = $this->Common_model->commonQuery("select * from options");	
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/general_settings";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	public function sms_settings()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$this->load->library('sms');
		//$sms = $this->sms->send_sms();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('options[user_name]', 'Username', 'trim|required');
			$this->form_validation->set_rules('options[password]', 'Password', 'trim|required');
			
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if($this->session->userdata('user_id') != 1)
				{	
					redirect('/','location');
				}
				
				foreach($options as $key=>$value)
				{
					$query = $this->Common_model->commonQuery("select * from options where option_key = '$key' ");			
					if($query->num_rows() > 0)
					{
						$row = $query->row();
						$options_id = $row->option_id;
						$datai = array('option_value' => $value);
						$this->Common_model->commonUpdate('options',$datai,'option_id',$options_id);			
					}
					else
					{
						$datai = array( 'option_key' => $key,	'option_value' => $value);
						$this->Common_model->commonInsert('options',$datai);
					}
				}
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								SMS Settings Update Successfully.
							</div>
							';
				redirect('/settings/sms_settings','location');	
			
			
			}
		
		}
		
		$data['options_list'] = $this->Common_model->commonQuery("select * from options");	
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/sms_settings";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	public function db_settings()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			
			$CI->load->dbutil(); 
          $prefs = array( 'format' => 'zip', // gzip, zip, txt 
             'filename' => 'backup_'.date('d_m_Y_H_i_s').'.sql', 
                                                          // File name - NEEDED ONLY WITH ZIP FILES 
             'add_drop' => TRUE,
                                                         // Whether to add DROP TABLE statements to backup file
             'add_insert'=> TRUE,
                                                        // Whether to add INSERT data to backup file 
             'newline' => "\n"
                                                       // Newline character used in backup file 
             ); 
             // Backup your entire database and assign it to a variable 
          $backup =&  $CI->dbutil->backup($prefs); 
             // Load the file helper and write the file to your server 
          $CI->load->helper('file'); 
          write_file(FCPATH.'/data/backups/'.'dbbackup_'.date('d_m_Y_H_i_s').'.zip', $backup); 
             // Load the download helper and send the file to your desktop 
          $CI->load->helper('download'); 
          force_download('dbbackup_'.date('d_m_Y_H_i_s').'.zip', $backup);
		}
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/db_settings";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	
	public function loan_settings()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('loan_type_title', 'Loan Type Title', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if($this->session->userdata('user_id') != 1)
				{	
					redirect('/','location');
				}
				
				$loan_slug = $this->global->get_slug($loan_type_title);
				$datai = array( 'loan_title' => $loan_type_title,	'loan_slug' => $loan_slug);
				$this->Common_model->commonInsert('loan_types',$datai);
					
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Loan Type Added Successfully.
							</div>
							';
				redirect('/settings/loan_settings','location');	
			
			
			}
		
		}
		
		if(isset($_POST['edit_entry']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('loan_type_title', 'Loan Type Title', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if($this->session->userdata('user_id') != 1)
				{	
					redirect('/','location');
				}
				$loan_type_id = $this->DecryptClientId($loan_type_id);
				$loan_slug = $this->global->get_slug($loan_type_title);
				$datai = array( 'loan_title' => $loan_type_title,	'loan_slug' => $loan_slug);
				
				$this->Common_model->commonUpdate('loan_types',$datai,'loan_type_id',$loan_type_id);
					
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Loan Type Update Successfully.
							</div>
							';
				redirect('/settings/loan_settings','location');	
			
			
			}
		
		}
		
		$data['loan_type_list'] = $this->Common_model->commonQuery("select * from loan_types order by loan_type_id DESC");	
		
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/loan_settings";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function employee_settings()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
		
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']) && 0)
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('options[company_title]', 'Company Title', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if($this->session->userdata('user_id') != 1)
				{	
					redirect('/','location');
				}
				
				foreach($options as $key=>$value)
				{
					$query = $this->Common_model->commonQuery("select * from options where option_key = '$key' ");			
					if($query->num_rows() > 0)
					{
						$row = $query->row();
						$options_id = $row->option_id;
						$datai = array('option_value' => $value);
						$this->Common_model->commonUpdate('options',$datai,'option_id',$options_id);			
					}
					else
					{
						$datai = array( 'option_key' => $key,	'option_value' => $value);
						$this->Common_model->commonInsert('options',$datai);
					}
				}
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								General Settings Update Successfully.
							</div>
							';
				redirect('/settings/general_settings','location');	
			
			
			}
		
		}
		
		//$data['options_list'] = $this->Common_model->commonQuery("select * from options");	
		$data['query'] = $this->Common_model->commonQuery("
				select * from users 
				where user_status = 'Y' and (user_type != 'user')
				order by user_id  DESC
				");	
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/employee_settings";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function change_password()
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
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('repeat_password', 'Repeat Password', 'trim|required|matches[password]');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				
				$datai = array( 
								'user_pass' => md5($password),
								);
				
				
				$this->Common_model->commonUpdate('users',$datai,'user_id',$this->session->userdata('user_id'));
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Password Change Successfully.
							</div>
							';
				redirect('/settings/change_password','location');	
			
			
			}
		
		}
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/change_password";
		
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
	
	public function delete($slug,$rowid)
	{
		
		
		
		$CI =& get_instance();
		$this->load->library('global');
		if(!is_array($rowid))
			$rowid	= $this->global->DecryptClientId($rowid);
		$this->load->model('Common_model');
		if($slug == 'loan_settings')
		{
			$tbl='loan_types';
			$pid='loan_type_id';
			$url='/settings/'.$slug;	 	
			$fld='Loan Type';
		}
		else
		{
			$tbl='users';
			$pid='user_id';
			$url='/branch/manage/';	 	
			$fld='Branch';
		}
		
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
	
public function day_off()
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
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_rules('dayoff', 'Day Off', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$upd['option_value']=$_POST['dayoff'];
			
				
				$this->Common_model->commonUpdate('options',$upd,'option_id',31);
				
				redirect('/settings/day_off','location');	
			
			
			}
		
		}
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/settings/day_off";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
}
