<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Saving extends CI_Controller {
class Saving extends MY_Controller {	
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function index()
		{
			if(!$this->isLogin())
			{
				redirect('/logins','location');
			}
				redirect('/saving/borrowers','location');
		}
	
	
	public function borrowers()
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
		$this->load->library('sms');
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('client_ID', 'Client/Id', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$client_ID = $this->DecryptClientId($client_ID);
				 $datai = array( 
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_mode,
							'trans_type' => 'saving',
							'client_ID' => $client_ID,
							'cap_by' => 'borrower',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
				
				$result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
				inner join  user_meta as um on um.user_id = users.user_id
				and um.meta_key = 'mobile_no'
				WHERE users.user_id = $client_ID
				");	
				
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'deposit_message_sms'");			
					
					$message = "Dear [NAME], Tk[AMOUNT] has been saved to your [SAVING_TYPE] account. 
					Current Savings Tk[SAVING_AMOUNT]";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}

					$result = $this->Common_model->commonQuery("select um.meta_value as client_acc_no,
					um2.meta_value as client_first_name,
					um3.meta_value as client_last_name
					from users 
					left join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'acc_no' 
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'first_name' 
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'last_name'
					where users.user_id = $client_ID
					");	
					
					$client_acc_no = '';
					$client_first_name = '';
					$client_last_name = '';
					if($result->num_rows() > 0)
					{
						$client_row = $result->row();
						$client_acc_no = $client_row->client_acc_no;
						$client_first_name = $client_row->client_first_name;
					}
					
					$total_current_saving = 0;
					$total_current_deposit = 0;
					$total_current_withdraw = 0;
					$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
					where ca.trans_type = 'saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_deposit += $row->total_current_deposit;
					}
					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
					where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_withdraw += $row->total_current_withdraw;
					}
					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
					
					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
					$string = str_replace("[AMOUNT]",$amount,$string);
					$string = str_replace("[SAVING_TYPE]",'General Saving',$string);
					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
					
					$args = array('mobile' => $user_row->mobile_no , "message" => $string);
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
								
								Saving Entry Added Successfully.
							</div>
							';
				redirect('/saving/borrowers','location');	
			
			
			}
		
		}
		
		if(isset($_POST['edit_entry']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$cap_acc_id = $this->DecryptClientId($cap_acc_id);
				
				 $datai = array( 
							'payment_in' => $amount,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_mode,
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
					
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Saving Entry Updated Successfully.
							</div>
							';
				redirect('/saving/borrowers','location');	
			
			
			}
			
		}
		
		/*
		$data['client_list'] = $this->Common_model->commonQuery("
				select * from clients 
				order by client_ID DESC
				");
		*/
		
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
				where users.user_type = 'user' and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		/*
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		*/
		
		$query = "select ca.*,um.meta_value as first_name,
						um2.meta_value as last_name  
		from capital_account ca
		inner join users on users.user_id = ca.client_ID
		inner join user_meta um on um.user_id = users.user_id
		and um.meta_key = 'first_name'
		left join user_meta um2 on um2.user_id = users.user_id
		and um2.meta_key = 'last_name'
		where ca.trans_type = 'saving' and cap_by = 'borrower' 
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/saving/borrowers";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function employers()
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
		$this->load->library('sms');
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('client_ID', 'Client/Id', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$client_ID = $this->DecryptClientId($client_ID);
				$datai = array( 
						'payment_in' => $amount,
						'payment_out' => 0,
						'payment_log' => $remarks,
						'payment_date' => $pay_date,
						'trans_type' => 'saving',
						'client_ID' => $client_ID,
						'payment_method' => $payment_mode,
						'cap_by' => 'employee',
						'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),
				);
				$this->Common_model->commonInsert('capital_account',$datai);
				
				$result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
				inner join  user_meta as um on um.user_id = users.user_id
				and um.meta_key = 'mobile_no'
				WHERE users.user_id = $client_ID");	
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'deposit_message_sms'");			
					
					$message = "Dear [NAME], Tk[AMOUNT] has been saved to your [SAVING_TYPE] account. 
					Current Savings Tk[SAVING_AMOUNT]";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}

					$result = $this->Common_model->commonQuery("select um.meta_value as client_acc_no,
					um2.meta_value as client_first_name,
					um3.meta_value as client_last_name
					from users 
					left join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'acc_no' 
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'first_name' 
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'last_name'
					where users.user_id = $client_ID
					");	
					
					$client_acc_no = '';
					$client_first_name = '';
					$client_last_name = '';
					if($result->num_rows() > 0)
					{
						$client_row = $result->row();
						$client_acc_no = $client_row->client_acc_no;
						$client_first_name = $client_row->client_first_name;
						$client_last_name = $client_row->client_last_name;
					}
					
					$total_current_saving = 0;
					$total_current_deposit = 0;
					$total_current_withdraw = 0;
					$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
					where ca.trans_type = 'saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_deposit += $row->total_current_deposit;
					}
					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
					where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_withdraw += $row->total_current_withdraw;
					}
					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
					
					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
					$string = str_replace("[AMOUNT]",$amount,$string);
					$string = str_replace("[SAVING_TYPE]",'General Saving',$string);
					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
					
					$args = array('mobile' => $user_row->mobile_no, "message" => $string);
					$sms = $this->sms->send_sms($args);
                    
                    $notify_read = 'N';
        			$notify_type = 'Notification';
        			$description = $string;
        			$notify_saving = array( 
        							'description' => $description,
        							'client_ID'   => $client_ID,
        							'notify_read' => $notify_read,
        							'notify_type' => $notify_type,);
        			$this->Common_model->commonInsert('notification',$notify_saving);
					
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
								
								Saving Entry Added Successfully.
							</div>
							';
				redirect('/saving/employers','location');	
			
			
			}
		
		}
		
		if(isset($_POST['edit_entry']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$cap_acc_id = $this->DecryptClientId($cap_acc_id);
				
				 $datai = array( 
							'payment_in' => $amount,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_mode,
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
					
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Saving Entry Updated Successfully.
							</div>
							';
				redirect('/saving/employers','location');	
			
			
			}
			
		}
		
		/*
		$data['client_list'] = $this->Common_model->commonQuery("
				select * from clients 
				order by client_ID DESC
				");
		*/
		
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
				where users.user_type != 'user' and users.user_type != 'admin' and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		/*
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		*/
		$query = "select ca.*,um.meta_value as first_name,
						um2.meta_value as last_name  
		from capital_account ca
		inner join users on users.user_id = ca.client_ID
		inner join user_meta um on um.user_id = users.user_id
		and um.meta_key = 'first_name'
		left join user_meta um2 on um2.user_id = users.user_id
		and um2.meta_key = 'last_name'
		where ca.trans_type = 'saving' and cap_by = 'employee' 
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/saving/employers";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function other_dpst()
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
		$this->load->library('sms');
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('client_ID', 'Client/Id', 'trim|required');
			//$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$client_ID = $this->DecryptClientId($client_ID);
				$result = $this->Common_model->commonQuery("select user_type from users 
				where user_id = $client_ID
				");	
				
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					if($user_row->user_type == 'user')
						$cap_type = 'borrower';
					else if($user_row->user_type != 'user' && $user_row->user_type != 'admin')
						$cap_type = 'employee';
				}
				
				
				if($dpst_option == "general")
				{
					$datai = array( 'payment_in' => $amount, 'payment_out' => 0, 'payment_log' => $remarks,
						'payment_date' => $pay_date, 'trans_type' => 'conf_saving',
						'client_ID' => $client_ID,	 'payment_method' => $payment_mode,
						'cap_by' => $cap_type,		 'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),	);
						
					$cat_acc_id = $this->Common_model->commonInsert('capital_account',$datai);
				
				
					if(isset($profit_type) && !empty($profit_type))
					{
						$profit_value = '';
						$profit_amount = 0;
						if(isset($ProfitPer) && !empty($ProfitPer))
						{
							$profit_value = $ProfitPer;
							$profit_amount = round(($amount*$ProfitPer)/100);
						}
						else if(isset($ProfitFix) && !empty($ProfitFix))
						{
							$profit_amount = $profit_value = $ProfitFix;
						}
						
						 $datai = array( 
									'cap_acc_id' => $cat_acc_id,
									'profit_type' => $profit_type,
									'profit_value' => $profit_value,
									'profit_amount' => $profit_amount,
									'deposit_amount' => $amount,
									'total_profit' => ($amount+$profit_amount)
									
							);
						//$this->Common_model->commonInsert('other_deposits',$datai);
						
						
						/*$datai = array( 
								'payment_in' => ($amount+$profit_amount),
						);
						$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cat_acc_id);*/
						
					}
				}else{
				
					$profit_value = '';
					$profit_amount = 0;
					$amount = 0;
					if(isset($ProfitPer) && !empty($ProfitPer))
					{
						$profit_value = $ProfitPer;
						$profit_amount = round(($total_current_saving*$ProfitPer)/100);
					}
					else if(isset($ProfitFix) && !empty($ProfitFix))
					{
						$profit_amount = $profit_value = $ProfitFix;
					}
					
					$amount  = ($amount+$profit_amount);
					
					$datai = array( 'payment_in' => 0, 'payment_out' => $amount, 'payment_log' => $remarks,
						'payment_date' => $pay_date, 'trans_type' => 'expense','trans_from' => 'conf_saving',
						'client_ID' => $client_ID,	 'payment_method' => $payment_mode,
						'cap_by' => $cap_type,		 'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),	);
						
					$cat_acc_id = $this->Common_model->commonInsert('capital_account',$datai);
					
					/*
					$datai = array( 
									'cap_acc_id' => $cat_acc_id,
									'profit_type' => $profit_type,
									'profit_value' => $profit_value,
									'profit_amount' => $profit_amount,
									'deposit_amount' => $amount,
									'total_profit' => ($amount+$profit_amount)
									
							);
					$this->Common_model->commonInsert('other_deposits',$datai);
					*/
				
				}	
				
				
				$result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
				inner join  user_meta as um on um.user_id = users.user_id
				and um.meta_key = 'mobile_no'
				WHERE users.user_id = $client_ID");	
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'deposit_message_sms'");			
					
					$message = "Dear [NAME], Tk[AMOUNT] has been saved to your [SAVING_TYPE] account. Current Savings Tk[SAVING_AMOUNT]";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}

					$result = $this->Common_model->commonQuery("select um.meta_value as client_acc_no,
					um2.meta_value as client_first_name,
					um3.meta_value as client_last_name
					from users 
					left join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'acc_no' 
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'first_name' 
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'last_name'
					where users.user_id = $client_ID
					");	
					
					$client_acc_no = '';
					$client_first_name = '';
					$client_last_name = '';
					if($result->num_rows() > 0)
					{
						$client_row = $result->row();
						$client_acc_no = $client_row->client_acc_no;
						$client_first_name = $client_row->client_first_name;
						$client_last_name = $client_row->client_last_name;
					}
					
					$total_current_saving = 0;
					$total_current_deposit = 0;
					$total_current_withdraw = 0;
					$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
					where ca.trans_type = 'conf_saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_deposit += $row->total_current_deposit;
					}
					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
					where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_withdraw += $row->total_current_withdraw;
					}
					
					$query = "select sum(ca.payment_out) as total_current_expense from capital_account ca
					where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_deposit += $row->total_current_expense;
					}
					
					/*
					$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
					where ca.trans_type = 'charges' and client_ID = $client_ID and ca.trans_from = 'conf_saving'";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_withdraw += $row->total_current_charges;
					}
					*/
					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
					
					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
					$string = str_replace("[AMOUNT]",$amount,$string);
					$string = str_replace("[SAVING_TYPE]",'Confidential Saving',$string);
					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
					
					$args = array('mobile' => $user_row->mobile_no , "message" => $string);
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
					
					if(isset($profit_type) && !empty($profit_type))
					{
						$query = $this->Common_model->commonQuery("select * from options where option_key = 'getting_interest_message_sms'");			
						
						$message = "Dear [NAME], You got interest amount Tk[AMOUNT] for saving [SAVING_TYPE]. Current Balance Tk[SAVING_AMOUNT]";
				
						if($query->num_rows() > 0)
						{
							foreach($query->result() as $row)
							{
								$message = $row->option_value;
							}
						}
						$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
						$string = str_replace("[AMOUNT]",$profit_amount,$string);
						$string = str_replace("[SAVING_TYPE]",'Confidential Saving',$string);
						$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
						
						$args = array('mobile' => $user_row->mobile_no , "message" => $string);
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
						
						$headers = 'From: webmaster@example.com' . "\r\n" .
						'Reply-To: webmaster@example.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						//mail('websolone@gmail.com','Notification',$string,$headers);
						
					}
				}
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Deposit Entry Added Successfully.
							</div>
							';
				redirect('/saving/other_dpst','location');	
			
			
			}
		
		}
		
		if(isset($_POST['edit_entry']))
		{
			extract($_POST);
			//print_r($_POST);  exit;
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				echo $cap_acc_id = $this->DecryptClientId($cap_acc_id);
				
				 $datai = array( 
							'payment_in' => $amount,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_mode,
					);
				$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
				
				//echo "here"; exit;
				$profit_value = '';
				$profit_amount = 0;
				if(isset($ProfitPer) && !empty($ProfitPer))
				{
					$profit_value = $ProfitPer;
					$profit_amount = round(($amount*$ProfitPer)/100);
				}
				else if(isset($ProfitFix) && !empty($ProfitFix))
				{
					$profit_amount = $profit_value = $ProfitFix;
				}
				$datai = array( 
							'profit_type' => $profit_type,
							'profit_value' => $profit_value,
							'profit_amount' => $profit_amount,
							'deposit_amount' => $amount,
							'total_profit' => ($amount+$profit_amount)
							
					);
				//$this->Common_model->commonUpdate('other_deposits',$datai,'cap_acc_id',$cap_acc_id);
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Deposit Entry Updated Successfully.
							</div>
							';
				redirect('/saving/other_dpst','location');	
			
			
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
				where users.user_type = 'user' and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		$data['employee_list'] = $this->Common_model->commonQuery("
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
				where users.user_type != 'user' and users.user_type != 'admin' and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		
		/*
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		*/
		
		/*$query = "select ca.*,od.*,um.meta_value as first_name,
					um2.meta_value as last_name  
					from capital_account ca
					inner join users on users.user_id = ca.client_ID
					inner join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'first_name'
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'last_name'
					inner join other_deposits od on od.cap_acc_id = ca.cap_acc_id
					where ca.trans_type = 'conf_saving'
					order by ca.cap_acc_id DESC";*/
					
		$query = "select ca.*,od.*,um.meta_value as first_name,
					um2.meta_value as last_name   , ca.cap_acc_id
					from capital_account ca
					inner join users on users.user_id = ca.client_ID
					inner join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'first_name'
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'last_name'
					left join other_deposits od on od.cap_acc_id = ca.cap_acc_id
					where ca.trans_type = 'conf_saving'
					order by ca.cap_acc_id DESC";
					
					
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/saving/other_dpst";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
	public function withdraw()
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
		$this->load->library('sms');
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('client_ID', 'Client/Id', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$client_ID = $this->DecryptClientId($client_ID);
				$result = $this->Common_model->commonQuery("select user_type from users 
				where user_id = $client_ID
				");	
				
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					if($user_row->user_type == 'user')
						$cap_type = 'borrower';
					else if($user_row->user_type != 'user' && $user_row->user_type != 'admin')
						$cap_type = 'employee';
				}
				
				 
				 $datai = array( 
						'payment_in' => 0,
						'payment_out' => $amount,
						'payment_log' => $remarks,
						'payment_date' => $pay_date,
						'trans_type' => 'withdraw',
						'trans_from' => $withdrawFrom,
						'payment_method' => $payment_mode,
						'client_ID' => $client_ID,
						'cap_by' => $cap_type,
						'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),
				);
				$cat_acc_id = $this->Common_model->commonInsert('capital_account',$datai);
				
				
				$result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
				inner join  user_meta as um on um.user_id = users.user_id
				and um.meta_key = 'mobile_no'
				WHERE users.user_id = $client_ID");	
				if($result->num_rows() > 0)
				{
					$user_row = $result->row();
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'withdraw_message_sms'");			
					
					$message = "Dear [NAME], Tk[AMOUNT] has been withdraw from your [SAVING_TYPE] account. 
					Current balance Tk[SAVING_AMOUNT]";
			
					if($query->num_rows() > 0)
					{
						foreach($query->result() as $row)
						{
							$message = $row->option_value;
						}
					}

					$result = $this->Common_model->commonQuery("select um.meta_value as client_acc_no,
					um2.meta_value as client_first_name,
					um3.meta_value as client_last_name
					from users 
					left join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'acc_no' 
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'first_name' 
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'last_name'
					where users.user_id = $client_ID
					");	
					
					$client_acc_no = '';
					$client_first_name = '';
					$client_last_name = '';
					if($result->num_rows() > 0)
					{
						$client_row = $result->row();
						$client_acc_no = $client_row->client_acc_no;
						$client_first_name = $client_row->client_first_name;
						$client_last_name = $client_row->client_last_name;
					}
					
					$total_current_saving = 0;
					$total_current_deposit = 0;
					$total_current_withdraw = 0;
					$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
					where ca.trans_type = '$withdrawFrom' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_deposit += $row->total_current_deposit;
					}
					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
					where ca.trans_type = 'withdraw' and ca.trans_from = '$withdrawFrom' and client_ID = $client_ID";
					$result = $this->Common_model->commonQuery($query);
					if($result->num_rows() > 0)
					{
						$row = $result->row();
						$total_current_withdraw += $row->total_current_withdraw;
					}
					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
					
					if($withdrawFrom == 'conf_saving')
					{
						$saving_type_text = 'Confidential Saving';
					}	
					else
					{
						$saving_type_text = $withdrawFrom;
					}
					
					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
					$string = str_replace("[AMOUNT]",$amount,$string);
					$string = str_replace("[SAVING_TYPE]",$saving_type_text,$string);
					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
					
					$args = array('mobile' => $user_row->mobile_no , "message" => $string);
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
								
								Withdraw Entry Added Successfully.
							</div>
							';
				redirect('/saving/withdraw','location');	
			
			
			}
		
		}
		
		if(isset($_POST['edit_entry']))
		{
			extract($_POST);
			$this->form_validation->set_error_delimiters('<div class="box-body"><div class="alert alert-danger alert-dismissable" style="margin-bottom:0px;">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				', "</div></div>");
			
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				$cap_acc_id = $this->DecryptClientId($cap_acc_id);
				
				 $datai = array( 
							'payment_out' => $amount,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_mode,
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
				
				
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Withdraw Entry Updated Successfully.
							</div>
							';
				redirect('/saving/withdraw','location');	
			
			
			}
			
		}
		
		
		$data['client_list'] = $this->Common_model->commonQuery("
				select users.*,
						um.meta_value as first_name,
						um2.meta_value as last_name,
						um3.meta_value as acc_no 
				from users
				inner  join capital_account ca on ca.client_ID = users.user_id
				and ca.trans_type = 'saving' and cap_by = 'borrower'
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'first_name'
				left join user_meta um2 on um2.user_id = users.user_id
				and um2.meta_key = 'last_name'
				left join user_meta um3 on um3.user_id = users.user_id
				and um3.meta_key = 'acc_no'
				where users.user_type = 'user' and users.user_status = 'Y'
				group by ca.client_ID
				order by users.user_id DESC
				");
		
		$data['employee_list'] = $this->Common_model->commonQuery("
				select users.*,
						um.meta_value as first_name,
						um2.meta_value as last_name,
						um3.meta_value as acc_no 
				from users
				inner  join capital_account ca on ca.client_ID = users.user_id
				and ca.trans_type = 'saving' and cap_by = 'employee'
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'first_name'
				left join user_meta um2 on um2.user_id = users.user_id
				and um2.meta_key = 'last_name'
				left join user_meta um3 on um3.user_id = users.user_id
				and um3.meta_key = 'acc_no'
				where users.user_type != 'user' and users.user_type != 'admin' and users.user_status = 'Y'
				group by ca.client_ID
				order by users.user_id DESC
				");
		
		
		/*
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		*/
		
		$query = "select ca.*,um.meta_value as first_name,
						um2.meta_value as last_name  
		from capital_account ca
		inner join users on users.user_id = ca.client_ID
		inner join user_meta um on um.user_id = users.user_id
		and um.meta_key = 'first_name'
		left join user_meta um2 on um2.user_id = users.user_id
		and um2.meta_key = 'last_name'
		where ca.trans_type = 'withdraw'
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/saving/withdraw";
		
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
			
		$tbl='capital_account';
		$pid='cap_acc_id';
		$url='/saving/'.$slug;	 	
		$fld='Record';
		
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
		/*
		if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
		
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

	public function borrowers_list()
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
		$this->load->library('sms');
		$this->load->library('global');
		$this->load->library('login');
		
		//$data = $this->uri_check();
		$data = $this->global->uri_check();
		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
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
				where users.user_type = 'user' and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		/*
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		*/
		
		$query = "select ca.*,um.meta_value as first_name,
						um2.meta_value as last_name  
		from capital_account ca
		inner join users on users.user_id = ca.client_ID
		inner join user_meta um on um.user_id = users.user_id
		and um.meta_key = 'first_name'
		left join user_meta um2 on um2.user_id = users.user_id
		and um2.meta_key = 'last_name'
		where ca.trans_type = 'saving' and cap_by = 'borrower' 
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/saving/borrowers_list";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	
}
