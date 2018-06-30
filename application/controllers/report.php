<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Report extends CI_Controller {
class Report extends MY_Controller {	
	public function index()
	{
		redirect('/report/statement','location');
	}
	
	public function statement()
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
		$data = $this->global->uri_check();
		date_default_timezone_set('Asia/Kolkata');
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		$data['theme']=$theme;
		
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
				
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/report/statement";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function expenses()
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
		
		$data['theme']=$theme;
		
		$has_default_state = false;
		
		if(isset($_POST['submit']))
		{
			$data['current_date'] = $_POST['loan_dates'];
			$loan_date = $_POST['loan_dates'];
			/*
			$s_date = trim($loan_date);
			$s_date_explode = explode('/',$s_date);
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			*/
			$date_explode = explode('-',$loan_date);
			$s_date = trim($date_explode[0]);
			$s_date_explode = explode('/',$s_date);
			$start_date = mktime(0,0,0,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			$e_date = trim($date_explode[1]);
			$e_date_explode = explode('/',$e_date);
			$end_date = mktime(23,59,59,$e_date_explode[0],$e_date_explode[1],$e_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " ca.user_id = ".$this->session->userdata('user_id')." and";
				$where2 = " loans.user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where2 = "";
			}
            
            $branch_id = $this->input->post('branch_id');
            if($branch_id)
            {
                $where1 .= " us.branch_id = ".$branch_id." AND ";
                $where2 .= " us.branch_id = ".$branch_id." AND ";
            }
			
			
			
		}
		else
		{
			$s_date_explode = explode('/',date('m/d/Y',time()));
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " ca.user_id = ".$this->session->userdata('user_id')." and";
				$where2 = " loans.user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where2 = "";
			}
			
		}
		
		$data['today_expenses_detail'] = $this->Common_model->commonQuery("
			
			select *
			from 
			capital_account ca 
            inner join users us on ca.client_ID = us.user_id
			where $where1 ca.payment_date between '".$start_date."' and '".$end_date."'
			and (ca.trans_type = 'expense' or ca.trans_type = 'withdraw')
			order by ca.cap_acc_id DESC
			");
		
		$data['today_loan_detail'] = $this->Common_model->commonQuery("select *
			from loans 
			inner join loan_types lt on lt.loan_type_id = loans.loan_type
            inner join users us on loans.client_ID = us.user_id
			where $where2 loans.loan_date between '".$start_date."' and '".$end_date."'
			order by loans.loan_id Desc
			");
		
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/report/expenses";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function income()
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
		
		$data['theme']=$theme;
		
		$has_default_state = false;
		
		if(isset($_POST['submit']))
		{
			$data['current_date'] = $_POST['loan_dates'];
			$loan_date = $_POST['loan_dates'];
			/*
			$s_date = trim($loan_date);
			$s_date_explode = explode('/',$s_date);
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			*/
			$date_explode = explode('-',$loan_date);
			$s_date = trim($date_explode[0]);
			$s_date_explode = explode('/',$s_date);
			$start_date = mktime(0,0,0,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			$e_date = trim($date_explode[1]);
			$e_date_explode = explode('/',$e_date);
			$end_date = mktime(23,59,59,$e_date_explode[0],$e_date_explode[1],$e_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " ca.user_id = ".$this->session->userdata('user_id')." and";
				$where = " lp.user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where = "";
			}
            
            $branch_id = $this->input->post('branch_id');
            if($branch_id)
            {
                $where1 .= " us.branch_id = ".$branch_id." AND ";
                $where .= " us.branch_id = ".$branch_id." AND ";
            }
		$clients_id = $this->input->post('clients_id');
            if($clients_id)
            {
                $where1 .= " cl.client_ID = ".$clients_id." AND ";
                $where .= " cl.client_ID = ".$clients_id." AND ";
            }	
            $account_manager = $this->input->post('account_manager');
            if($account_manager)
            {
                $where1 .= " cl.account_managent = ".$account_manager." AND ";
                $where .= " cl.account_managent = ".$account_manager." AND ";
            }	
		}
		else
		{
			$s_date_explode = explode('/',date('m/d/Y',time()));
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " ca.user_id = ".$this->session->userdata('user_id')." and";
				$where = " lp.user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where = "";
			}
			
		}
		
		$data['today_income_detail'] = $this->Common_model->commonQuery("
			
			select *, um.meta_value as client_name
			from 
			capital_account ca 
            inner join user_meta um on ca.client_ID = um.user_id
            inner join clients cl on cl.client_ID = um.user_id
            inner join users us on ca.client_ID = us.user_id
			where $where1 ca.payment_date between '".$start_date."' and '".$end_date."' AND um.meta_key = 'first_name'
			and (ca.trans_type = 'saving' or ca.trans_type = 'conf_saving' or ca.trans_type = 'Charges')
			order by ca.cap_acc_id DESC
			");
		
		$data['today_emi_detail'] = $this->Common_model->commonQuery("select *,sum(deposit_amount) as total_emi_received,lt.loan_title as loan_type_text, um.meta_value as client_name
			from 
			loan_payments lp 
			inner join loans on loans.loan_id = lp.loan_id
			inner join loan_types lt on lt.loan_type_id = loans.loan_type
                        inner join clients cl on lp.client_ID = cl.client_ID
            inner join user_meta um on lp.client_ID = um.user_id
            inner join users us on lp.client_ID = us.user_id
			where $where lp.payment_date between '".$start_date."' and '".$end_date."' AND um.meta_key = 'first_name'
			group by lp.loan_id
			");     
		
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");
			
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
        $data['manage'] = $this->Common_model->commonQuery("select * from users where user_type != 'admin' order by user_id DESC");	
        
		$data['content'] = "$theme/report/income";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function income_v_expense()
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
		
		$data['theme']=$theme;
		//$decId = $this->DecryptClientId($l_id);
		//where loans.loan_id = $decId
		
		$has_default_state = false;
		
		
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		
		$data['branch_list'] = $this->Common_model->commonQuery("
				select * from users where user_name != 'admin'
				order by user_name asc");
		
		$data['content'] = "$theme/report/income_v_expense";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function profile_report()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		/*if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
		}*/
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
		
		$data['theme']=$theme;
		
		$has_default_state = false;
		
		
		
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
				
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/report/profile_report";
		
		$this->load->view("$theme/header",$data);
		
	}
	public function file_import()
	{
	    $CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
		$this->load->library('login');
		$data = $this->global->uri_check();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->model('Common_model');
		$this->load->helper('text');
		$data['theme']=$theme;
		$data['myHelpers']=$this;
		
		$has_default_state = false;
		$data['content'] = "$theme/report/file_import";
		$insert_csv = array();
		$count=0;
		if(isset($_POST['submit']))
		{
			$fp = fopen($_FILES['attachments']['tmp_name'],'r') or die("can't open file");
			 while($csv_line = fgetcsv($fp,1024))
        {
            $count++;
			if($count == 1){
				continue;
			}
            //keep this if condition if you want to remove the first row
		     $j = count($csv_line);
            for($i = 0;  $i < $j; $i++)
            {
				//print $csv_line[0];
                $insert_csv = array();
                $insert_csv['id'] = $csv_line[0];//remove if you want to have primary key,
                $insert_csv['loanpayment'] = $csv_line[1];
                $insert_csv['saving'] = $csv_line[2];
				$insert_csv['other_deposit'] = $csv_line[3];
            }
            $i++;
			$loan_ops = $this->Common_model->commonQuery("select * from loans where client_id=". $insert_csv['id']);	
		if($loan_ops->num_rows() > 0)
		{
			$row = $loan_ops->row();
			$emiamount = $row->emi_amount;
			$loanid = $row->loan_id;
			$entry_time = time();
			$loan_payi = array( 
							'loan_id' => $loanid,
							'emi_amount' => $emiamount,
							'deposit_amount' => $insert_csv['loanpayment'],
							'payment_date' => $entry_time,
							'client_ID' => $insert_csv['id'],
							'payment_mode' => 'cash',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('loan_payments',$loan_payi);
					$capital_payi = array( 
							'payment_in' => $insert_csv['loanpayment'],
							'payment_method' => 'cash',
							'payment_date' => $entry_time,
							'trans_type' => 'emi',
							'client_ID' => $insert_csv['id'],
							'payment_method' => 'cash',
							'entry_date' => $entry_time,
							'cap_by' => 'borrower',
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$capital_payi);
					$capital_saving_payi = array( 
							'payment_in' => $insert_csv['saving'],
							'payment_method' => 'cash',
							'payment_date' => $entry_time,
							'trans_type' => 'saving',
							'client_ID' => $insert_csv['id'],
							'payment_method' => 'cash',
							'entry_date' => $entry_time,
							'cap_by' => 'borrower',
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$capital_saving_payi);
					$this->Common_model->commonInsert('capital_account',$capital_payi);
					$capital_saving_payi1 = array( 
							'payment_in' => $insert_csv['other_deposit'],
							'payment_method' => 'cash',
							'payment_date' => $entry_time,
							'trans_type' => 'conf_saving',
							'client_ID' => $insert_csv['id'],
							'payment_method' => 'cash',
							'entry_date' => $entry_time,
							'cap_by' => 'borrower',
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$capital_saving_payi1);
					
		}
		/*	$data1 = array(
                'id' => $insert_csv['id'] ,
                'empName' => $insert_csv['empName'],
                'empAddress' => $insert_csv['empAddress'],
				);
				print $data1; */
           # $data['crane_features']=$this->db->insert('tableName', $data);
		}
		
			//$this->load->view("$theme/header",$data);
		}
		
		$this->load->view("$theme/header",$data);
	}
	/*
	public function profit_report()
	{
		if(!$this->isLogin())
		{
			redirect('/logins','location');
		}
		if($this->session->userdata('user_id') != 1)
		{	
			redirect('/','location');
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
		
		$data['theme']=$theme;
		
		$has_default_state = false;
		
		
		
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
				where users.user_type = 'user'
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
				where users.user_type != 'user' and users.user_type != 'admin'
				order by users.user_id DESC
				");
				
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/report/profit_report";
		
		$this->load->view("$theme/header",$data);
		
	}
	*/
	
	public function profit_report()
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
		
		$data['theme']=$theme;
		
		$has_default_state = false;
		
		if(isset($_POST['submit']))
		{
			$data['current_date'] = $_POST['loan_dates'];
			$loan_date = $_POST['loan_dates'];
			
			$date_explode = explode('-',$loan_date);
			$s_date = trim($date_explode[0]);
			$s_date_explode = explode('/',$s_date);
			$start_date = mktime(0,0,0,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			$e_date = trim($date_explode[1]);
			$e_date_explode = explode('/',$e_date);
			$end_date = mktime(23,59,59,$e_date_explode[0],$e_date_explode[1],$e_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " user_id = ".$this->session->userdata('user_id')." and";
				$where = " user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where = "";
			}
			
			
		}
		else
		{
			$s_date_explode = explode('/',date('m/d/Y',time()));
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			if($this->session->userdata('user_id') != 1)
			{
				$where1 = " user_id = ".$this->session->userdata('user_id')." and";
				$where = " user_id = ".$this->session->userdata('user_id')." and";
			}
			else
			{
				$where1 = "";
				$where = "";
			}
			
		}
		
		
		$data['today_emi_detail'] = $this->Common_model->commonQuery("
		select *,lt.loan_title as loan_type_text
			from 
			loan_payments lp 
			inner join loans on loans.loan_id = lp.loan_id
			inner join loan_types lt on lt.loan_type_id = loans.loan_type
			where $where1 lp.payment_date between '".$start_date."' and '".$end_date."'
			
			");
		/*group by lp.loan_id*/
		$option_meta = $this->Common_model->commonQuery("select * from options where option_key = 'company_title'");	
		if($option_meta->num_rows() > 0)
		{
			$row = $option_meta->row();
			$data['compnay_title'] = $row->option_value;
		}
		$data['content'] = "$theme/report/profit_report";
		
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
		$url='/ploan/manage/';	 	
		$fld='Personal Loan';
		
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
