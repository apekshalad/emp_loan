<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//http://www.freecontactform.com/email_form.php
//class Ajax extends CI_Controller {
class Ajax extends MY_Controller {	
	public function index()	 
	{		
		redirect('/main','location');	
	}
	
	public function upload_images_callback_func()	
	{		 
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$target = "";
		if($user_type == 'client')
			$target = 'client/';
		else if($user_type == 'user')
			$target = 'user/';
		if(isset($_FILES) && !empty($_FILES))		
		{				
			$name = $_FILES["img"]["name"];				
			$path_parts = pathinfo($_FILES["img"]["name"]);								
			$extension = $path_parts['extension'];				
			$actual_name= $path_parts['filename'];								
			$exp_data = explode(' ',$actual_name);				
			$actual_name = implode('_',$exp_data);				
			$name = $actual_name.'-'.time().".".$extension;								
			$CI->simpleimage->load($_FILES['img']['tmp_name']);				
			$CI->simpleimage->save('uploads/'.$target.$name);
			if($user_type == 'client')
			{ 
				$CI->simpleimage->load($_FILES['img']['tmp_name']);				
				$CI->simpleimage->save('uploads/user/'.$name);
			}
			header('Content-type: application/json');				
			echo json_encode(array('img_url'=> base_url().'uploads/'.$target.$name,'img_name' => $name));
		}			
	}	
	
	public function delete_images_callback_func()	
	{		 
		extract($_POST);		
		$CI =& get_instance();	
		$this->load->model('Common_model');		
		$this->load->library('global');		
		$target = "";
		if($user_type == 'client')
			$target = 'client/';								
		else if($user_type == 'user')
			$target = 'user/';
		if(file_exists('uploads/'.$target.$img_name))
			unlink('uploads/'.$target.$img_name);
		
		if(isset($client_id) && isset($field_name))
		{
			$datai = array( 
					$field_name => ""
			);
			$encId = $this->DecryptClientId($client_id);	
			$this->Common_model->commonUpdate('clients',$datai,'client_ID',$encId);
		}
		
		echo 'success';
	}
	
	public function upload_logo_images_callback_func()	
	{		 
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$this->load->model('Common_model');
		$target = $image_type;
		$pre_post_id = '';
		if(isset($_FILES) && !empty($_FILES))		
		{				
			$name = $_FILES["img"]["name"];				
			$path_parts = pathinfo($_FILES["img"]["name"]);								
			$extension = $path_parts['extension'];				
			$actual_name= $path_parts['filename'];								
			$exp_data = explode(' ',$actual_name);				
			$actual_name = implode('_',$exp_data);				
			$name = $actual_name.'-'.time();								
			
			$origoinal_image_name = $name.".".$extension;
			$thumbnail_image_name = $name.'-300X300.'.$extension;
			
			$CI->simpleimage->load($_FILES['img']['tmp_name']);				
			$CI->simpleimage->save('uploads/'.$origoinal_image_name);								
			
			
			  
			header('Content-type: application/json');				
			echo json_encode(array('img_url'=> base_url().'uploads/'.$origoinal_image_name,'img_name' => $origoinal_image_name));
		}			
	}	
	
	public function delete_logo_images_callback_func()	
	{		 
		extract($_POST);		
		$CI =& get_instance();	
		$this->load->model('Common_model');		
		$this->load->library('global');		
		$image_type = $image_type;
		$image_name = $img_name;
		
		if(file_exists('uploads/'.$image_name))
			unlink('uploads/'.$image_name);
		if($image_type == 'logo')
			$this->Common_model->commonQuery("delete from options where option_key = 'website_logo'");
		else if($image_type == 'fevi')
			$this->Common_model->commonQuery("delete from options where option_key = 'fevicon_icon'");
		echo 'success';
	}
	
	
	public function fetch_client_detail_callback_func()	{		
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$client_detail = array();
		$encId = $this->DecryptClientId($id);
		$result = $this->Common_model->commonQuery("
						select users.*,
								um.meta_value as client_ID,
								um2.meta_value as client_acc_no,
								um3.meta_value as first_name,
								um4.meta_value as client_mobile,
								um5.meta_value as client_address,
								um6.meta_value as client_photo_proof,
								um7.meta_value as client_id_proof,
								um8.meta_value as last_name,
                                                                um9.meta_value as age
						from users 
						left join user_meta um on um.user_id = users.user_id
						and um.meta_key = 'client_ID'
						left join user_meta um2 on um2.user_id = users.user_id
						and um2.meta_key = 'acc_no'
						left join user_meta um3 on um3.user_id = users.user_id
						and um3.meta_key = 'first_name'
						left join user_meta um4 on um4.user_id = users.user_id
						and um4.meta_key = 'mobile_no'
						left join user_meta um5 on um5.user_id = users.user_id
						and um5.meta_key = 'address'
						left join user_meta um6 on um6.user_id = users.user_id
						and um6.meta_key = 'photo_url'
						left join user_meta um7 on um7.user_id = users.user_id
						and um7.meta_key = 'id_url'
						left join user_meta um8 on um8.user_id = users.user_id
						and um8.meta_key = 'last_name'
                                                left join user_meta um9 on um9.user_id = users.user_id
						and um9.meta_key = 'age'
						where users.user_id = $encId");
		if($result->num_rows() > 0 )
		{
			$client_row = $result->row();
                        $decId=$client_row->client_ID;
                        $client_det= $this->Common_model->commonQuery("select * from clients where client_ID = $decId");
                        $total_current_saving = 0;
                                    $query = "select IFNULL(sum(ca.payment_in),0) as total_current_deposit from capital_account ca
                                    where ca.trans_type = 'saving' and client_ID = $encId ";
                                    //echo $query;
                                    $result = $this->Common_model->commonQuery($query);
                                    if($result->num_rows() > 0)
                                    {
                                            $row = $result->row();
                                            $total_current_saving += $row->total_current_deposit;
                                    }
                                    $query = "select IFNULL(sum(ca.payment_out),0) as total_current_withdraw from capital_account ca
                                    where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $encId ";
                                    $result = $this->Common_model->commonQuery($query);
                                    if($result->num_rows() > 0)
                                    {
                                            $row = $result->row();
                                            $total_current_saving -= $row->total_current_withdraw;
                                    }

                                    $query = "select IFNULL(sum(ca.payment_out),0) as total_current_expense from capital_account ca
                                    where ca.trans_type = 'expense' and ca.trans_from = 'saving' and client_ID = $encId";
                                    $result = $this->Common_model->commonQuery($query);
                                    if($result->num_rows() > 0)
                                    {
                                            $row = $result->row();
                                            $total_current_saving += $row->total_current_expense;
                                    }

                                    $query = "select IFNULL(sum(ca.payment_in),0) as total_current_charges from capital_account ca
                                    where ca.trans_type = 'charges' and client_ID = $encId and ca.trans_from = 'saving'";
                                    $result = $this->Common_model->commonQuery($query);
                                    if($result->num_rows() > 0)
                                    {
                                            $row = $result->row();
                                            $total_current_saving -= $row->total_current_charges;
                                    }
                                    $total_conf_saving = 0;
                    $query = "select IFNULL(sum(ca.payment_in),0) as total_current_deposit from capital_account ca
                    where ca.trans_type = 'conf_saving' and client_ID = $encId ";
                    $result = $this->Common_model->commonQuery($query);
                    if($result->num_rows() > 0)
                    {
                            $row = $result->row();
                            $total_conf_saving += $row->total_current_deposit;
                    }
                    $query = "select IFNULL(sum(ca.payment_out),0) as total_current_withdraw from capital_account ca
                    where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $encId ";
                    $result = $this->Common_model->commonQuery($query);
                    if($result->num_rows() > 0)
                    {
                            $row = $result->row();
                            $total_conf_saving -= $row->total_current_withdraw;
                    }

                    $query = "select IFNULL(sum(ca.payment_out),0) as total_current_expense from capital_account ca
                    where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $encId";
                    $result = $this->Common_model->commonQuery($query);
                    if($result->num_rows() > 0)
                    {
                            $row = $result->row();
                            $total_conf_saving += $row->total_current_expense;
                    }

                    $query = "select IFNULL(sum(ca.payment_in),0) as total_current_charges from capital_account ca
                    where ca.trans_type = 'charges' and client_ID = $encId and ca.trans_from = 'conf_saving'";
                    $result = $this->Common_model->commonQuery($query);
                    if($result->num_rows() > 0)
                    {
                            $row = $result->row();
                            $total_conf_saving -= $row->total_current_charges;
                    }
                       $client_dets = $client_det->row();
			$client_detail['client_ID'] = $this->EncryptClientId($client_row->user_id);
			$client_detail['client_acc_no'] = $client_row->client_acc_no;
			$client_detail['client_name'] = $client_row->first_name.' '.$client_row->last_name;
			$client_detail['client_mobile'] = $client_row->client_mobile;
			$client_detail['client_address'] = $client_row->client_address;
			$client_detail['photo_proof'] = $client_row->client_photo_proof;
			$client_detail['photo_proof_url'] = base_url().'/uploads/client/'.$client_row->client_photo_proof;
			$client_detail['id_proof'] = $client_row->client_id_proof;
                        $client_detail['age'] = $client_dets->age;
                        $client_detail['nominee'] = $client_dets->nominee;
                        $client_detail['age_of_nominee'] = $client_dets->age_of_nominee;
                        $client_detail['relation_with_nominee'] = $client_dets->relation_with_nominee;
                        $client_detail['gen_saving'] = $total_current_saving;
                        $client_detail['conf_saving'] = $total_conf_saving;
                        $client_detail['remarks'] = $client_dets->remarks;
                       
			$client_detail['id_proof_url'] = base_url().'/uploads/client/'.$client_row->client_id_proof;
		}
								
		header('Content-type: application/json');				
		echo json_encode(array('client_detail'=> $client_detail));
		
	}
    
	public function fetch_loan_list_callback_func()	{		
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$encId = $this->DecryptClientId($id);
		$sql = "select * from loans 
				where loans.client_ID = $encId 
				order by loans.loan_id DESC";
		$result = $this->Common_model->commonQuery($sql);
		$output = '<option value="">Select Loan</option>';
		if($result->num_rows() > 0 )
		{
			foreach($result->result() as $client_row)
			{
				$time_periods = ($client_row->time_periods);
				$total_loan_payment_count = $this->Common_model->commonQuery("
						select * from loan_payments 
						where loan_payments.loan_id = $client_row->loan_id and deposit_amount > 0");
				 
				 $emi_paid = $total_loan_payment_count->num_rows();
				 if((($time_periods  - $emi_paid) > 0) )
				{
					$output .= '<option value="'.$this->EncryptClientId($client_row->loan_id).'">'.$client_row->principal_amount.' Tk. / '.$client_row->interest_rate.'% per Year / '.$client_row->time_periods.' Installments</option>';
				}
			}
		}
								
		header('Content-type: application/json');				
		echo json_encode(array('loan_detail'=> $output));
		
	}
	
	public function fetch_loan_detail_callback_func()
	{		 
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		
		$sql = "select loans.*,cst.* from loans 				
				inner join users u on u.user_id = loans.client_ID
				inner join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				inner join clients cst on cst.client_ID=um.meta_value
				where loans.loan_id = $decId
				";
		
		$result = $this->Common_model->commonQuery($sql);
		$loan_detail = array();
		$has_more_due_emi = 'yes';
		if($result->num_rows() > 0 )
		{ 
			$loan_detail_row = $result->row();
			$totalInterest = round(( $loan_detail_row->principal_amount  * $loan_detail_row->interest_rate) / 100);
			$outstanding_amount = ($loan_detail_row->net_amount)  - $loan_detail_row->total_amount_deposite ;
			
			$sql2 = "select * from loan_payments 
						where loan_id = $loan_detail_row->loan_id and deposit_amount > 0";
				
			$loan_payments_data = $this->Common_model->commonQuery("$sql2");
			$emi_paid = $loan_payments_data->num_rows();
			/*
			$output = '';
			$loan_payment_detail = $this->Common_model->commonQuery("
					select loan_payments.* from loan_payments 
					where loan_payments.loan_id = $loan_detail_row->loan_id 
					order by loan_payments.payment_id DESC");
			if($loan_payment_detail->num_rows() > 0) { 
				$i=0;
				foreach($loan_payment_detail->result() as $loan_payment_detail_row) {
					$i++;
					 $total_undeposited_row = 0; 
					 $amount_due = ($loan_payment_detail_row->emi_amount);
					 $amount_deposited = ($loan_payment_detail_row->deposit_amount);
					 
					if($loan_payment_detail_row->due_date > $loan_payment_detail_row->entry_date)
							$advance_payment_month = ' (<span style="color:green;">Advanced</span>)';
						else
							$advance_payment_month = "";
					 
					$total_undeposited_row = ($amount_due - $amount_deposited);
					$output .= '<div class="col-md-12">
					  
					  <div class="col-md-1">'.$i.'</div>
					  <div class="col-md-2">'.date('d/m/Y',$loan_payment_detail_row->payment_date).'</div>
					  <div class="col-md-2">'.date('d/m/Y',$loan_payment_detail_row->due_date).'</div>
					  <div class="col-md-2">'.$loan_payment_detail_row->emi_amount.' <i class="fa fa-money"></i></div>
					  <div class="col-md-2">'.$loan_payment_detail_row->deposit_amount.' <i class="fa fa-money"></i> '.$advance_payment_month.'</div>
					  <div class="col-md-2">'.$total_undeposited_row.' <i class="fa fa-money"></i></div>
					</div>';
				}
			}
			*/
			if(($loan_detail_row->time_periods - $emi_paid) == 0)
				$has_more_due_emi = 'no';
			$loan_detail['has_more_due_emi'] = $has_more_due_emi;
			$loan_detail['due_installments'] = ($loan_detail_row->time_periods - $emi_paid);
			$loan_detail['outstanding_amount'] = $outstanding_amount.' <i class="fa fa-money"></i>';
			$loan_detail['interest_amount'] = $totalInterest.' <i class="fa fa-money"></i>';
			$loan_detail['loan_id'] = $this->EncryptClientId($loan_detail_row->loan_id);
			$loan_detail['client_id'] = $loan_detail_row->client_acc_no;
			$loan_detail['client_name'] = $loan_detail_row->client_name;
			$loan_detail['client_mobile'] = $loan_detail_row->client_mobile;
			$loan_detail['client_address'] = $loan_detail_row->client_address;
			$loan_detail['principal_amount'] = $loan_detail_row->principal_amount.' <i class="fa fa-money"></i>';
			$loan_detail['interest_rate'] = $loan_detail_row->interest_rate.' %';
			$loan_detail['net_amount'] = $loan_detail_row->net_amount.' <i class="fa fa-money"></i>';
			$loan_detail['total_installment'] = $loan_detail_row->time_periods;
			$loan_detail['loan_type'] = $loan_detail_row->loan_type;
			$loan_detail['loan_date'] = date('d-M-Y',$loan_detail_row->loan_date);
			$loan_detail['emi_amount'] = $loan_detail_row->emi_amount.' <i class="fa fa-money"></i>';
			$loan_detail['payment_terms'] = $loan_detail_row->payment_terms;
			$loan_detail['total_amount_deposite'] = $loan_detail_row->total_amount_deposite.' <i class="fa fa-money"></i>';
			//$loan_detail['transaction_list'] = $output;
		}
								
		header('Content-type: application/json');				
		echo json_encode(array('loan_detail'=> $loan_detail));
		
	}
	
	public function open_pay_now_model_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($loan_id);
		date_default_timezone_set('Asia/Kolkata');
		$data1 = $this->Common_model->commonQuery("
				select *, loans.client_ID as customer_id from loans
				inner join users u on u.user_id = loans.client_ID
				inner join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				inner join clients cst on cst.client_ID=um.meta_value
				where loans.loan_id = $decId");	
		$loan_detail = $data1->row();
		
		$data2 = $this->Common_model->commonQuery("
				select * from loan_payments 
				where loan_payments.loan_id = $decId and deposit_amount > 0
				order by payment_id DESC 
				limit 1");
		
		 
		 $total_loan_payment_count = $this->Common_model->commonQuery("
				select * from loan_payments 
				where loan_payments.loan_id = $decId and deposit_amount > 0");
		 
	 
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
		
        
        //var_dump($due_months, $next_month);die;
		$next_due_date = date("d/m/Y", strtotime('+'.$due_months.' week', $next_month));
        //echo date("d/m/Y", ('1495564200')).' 1495564200';die;
		
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
		
		$segments1 = array('ajax','loan_payment_callback_func');
		$segments2 = array('ajax','change_payment_date_callback_func');
		echo '<div class="example-modal">
				<div class="modal">
				  <div class="modal-dialog" style="margin:25px auto;">
					<div class="modal-content">
					  <form method="POST" name="loan_payment_form" id="loan_payment_form" class="loan_payment_form">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Payment Option</h4>
					  </div>
					  <div class="modal-body">
					    
						<div class="row">
							<div class="col-md-6">
								<label>Customer Account No. &nbsp;:&nbsp;</label><span>'.$loan_detail->client_acc_no.'</span>
								<input type="hidden" name="customer_acc_no" value="'.$loan_detail->client_acc_no.'">
								<input type="hidden" name="loan_type" value="'.$loan_detail->loan_type.'">
								<input type="hidden" name="loan_date" value="'.$loan_detail->loan_date.'">
								<input type="hidden" name="ClientMobile" value="'.$loan_detail->client_mobile.'">
								<input type="hidden" name="ClientName" value="'.$loan_detail->client_name.'">
								<input type="hidden" name="NetAmount" value="'.$loan_detail->net_amount.'">
								<input type="hidden" name="TimePeriods" value="'.$loan_detail->time_periods.'">
							</div>
							<div class="col-md-6">
								<label>Customer Name &nbsp;:&nbsp;</label><span>'.$loan_detail->client_name.'</span><br>
							</div>
						</div>
						
						<hr style="margin-top:10px; margin-bottom:10px;">
						
						<div class="row">
						    <div class="col-md-6">
								<label>Principal Amount&nbsp;:&nbsp;</label><span>'.$loan_detail->principal_amount.'</span><br>
							</div>
							
							<div class="col-md-6">
								<input type="hidden" name="customer_id" value="'.$loan_detail->customer_id.'">
								<input type="hidden" name="loan_id" value="'.$loan_detail->loan_id.'">
							</div>
							
							<div class="col-md-6">
								<label>EMI To Deposite&nbsp;:&nbsp;</label><span>'.$loan_detail->emi_amount.' X '.$due_months.' = '.($loan_detail->emi_amount * $due_months).'</span>
								<input type="hidden" name="emi_amount" value="'.$loan_detail->emi_amount.'">
								<input type="hidden" name="emi_to_deposite" value="'.($loan_detail->emi_amount * $due_months).'"><br>
							</div>
							
							<div class="col-md-6">
								<label>Due Date&nbsp;:&nbsp;</label><span>'.$next_due_date.'</span>
								<input type="hidden" name="due_date" value="'.$next_due_date.'"><br>
							</div>
							
						</div>
						
						<hr style="margin-top:10px; margin-bottom:10px;">
						
						<div class="row">
							<div class="col-md-6">
								<label>Total Amount&nbsp;:&nbsp;</label>
							</div>
							<div class="col-md-6">
								<span>'.round(($loan_detail->emi_amount * $due_months)).'</span>
							</div>
						</div>
						
						<hr style="margin-top:10px; margin-bottom:10px;">
						
						<div class="row">
							<div class="col-md-6">
								<label>Payment Date&nbsp;:&nbsp;</label>
								<input type="text" class="form-control p_p_date" required="required" name="payment_date"  data-inputmask="alias: dd/mm/yyyy" data-mask value="'.date('d/m/Y',time()).'"><br>
							</div>
							<div class="col-md-6">
								<label>EMI Amount&nbsp;:&nbsp;</label>
								<input type="text" class="form-control deposit_amount" id="deposit_amount" name="deposit_amount" placeholder="Enter EMI Amount" value="'.($loan_detail->emi_amount * $due_months).'" required >
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>Payment Mode&nbsp;:&nbsp;</label>
								<select class="form-control" name="payment_mode">
									<option value="cash">Cash</option>
									<option value="cheque">Cheque</option>
								</select>
							</div>
							
						</div>
						</div>
						
					   <div class="modal-footer">
						<button type="button" class="btn btn-default pull-left hide-model" data-dismiss="modal">Close</button>
						<button type="submit" name="submit" class="btn btn-primary" id="pay-amount">Pay Amount</button>
					  </div>
					  <div class="overlay" style="display:none;">
						  <i class="fa fa-refresh fa-spin"></i>
						</div>
					  </form>
					</div>
				  </div>
				</div>
			  </div>
			  <style>
				.modal .overlay {
					background: rgba(255, 255, 255, 0.7) none repeat scroll 0 0;
					border-radius: 3px;
					z-index: 50;
					height: 100%;
					left: 0;
					position: absolute;
					top: 0;
					width: 100%;
				}
				.modal .overlay > .fa {
					color: #000;
					font-size: 30px;
					left: 50%;
					margin-left: -15px;
					margin-top: -15px;
					position: absolute;
					top: 50%;
				}
			  </style>
			  
			  <script>
			  
			  $(document).ready(function() {
				  /*
				  var currentDate = new Date();
				  $(".p_p_date").datepicker("setDate", currentDate);*/
				  
				    var checkin  = $(".p_p_date").datepicker({
					  format: "dd/mm/yyyy"
					 }).on("changeDate", function(ev) {
						 checkin.hide();
					 }).data("datepicker");
				  
				 
				  
				  $(".close,.hide-model").click(function() {
					  /*$(".modal").hide();*/
					  $(".model_wrapper").html("");
				  });
				  
				  $(".loan_payment_form").on("submit",function() {
					  
					  var thiss = $(this);
					  var emi = $("input[type=hidden][name=emi_amount]").val();
					  var depo = $(".deposit_amount").val();
					  if(depo % emi == 0)
					  {
						  $(".modal .overlay").show();
						  $.ajax({
							  url: "'.site_url($segments1).'",
							  type: "POST",
							  data:thiss.serialize(),
							  cache: false,
							  success: function(data){
								  $(".modal .overlay").hide();
								  $(".model_wrapper").html("");
								  alert("Payment Made Successfully.");
								  window.location.href = "'.site_url().'loan/invoice_details/'.$this->EncryptClientId($loan_detail->loan_id).'";
							  }
							});
					  }
					  else
						{	
							alert("Enter Amount Multiply of EMI.");
							$(".deposit_amount").focus();
							
						}
					
					return false;
				});
				
				$(".deposit_amount").on("change",function() {
					var emi = $("input[type=hidden][name=emi_amount]").val();
					var depo = $(this).val();
					if(depo % emi != 0)
					{	
						alert("Enter Amount Multiply of EMI.");
						$(this).focus();
						return false;
					}
				});
				
				
			});
			  </script>
			  ';
	}
	
	public function loan_payment_callback_func()
	{
		extract($_POST);
        $CI =& get_instance();		
		$this->load->library('global');	
		$this->load->library('sms');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		 
		 if($loan_type == 'personal')
			 $l_type = 'ploan';
		 else if($loan_type == 'vehicle') 
			 $l_type = 'vloan';
		 else
			 $l_type = '';
		 $entry_date = time();
		$date_explode = explode('/',$payment_date);
		$payment_date = mktime(0,0,0,$date_explode[1],$date_explode[0],$date_explode[2]);
		 
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
		
		$result = $this->Common_model->commonQuery("select users.user_type from users 
		where users.user_id  =  $customer_id
		");	
		
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
				where loan_payments.loan_id = $loan_id and deposit_amount > 0");
                
                $client_total_paid_emi = 0;
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
                
                $data1 = $this->Common_model->commonQuery("
        				select *, loans.client_ID as customer_id from loans
        				inner join users u on u.user_id = loans.client_ID
        				inner join user_meta um on um.user_id = u.user_id
        				and um.meta_key = 'client_ID'
        				inner join clients cst on cst.client_ID=um.meta_value
        				where loans.loan_id = $loan_id");	
        		$loan_detail = $data1->row();
                
                $notify_install_count = $loan_detail->notify_install_count;
                
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
		
		echo 'success';
	}
	
	public function fetch_borrower_current_saving_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$total_current_saving = 0;
		$total_current_deposit = 0;
		$total_current_withdraw = 0;
		$total_current_charges = 0;
		$total_profit = 0;
		if(isset($trans_type) && $trans_type == 'conf_saving')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			
			$query = "select sum(ca.payment_out) as total_current_expense from capital_account ca
			where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_expense;
			}
			
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and client_ID = $decId and ca.trans_from = 'conf_saving' and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		else if(isset($trans_type) && $trans_type == 'withdraw')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and client_ID = $decId and ca.trans_from = 'conf_saving' and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
			
		}
		else if(isset($trans_type) && $trans_type == 'saving')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and client_ID = $decId and ca.trans_from = 'saving' and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		else
		{
			$query = "select sum(ca.payment_in) as total_current_saving from capital_account ca
			where ca.trans_type = 'saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_saving;
				//$total_current_saving += $row->total_current_saving;
			}
			
			
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and client_ID = $decId and ca.trans_from = 'saving' and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
			
						
			$query = "select sum(ca.payment_out) as total_current_expense from capital_account ca
			where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $decId and payment_method = '$payment_mode'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_profit += $row->total_current_expense;
			}
			
		}
		
		
		header('Content-type: application/json');				
		echo json_encode(array('total_current_saving' => $total_current_saving , 'total_current_deposit' => $total_current_deposit  , 'total_current_withdraw' => $total_current_withdraw,
				'total_profit' => $total_profit));
		
	}
	
	public function fetch_borrower_saving_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select ca.*,(select sum(payment_in) from capital_account ca2 where ca2.client_ID = ca.client_ID and ca.trans_type = 'saving') as total_current_saving
		from capital_account ca
		where ca.trans_type = 'saving' and ca.cap_acc_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_saving = $row->total_current_saving;
			$client_ID = $row->client_ID;
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_in;
			$cap_acc_id = $row->cap_acc_id;
			$payment_method = $row->payment_method;
		}
		header('Content-type: application/json');				
		echo json_encode(array('total_current_saving' => $total_current_saving, 
								'client_ID' => $this->EncryptClientId($client_ID), 
								'payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								'payment_mode' => $payment_method
								));
		
	}
	
	
	public function fetch_current_balance_for_transfer_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		if(isset($mode) && $mode == 'ctob')
		{
			$query = "select IFNULL(sum(payment_in),0) as payment_in, IFNULL(sum(payment_out),0) as payment_out
			from capital_account ca
			where ca.payment_method = 'cash'";
			$result = $this->Common_model->commonQuery($query);
		}
		else if(isset($mode) && $mode == 'btoc')
		{
			$query = "select IFNULL(sum(payment_in),0) as payment_in , IFNULL(sum(payment_out),0) as payment_out
			
			from capital_account ca
			where ca.payment_method = 'cheque'";
			$result = $this->Common_model->commonQuery($query);
		}
		
		
		if(isset($result) && $result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_balance = ($row->payment_in - $row->payment_out);
		}
		else
			$total_current_balance = 0;
		header('Content-type: application/json');				
		echo json_encode(array('total_current_balance' => $total_current_balance,
								));
		
	}
	
	public function fetch_capital_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select ca.*
		from capital_account ca
		where ca.trans_type = 'capital' and ca.cap_acc_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_in;
			$cap_acc_id = $row->cap_acc_id;
			$payment_method = $row->payment_method;
		}
		header('Content-type: application/json');				
		echo json_encode(array('payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'payment_method' => $payment_method,
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								));
		
	}
	
	public function fetch_deposit_saving_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select ca.*,od.*,(select sum(payment_in) from capital_account ca2 where ca2.client_ID = ca.client_ID and ca2.trans_type = 'conf_saving') as total_current_saving
		from capital_account ca
		inner join other_deposits od on od.cap_acc_id = ca.cap_acc_id
		where ca.trans_type = 'conf_saving' and ca.cap_acc_id = $decId";
		
		$query = "select ca.*,od.*,(select sum(payment_in) from capital_account ca2 where ca2.client_ID = ca.client_ID and ca2.trans_type = 'conf_saving') as total_current_saving
		from capital_account ca
		left join other_deposits od on od.cap_acc_id = ca.cap_acc_id
		where ca.trans_type = 'conf_saving' and ca.cap_acc_id = $decId";
		
		
		
		$result = $this->Common_model->commonQuery($query);
		$total_current_saving = 0 ;
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_saving = $row->total_current_saving;
			$client_ID = $row->client_ID;
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_in;
			$cap_acc_id = $row->cap_acc_id;
			$profit_type = $row->profit_type;
			$profit_value = $row->profit_value;
			$payment_method = $row->payment_method;
		}
		header('Content-type: application/json');				
		echo json_encode(array('total_current_saving' => $total_current_saving, 
								'client_ID' => $this->EncryptClientId($client_ID), 
								'payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'profit_type' => $profit_type,
								'profit_value' => $profit_value,
								'payment_mode' => $payment_method,
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								));
		
	}
	
	public function fetch_withdraw_saving_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select *
		from capital_account ca
		where ca.trans_type = 'withdraw' and ca.cap_acc_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$client_ID = $row->client_ID;
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_out;
			$cap_acc_id = $row->cap_acc_id;
			$trans_from = $row->trans_from;
			$payment_method = $row->payment_method;
		}
		$total_current_deposit = 0;
		$total_current_withdraw = 0;
		$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
		where ca.trans_type = '$trans_from' and client_ID = $client_ID";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_deposit += $row->total_current_deposit;
		}
		$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
		where ca.trans_type = 'withdraw' and ca.trans_from = '$trans_from' and client_ID = $client_ID";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_withdraw += $row->total_current_withdraw;
		}
		$total_current_saving = ($total_current_deposit - $total_current_withdraw);
		
		header('Content-type: application/json');				
		echo json_encode(array('total_current_saving' => $total_current_saving, 
								'client_ID' => $this->EncryptClientId($client_ID), 
								'payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								'trans_from' => $trans_from,
								'payment_mode' => $payment_method
								));
		
	}
	
	
	public function fetch_borrower_employee_list_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		date_default_timezone_set('Asia/Kolkata');
		
		if($mode == 'saving')
		{
			$client_list = $this->Common_model->commonQuery("
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
					where users.user_type = 'user'
					group by ca.client_ID
					order by users.user_id DESC
					");
			
			$employee_list = $this->Common_model->commonQuery("
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
					where users.user_type != 'user' and users.user_type != 'admin'
					group by ca.client_ID
					order by users.user_id DESC
					");
		}
		else
		{
			$client_list = $this->Common_model->commonQuery("
					select users.*,
							um.meta_value as first_name,
							um2.meta_value as last_name,
							um3.meta_value as acc_no  
					from users
					inner  join capital_account ca on ca.client_ID = users.user_id
					and ca.trans_type = 'conf_saving' and cap_by = 'borrower'
					inner join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'first_name'
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'last_name'
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'acc_no'
					where users.user_type = 'user'
					group by ca.client_ID
					order by users.user_id DESC
					");
			
			$employee_list = $this->Common_model->commonQuery("
					select users.*,
							um.meta_value as first_name,
							um2.meta_value as last_name,
							um3.meta_value as acc_no  
					from users
					inner  join capital_account ca on ca.client_ID = users.user_id
					and ca.trans_type = 'conf_saving' and cap_by = 'employee'
					inner join user_meta um on um.user_id = users.user_id
					and um.meta_key = 'first_name'
					left join user_meta um2 on um2.user_id = users.user_id
					and um2.meta_key = 'last_name'
					left join user_meta um3 on um3.user_id = users.user_id
					and um3.meta_key = 'acc_no'
					where users.user_type != 'user' and users.user_type != 'admin'
					group by ca.client_ID
					order by users.user_id DESC
					");
		}
			$output = '<option value="">Select Client/Employee</option>';
			 if(isset($client_list) && $client_list->num_rows() > 0)
			{
				$output .= '<optgroup label="Clients">';
				foreach($client_list->result() as $client_row)
				{
					$acc_text ='';
					if(isset($client_row->acc_no) && !empty($client_row->acc_no))
						$acc_text = '('.$client_row->acc_no.')';
					$output .= '<option value="'.$this->EncryptClientId($client_row->user_id).'">
					'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' '.$acc_text.'</option>';
				}
				$output .= '</optgroup>';
			}
			 if(isset($employee_list) && $employee_list->num_rows() > 0)
			{
				$output .= '<optgroup label="Employees">';
				foreach($employee_list->result() as $employee_row)
				{
					$acc_text ='';
					if(isset($employee_row->acc_no) && !empty($employee_row->acc_no))
						$acc_text = '('.$employee_row->acc_no.')';
					$output .= '<option value="'.$this->EncryptClientId($employee_row->user_id).'">
					'.ucfirst($employee_row->first_name).' '.ucfirst($employee_row->last_name).' '.$acc_text.'</option>';
				}
				$output .= '</optgroup>';
			}
			
		
		header('Content-type: application/json');				
		echo json_encode(array('borrower_employee_list' => $output));
	}
	
	public function fetch_expense_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select *
		from capital_account ca
		where ca.trans_type = 'expense' and ca.cap_acc_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_out;
			$cap_acc_id = $row->cap_acc_id;
			$payment_method = $row->payment_method;
			$expense_type = $row->expense_type;
			$account_manager = $row->account_manager;
		}
		header('Content-type: application/json');				
		echo json_encode(array( 'payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'payment_method' => $payment_method,
								'expense_type' => $expense_type,
								'account_manager' => $account_manager,
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								));
		
	}
	
	public function fetch_loan_type_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select *
		from loan_types
		where loan_type_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$loan_title = $row->loan_title;
			$loan_type_id = $row->loan_type_id;
		}
		header('Content-type: application/json');				
		echo json_encode(array( 'loan_title' => $loan_title,
								'loan_type_id' => $this->EncryptClientId($loan_type_id),
								));
		
	}
	
	public function fetch_customer_charges_entry_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		$query = "select *
		from capital_account ca
		where ca.trans_type = 'charges' and ca.cap_acc_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$payment_date = date('m/d/Y',$row->payment_date);
			$payment_log = $row->payment_log;
			$amount = $row->payment_in;
			$cap_acc_id = $row->cap_acc_id;
			$client_ID = $row->client_ID;
			$trans_from = $row->trans_from;
			$payment_method = $row->payment_method;
			
			$total_current_deposit = 0;
			$total_current_withdraw = 0;
			$total_current_charges = 0;
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = '$trans_from' and client_ID = $client_ID";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = '$trans_from' and client_ID = $client_ID";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and client_ID = $client_ID and ca.trans_from = '$trans_from'";
			$result = $this->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		header('Content-type: application/json');				
		echo json_encode(array( 'payment_date' => $payment_date, 
								'remarks' => $payment_log,
								'amount' => $amount,
								'client_ID' => $this->EncryptClientId($client_ID),
								'cap_acc_id' => $this->EncryptClientId($cap_acc_id),
								'trans_from' => $trans_from,
								'total_current_saving' => $total_current_saving,
								'payment_mode' => $payment_method
								));
		
	}
	
	
	public function fetch_available_bal_on_customer_charges_page_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		
		$total_current_deposit = 0;
		$total_current_withdraw = 0;
		$total_current_charges = 0;
		$query = "select IFNULL(sum(ca.payment_in),0) as total_current_deposit from capital_account ca
		where ca.trans_type = '$account_type' and client_ID = $decId and payment_method = '$payment_mode'";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_deposit += $row->total_current_deposit;
		}
		
		$query = "select IFNULL(sum(ca.payment_out),0) as total_current_withdraw from capital_account ca
		where ca.trans_type = 'withdraw' and ca.trans_from = '$account_type' and client_ID = $decId  and payment_method = '$payment_mode'";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_withdraw += $row->total_current_withdraw;
		}
		
		$query = "select IFNULL(sum(ca.payment_in),0) as total_current_charges from capital_account ca
		where ca.trans_type = 'charges' and client_ID = $decId and ca.trans_from = '$account_type'  and payment_method = '$payment_mode'";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_charges += $row->total_current_charges;
		}
		
		$query = "select IFNULL(sum(ca.payment_out),0) as total_current_expense from capital_account ca
		where ca.trans_type = 'expense' and ca.trans_from = '$account_type' and client_ID = $decId and payment_method = '$payment_mode'";
		$result = $this->Common_model->commonQuery($query);
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$total_current_deposit += $row->total_current_expense;
		}
		
		$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
			
		
		
		
		
		header('Content-type: application/json');				
		echo json_encode(array('total_current_saving' => $total_current_saving));
		
	}
	
	public function fetch_statement_list_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		
		if(isset($date_rang) && !empty($date_rang))
		{
			$loan_date = $date_rang;
			$s_date = trim($loan_date);
			$s_date_explode = explode('-',$s_date);
			$s_date_explode1 = explode('/',$s_date_explode[0]);
			$s_date_explode2 = explode('/',$s_date_explode[1]);
			$start_date = mktime(00,00,00.00,$s_date_explode1[0],$s_date_explode1[1],$s_date_explode1[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode2[0],$s_date_explode2[1],$s_date_explode2[2]);
			
		}
		else
		{
			$s_date_explode = explode('/',date('m/d/Y',time()));
			$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
			
		}
		 
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
		
		header('Content-type: application/json');				
		echo json_encode(array('emi_row' => $emi_row, 'loan_row' => $loan_row, 'saving_row' => $saving_row,
						'conf_saving_row' => $conf_saving_row, 'withdraw_row' => $withdraw_row));
		
		
	}
	
	public function fetch_profile_report_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		
		$query = "select *,
				um2.meta_value as first_name, 
				um3.meta_value as last_name,
				um4.meta_value as mobile_no,
				um5.meta_value as address,
				um6.meta_value as photo_url,
				um7.meta_value as id_url
				
				from users u 
				
				left join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				
				left join user_meta um2 on um2.user_id = u.user_id
				and um2.meta_key = 'first_name'
				
				left join user_meta um3 on um3.user_id = u.user_id
				and um3.meta_key = 'last_name'
				
				left join user_meta um4 on um4.user_id = u.user_id
				and um4.meta_key = 'mobile_no'
				
				left join user_meta um5 on um5.user_id = u.user_id
				and um5.meta_key = 'address'
				
				left join user_meta um6 on um6.user_id = u.user_id
				and um6.meta_key = 'photo_url'
				
				left join user_meta um7 on um7.user_id = u.user_id
				and um7.meta_key = 'id_url'
				
				left join clients cst on cst.client_ID=um.meta_value
				
				where u.user_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		$first_name = '';
		$last_name = '';
		$mobile_no = '';
		$address = '';
		$photo_url = '';
		$id_url  = '';
		$email  = '';
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			if(!empty($row->first_name))
				$first_name = $row->first_name;
			if(!empty($row->last_name))
				$last_name = $row->last_name;
			if(!empty($row->mobile_no))
				$mobile_no = $row->mobile_no;
			if(!empty($row->address))
				$address = $row->address;
			if(!empty($row->photo_url))
				$photo_url = '<a title="'.$first_name.' '.$last_name.' (Photo)" href="'.base_url().'uploads/user/'.$row->photo_url.'" rel="prettyPhoto">
				<img src="'.base_url().'uploads/user/'.$row->photo_url.'" width="80" height="80"></a>';
			if(!empty($row->id_url))
				$id_url = '<a title="'.$first_name.' '.$last_name.' (ID)" href="'.base_url().'uploads/user/'.$row->id_url.'" rel="prettyPhoto">
				<img src="'.base_url().'uploads/user/'.$row->id_url.'" width="80" height="80"></a>';
			if(!empty($row->user_email))
				$email = $row->user_email;
		}
		
		/*
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
					<td>'.date('d/m/Y',$row->payment_date).'</td>
					<td>'.$row->deposit_amount.'</td>
					<td>'.ucfirst($row->payment_mode).'</td>
					<td>'.$row->payment_log.'</td>
				</tr>';
			}
		}
		else
		{
			$emi_row = '<tr><td colspan="5">There is no record found.</td></tr>';
		}
		*/
		
		$loan_row = '';
		$query = "select * from loans 
		inner join loan_types on loans.loan_type = loan_types.loan_type_id
		where loans.client_ID = $decId ";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $row)
			{
				$loan_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$row->loan_date).'</td>
					<td>'.$row->principal_amount.'</td>
					<td>'.$row->net_amount.'</td>
					<td>'.ucfirst($row->payment_mode).'</td>
					<td>'.ucfirst($row->loan_title).'</td>
					<td>'.$row->total_amount_deposite.'</td>
					<td>'.($row->net_amount - $row->total_amount_deposite).'</td>
				</tr>';
			}
		}
		else
		{
			$loan_row = '<tr><td colspan="8">There is no record found.</td></tr>';
		}
		
		
		$saving_row = '';
		$query = "select * from capital_account ca
		where ca.client_ID = $decId and
		ca.trans_type = 'saving'";
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
		
		$conf_saving_row = '';
		$query = "
		select ca.*,od.*
		from capital_account ca
		inner join other_deposits od on od.cap_acc_id = ca.cap_acc_id
		where ca.trans_type = 'conf_saving'and ca.client_ID = $decId 
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
		
		$withdraw_row = '';
		$query = "select * from capital_account ca
		where ca.client_ID = $decId and ca.trans_type = 'withdraw'";
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
		/*
		header('Content-type: application/json');				
		echo json_encode(array('emi_row' => $emi_row, 'loan_row' => $loan_row, 'saving_row' => $saving_row,
						'conf_saving_row' => $conf_saving_row, 'withdraw_row' => $withdraw_row));
		
		*/
		header('Content-type: application/json');				
		echo json_encode(array('first_name' => $first_name,
								'last_name' => $last_name,
								'mobile_no' => $mobile_no,
								'address' => $address,
								'photo_url' => $photo_url,
								'id_url'  => $id_url,
								'email'  => $email,
								'loan_row' => $loan_row,
								'saving_row' => $saving_row,
								'conf_saving_row' => $conf_saving_row, 
								'withdraw_row' => $withdraw_row
							));
		
	}
	
	public function fetch_profit_report_callback_func()
	{
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		$decId = $this->DecryptClientId($id);
		date_default_timezone_set('Asia/Kolkata');
		
		$query = "select *,
				um2.meta_value as first_name, 
				um3.meta_value as last_name,
				um4.meta_value as mobile_no,
				um5.meta_value as address,
				um6.meta_value as photo_url,
				um7.meta_value as id_url
				
				from users u 
				
				left join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				
				left join user_meta um2 on um2.user_id = u.user_id
				and um2.meta_key = 'first_name'
				
				left join user_meta um3 on um3.user_id = u.user_id
				and um3.meta_key = 'last_name'
				
				left join user_meta um4 on um4.user_id = u.user_id
				and um4.meta_key = 'mobile_no'
				
				left join user_meta um5 on um5.user_id = u.user_id
				and um5.meta_key = 'address'
				
				left join user_meta um6 on um6.user_id = u.user_id
				and um6.meta_key = 'photo_url'
				
				left join user_meta um7 on um7.user_id = u.user_id
				and um7.meta_key = 'id_url'
				
				left join clients cst on cst.client_ID=um.meta_value
				
				where u.user_id = $decId";
		$result = $this->Common_model->commonQuery($query);
		$first_name = '';
		$last_name = '';
		$mobile_no = '';
		$address = '';
		$photo_url = '';
		$id_url  = '';
		$email  = '';
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			if(!empty($row->first_name))
				$first_name = $row->first_name;
			if(!empty($row->last_name))
				$last_name = $row->last_name;
			if(!empty($row->mobile_no))
				$mobile_no = $row->mobile_no;
			if(!empty($row->address))
				$address = $row->address;
			if(!empty($row->photo_url))
				$photo_url = '<a title="'.$first_name.' '.$last_name.' (Photo)" href="'.base_url().'uploads/user/'.$row->photo_url.'" rel="prettyPhoto">
				<img src="'.base_url().'uploads/user/'.$row->photo_url.'" width="80" height="80"></a>';
			if(!empty($row->id_url))
				$id_url = '<a title="'.$first_name.' '.$last_name.' (ID)" href="'.base_url().'uploads/user/'.$row->id_url.'" rel="prettyPhoto">
				<img src="'.base_url().'uploads/user/'.$row->id_url.'" width="80" height="80"></a>';
			if(!empty($row->user_email))
				$email = $row->user_email;
		}
		
		
		$loan_row = '';
		$query = "select * from loans 
		inner join loan_types on loans.loan_type = loan_types.loan_type_id
		where loans.client_ID = $decId ";
		$result = $this->Common_model->commonQuery($query);
		$grand_total_of_interst = 0;
		if($result->num_rows() > 0)
		{$n=1;
			foreach($result->result() as $single_loan_row)
			{
				$payment_query = "select * from loan_payments 
				where loan_payments.loan_id = $single_loan_row->loan_id and deposit_amount > 0";
				$payment_result = $this->Common_model->commonQuery($payment_query);
				$total_emi_paid = $payment_result->num_rows();
				$total_interest = ((($total_emi_paid * $single_loan_row->emi_amount) * $single_loan_row->interest_rate)/100);
				
				$grand_total_of_interst += $total_interest;
				$loan_row .= '<tr>
					<td>'.$n++.'</td>
					<td>'.date('d/m/Y',$single_loan_row->loan_date).'</td>
					<td>'.$single_loan_row->principal_amount.'</td>
					<td>'.$single_loan_row->net_amount.'</td>
					<td>'.ucfirst($single_loan_row->loan_title).'</td>
					<td>'.$single_loan_row->emi_amount.'</td>
					<td>'.$single_loan_row->interest_rate.'%</td>
					<td>'.$total_emi_paid.'</td>
					<td>'.$total_interest.'</td>
				</tr>';
			}
			$loan_row .= '<tr>
					<td colspan="7"></td>
					<td><strong>Total Profit</strong></td>
					<td>'.$grand_total_of_interst.'</td>
				</tr>';
			
		}
		else
		{
			$loan_row = '<tr><td colspan="8">There is no record found.</td></tr>';
		}
		
		
		
		header('Content-type: application/json');				
		echo json_encode(array('first_name' => $first_name,
								'last_name' => $last_name,
								'mobile_no' => $mobile_no,
								'address' => $address,
								'photo_url' => $photo_url,
								'id_url'  => $id_url,
								'email'  => $email,
								'loan_row' => $loan_row,
								'grand_total_of_interst' => $grand_total_of_interst
							));
		
	}
	
	public function check_username_existence()	{		
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$sql = "select * from users 
				where user_name = '$user_name' ";
		$result = $this->Common_model->commonQuery($sql);
		if($result->num_rows() > 0 )
		{
			echo 'error';
		}
		else
		{
			echo 'success';
		}
	}
	
	public function check_account_no_existence()	{	
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$sql = "select * from users
				inner join user_meta um on um.user_id = users.user_id
				and um.meta_key = 'acc_no' and um.meta_value = '$acc_no' ";
		$result = $this->Common_model->commonQuery($sql);
		if($result->num_rows() > 0 )
		{
			echo 'error';
		}
		else
		{
			echo 'success';
		}
        die;
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
	
	public function print_sql()
	{
		$CI =& get_instance();		
		$this->load->model('Common_model');
		
		
			
		$qry = "select loans.*,cst.* from loans 				
				inner join users u on u.user_id = loans.client_ID
				inner join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				inner join clients cst on cst.client_ID=um.meta_value
				where loans.loan_id = 2";
		
		$qry = "select * from loans 				
				inner join users u on u.user_id = loans.client_ID
				inner join user_meta um on um.user_id = u.user_id
				and um.meta_key = 'client_ID'
				inner join clients cst on cst.client_ID=um.meta_value
				where loans.loan_id = 2";
		
		$qry = "select * from user_meta where user_id = 21";
		$qry = "select * from loans";
		$result = $this->Common_model->commonQuery($qry);
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $row)
			{
				echo '<pre>';
				print_r($row);
				echo '</pre>';
			}
		}
	}
	
	public function empty_db()
	{
		$CI =& get_instance();		
		$this->load->model('Common_model');
		
		$table_list = array('capital_account', 
							'loans', 
							'loan_payments', 
							'users',
							'user_meta',
							'loan_types',
							'branches',
							'clients',
							'other_deposits');
			//'options',				
		foreach($table_list as $k=>$v)
		{
			if($v == 'users' || $v == 'user_meta')
			{
				$qry = "delete from $v where user_id != 1";
			}
			else
			{
				$qry = "truncate $v";
			}
			$this->Common_model->commonQuery($qry);
		}
	}
    
    public function mark_read()
    {
        $this->db->query("UPDATE `notification` SET `notify_read` = 'Y' WHERE client_id = ".$this->input->post('client_id'));
        echo json_encode("1");die;
    }
    
    public function fetch_client_list_by_branch_callback_func()	{		
		extract($_POST);		
		$CI =& get_instance();		
		$this->load->library('global');		
		$this->load->model('Common_model');
		$this->load->helper('text');
		$encId = $this->DecryptClientId($id);
        
        $extraSql = '';
        if($id)
        {
            $extraSql .= ' AND users.branch_id = '.$id;
        }
        
        $client_list = $this->Common_model->commonQuery("
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
                ".$extraSql."
				order by users.user_id DESC
				");
		
		$employee_list = $this->Common_model->commonQuery("
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
                
            $output = '<option value="">Select Borrower/Employee</option>';
            if(isset($client_list) && $client_list->num_rows() > 0)
			{
				$output .= '<optgroup label="Clients">';
				foreach($client_list->result() as $client_row)
				{
					$acc_text ='';
					if(isset($client_row->acc_no) && !empty($client_row->acc_no))
						$acc_text = '('.$client_row->acc_no.')';
					$output .= '<option value="'.$this->EncryptClientId($client_row->user_id).'">'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' '.$acc_text.'</option>';
				}
				$output .= '</optgroup>';
			}
    											
											
			if(isset($employee_list) && $employee_list->num_rows() > 0)
			{
				$output .= '<optgroup label="Employees">';
				foreach($employee_list->result() as $employee_row)
				{
					$acc_text ='';
					if(isset($employee_row->acc_no) && !empty($employee_row->acc_no))
						$acc_text = '('.$employee_row->acc_no.')';
					$output .= '<option value="'.$this->EncryptClientId($employee_row->user_id).'">'.ucfirst($employee_row->first_name).' '.ucfirst($employee_row->last_name).' '.$acc_text.'</option>';
				}
				$output .= '</optgroup>';
			}
				
                
                
		/*$sql = "select * from loans 
				where loans.client_ID = $encId 
				order by loans.loan_id DESC";
		$result = $this->Common_model->commonQuery($sql);
		$output = '<option value="">Select Loan</option>';
		if($result->num_rows() > 0 )
		{
			foreach($result->result() as $client_row)
			{
				$time_periods = ($client_row->time_periods);
				$total_loan_payment_count = $this->Common_model->commonQuery("
						select * from loan_payments 
						where loan_payments.loan_id = $client_row->loan_id and deposit_amount > 0");
				 
				 $emi_paid = $total_loan_payment_count->num_rows();
				 if((($time_periods  - $emi_paid) > 0) )
				{
					$output .= '<option value="'.$this->EncryptClientId($client_row->loan_id).'">'.$client_row->principal_amount.' Tk. / '.$client_row->interest_rate.'% per Year / '.$client_row->time_periods.' Installments</option>';
				}
			}
		}*/
								
		header('Content-type: application/json');				
		echo json_encode(array('clients'=> $output));
		
	}
	function fetch_empolyee_salary(){
		extract($_POST);
		$CI =& get_instance();		
		$this->load->library('global');		
		$CI->load->library('simpleimage');		
		$data['myHelpers']=$this;
		$this->load->model('Common_model');
		$this->load->helper('text');
		
		date_default_timezone_set('Asia/Kolkata');
		$query = "SELECT designation_salary FROM `designation` where id = (select designation_id from users where user_id= $id)";
		$result = $this->Common_model->commonQuery($query);
		
		if($result->num_rows() > 0)
		{	$row = $result->row();
			$amount = $row->designation_salary;
			
		}
		else{

			$amount=0;
		}
		header('Content-type: application/json');				
		echo json_encode(array( 'amount' => $amount
								));
		
	}
	
}
