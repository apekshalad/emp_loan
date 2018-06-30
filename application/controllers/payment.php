<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Payment extends CI_Controller {
class Payment extends MY_Controller {	
	
	public function index()
	{
		if(!$this->isLogin())
		{
			
			redirect('/logins','location');
		}
		
		redirect('/payment/missed','location');	
	
	}
	
	public function missed()
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
		
		if($this->session->userdata('user_id') != 1)
			$where = " and loans.user_id = ".$this->session->userdata('user_id')." ";
		else
			$where = "";
		
		
		
		$data['query'] = $this->Common_model->commonQuery("
				select * ,(select due_date from loan_payments 
				where loan_payments.loan_id = loans.loan_id and deposit_amount > 0 
				$where
				order by payment_id DESC 
				limit 1) as next_due_date from loans
				inner join users u on u.user_id = loans.client_ID
				inner join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				inner join clients cst on cst.client_ID=um.meta_value
				");	
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/payment/missed";
		
		$this->load->view("$theme/header",$data);
		
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
						um2.meta_value as last_name ,
						um3.meta_value as acc_no 	
				from users
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'first_name'
				left join user_meta um2 on um2.user_id = users.user_id
				and um2.meta_key = 'last_name'
				left join user_meta um3 on um3.user_id = users.user_id
				and um3.meta_key = 'acc_no'
				where users.user_type = 'user'  and users.user_status = 'Y'
				order by users.user_id DESC
				");
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/payment/borrowers";
		
		$this->load->view("$theme/header",$data);
		
	}
	
	public function my_payment()
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
		
		if($this->session->userdata('user_id') != 1 && $this->session->userdata('user_id') != '' )
		{
			$where = "and users.user_id = ".$this->session->userdata('user_id');
		}
		else
		{
			$where = '';
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
				where users.user_type = 'user' $where
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
				where users.user_type != 'user' and users.user_type != 'admin' $where
				order by users.user_id DESC
				");
		
		
		$s_date_explode = explode('/',date('m/d/Y',time()));
		$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
		$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
		
		$decId = $this->session->userdata('user_id');
		$emi_row = '';
		$query = "select * from loan_payments lp
		inner join loans on loans.loan_id = lp.loan_id
		where  loans.client_ID = $decId and
		lp.payment_date between '".$start_date."' and '".$end_date."'";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=0;
			foreach($result->result() as $row)
			{
				$n++;
				$emi_row .= '<tr>
					<td>'.$n.'</td>
					<td>'.date('d/m/Y',$row->loan_date).'</td>
					<td>'.$row->principal_amount.'</td>
					<td>'.date('d/m/Y',$row->payment_date).'</td>
					<td>'.$row->deposit_amount.'</td>
					<td>'.ucfirst($row->payment_mode).'</td>
					<td>'.$row->payment_log.'</td>
				</tr>';
			}
		}
		else
		{
			$emi_row = '<tr><td colspan="7">There is no record found.</td></tr>';
		}
		$data['emi_row'] = $emi_row;
		
		$loan_row = '';
		$query = "select * from loans 
		inner join loan_types on loans.loan_type = loan_types.loan_type_id
		where loans.client_ID = $decId and
		loans.loan_date between '".$start_date."' and '".$end_date."'";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $row)
			{
				$loan_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$row->loan_date).'</td>
					<td>'.$row->principal_amount.'</td>
					<td>'.ucfirst($row->payment_mode).'</td>
					<td>'.ucfirst($row->loan_title).'</td>
					<td>'.$row->time_periods.'</td>
				</tr>';
			}
		}
		else
		{
			$loan_row = '<tr><td colspan="6">There is no record found.</td></tr>';
		}
		$data['loan_row'] = $loan_row;
		
		$saving_row = '';
		$query = "select * from capital_account ca
		where ca.client_ID = $decId and
		ca.payment_date between '".$start_date."' and '".$end_date."' and ca.trans_type = 'saving'";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $row)
			{
				$saving_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$row->payment_date).'</td>
					<td>'.$row->payment_in.'</td>
					<td>'.ucfirst($row->payment_method).'</td>
					<td>'.$row->payment_log.'</td>
				</tr>';
			}
		}
		else
		{
			$saving_row = '<tr><td colspan="5">There is no record found.</td></tr>';
		}
		$data['saving_row'] = $saving_row;
		
		$conf_saving_row = '';
		$query = "
		select ca.*,od.*
		from capital_account ca
		inner join other_deposits od on od.cap_acc_id = ca.cap_acc_id
		where ca.trans_type = 'conf_saving'and ca.client_ID = $decId and
		ca.payment_date between '".$start_date."' and '".$end_date."'
		order by ca.cap_acc_id DESC
		";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$n=1;
			foreach($result->result() as $row)
			{
				if($row->profit_type == 'per')
				{
					$profit_type = 'Percentage ('.$row->profit_value.'%)';
				}
				else if($row->profit_type == 'fix')
				{
					$profit_type = 'Fixed';
				}
				else
				{
					$profit_type = '';
				}
				$conf_saving_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$row->payment_date).'</td>
					<td>'.$row->payment_in.'</td>
					<td>'.$profit_type.'</td>
					<td>'.ucfirst($row->payment_method).'</td>
				</tr>';
			}
		}
		else
		{
			$conf_saving_row = '<tr><td colspan="5">There is no record found.</td></tr>';
		}
		$data['conf_saving_row'] = $conf_saving_row;
		
		$withdraw_row = '';
		$query = "select * from capital_account ca
		where ca.client_ID = $decId and
		ca.payment_date between '".$start_date."' and '".$end_date."' and ca.trans_type = 'withdraw'";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $row)
			{
				$withdraw_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$row->payment_date).'</td>
					<td>'.$row->payment_out.'</td>
					<td>'.ucfirst($row->payment_method).'</td>
					<td>'.$row->payment_log.'</td>
				</tr>';
			}
		}
		else
		{
			$withdraw_row = '<tr><td colspan="5">There is no record found.</td></tr>';
		}
		$data['withdraw_row'] = $withdraw_row;
		
		$data['theme']=$theme;
		
		$data['content'] = "$theme/payment/my_payment";
		
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
    
    public function file_import()
    {   
        $CI =& get_instance();
		$theme = $CI->config->item('theme') ;
		
		$this->load->library('global');
        $this->load->library('sms');	
		$this->load->library('login');
		$data = $this->global->uri_check();
		date_default_timezone_set('Asia/Kolkata');
		$this->load->model('Common_model');
		$this->load->helper('text');
		$data['theme']=$theme;
		$data['myHelpers']=$this;
		
		$has_default_state = false;
		$data['content'] = "$theme/payment/file_import";
		$insert_csv = array();
		$count=0;
        
        
        
        if(isset($_POST['submit']))
		{
            $pay_date1 =$this->input->post('pay_date', true);
			$s_date_explode = explode('/',$pay_date1);
		    $pay_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
            
            $file            = $_FILES['attachments']['tmp_name'];//'./uploads/02-feb.xlsx';
            $this->load->library('excel');
            $objPHPExcel     = PHPExcel_IOFactory::load($file);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            
            $id_exits = array();
            
            foreach ($cell_collection as $cell) 
            {
                $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();
                //header will/should be in row 1 only. of course this can be modified to suit your need.
                
                if ($row == 1) {
                    $header[$row][$column] = $data_value;
                } else {
                    $arr_data[$row][$column] = $data_value;
                }
            }
            
            $withdraw_row = '';
            $n=1;
            foreach($arr_data as $arr)
            {
                $insert_csv                 = array();
                $insert_csv['id']           = $arr['C'];//remove if you want to have primary key,
                $insert_csv['loanpayment']  = $arr['D'];
                $insert_csv['saving']       = $arr['E'];
				$insert_csv['other_deposit']= $arr['F'];
                
                $csv_client_name            = $arr['B'];
                
                $sql = "SELECT `user_id` FROM `user_meta` WHERE `meta_key` = 'acc_no' AND `meta_value`='".$insert_csv['id']."'";
                $client_id = $this->db->query($sql)->row()->user_id;
                
                if($client_id)
                {
                    /****************************** Saving and Other Deposit *************************/
                    $result = $this->Common_model->commonQuery("select users.user_type from users where users.user_id  =  $client_id");	
            		$cap_type = '';
            		if($result->num_rows() > 0)
            		{
            			$user_row = $result->row();
            			if($user_row->user_type == 'user')
            				$cap_type = 'borrower';
            			else if($user_row->user_type != 'user' && $user_row->user_type != 'admin')
            				$cap_type = 'employee';
            		}
                    
                    $entry_date = time();
            		$entry_time = time();
                    
                    if($insert_csv['other_deposit'] > 0)
                    {
                        $datai = array( 'payment_in' => $insert_csv['other_deposit'], 'payment_out' => 0, 'payment_log' => "Deposit of ".$insert_csv['other_deposit'],
    						'payment_date' => $entry_date, 'trans_type' => 'conf_saving',
    						'client_ID' => $client_id,	 'payment_method' => 'cash',
    						'cap_by' => $cap_type,		 'entry_date' => $entry_time,
    						'user_id' => $this->session->userdata('user_id'),);
    						
    					$cat_acc_id = $this->Common_model->commonInsert('capital_account',$datai);
                        
                        $result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
        				inner join  user_meta as um on um.user_id = users.user_id
        				and um.meta_key = 'mobile_no'
        				WHERE users.user_id = $client_id");	
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
        					where users.user_id = $client_id
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
        					where ca.trans_type = 'conf_saving' and client_ID = $client_id";
        					$result = $this->Common_model->commonQuery($query);
        					if($result->num_rows() > 0)
        					{
        						$row = $result->row();
        						$total_current_deposit += $row->total_current_deposit;
        					}
        					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
        					where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $client_id";
        					$result = $this->Common_model->commonQuery($query);
        					if($result->num_rows() > 0)
        					{
        						$row = $result->row();
        						$total_current_withdraw += $row->total_current_withdraw;
        					}
        					
        					$query = "select sum(ca.payment_out) as total_current_expense from capital_account ca
        					where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $client_id";
        					$result = $this->Common_model->commonQuery($query);
        					if($result->num_rows() > 0)
        					{
        						$row = $result->row();
        						$total_current_deposit += $row->total_current_expense;
        					}
        					
        					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
        					
        					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
        					$string = str_replace("[AMOUNT]",$insert_csv['other_deposit'],$string);
        					$string = str_replace("[SAVING_TYPE]",'Confidential Saving',$string);
        					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
        					
        					$args = array('mobile' => $user_row->mobile_no , "message" => $string);
        					$sms = $this->sms->send_sms($args);
                            
                            $notify_read = 'N';
                			$notify_type = 'Notification';
                			$description = $string;
                			$notify_other = array( 
                							'description' => $description,
                							'client_ID' => $client_id,
                							'notify_read' => $notify_read,
                							'notify_type' => $notify_type,);
                			$this->Common_model->commonInsert('notification',$notify_other);
                        }
				    }
                    
                    if($insert_csv['saving']>0)
                    {
                        $datai = array( 
    						'payment_in' => $insert_csv['saving'],
    						'payment_out' => 0,
    						'payment_log' => '',
    						'payment_date' => $entry_date,
    						'trans_type' => 'saving',
    						'client_ID' => $client_id,
    						'payment_method' => 'cash',
    						'cap_by' => $cap_type,
    						'entry_date' => $entry_time,
    						'user_id' => $this->session->userdata('user_id'),
        				);
        				$this->Common_model->commonInsert('capital_account',$datai);
        				
        				$result = $this->Common_model->commonQuery("SELECT um.meta_value as mobile_no FROM `users` 
        				inner join  user_meta as um on um.user_id = users.user_id
        				and um.meta_key = 'mobile_no'
        				WHERE users.user_id = $client_id");	
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
        					where users.user_id = $client_id
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
        					where ca.trans_type = 'saving' and client_ID = $client_id";
        					$result = $this->Common_model->commonQuery($query);
        					if($result->num_rows() > 0)
        					{
        						$row = $result->row();
        						$total_current_deposit += $row->total_current_deposit;
        					}
        					$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
        					where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $client_id";
        					$result = $this->Common_model->commonQuery($query);
        					if($result->num_rows() > 0)
        					{
        						$row = $result->row();
        						$total_current_withdraw += $row->total_current_withdraw;
        					}
        					$total_current_saving = ($total_current_deposit - $total_current_withdraw);
        					
        					$string = str_replace("[NAME]",$client_first_name.' '.$client_last_name,$message);
        					$string = str_replace("[AMOUNT]",$insert_csv['saving'],$string);
        					$string = str_replace("[SAVING_TYPE]",'General Saving',$string);
        					$string = str_replace("[SAVING_AMOUNT]",$total_current_saving,$string);
        					
        					$args = array('mobile' => $user_row->mobile_no, "message" => $string);
        					$sms = $this->sms->send_sms($args);
                            
                            $notify_read = 'N';
                			$notify_type = 'Notification';
                			$description = $string;
                			$notify_saving = array( 
                							'description' => $description,
                							'client_ID'   => $client_id,
                							'notify_read' => $notify_read,
                							'notify_type' => $notify_type,);
                			$this->Common_model->commonInsert('notification',$notify_saving);
                        }
                    }
                    
                    /*********************************** END *****************************************/
                    
                    
                    $loan_ops = $this->Common_model->commonQuery("select * from loans where client_id=". $client_id);	
                    if($loan_ops->num_rows() > 0)
            		{
                        $row = $loan_ops->row();
                        
                        $emiamount  = $row->emi_amount;
            			$loanid  = $loan_id  = $row->loan_id;
                        
                        $data1 = $this->Common_model->commonQuery("
                				select *, loans.client_ID as customer_id from loans
                				inner join users u on u.user_id = loans.client_ID
                				inner join user_meta um on um.user_id = u.user_id
                				and um.meta_key = 'client_ID'
                				inner join clients cst on cst.client_ID=um.meta_value
                				where loans.loan_id = $loanid");	
                		$loan_detail = $data1->row();
                        
                        $data2 = $this->Common_model->commonQuery("
                				select * from loan_payments 
                				where loan_payments.loan_id = $loan_id and deposit_amount > 0
                				order by payment_id DESC 
                				limit 1");
                		
                		 
                		$total_loan_payment_count = $this->Common_model->commonQuery("
                				select * from loan_payments 
                				where loan_payments.loan_id = $loan_id and deposit_amount > 0");
                		 
                	 
                		if($data2->num_rows() > 0)
                		{
            				$loan_payment_last_row = $data2->row();
            				$due_date = $p_date = $loan_payment_last_row->due_date;
            				
            				$advance_emi = 1;
            			}
            			else
            			{
            				$due_date = $p_date = $loan_detail->loan_date;
            				$advance_emi = 0;
            			}
                		
                        $e_date = time();
                        $datediff = ($e_date - $p_date);
                        $due_months = floor($datediff/(60*60*24*30));
                        if($due_months == 0 || $due_months < 0)
                            $due_months = 1;
                		 
                		$next_month = $due_date;
                		
                		if($next_month > time() && $data2->num_rows() > 0)
                			$due_months = 1;
                		else if($next_month > time() && $data2->num_rows() == 0)
                			$due_months = 0;
                		
                		$next_due_date = date("d/m/Y", strtotime('+'.$due_months.' month', $next_month));
                		
                		$next_month = $due_date;
                		$next_due_date_after_one = $next_month;
                		
                		$p_date = $next_due_date_after_one;
                		$e_date = time();
                		$datediff = ($e_date - $p_date);
                		 
                		 $due_days = floor($datediff/(60*60*24));
                		 $due_days = $due_days - 3;
                		 if($due_days < 0)
                			 $due_days = 0;
                		 else if($due_days != 0 || $due_days > 0)
                			 $due_days = $due_days;
                		 
                		 for($i=1;$i<=$due_months;$i++)
                		 {
                			$next_due_date_after_two = strtotime('+'.$i.' month', $next_due_date_after_one);
                			
                			$p_date = $next_due_date_after_two;
                			 $e_date = time();
                			 
                			 $datediff = ($e_date - $p_date);
                			 
                			 $nxtdue_days = floor($datediff/(60*60*24));
                			 $nxtdue_days = $nxtdue_days-3;
                			 if($nxtdue_days < 0)
                				 $due_days += 0;
                			 else if($nxtdue_days != 0 || $nxtdue_days > 0)
                				 $due_days += $nxtdue_days;
                		 }
                		 
                		if($loan_detail->time_periods == $total_loan_payment_count->num_rows())
                        {
                            $loan_detail->emi_amount = $due_months = 0;
                        }
                        
                        $customer_acc_no   = $loan_detail->client_acc_no;
    					$loan_type         = $loan_detail->loan_type;
    					$loan_date         = $loan_detail->loan_date;
    					$ClientMobile      = $loan_detail->client_mobile;
    					$ClientName        = $loan_detail->client_name;
    					$NetAmount         = $loan_detail->net_amount;
    					$TimePeriods       = $loan_detail->time_periods;
                        $emi_amount        = $loan_detail->emi_amount;
    					$emi_to_deposite   = $loan_detail->emi_amount * $due_months;
                        
                        $entry_date = time();
            			$entry_time = time();
                        $deposit_amount = $insert_csv['loanpayment'];
                        $customer_id  = $client_id;
                        $payment_date = $pay_date;
                        $payment_mode = 'cash';
                        $customer_acc_no = $insert_csv['id'];
                        
                        $data = $this->Common_model->commonQuery("
                				select * from loan_payments 
                				where loan_payments.loan_id = $loan_id 
                				order by payment_id DESC 
                				limit 1");	
                		if($data->num_rows()>0)	
                		{
                			$loan_payment_last_row = $data->row();
                			$next_month = $loan_payment_last_row->due_date;
                			$next_month = strtotime('+1 month', $next_month);
                		}
                		else
                		{
                			$next_month = $loan_date;
                		}
                		
                		$result = $this->Common_model->commonQuery("select users.user_type from users where users.user_id  =  $client_id");	
                		$cap_type = '';
                		if($result->num_rows() > 0)
                		{
                			$user_row = $result->row();
                			if($user_row->user_type == 'user')
                				$cap_type = 'borrower';
                			else if($user_row->user_type != 'user' && $user_row->user_type != 'admin')
                				$cap_type = 'employee';
                		}
                        
                		if(isset($deposit_amount) && !empty($deposit_amount))
                		{
                            if(round($deposit_amount) % $emi_amount == 0)
					        {
                    			$total_emi_depo = (round($deposit_amount)/$emi_amount);
                    			$total_amount_deposite = 0;
                    			for($i=1;$i<=$total_emi_depo;$i++)
                    			{
                    				$next_due_date = date("d/m/Y", strtotime('+'.$i.' month', $next_month));
                    				$date_explode = explode('/',$next_due_date);
                    				$next_due_date = mktime(0,0,0,$date_explode[1],$date_explode[0],$date_explode[2]);
                    				
                    				if($total_emi_depo == $i)
                    				{	
                    					$datai = array( 
                    					'client_ID' => $customer_id,	
                    					'loan_id' => $loan_id,	
                    					'emi_amount' => $emi_amount,	
                    					'deposit_amount' => $emi_amount,
                    					'due_date' => $next_due_date,
                    					'payment_date' => $payment_date,
                    					'payment_mode' => $payment_mode,
                    					'entry_date' => $entry_date,
                    					'user_id' => $this->session->userdata('user_id'),
                    					'payment_log' => "EMI received from account no. $customer_acc_no",
                    					);
                    					
                    					$datai2 = array( 
                    							'payment_in' => $emi_amount,
                    							'payment_out' => 0,
                    							'payment_log' => "EMI received from account no. $customer_acc_no",
                    							'payment_date' => $payment_date,
                    							'payment_method' => $payment_mode,
                    							'trans_type' => 'emi',
                    							'client_ID' => $customer_id,
                    							'cap_by' => $cap_type,
                    							'entry_date' => $entry_date,
                    							'user_id' => $this->session->userdata('user_id'),
                    					);
                    					$this->Common_model->commonInsert('capital_account',$datai2);
                    					
                    					
                    				}
                    				else
                    				{
                    					$datai = array( 
                    					'client_ID' => $customer_id,	
                    					'loan_id' => $loan_id,	
                    					'emi_amount' => $emi_amount,	
                    					'deposit_amount' => $emi_amount,
                    					'due_date' => $next_due_date,
                    					'payment_date' => $payment_date,
                    					'payment_mode' => $payment_mode,
                    					'entry_date' => $entry_date,
                    					'user_id' => $this->session->userdata('user_id'),
                    					'payment_log' => "EMI received from account no. $customer_acc_no",
                    					);
                    					
                    					$datai2 = array( 
                    							'payment_in' => $emi_amount,
                    							'payment_out' => 0,
                    							'payment_log' => "EMI received from account no. $customer_acc_no",
                    							'payment_date' => $payment_date,
                    							'payment_method' => $payment_mode,
                    							'trans_type' => 'emi',
                    							'client_ID' => $customer_id,
                    							'cap_by' => $cap_type,
                    							'entry_date' => $entry_date,
                    							'user_id' => $this->session->userdata('user_id'),
                    					);
                    					$this->Common_model->commonInsert('capital_account',$datai2);
                    					
                    				}
                    				
                    				$this->Common_model->commonInsert('loan_payments',$datai);
                    				
                    				$sql = "select sum(deposit_amount) as total_amount_deposite
                    						from loan_payments 
                    						where loan_id = $loan_id";
                    				$data = $this->Common_model->commonQuery($sql);
                    				if($data->num_rows() > 0)
                    				{
                    					$row = $data->row();
                    					$total_amount_deposite = $row->total_amount_deposite;
                    				}
                    				
                    				
                    				$datai = array(
                    					'total_amount_deposite' => $total_amount_deposite
                    				);
                    				$this->Common_model->commonUpdate('loans',$datai,'loan_id',$loan_id);
                    			}
                    			
                    			if(!empty($ClientMobile))
                    			{
                    				
                    				$query = $this->Common_model->commonQuery("select * from options where option_key = 'pay_emi_message_sms'");			
                    				
                    				$message = "Dear [NAME], You EMI Tk[AMOUNT] has been posted successfully. 
                    				Current due Tk[DUE_AMOUNT], Pending  EMI [DUE_EMI_COUNT]";
                    		
                    				if($query->num_rows() > 0)
                    				{
                    					foreach($query->result() as $row)
                    					{
                    						$message = $row->option_value;
                    					}
                    				}
                    				
                    				$due_emi_count = 0;
                    				$data2 = $this->Common_model->commonQuery("
                    				select count(*) as total_emi_paid from loan_payments 
                    				where loan_payments.loan_id = $loan_id and deposit_amount > 0
                    				");
                                    
                                    $client_total_paid_emi = 0;
                                    $notify_install_count = $loan_detail->notify_install_count;
                    				if($data2->num_rows() > 0)
                    				{
                    					$emi_paid_count = $data2->row();
                    					$due_emi_count = ($TimePeriods - $emi_paid_count->total_emi_paid);
                                        $client_total_paid_emi = $emi_paid_count->total_emi_paid;
                    				}
                    				
                    				$due_amount = ($NetAmount - $total_amount_deposite);
                    				/*
                    				$message = "Dear $ClientName, You EMI $deposit_amount has been posted successfully. 
                    				Current due $due_amount, Pending  EMI $due_emi_count";
                    				*/
                    				$string = str_replace("[NAME]",$ClientName,$message);
                    				$string = str_replace("[AMOUNT]",$deposit_amount,$string);
                    				$string = str_replace("[DUE_AMOUNT]",$due_amount,$string);
                    				$string = str_replace("[DUE_EMI_COUNT]",$due_emi_count,$string);
                    				
                    				$args = array('mobile' => $ClientMobile , "message" => $string);
                    				$sms = $this->sms->send_sms($args);
                                    
                                    $notify_read = 'N';
                        			$notify_type = 'Notification';
                        			$description = $string;
                        			$capital_payi = array( 
                        							'description' => $description,
                        							'client_ID'   => $client_id,
                        							'notify_read' => $notify_read,
                        							'notify_type' => $notify_type,);
                        			$this->Common_model->commonInsert('notification',$capital_payi);
                                    
                                    if($notify_install_count == $client_total_paid_emi)
                                    {
                                        $query = $this->Common_model->commonQuery("select * from options where option_key = 'notify_install_count_sms'");			
                    				
                        				$message = "Dear [NAME], Client [CLIENT_NAME], account no [CLIENT_ACC_NO] has paid [INSTALLMENTS_COUNT] installment, due [DUE_INSTALLMENTS] installment.";
                        		
                        				if($query->num_rows() > 0)
                        				{
                        					foreach($query->result() as $row)
                        					{
                        						$message = $row->option_value;
                        					}
                        				}
                                        
                                        $adminquery = "SELECT um.meta_value AS mobile_no, um2.meta_value AS admin_first_name, um3.meta_value AS admin_last_name, users.user_id AS admin_id
                                                    FROM users
                                                    LEFT JOIN user_meta um ON um.user_id = users.user_id
                                                    AND um.meta_key = 'mobile_no'
                                                    LEFT JOIN user_meta um2 ON um2.user_id = users.user_id
                                                    AND um2.meta_key = 'first_name'
                                                    LEFT JOIN user_meta um3 ON um3.user_id = users.user_id
                                                    AND um3.meta_key = 'last_name'
                                                    WHERE (
                                                    users.`user_type` = 'admin'
                                                    OR users.`user_type` = 'manager'
                                                    )";
                                        $admins = $this->db->query($adminquery)->result();
                                        
                                        if($admins)
                                        {
                                            foreach($admins as $admin)
                                            {
                                                $admin_name = $admin->admin_first_name.' '.$admin->admin_last_name;
                                                if(trim($admin->admin_first_name)=='' && trim($admin->admin_last_name)=='')
                                                {
                                                    $admin_name ='Admin';
                                                }
                                                
                                                $string = str_replace("[NAME]",$admin_name,$message);
                                                $string = str_replace("[CLIENT_NAME]",$ClientName,$string);
                                				$string = str_replace("[CLIENT_ACC_NO]",$customer_acc_no,$string);
                                				$string = str_replace("[INSTALLMENTS_COUNT]",$notify_install_count,$string);
                                				$string = str_replace("[DUE_INSTALLMENTS]",$due_emi_count,$string);
                                				
                                                if($admin->mobile_no !='')
                                                {
                                    				$args = array('mobile' => $admin->mobile_no , "message" => $string);
                                    				$sms = $this->sms->send_sms($args);
                                                }
                                                
                                                $notify_read = 'N';
                                    			$notify_type = 'Notification';
                                    			$description = $string;
                                    			$admin_notify = array( 
                                    							'description' => $description,
                                    							'client_ID' => $admin->admin_id,
                                    							'notify_read' => $notify_read,
                                    							'notify_type' => $notify_type,);
                                    			$this->Common_model->commonInsert('notification',$admin_notify);
                                            }
                                        }
                                    }
                    			
                    			}
                            }
                            else
                            {
                                /*$notify_read = 'N';
                    			$notify_type = 'Warning';
                    			$description = 'Enter Amount Multiply of EMI for client id('.$insert_csv['id'].')';
                    			$capital_payi = array( 
                    							'description' => $description,
                    							'client_ID'   => $this->session->userdata('user_id'),
                    							'notify_read' => $notify_read,
                    							'notify_type' => $notify_type,);
                    			$this->Common_model->commonInsert('notification',$capital_payi);*/
                                
                                $withdraw_row .= '<div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    Enter Amount Multiply of EMI for '.$csv_client_name.'('.$insert_csv['id'].').
                                                  </div>';
                            }
                        }
            			/*$row = $loan_ops->row();
            			$emiamount = $row->emi_amount;
            			$loanid = $row->loan_id;
            			$entry_time = time();
            			$loan_payi = array( 
            							'loan_id' => $loanid,
            							'emi_amount' => $emiamount,
            							'deposit_amount' => $insert_csv['loanpayment'],
            							'payment_date' => $pay_date,
            							'client_ID' => $client_id,
            							'payment_mode' => 'cash',
            							'entry_date' => $entry_time,
            							'user_id' => $this->session->userdata('user_id'),);
                        $this->Common_model->commonInsert('loan_payments',$loan_payi);
    					$capital_payi = array( 
    							'payment_in' => $insert_csv['loanpayment'],
    							'payment_method' => 'cash',
    							'payment_date' => $pay_date,
    							'trans_type' => 'emi',
    							'client_ID' => $client_id,
    							'payment_method' => 'cash',
    							'entry_date' => $entry_time,
    							'cap_by' => 'borrower',
    							'user_id' => $this->session->userdata('user_id'),
    					);
    					$this->Common_model->commonInsert('capital_account',$capital_payi);
    					$exits_error = array( 
    							'id' => $client_id,
    					);
    					$this->Common_model->commonInsert('update_file_id',$exits_error);*/
                    }
                    else
                    {
            			/*$notify_read = 'N';
            			$notify_type = 'Warning';
            			$description = 'The Loan Does Not exits with this client id('.$insert_csv['id'].')';
            			$capital_payi = array( 
            							'description' => $description,
            							'client_ID' => $this->session->userdata('user_id'),
            							'notify_read' => $notify_read,
            							'notify_type' => $notify_type,);
            			$this->Common_model->commonInsert('notification',$capital_payi);*/
                        
                        
                        $withdraw_row .= '<div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            The Loan Does Not exists with this '.$csv_client_name.'('.$insert_csv['id'].').
                                          </div>';
            		}
                    
                }
                else if($insert_csv['id']!='')
                {
                    $withdraw_row .= '<div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        Client('.$csv_client_name.') Does Not exists with this Account No.('.$insert_csv['id'].').
                                      </div>';
                }
                
                /*$entry_time = time();
        		$sav_ops = $this->Common_model->commonQuery("select * from users where user_id=". $client_id);
        		if($sav_ops->num_rows() > 0)
        		{
					$capital_saving_payi = array( 
							'payment_in' => $insert_csv['saving'],
							'payment_method' => 'cash',
							'payment_date' => $pay_date,
							'trans_type' => 'saving',
							'client_ID' => $client_id,
							'payment_method' => 'cash',
							'entry_date' => $entry_time,
							'cap_by' => 'borrower',
							'user_id' => $this->session->userdata('user_id'),);
					$this->Common_model->commonInsert('capital_account',$capital_saving_payi);
					$this->Common_model->commonInsert('capital_account',$capital_payi);
					$capital_saving_payi1 = array( 
							'payment_in' => $insert_csv['other_deposit'],
							'payment_method' => 'cash',
							'payment_date' => $pay_date,
							'trans_type' => 'conf_saving',
							'client_ID' => $client_id,
							'payment_method' => 'cash',
							'entry_date' => $entry_time,
							'cap_by' => 'borrower',
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$capital_saving_payi1);
					$exits_error = array( 
							'id' => $client_id,
					);
					$this->Common_model->commonInsert('update_file_id',$exits_error);
        					
        		}
                else
                {
        			$notify_read1 = 'N';
        			$notify_type1 = 'Warning';
        			$description1 = 'The client Does Not exits with this client id('.$insert_csv['id'].')';
        			$capital_payi1 = array( 
        							'description' => $description1,
        							'client_ID' => $client_id,
                					'notify_read' => $notify_read1,
                					'notify_type' => $notify_type1,);
        			$this->Common_model->commonInsert('notification',$capital_payi1);
        
                }*/
                
                $si[$i] = $client_id;
                $id_exits = $si;
            }
            //var_dump($insert_csv);die;
            //echo '<table>'.$withdraw_row.'</table>';die;
            /*$withdraw_row = '';
    		$query = "select * from notification";
    		$result = $this->Common_model->commonQuery($query);
    		
    		if($result->num_rows() > 0)
    		{
                $n=1;
    			foreach($result->result() as $row)
    			{
    				$withdraw_row .= '<tr>
    					<td>'.$n++.'</td>
    					<td>'.$row->description.'</td>
    				</tr>';
    			}
    		}
    		else
    		{
    			$withdraw_row = '<tr><td colspan="5">There is no error found.</td></tr>';
    		}*/
    		//$withdraw_row1 = '';
    		$query1 = "select * from upload_error";
    		$result1 = $this->Common_model->commonQuery($query1);
    		
    		/*if($result1->num_rows() > 0)
    		{
                $n=1;
    			foreach($result1->result() as $row1)
    			{
    				$withdraw_row1 .= '<tr>
    					<td>'.$n++.'</td>
    					<td>'.$row1->description.'</td>
    				</tr>';
    			}
    		}
    		else
    		{
    			$withdraw_row1 = '<tr><td colspan="5">There is no existing record found.</td></tr>';
    		}*/
            
            $withdraw_row = '<table>'.$withdraw_row.'</table>';
            
    		//$this->Common_model->commonQuery("delete from update_file_id");
    		$this->session->set_flashdata('msg1234', '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Data Successfully Saved</div>');
    		$this->session->set_flashdata('msg1235', $withdraw_row);
    		$this->session->set_flashdata('msg12389', $withdraw_row1);
    		//$this->Common_model->commonQuery("delete from notification");
    		//$this->Common_model->commonQuery("delete from upload_error");
    		redirect('payment/file_import');
        }
        $this->Common_model->commonQuery("delete from update_file_id");
		$this->load->view("$theme/header",$data);
    }
	
	public function file_import2()
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
		$data['content'] = "$theme/payment/file_import";
		$insert_csv = array();
		$count=0;
		if(isset($_POST['submit']))
		{
			$pay_date1 =$this->input->post('pay_date', true);
			$s_date_explode = explode('/',$pay_date1);
		    $pay_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
			
			$fp = fopen($_FILES['attachments']['tmp_name'],'r') or $this->session->set_flashdata('msg1238', 'File can not open');
			$id_exits = array();
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
                $insert_csv['id'] = $csv_line[2];//remove if you want to have primary key,
                $insert_csv['loanpayment'] = $csv_line[3];
                $insert_csv['saving'] = $csv_line[4];
				$insert_csv['other_deposit'] = $csv_line[5];
            }
            $i++;
			$upload_ops = $this->Common_model->commonQuery("select * from update_file_id where id=". $insert_csv['id']);
			if ($upload_ops->num_rows() > 0)
             {
			$description1 = 'Already inserted data for customer '. $insert_csv['id'].'<name>';
			     $upload_error = array( 
							'description' => $description1,
					);
					$this->Common_model->commonInsert('upload_error',$upload_error);
			 }else{
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
        							'payment_date' => $pay_date,
        							'client_ID' => $insert_csv['id'],
        							'payment_mode' => 'cash',
        							'entry_date' => $entry_time,
        							'user_id' => $this->session->userdata('user_id'),
        					);
        					$this->Common_model->commonInsert('loan_payments',$loan_payi);
        					$capital_payi = array( 
        							'payment_in' => $insert_csv['loanpayment'],
        							'payment_method' => 'cash',
        							'payment_date' => $pay_date,
        							'trans_type' => 'emi',
        							'client_ID' => $insert_csv['id'],
        							'payment_method' => 'cash',
        							'entry_date' => $entry_time,
        							'cap_by' => 'borrower',
        							'user_id' => $this->session->userdata('user_id'),
        					);
        					$this->Common_model->commonInsert('capital_account',$capital_payi);
        					$exits_error = array( 
        							'id' => $insert_csv['id'],
        					);
        					$this->Common_model->commonInsert('update_file_id',$exits_error);
        		}else{
        			$notify_read = 'N';
        			$notify_type = 'Warning';
        			$description = 'The Loan Does Not exits with this client id('.$insert_csv['id'].')';
        			$capital_payi = array( 
        							'description' => $description,
        							'client_ID' => $insert_csv['id'],
        							'notify_read' => $notify_read,
        							'notify_type' => $notify_type,
        					);
        					$this->Common_model->commonInsert('notification',$capital_payi);
        		}
        		$sav_ops = $this->Common_model->commonQuery("select * from users where user_id=". $insert_csv['id']);
        		if($sav_ops->num_rows() > 0)
        		{
        					$capital_saving_payi = array( 
        							'payment_in' => $insert_csv['saving'],
        							'payment_method' => 'cash',
        							'payment_date' => $pay_date,
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
        							'payment_date' => $pay_date,
        							'trans_type' => 'conf_saving',
        							'client_ID' => $insert_csv['id'],
        							'payment_method' => 'cash',
        							'entry_date' => $entry_time,
        							'cap_by' => 'borrower',
        							'user_id' => $this->session->userdata('user_id'),
        					);
        					$this->Common_model->commonInsert('capital_account',$capital_saving_payi1);
        					$exits_error = array( 
        							'id' => $insert_csv['id'],
        					);
        					$this->Common_model->commonInsert('update_file_id',$exits_error);
        					
        		}else{
        			$notify_read1 = 'N';
        			$notify_type1 = 'Warning';
        			$description1 = 'The client Does Not exits with this client id('.$insert_csv['id'].')';
        			$capital_payi1 = array( 
        							'description' => $description1,
        							'client_ID' => $insert_csv['id'],
							'notify_read' => $notify_read1,
							'notify_type' => $notify_type1,
					);
					$this->Common_model->commonInsert('notification',$capital_payi1);
		
		}
		/*	$data1 = array(
                'id' => $insert_csv['id'] ,
                'empName' => $insert_csv['empName'],
                'empAddress' => $insert_csv['empAddress'],
				);
				print $data1; */
           # $data['crane_features']=$this->db->insert('tableName', $data);
			 }
			 
			 $si[$i] = $insert_csv['id'];
			 $id_exits = $si ;
		}
		$withdraw_row = '';
		$query = "select * from notification";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $row)
			{
				$withdraw_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.$row->description.'</td>
				</tr>';
			}
		}
		else
		{
			$withdraw_row = '<tr><td colspan="5">There is no error found.</td></tr>';
		}
		$withdraw_row1 = '';
		$query1 = "select * from upload_error";
		$result1 = $this->Common_model->commonQuery($query1);
		
		if($result1->num_rows() > 0)
		{
            $n=1;
			foreach($result1->result() as $row1)
			{
				$withdraw_row1 .= '<tr>
					<td>'.$n++.'</td>
					<td>'.$row1->description.'</td>
				</tr>';
			}
		}
		else
		{
			$withdraw_row1 = '<tr><td colspan="5">There is no existing record found.</td></tr>';
		}
		$this->Common_model->commonQuery("delete from update_file_id");
		$this->session->set_flashdata('msg1234', 'Data Successfully Saved');
		$this->session->set_flashdata('msg1235', $withdraw_row);
		$this->session->set_flashdata('msg12389', $withdraw_row1);
		$this->Common_model->commonQuery("delete from notification");
		$this->Common_model->commonQuery("delete from upload_error");
		redirect('payment/file_import');
			//$this->load->view("$theme/header",$data);
		}
		$this->Common_model->commonQuery("delete from update_file_id");
		$this->load->view("$theme/header",$data);
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
	public function file_array_return($file_name)
	{
		
		$fp1 = fopen($file_name,'r') or $this->session->set_flashdata('msg1238', 'File can not open');
			
			$id_exits1 = array();
			 while($csv_line1 = fgetcsv($fp1,1024))
             {
				 $count++;
				 if($count == 1){
				continue;
			     }
				 $j = count($csv_line1);
                for($i = 0;  $i < $j; $i++)
                {
			     $id_exits1[] = $csv_line1[2];
			    }
		     }
			 return $id_exits1;
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
	
 function masspost(){
     if(!$this->isLogin())
		{
			redirect('/logins','location');
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
                if(isset($_POST['masspostform'])){
                    $is=$_POST['loopsdb'];
               for($q=1;$q<$is;$q++)     
                {
                     $s_date_explode = explode('/',$_POST['loan_dates'.trim($q)]);
		    $entry_time = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
                    $client_ID=$_POST['client'.trim($q)];
                    if($_POST['gnrl_id'.trim($q)]=='0'){
                        $amount=$_POST['gnrl_saving'.trim($q)];
                     $datai = array( 
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_date' => $entry_time,
							'payment_method' => 'cash',
							'trans_type' => 'saving',
							'client_ID' => $client_ID,
							'cap_by' => 'borrower',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
                    }
                    else{ 
                        $amount=$_POST['gnrl_saving'.trim($q)];
                        $cap_acc_id=$_POST['gnrl_id'.trim($q)];
                         $datai = array( 
							'payment_in' => $amount,
							
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
                    }
                     if($_POST['conf_id'.trim($q)]=='0'){
                          $amount=$_POST['conf_saving'.trim($q)];
                        
                       $datai = array( 
							'payment_in' => $amount,
							'payment_out' => 0,
							'payment_date' => $entry_time,
							'payment_method' => 'cash',
							'trans_type' => 'conf_saving',
							'client_ID' => $client_ID,
							'cap_by' => 'borrower',
							'entry_date' => $entry_time,
							'user_id' => $this->session->userdata('user_id'),
					);
					$this->Common_model->commonInsert('capital_account',$datai);
                     }
                     else{
                          $amount=$_POST['conf_saving'.trim($q)];
                        $cap_acc_id=$_POST['conf_id'.trim($q)];
                          $datai = array( 
							'payment_in' => $amount,
							
					);
					$this->Common_model->commonUpdate('capital_account',$datai,'cap_acc_id',$cap_acc_id);
                     }
                }
                }
		
                $data['client_list'] = $this->Common_model->commonQuery("
				select users.*,
						um.meta_value as first_name,
						um2.meta_value as last_name ,
						um3.meta_value as acc_no 	
				from users
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'first_name'
				left join user_meta um2 on um2.user_id = users.user_id
				and um2.meta_key = 'last_name'
				left join user_meta um3 on um3.user_id = users.user_id
				and um3.meta_key = 'acc_no'
				where users.user_type = 'user'  and users.user_status = 'Y'
				order by users.user_id DESC
				");
                 $data['manage'] = $this->Common_model->commonQuery("select * from users where user_type != 'admin' order by user_id DESC");
        
		$data['content'] = "$theme/payment/masspost";
		
		$this->load->view("$theme/header",$data);
		
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
