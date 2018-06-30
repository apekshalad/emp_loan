<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Main extends CI_Controller {
class Main extends MY_Controller {
	public function index()
	{
		
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		
		//redirect('/client/manage','location');
		
			//date_default_timezone_set('Asia/Kolkata');
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
			date_default_timezone_set('Asia/Kolkata');
			
			if($this->session->userdata('user_type') == 'user' )
				$where = " where loans.user_id = ".$this->session->userdata('user_id')." ";
			else
				$where = "";
			
			$data['running_loan_detail'] = $this->Common_model->commonQuery("
			select * from loans 
			$where
			order by loans.loan_id  desc");
			
			
			$s_date_explode = explode('/',date('m/d/Y',time()));
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			if($this->session->userdata('user_type') == 'user' )
			{
				$where = " and loans.user_id = ".$this->session->userdata('user_id')." ";
				$where1 = " and loans.client_ID = ".$this->session->userdata('user_id')." ";
			}
			else
			{
				$where = "";
				$where1 = "";
			}
			
			$data['today_loan_detail'] = $this->Common_model->commonQuery("
			select loans.*,um.meta_value as customer_acc_no from loans
			inner join users u on u.user_id = loans.client_ID
			inner join user_meta um on um.user_id = u.user_id
			and um.meta_key = 'acc_no'
			where loans.loan_date between '".$start_date."' and '".$end_date."' 
			$where1
			order by loans.loan_id desc limit 0,10");
			
			
			$data['today_cash_received_detail'] = $this->Common_model->commonQuery("
			select *,um.meta_value as customer_acc_no from loan_payments 
			inner join loans on loans.loan_id = loan_payments.loan_id
			inner join users u on u.user_id = loans.client_ID
			inner join user_meta um on um.user_id = u.user_id
			and um.meta_key = 'acc_no'
			where loan_payments.payment_date between '".$start_date."' and '".$end_date."'
			order by loan_payments.payment_id desc ");
			
			$data['total_client_list'] = $this->Common_model->commonQuery("
				select * from users where user_type = 'user' ");
			
			$data['total_branch_list'] = $this->Common_model->commonQuery("
				select * from users where user_type != 'user' and user_type != 'admin'");
			
			$data['running_loan_amount_detail'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(net_amount),0) as total_net_amount FROM `loans` where net_amount != total_amount_deposite");
			
			$data['total_loan_amount_detail'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(net_amount),0) as total_net_amount FROM `loans`");
			
			if($this->session->userdata('user_type') == 'user' )
			{
				$where = " and client_ID = ".$this->session->userdata('user_id');
			}
			else
			{
				$where = "";
			}
			
			
			$data['total_outstanding_amount'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(net_amount),0) as total_net_amount ,
					IFNULL(sum(total_amount_deposite),0) as total_deposit_amount
				FROM `loans` where net_amount != total_amount_deposite $where");
			
			$data['total_general_saving_amount'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(payment_in),0) as total_general_saving,
				(select IFNULL(sum(ca.payment_out),0) from capital_account ca
				where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and cap_by = 'borrower' ) as total_general_withdraw
				FROM `capital_account` 
				where trans_type = 'saving' and cap_by = 'borrower'");
			
			$data['total_employee_saving_amount'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(payment_in),0) as total_employee_saving,
				(select IFNULL(sum(ca.payment_out),0) from capital_account ca
				where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and cap_by = 'employee' ) as total_employee_withdraw
				FROM `capital_account` 
				where trans_type = 'saving' and cap_by = 'employee' ");
			
			
			$data['total_conf_saving_amount'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(payment_in),0) as total_conf_saving,
				(select IFNULL(sum(ca.payment_out),0) from capital_account ca
				where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' ) as total_conf_saving_withdraw
				FROM `capital_account` 
				where trans_type = 'conf_saving'  ");
			
			/*
			$data['total_saving_withdraw_total'] = $this->Common_model->commonQuery("
				
				(SELECT IFNULL(sum(payment_out),0)
				FROM `capital_account` 
				where trans_type = 'withdraw') as total_withdraw");
			*/
			
			$data['total_withdraw_amount'] = $this->Common_model->commonQuery("
				SELECT IFNULL(sum(payment_out),0) as total_withdraw
				FROM `capital_account` 
				where trans_type = 'withdraw' ");
			
			$data['theme']=$theme;
			$data['content'] = "$theme/home_page";
			$this->load->view("$theme/header",$data);
			//$data['sidebar'] = 'sidebar-left';		
	}
	
	public function error_404()
	{
		date_default_timezone_set('Asia/Kolkata');
			$CI =& get_instance();
			$theme = $CI->config->item('theme') ;
			$this->load->library('global');
			$this->load->library('login');
			//$data = $this->uri_check();
			$data = $this->global->uri_check();
			$data['myHelpers']=$this;
			$this->load->model('Common_model');
			$this->load->helper('text');
			$data['theme']=$theme;
			$data['content'] = "$theme/error_404_page";
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

		return $data;
	}
	public function user_name_check($val)
	{
		$this->load->model('Common_model');
		$options=array('where'=>array('user_name'=>$val));
		$user_exsist=$this->Common_model->commonSelect('users',$options);
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
		if(
		isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == TRUE 
		&& isset($_SESSION['site_url']) 
		&& $_SESSION['site_url'] == $site_url 
		){
			
			return true;		

		}		
		else
		{
			$_SESSION['msg'] = '<p class="error_msg">You have to login first to proceed.</p>';
			return false;
		}
	}

	

	

}

