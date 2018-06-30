<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Loan extends CI_Controller {
class Loan extends MY_Controller {	
	
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
		
		 if(!$this->has_permission("loan" , "view_all"))
			$where = "where cst.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		
		$query = "select loans.*,cst.* from loans 				
			inner join users u on u.user_id = loans.client_ID
			inner join user_meta um on um.user_id = u.user_id
			and um.meta_key = 'client_ID'
			inner join clients cst on cst.client_ID=um.meta_value 
			$where
			order by loans.loan_id DESC";
        //echo $query;
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/loan/manage";
		
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
			
				
			$this->form_validation->set_rules('principalAmt', 'Principal Amount', 'trim|required');
			$this->form_validation->set_rules('loan_type', 'Loan Type', 'trim|required');
			
			if ($this->form_validation->run() != FALSE)
			{
				//$post_slug = $page_title; 
				//$post_type = "page";					
				extract($_POST,EXTR_OVERWRITE);
				
				$client_id = $this->DecryptClientId($client_id);
				$loan_type = $this->DecryptClientId($loan_type);
				
				if(empty($user_id) || $user_id == 0)
				{	
					$_SESSION['msg'] = '<p class="error_msg">Session Expired.</p>';
					$this->session->set_userdata('logged_in', false);
					$_SESSION['logged_in'] = false;	
					redirect('/logins','location');
				}
				
				$entry_time = time();
				 $date_explode = explode('/',$loanDate);
				 $loanDate = mktime(0,0,0,$date_explode[1],$date_explode[0],$date_explode[2]);
				//$loanDate = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$emi_type = 'normal';
				$datai = array( 
						'loan_type' => $loan_type,	
						'client_ID' => $client_id,	
						'loan_date' => $loanDate,	
						'principal_amount' => $principalAmt,	
						'time_periods' => $timePeriods,	
						'interest_rate' => $interestRate,	
						'emi_amount' => round($emiAmt),
						'emi_type' => $emi_type,
						'payment_terms' => $payment_terms,
						'payment_mode' => $payment_mode,
						'net_amount' => round($netAmt),
						'user_id' => $user_id,
						'entry_date' => $entry_time,
                        'notify_install_count' => $notifyCount,
						'total_amount_deposite' => 0
						);
				
				$loan_id=$this->Common_model->commonInsert('loans',$datai);
				
				$result = $this->Common_model->commonQuery("select users.*,um.meta_value as client_acc_no from users 
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'acc_no' 
				where users.user_id = $client_id
				");	
				
				if($result->num_rows() > 0)
				{
					$client_row = $result->row();
					$client_acc_no = $client_row->client_acc_no;
				}
				
				$result = $this->Common_model->commonQuery("select users.user_type from users 
				where user_id =  $client_id
				");	
				
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					if($user_row->user_type == 'user')
						$cap_type = 'borrower';
					else if($user_row->user_type != 'user' && $user_row->user_type != 'admin')
						$cap_type = 'employee';
				}
				
				$result = $this->Common_model->commonQuery("select loan_title from loan_types 
				where loan_type_id = $loan_type
				");	
				$loan_type_text = 'Loan';
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					$loan_type_text = $user_row->loan_title;
				}
				
				
				
				$datai = array( 
							'payment_in' => 0,
							'payment_out' => $principalAmt,
							'payment_log' => "$loan_type_text given to account no. $client_acc_no",
							'payment_date' => $loanDate,
							'payment_method' => $payment_mode,
							'trans_type' => 'loan',
							'client_ID' => $client_id,
							'cap_by' => $cap_type,
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
				$this->Common_model->commonInsert('capital_account',$datai);
				
				/*
				$datai = array( 
				'client_ID' => $client_id,	
				'loan_id' => $loan_id,	
				'emi_amount' => round($emiAmt),	
				'deposit_amount' => round($emiAmt),
				'due_date' => $loanDate,
				'payment_date' => $loanDate ,
				'user_id' => $user_id,
				'entry_date' => $entry_time
				);
								
				$this->Common_model->commonInsert('loan_payments',$datai);
				*/ 
				
				/*
				if(!empty($ClientMobile))
				{
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'addloan_message_sms'");			
					
					$message = "Dear [NAME], Your loan of Tk[AMOUNT] has been approved! 
					Installment count [INSTALLEMENT_COUNT], Payment term [TERM], EMI Tk[EMI_AMOUNT].";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}	
					/*
					$message = "Dear $ClientName, Your loan of Tk$principalAmt has been approved! 
					Installment count $timePeriods, Payment term $payment_terms, EMI ".round($emiAmt);
					*/
				/*	
					$string = str_replace("[NAME]",$ClientName,$message);
					$string = str_replace("[AMOUNT]",$principalAmt,$string);
					$string = str_replace("[INSTALLEMENT_COUNT]",$timePeriods,$string);
					$string = str_replace("[TERM]",$payment_terms,$string);
					$string = str_replace("[EMI_AMOUNT]",round($emiAmt),$string);
					
					$args = array('mobile' => $ClientMobile , "message" => $string);
					$sms = $this->sms->send_sms($args);
				
				}
				*/
				$_SESSION['msg'] = '<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Loan Added Successfully.
							</div>
							';
				redirect('/loan/manage','location');	
			
			
			}
		}
		
		$data['client_list'] = $this->Common_model->commonQuery("
				select users.*,
				um.meta_value as first_name,
				um2.meta_value as last_name,
				um3.meta_value as acc_no 						
				from users 
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'first_name'
				left join user_meta um2 on um2.user_id = users.user_id
				and um2.meta_key = 'last_name'
				left join user_meta um3 on um3.user_id = users.user_id
				and um3.meta_key = 'acc_no'
				where user_type = 'user' and user_status = 'Y'
				order by user_id DESC
				");	
		
		$data['loan_type_list'] = $this->Common_model->commonQuery("
				select *
				from loan_types 
				order by loan_type_id DESC
				");	
		
		$data['theme']=$theme;
		$data['content'] = "$theme/loan/add_new";
		$this->load->view("$theme/header",$data);
		//$data['sidebar'] = 'sidebar-left';		

		
	}
	
	public function edit($l_id = NULL)
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if(!$this->has_method_access())
		{
			redirect('/main/','location');
		}
		redirect('/main/','location');
		$CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		
		if(isset($_POST['submit']) )
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', "</div>");
			
				
			$this->form_validation->set_rules('principalAmt', 'Principal Amount', 'trim|required');
			
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				if(isset($_POST['submit']))
					$page_status = 'publish';
				else
					$page_status = 'draft';
				
				$cId = $this->DecryptClientId($loan_id);
				
				$date_explode = explode('/',$loanDate);
				//$loanDate = mktime(0,0,0,$date_explode[1],$date_explode[0],$date_explode[2]);
				
				$datai = array( 
								'loan_type' => 'personal',	
								'customer_ID' => $customer_ID,	
								'loan_date' => $loanDate,	
								'loan_no' => $loanNo,	
								'principal_amount' => $principalAmt,	
								'file_charge' => $fileCharge,	
								'penalty' => $penalty,	
								'time_periods' => $timePeriods,	
								'interest_rate' => $interestRate,	
								'emi_amount' => $emiAmt,	
								);
							
				$post_id = $this->Common_model->commonUpdate('loans',$datai,'loan_id',$cId);
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								Personal Loan Updated Successfully.
						  </div>
							';
				redirect('/ploan/manage','location');	
			
			
			}else
				redirect('/main','location');	
		
		}
		
		$data['loan_id'] = $l_id;
		$decId = $this->DecryptClientId($l_id);
		$data['query'] = $this->Common_model->commonQuery("select * from loans where loan_id = $decId");	
		$data['theme']=$theme;
		$data['customers'] = $this->Common_model->commonQuery("select * from customers ");
		$data['content'] = "$theme/ploan/edit";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function invoice($l_id = NULL)
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
		$decId = $this->DecryptClientId($l_id);
		
		$data['customer_loan_detail'] = $this->Common_model->commonQuery("
					select loans.*,cst.*,ty.loan_title as loan_type_text from loans 				
					inner join users u on u.user_id = loans.client_ID
					inner join user_meta um on um.user_id = u.user_id
					and um.meta_key = 'client_ID'
					inner join clients cst on cst.client_ID=um.meta_value
					inner join loan_types ty on ty.loan_type_id = loans.loan_type
					where loans.loan_id = $decId");	
		
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/loan/invoice";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function invoice_details($l_id = NULL)
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
		$decId = $this->DecryptClientId($l_id);
		
		$data['customer_loan_detail'] = $this->Common_model->commonQuery("
					select loans.*,cst.*,ty.loan_title as loan_type_text from loans 				
					inner join users u on u.user_id = loans.client_ID
					inner join user_meta um on um.user_id = u.user_id
					and um.meta_key = 'client_ID'
					inner join clients cst on cst.client_ID=um.meta_value
					inner join loan_types ty on ty.loan_type_id = loans.loan_type
					where loans.loan_id = $decId");	
		
		$data['loan_payment_detail'] = $this->Common_model->commonQuery("
					select loan_payments.* from loan_payments 
					where loan_payments.loan_id = $decId 
					order by loan_payments.payment_id DESC");
		
		
		
		$data['content'] = "$theme/loan/invoice_details";
		
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
			
		$tbl='loans';
		$pid='loan_id';
		$url='/loan/manage/';	 	
		$fld='Loan';
		   
		$this->Common_model->commonDelete('loan_payments',$rowid,'loan_id' );
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
	
	
}
