<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Transactions extends CI_Controller {
class Transactions extends MY_Controller {	
	
	public function index()
	{
		if(!$this->isLogin())
		{
			
			redirect('/logins','location');
		}
		
		redirect('/transactions/balance_sheet','location');

		
	}
	
	
	public function balance_sheet()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if( !$this->has_method_access())
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
		
		
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/transactions/balance_sheet";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function transfer()
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
			
			$this->form_validation->set_rules('transfer_mode', 'Transfer Mode', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			if ($this->form_validation->run() != FALSE)
			{
				extract($_POST,EXTR_OVERWRITE);
				
				$entry_time = time();
				$date_explode = explode('/',$pay_date);
				$pay_date = mktime(0,0,0,$date_explode[0],$date_explode[1],$date_explode[2]);
				
				if(isset($transfer_mode) && $transfer_mode == 'ctob')
				{	
					 $datai = array( 
							'payment_in' => 0,
							'payment_out' => $amount,
							'payment_log' => "$amount rs. amount transfer from cash to bank.",
							'payment_date' => $pay_date,
							'trans_type' => 'transfer',
							'payment_method' => 'cash',
							'trans_from' => $transfer_mode,
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
					
					 $datai = array( 
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_log' => "$amount rs. amount received as transfer from cash.",
							'payment_date' => $pay_date,
							'trans_type' => 'capital',
							'trans_from' => 'transfer',
							'payment_method' => 'cheque',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
				}
				else if(isset($transfer_mode) && $transfer_mode == 'btoc')
				{
					 $datai = array( 
							'payment_in' => 0,
							'payment_out' => $amount,
							'payment_log' => "$amount rs. amount transfer from bank to cash.",
							'payment_date' => $pay_date,
							'trans_type' => 'transfer',
							'payment_method' => 'cheque',
							'trans_from' => $transfer_mode,
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
					$datai = array( 
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_log' => "$amount rs. amount received as transfer from bank.",
							'payment_date' => $pay_date,
							'trans_type' => 'capital',
							'payment_method' => 'cash',
							'trans_from' => 'transfer',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
				}
				
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Transfer Entry Added Successfully.
							</div>
							';
				redirect('/transactions/transfer','location');	
			
			
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
		
		
		$query = "select *
		from capital_account ca
		where ca.trans_type = 'transfer'
		and (ca.trans_from = 'ctob' or ca.trans_from = 'btoc')
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/transactions/transfer";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function capital_account()
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
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_log' => $remarks,
							'payment_date' => $pay_date,
							'trans_type' => 'capital',
							'client_ID' => '',
							'payment_method' => $payment_method,
							'cap_by' => '',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
				
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Capital Account Entry Added Successfully.
							</div>
							';
				redirect('/transactions/capital_account','location');	
			
			
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
							'payment_method' => $payment_method,
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
					
				$_SESSION['msg'] = '
							<div class="alert alert-success alert-dismissable" style="margin-bottom:0px;">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								
								Capital Account Entry Updated Successfully.
							</div>
							';
				redirect('/transactions/capital_account','location');	
			
			
			}
			
		}
		
		$query = "select ca.*
		from capital_account ca
		where ca.trans_type = 'capital'
		order by ca.cap_acc_id DESC";
		$data['query'] = $this->Common_model->commonQuery($query);
		
		$query = "select sum(payment_in) as total_current_capital
		from capital_account ca
		where ca.trans_type = 'capital'";
		$cur_cap = $this->Common_model->commonQuery($query);
		if($cur_cap->num_rows() > 0)
		{
			$cur_cap_row = $cur_cap->row();
			$data['total_current_capital'] = $cur_cap_row->total_current_capital;
		}
		$data['theme']=$theme;
		
		$data['content'] = "$theme/transactions/capital_account";
		
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
		//print_r($strs);
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
	
	
}
