<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Client extends CI_Controller {
class Client extends MY_Controller {	 
	
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
		
		redirect('/client/manage','location');	

		
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
		
		//if($this->session->userdata('user_id') != 1)
		if(!$this->has_permission("client" , "view_all"))
			$where = " where clients.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
            
        $branch_id = $_REQUEST['branch_id']; 
        $extraSql = ''; 
        if($branch_id)
        {
            if($where!='')
            {
                $extraSql = ' AND a.branch_id = '.$branch_id;
            }
            else
            {
                $extraSql = ' WHERE a.branch_id = '.$branch_id;
            }
        }
		
		$data['query'] = $this->Common_model->commonQuery("
				select a.*, b.branch_name from clients as a
                LEFT JOIN branches as b ON a.branch_id = b.id
				$where
                $extraSql
				order by a.client_ID DESC
				");	
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/client/manage";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function add_new()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		
		//echo $this->has_method_access();
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
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		/*$this->load->library('breadcrumbs');*/
		date_default_timezone_set('Asia/Kolkata');
		//print_r($_POST);
		 /*echo '<pre>';
		 print_r($_SESSION);
		 echo '</pre>';
		 exit;*/
		 
		$default_status = $this->get_default_status ("client");
		$user_status = $this->get_default_status ("user");
		
		if($user_status == 'publish') $user_status = 'Y';
		else	$user_status = 'N';
		 
		if(isset($_POST['submit']) || isset($_POST['draft']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>', "</div>");
			
				
			$this->form_validation->set_rules('ClientName', 'Client Name', 'trim|required');
			$this->form_validation->set_rules('ClientId', 'Client ID', 'trim|required|callback_acc_no_check');
			$this->form_validation->set_rules('UserName', 'User Name', 'trim|required|callback_user_name_check');
			$this->form_validation->set_rules('Password', 'Password', 'trim|required');
			$this->form_validation->set_rules('RepeatPassword', 'Repeat Password', 'trim|required|matches[Password]');
			
			$this->form_validation->set_rules('FatherName', 'Father/Spouse Name', 'trim');
			$this->form_validation->set_rules('MotherName', 'Mother Name', 'trim');
			$this->form_validation->set_rules('ClientMobile', 'Client Mobile No.', 'trim');
			$this->form_validation->set_rules('ClientAddress', 'Client Address', 'trim');
            $this->form_validation->set_rules('ClientRemarks', 'Client Remarks', 'trim');
            $this->form_validation->set_rules('ClientAge', 'Client Age', 'trim');
            $this->form_validation->set_rules('ClientNominee', 'Nominee', 'trim');
            $this->form_validation->set_rules('ClientAgeofnominee', 'Age of nominee', 'trim');
            $this->form_validation->set_rules('ClientRelationWithnominee', 'Relation with nominee', 'trim');

 $this->form_validation->set_rules('account_manager', 'Account Manager', 'trim');			
			//user_name_check
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
				
				if($default_status == "publish"){
					if(isset($_POST['submit']))
						$page_status = 'publish';
					else if(isset($_POST['draft']))
						$page_status = 'draft';
					else
						$page_status = 'draft';
				}else{
					$page_status = 'draft';
				}	
				
				if($page_status == 'draft')
					$user_status = 'N';
				if($page_status == 'publish')
					$user_status = 'Y';
				else	
					$user_status = 'N';
					
				$cur_time = time();
				$datai = array( 
                                'branch_id' => $branch_id,
								'client_acc_no' => $ClientId,
								'client_name' => trim($ClientName),	
								'client_father_name' => trim($FatherName),
								'client_mother_name' => trim($MotherName),
								'client_mobile' => $ClientMobile,	
								'client_address' => trim($ClientAddress),	
								'entry_date' => $cur_time,
								'current_status' => $page_status,
								'user_id' => $user_id,
								'client_photo_proof' => $client_photo_proof,
								'client_id_proof' => $client_id_proof,
                                'remarks' => $remarks,
                                'age' => $age,
                                'account_managent'=>$_POST['account_manager'],
                                'nominee' => $nominee,
                                'age_of_nominee' => $age_of_nominee,
                                'relation_with_nominee' => $relation_with_nominee,                             
								);
						
				$client_ID=$this->Common_model->commonInsert('clients',$datai);

				
				$datai = array( 
								'user_name' => trim($UserName),	
								'user_pass' => md5(trim($Password)),
								'user_email' => '',
								'user_type' => trim($UserType),	
								'user_registered_date' => $cur_time,	
								'user_update_date' => $cur_time,
								'user_verified' => 'Y',
								'user_status' => $user_status, //	'Y',
                                'branch_id' => $branch_id,
								);
				$user_ID=$this->Common_model->commonInsert('users',$datai);
				
				$user_meta = array('first_name' => trim($ClientName),
									'mobile_no' => $ClientMobile,
									'address'   => trim($ClientAddress),
									'photo_url' => $client_photo_proof,
									'id_url'    => $client_id_proof,
									'client_ID' => $client_ID,
									'acc_no'    => $ClientId,
                                    'branch_id' => $branch_id,
									);
				foreach($user_meta as $key=>$val)
				{
					$datai = array( 
								'meta_key' => trim($key),	
								'meta_value' => trim($val),
								'user_id' => $user_ID
								);
					$this->Common_model->commonInsert('user_meta',$datai);
				}
				
				
				if(!empty($ClientMobile))
				{
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'welcome_message_sms'");			
					
					$message = "Dear [NAME] You have been added with [SITE_NAME]. You account ID : [ACCOUNT_ID], 
					username: [USER_NAME], Password: [PASSWORD]";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}	
					
					
					$site_name = 'http://www.loanify.com';
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'company_website'");			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$site_name = $row->option_value;
						}
					}	
					
					/*
					$message = ":  Dear $ClientName. You have been added with $site_name. 
					Your account ID : $ClientId, username: $UserName, Password: $Password";
					*/
					
					$string = str_replace("[NAME]",$ClientName,$message);
					$string = str_replace("[SITE_NAME]",$site_name,$string);
					$string = str_replace("[ACCOUNT_ID]",$ClientId,$string);
					$string = str_replace("[USER_NAME]",$UserName,$string);
					$string = str_replace("[PASSWORD]",$Password,$string);
					
					$args = array('mobile' => $ClientMobile , "message" => $string);
					$sms = $this->sms->send_sms($args);
                    
                    $notify_read = 'N';
        			$notify_type = 'Notification';
        			$description = $string;
        			$client_notify = array( 
        							'description' => $description,
        							'client_ID'   => $client_ID,
        							'notify_read' => $notify_read,
        							'notify_type' => $notify_type,);
        			$this->Common_model->commonInsert('notification',$client_notify);
					
					/*
					$headers = 'From: webmaster@example.com' . "\r\n" .
					'Reply-To: webmaster@example.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail('websolone@gmail.com','Notification',$string,$headers);
					*/
				}
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Client added Successfully.
							</div>
							';
				redirect('/client/manage','location');	
			
			
			}
		}
        
        $data['branch_list'] = $this->Common_model->commonQuery("select * from branches order by id DESC");	
        $data['manage'] = $this->Common_model->commonQuery("select * from users where user_type != 'admin' order by user_id DESC");	
        
		$data['theme']=$theme;
		
		$data['content'] = "$theme/client/add_new";
		
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
		
		
		$default_status = $this->get_default_status ("client");
		$user_status = $this->get_default_status ("user");
		
		if($user_status == 'publish') $user_status = 'Y';
		else	$user_status = 'N';
				
		if(isset($_POST['submit']) || isset($_POST['draft']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters("<div class='notification note-error'>	
			<a href='#' class='close' title='Close'>
			<span>close</span></a> 	<span class='icon'></span>	<p><strong>Error :</strong>", "</p></div>");
			
				
			$this->form_validation->set_rules('ClientName', 'Client Name', 'trim|required');
			$this->form_validation->set_rules('ClientId', 'Client ID', 'trim|required');
			
			
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				/*if(isset($_POST['submit']))
					$page_status = 'publish';
				else if(isset($_POST['draft']))
					$page_status = 'draft';
				else
					$page_status = 'draft';*/
				
				if($default_status == "publish"){
					if(isset($_POST['submit']))
						$page_status = 'publish';
					else if(isset($_POST['draft']))
						$page_status = 'draft';
					else
						$page_status = 'draft';
				}else{
					$page_status = 'draft';
				}	
				
				if($page_status == 'draft')
					$user_status = 'N';
				if($page_status == 'publish')
					$user_status = 'Y';
				else	
					$user_status = 'N';
				
				 $clientId=$_POST['ClientId'];
				$cId = $this->DecryptClientId($client_id);
				
				$datai = array( 
                                'branch_id' => $branch_id,
                                'client_name' => trim($ClientName),	
								'client_father_name' => trim($FatherName),
								'client_mother_name' => trim($MotherName),
								'client_mobile' => trim($ClientMobile),	
								'client_address' => trim($ClientAddress),	
								'current_status' => $page_status,
								'client_photo_proof' => $client_photo_proof,
								'client_id_proof' => $client_id_proof,
                                                                'client_acc_no' =>$clientId,
                                'remarks' => $remarks,
                                'age' => $age,
                                'nominee' => $nominee,
                                'age_of_nominee' => $age_of_nominee,
                                'relation_with_nominee' => $relation_with_nominee,  
								);
				
				$post_id = $this->Common_model->commonUpdate('clients',$datai,'client_ID',$cId);
				
				
				$user = $this->Common_model->commonQuery("select * from user_meta where meta_key = 'client_ID' and meta_value = $cId");	
				if ($user->num_rows() > 0)
				{ 	
					$row = $user->row();
					$datai = array( 'user_status' => $user_status,	);
				
					$user_id = $this->Common_model->commonUpdate('users',$datai,'user_id',$row->user_id);

				}
				
				$_SESSION['msg'] = '<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Client Updated Successfully.
						  </div>
							';
				redirect('/client/manage','location');	
			
			
			}
		}
		
		$data['client_id'] = $c_id;
		$decId = $this->DecryptClientId($c_id);
		$data['query'] = $this->Common_model->commonQuery("
				select * from clients where client_ID = $decId");	
		/*
		if($cur_row->num_rows() > 0)
		{
			$c_row = $cur_row->row();
			if($c_row->user_id != $this->session->userdata('user_id') &&
			$this->session->userdata('user_id') != 1)
			{
				redirect('/client/view','location');
			}
		}
		*/
		
        $data['branch_list'] = $this->Common_model->commonQuery("select * from branches order by id DESC");
        $data['manage'] = $this->Common_model->commonQuery("select * from users where user_type != 'admin' order by user_id DESC");	
        
		$data['theme']=$theme;
		
		$data['content'] = "$theme/client/edit";
		
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
					select *,um.user_id as user_id from clients cst
					inner join user_meta um on um.meta_key = 'client_ID' 
					and um.meta_value = cst.client_ID
					where cst.client_ID = $decId");	
		
		$data['content'] = "$theme/client/view";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function print_report($c_id = NULL)
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		$this->load->library('global');
		
		$data['theme']=$theme;
		$decId = $this->DecryptClientId($c_id);
		
		$data['customer_detail'] = $this->Common_model->commonQuery("
					select * from customers 
					where customer_ID = $decId");
		
		$data['p_loan_list'] = $this->Common_model->commonQuery("
					select * from loans 
					inner join customers cst on cst.customer_ID=loans.customer_ID  
					where loan_type='personal' and cst.customer_ID = $decId");	
		$data['total_loan'] = $data['p_loan_list']->num_rows();
		$this->load->view("$theme/customer/report_by_customer",$data);
		
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
			
		$tbl='clients';
		$pid='client_ID';
		$url='/client/manage/';	 	
		$fld='Client';
		
		$options=array('where'=>array('client_ID'=>$rowid));
		$data_result = $this->Common_model->commonSelect('clients',$options );	
		if($data_result->num_rows()>0)
		{
			foreach($data_result->result() as $row)
			{
				$photo_name = $row->client_photo_proof;
				if(isset($photo_name) && !empty($photo_name))
					unlink('uploads/client/'.$photo_name);
				$id_name = $row->client_id_proof;
				if(isset($id_name) && !empty($id_name))
					unlink('uploads/client/'.$id_name);
			}					
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
		$user_exists=$this->Common_model->commonSelect('users',$options);
		//echo $user_exsist->num_rows();die;
		if($user_exists->num_rows() > 0 )
		{
			$this->form_validation->set_message('user_name_check', ' %s already exists');
			return FALSE;
		}	
		else
		{	return TRUE;		}
	}
	
	public function acc_no_check($val)
	{
		$this->load->model('Common_model');
		//echo $val;
		$options=array('where'=>array('meta_key'=> 'acc_no' ,'meta_value'=>$val));
		//$sql = "";
		$user_exists=$this->Common_model->commonSelect('user_meta',$options);
		//echo $user_exsist->num_rows();die;
		if($user_exists->num_rows() > 0 )
		{
			$this->form_validation->set_message('acc_no_check', ' %s already exists');
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
