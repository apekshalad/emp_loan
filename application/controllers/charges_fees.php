<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Charges_fees extends CI_Controller { 
class Charges_fees extends MY_Controller {	
	
	public function index()
	{
		if(!$this->isLogin())
		{
			
			redirect('/logins','location');
		}
		
		redirect('/charges_fees/add_expenses','location');

		
	}
	
	
	public function add_expenses()
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
		date_default_timezone_set('Asia/Kolkata');
		/*$this->load->library('breadcrumbs');*/
		
		//print_r($_POST);
		
		if(isset($_POST['submit']))
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
				
				 $datai = array( 
						'payment_in' => 0,
						'payment_out' => $amount,
						'payment_log' => $remarks,
						'payment_date' => $pay_date,
						'payment_method' => $payment_method,
						'expense_type' => $_POST['expense_type'],
						'account_manager' =>$_POST['account_manager'],						
						'trans_type' => 'expense',
						'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),
				);
				$this->Common_model->commonInsert('capital_account',$datai);
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Expense Entry Added Successfully.
							</div>
							';
				redirect('/charges_fees/add_expenses','location');	
			
			
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
				
				$cap_acc_id = $this->DecryptClientId($exp_id);
				
				 $datai = array( 
							'payment_out' => $amount,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'payment_method' => $payment_method,
							'expense_type' => $expense_type
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
				
				
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Expense Entry Updated Successfully.
							</div>
							';
				redirect('/charges_fees/add_expenses','location');	
			
			
			}
			
		}
		
		
		
		
		
		if($this->session->userdata('user_id') != 1)
			$where = " and ca.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		
		
		$query = "select *
		from capital_account ca
		where ca.trans_type = 'expense' $where
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		$data['exp_cat'] = $this->Common_model->commonQuery("
                    select * from expense_category order by id DESC
				");
		$data['theme']=$theme;
		
		$data['content'] = "$theme/charges_fees/add_expenses";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function customer_charges()
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
			
			$this->form_validation->set_rules('client_ID', 'Client/Employee', 'trim|required');
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
						'payment_in' => $amount,
						'payment_out' => 0,
						'payment_log' => $remarks,
						'payment_date' => $pay_date,
						'payment_method' => $payment_mode,
						'trans_type' => 'charges',
						'trans_from' => $account_type,
						'client_ID' => $client_ID,
						'cap_by' => $cap_type,
						'entry_date' => $entry_time,
						'user_id' => $this->session->userdata('user_id'),
				);
				$this->Common_model->commonInsert('capital_account',$datai);
				
				$remark = "$amount Rs. Charges charged from $cap_type account.";
				$datai = array( 
						'payment_in' => 0,
						'payment_out' => $amount,
						'payment_log' => $remark,
						'payment_date' => $pay_date,
						'payment_method' => $payment_mode,
						'trans_type' => 'withdraw',
						'trans_from' => 'charges',
						'client_ID' => $client_ID,
						'cap_by' => $cap_type,
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
					
					$query = $this->Common_model->commonQuery("select * from options where option_key = 'charge_message_sms'");			
					
					$message = "Dear [NAME], Tk[AMOUNT] has been charged from your account [ACCOUNT_ID], saving type [SAVING_TYPE], for [REASON]";
			
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
					
					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
					$string = str_replace("[AMOUNT]",$amount,$string);
					$string = str_replace("[ACCOUNT_ID]",$client_acc_no,$string);
					$string = str_replace("[SAVING_TYPE]",ucfirst($account_type),$string);
					$string = str_replace("[REASON]",$remarks,$string);
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
					
					/*$headers = 'From: webmaster@example.com' . "\r\n" .
					'Reply-To: webmaster@example.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					mail('azizchouhan@gmail.com','Notification',$string,$headers);*/
					
				}
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Customer Charge Added Successfully.
							</div>
							';
				redirect('/charges_fees/customer_charges','location');	
			
			
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
							'payment_date' => $pay_date
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
				
				
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Customer Charge Updated Successfully.
							</div>
							';
				redirect('/charges_fees/customer_charges','location');	
			
			
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
		
		$query = "select ca.*,um.meta_value as first_name,
					um2.meta_value as last_name  
					from capital_account ca
					inner join users on users.user_id = ca.client_ID
					inner join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'first_name'
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'last_name'
					where ca.trans_type = 'charges'
					order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		$data['content'] = "$theme/charges_fees/customer_charges";
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
		$url='/charges_fees/'.$slug;
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
	
	
}
