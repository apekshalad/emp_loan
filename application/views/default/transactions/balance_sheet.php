<style type="text/css">
@media print{
	
	.content-wrapper {width:100%;}
	.content, .invoice .row .col-xs-12{padding:0;}
	.invoice{
		padding:0;
		/*border:1px solid #ccc;*/
		/*margin-left:-230px;*/
	}
	.invoice .row{
		margin-left:0;
		margin-right:0;
	}
	.content-wrapper{
		margin-left:0px;
	}
	
}
</style>
<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

	
<style>
.grand_total {
    border-bottom: 1px dashed;
    border-top: 1px dashed;
    margin-top: 5px;
    padding-bottom: 10px;
    padding-top: 10px;
}
@media print{
	.cash_book_detail .col-md-12{
		padding:0px;
	}
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Balance Sheet </h1>
	  
	</section>

	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
						<?php 
					$attributes = array('name' => 'add_form_post','class' => 'form');		 			
					echo form_open_multipart('transactions/balance_sheet',$attributes); ?>
							
							<div class="row">
								
								<div class="col-md-12">
									<div class="form-group specific_date_container" style="width:50%;">
										<label>Date</label>
										<div class="input-group">

										  <div class="input-group-addon">

											<i class="fa fa-calendar"></i>

										  </div>
										  
										  <input type="text" class="form-control pull-right" id="reservation" name="loan_dates" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { echo date('m/d/Y - m/d/Y',time()); } ?>">
										  
										</div><!-- /.input group -->
										
									  </div>
								</div>
								
								
								<div class="col-md-12">
									<div class="">
									  <input type="submit" class="btn" name="submit" >					  
									</div>
								</div>
							 </div>
						</form>
				</div>
			</div>
			</div>
		</div>
	</section>
	
	
	<!-- Main content -->
        <section class="content">
			<div class="invoice">
          <!-- title row -->
          
		  <div class="row">
            <div class="col-xs-12">
				
			  
              <h2 class="page-header">
                Balance Sheet <small style="display:inline;">&nbsp;&nbsp;DATE BETWEN 
				<?php
					if(isset($current_date) && !empty($current_date)) 
					{
						$date_explode = explode('-',$current_date);
						echo '<strong>'.trim($date_explode[0]).'</strong> TO <strong>'.trim($date_explode[1]).'</strong>';
					}
					else if(isset($_POST['loan_dates']) && !empty($_POST['loan_dates']))
					{	
						$date_explode = explode('-',$_POST['loan_dates']);
						echo '<strong>'.trim($date_explode[0]).'</strong> TO <strong>'.trim($date_explode[1]).'</strong>';
					}
					else
					{	
						echo '<strong>'.date('m/d/Y',time()).'</strong> TO <strong>'.date('m/d/Y',time()).'</strong>';
					}
						
				?>
				</small>
                <small class="pull-right">
				<?php 
					echo 'Date :- '.date('d/m/Y',time()); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
		  
	
          <?php 
		 
		  
			$cash_conf_saving_amount_count = 0;
			$cheque_conf_saving_amount_count = 0;
			$loan_cash_emi_count = 0;
			$loan_cheque_emi_count = 0;
			$loan_cash_saving_count = 0;
			$loan_cheque_saving_count = 0;
			$total_loan_count = 0;
			$total_withdraw = 0;
			$total_withdraw_cheque = 0;
			$total_cash_income = 0;
			$total_cheque_income = 0;
			$total_cash_expenses = 0;
			$total_cheque_expenses = 0;
			$bank_opening_balance = 0;
			$total_expenses_cash = 0;
			$total_expenses_cheque = 0;
			$total_cash_charges = 0;
			$total_cheque_charges = 0;
			$capital_account = 0;
			$total_bank_loan_count = 0;
		  if(isset($_POST['submit']))
			{
				$date_range = $_POST['loan_dates'];
			
				$date_explode = explode('-',$date_range);
				$s_date = trim($date_explode[0]);
				$s_date_explode = explode('/',$s_date);
				$start_date = mktime(0,0,0,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
				
				$e_date = trim($date_explode[1]);
				$e_date_explode = explode('/',$e_date);
				$end_date = mktime(23,59,59,$e_date_explode[0],$e_date_explode[1],$e_date_explode[2]);
				
				
				if($this->session->userdata('user_type') == 'user')
				{
					$where = " and user_id = ".$this->session->userdata('user_id')." ";
					$where1 = " and loans.user_id = ".$this->session->userdata('user_id')." ";
					$where2 = " and loan_payments.user_id = ".$this->session->userdata('user_id')." ";
				}
				else
				{
					
					$where = "";
					$where1 = "";
					$where2 = "";
					
					
				}
				
			}
			else
			{
				
				$s_date_explode = explode('/',date('m/d/Y',time()));
				$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
				$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
				
				if($this->session->userdata('user_type') == 'user')
				{
					$where = " and user_id = ".$this->session->userdata('user_id')." ";
					$where1 = " and loans.user_id = ".$this->session->userdata('user_id')." ";
					$where2 = " and loan_payments.user_id = ".$this->session->userdata('user_id')." ";
				}
				else
				{
					$where = "";
					$where1 = "";
					$where2 = "";
				}
				
			}
			
			/*Emi */
			$loan_emi_query = "SELECT IFNULL(sum(payment_in),0) as total_emi_deposit FROM `capital_account` ca
			where trans_type = 'emi' and payment_method = 'cash' and ca.payment_date between '".$start_date."' and '".$end_date."' $where";
			
			$loan_emi_cash_detail = $this->Common_model->commonQuery($loan_emi_query);
			if($loan_emi_cash_detail->num_rows() > 0)
			{
				$t_c_r_d_row = $loan_emi_cash_detail->row();
				$loan_cash_emi_count += $t_c_r_d_row->total_emi_deposit;
			}
			
			$loan_emi_query = "SELECT IFNULL(sum(payment_in),0) as total_emi_deposit FROM `capital_account` ca
			where trans_type = 'emi' and payment_method = 'cheque' and ca.payment_date between '".$start_date."' and '".$end_date."' $where";
			
			$loan_emi_cheque_detail = $this->Common_model->commonQuery($loan_emi_query);
			if($loan_emi_cheque_detail->num_rows() > 0)
			{
				$t_c_r_d_row = $loan_emi_cheque_detail->row();
				$loan_cheque_emi_count += $t_c_r_d_row->total_emi_deposit;
			}
			
			
			/*Loans*/
			$loan_query = "SELECT IFNULL(sum(payment_out),0) as total_principal_amount FROM `capital_account` ca
			where trans_type = 'loan' and payment_method = 'cash' and ca.payment_date between '".$start_date."' and '".$end_date."' $where";
			
			$today_loan_detail = $this->Common_model->commonQuery($loan_query);
			if($today_loan_detail->num_rows() > 0)
			{
				$today_loan_detail_row = $today_loan_detail->row();
				$total_loan_count += $today_loan_detail_row->total_principal_amount;
			}
			
			$loan_query = "SELECT IFNULL(sum(payment_out),0) as total_principal_amount FROM `capital_account` ca
			where trans_type = 'loan' and payment_method = 'cheque' and ca.payment_date between '".$start_date."' and '".$end_date."' $where";
			
			$today_loan_detail = $this->Common_model->commonQuery($loan_query);
			if($today_loan_detail->num_rows() > 0)
			{
				$today_loan_detail_row = $today_loan_detail->row();
				$total_bank_loan_count += $today_loan_detail_row->total_principal_amount;
			}
			
			/*capital account*/
			$capital_amount_payment_in_query = "select IFNULL(sum(payment_in),0) as payment_in,
			IFNULL(sum(payment_out),0) as payment_out
			from capital_account 
			where payment_date < $start_date $where and payment_method = 'cash' and 
			(trans_type = 'saving' or trans_type = 'conf_saving' or 
			trans_type = 'withdraw' or (trans_type = 'expense' and trans_from != 'conf_saving') 
			or (trans_type = 'charges' and ( trans_from != 'saving' or trans_from != 'conf_saving'))
			or trans_type = 'capital' or trans_type = 'emi' or trans_type = 'loan' 
			or trans_type = 'transfer')";
										
										
			$capital_amount_row = $this->Common_model->commonQuery($capital_amount_payment_in_query);
			
			if($capital_amount_row->num_rows() > 0)
			{
				$cur_row = $capital_amount_row->row();
				$cur_payment_in = 	$cur_row->payment_in;	
				$cur_payment_out = 	$cur_row->payment_out;	
				$capital_account += (($cur_payment_in) - ($cur_payment_out));
			}
			
			/**		capital account2	*/
			$capital_current_amount_payment_in_query = "select IFNULL(sum(payment_in),0) as cur_capital,	
			IFNULL(sum(payment_out),0) as payment_out
			from capital_account 
			where payment_date between $start_date and  $end_date and trans_type = 'capital' 
			and payment_method = 'cash'
			$where";
										
										
			$cur_capital_amount_row = $this->Common_model->commonQuery($capital_current_amount_payment_in_query);
			if($cur_capital_amount_row->num_rows() > 0)
			{
				$cur_cap_row = $cur_capital_amount_row->row();
				$capital_account  += $cur_cap_row->cur_capital;	
			}
			
			/**		capital account3	*/
			$capital_current_amount_payment_in_query = "select IFNULL(sum(payment_in),0) as payment_in,	
			IFNULL(sum(payment_out),0) as payment_out
			from capital_account 
			where payment_date between $start_date and  $end_date and trans_type = 'transfer' 
			and payment_method = 'cash' and trans_from = 'ctob'
			$where  ";
										
										
			$cur_capital_amount_row = $this->Common_model->commonQuery($capital_current_amount_payment_in_query);
			if($cur_capital_amount_row->num_rows() > 0)
			{
				
				$cur_cap_row = $cur_capital_amount_row->row();
				$capital_account  -= ($cur_cap_row->payment_out);	
			}
			
			/*bank book opening balance*/
			$total_bank_deposite = $this->Common_model->commonQuery("
				select IFNULL(sum(payment_in),0) as payment_in,	IFNULL(sum(payment_out),0) as payment_out
				from capital_account 
				where payment_date < $start_date $where and payment_method = 'cheque' and 
				(trans_type = 'saving' or trans_type = 'conf_saving' or 
				trans_type = 'withdraw' or (trans_type = 'expense' and trans_from != 'conf_saving') 
				or (trans_type = 'charges' and (trans_from != 'saving' or trans_from != 'conf_saving')) 
				or trans_type = 'capital'  or trans_type = 'emi' or trans_type = 'loan' 
				or trans_type = 'transfer')");
				
				
				
			if($total_bank_deposite->num_rows() > 0)
			{
				$cur_row = $total_bank_deposite->row();
				$cur_payment_in = 	$cur_row->payment_in;	
				$cur_payment_out = 	$cur_row->payment_out;	
				$bank_opening_balance += (($cur_payment_in) - ($cur_payment_out));
			}
			
			/**		bank account 2	*/
			$capital_current_amount_payment_in_query = "select IFNULL(sum(payment_in),0) as cur_capital,	
			IFNULL(sum(payment_out),0) as payment_out
			from capital_account 
			where payment_date between $start_date and  $end_date and trans_type = 'capital' 
			and payment_method = 'cheque'
			$where  ";
										
										
			$cur_capital_amount_row = $this->Common_model->commonQuery($capital_current_amount_payment_in_query);
			if($cur_capital_amount_row->num_rows() > 0)
			{
				
				$cur_cap_row = $cur_capital_amount_row->row();
				$bank_opening_balance  += $cur_cap_row->cur_capital;	
			}
			
			/**		bank account 3	*/
			$capital_current_amount_payment_in_query = "select IFNULL(sum(payment_in),0) as payment_in,	
			IFNULL(sum(payment_out),0) as payment_out
			from capital_account 
			where payment_date between $start_date and  $end_date and trans_type = 'transfer' 
			and payment_method = 'cheque' and trans_from = 'btoc'
			$where  ";
										
										
			$cur_capital_amount_row = $this->Common_model->commonQuery($capital_current_amount_payment_in_query);
			if($cur_capital_amount_row->num_rows() > 0)
			{
				
				$cur_cap_row = $cur_capital_amount_row->row();
				$bank_opening_balance  -= ($cur_cap_row->payment_out) ;	
			}
			
			
			
			/*cash expenses*/
			$loan_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and payment_method= 'cash'";
			
			$today_loan_detail = $this->Common_model->commonQuery($loan_query);
			if($today_loan_detail->num_rows() > 0)
			{
				$today_loan_detail_row = $today_loan_detail->row();
				$total_expenses_cash += $today_loan_detail_row->payment_out;
			}
			/*cheque expenses*/
			$loan_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and payment_method= 'cheque'";
			
			$today_loan_detail = $this->Common_model->commonQuery($loan_query);
			if($today_loan_detail->num_rows() > 0)
			{
				$today_loan_detail_row = $today_loan_detail->row();
				$total_expenses_cheque += $today_loan_detail_row->payment_out;
			}
			
			
			
			$sav_row = '';
			$borrower_cash_saving = 0;
			$borrower_cheque_saving = 0;
			
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'saving' and cap_by = 'borrower' and payment_method= 'cash'
			) as total_general_cash_saving
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'saving' and cap_by = 'borrower' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in -$exps_row->total_general_cash_saving ) > 0)
						$borrower_cash_saving += ($exps_row->payment_in -$exps_row->total_general_cash_saving );
					else
						$borrower_cash_saving += 0;
				}
				$loan_cash_saving_count += $borrower_cash_saving;
			}
			
			
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'saving' and cap_by = 'borrower' and payment_method= 'cheque'
			) as total_general_cheque_saving
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'saving' and cap_by = 'borrower' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in - $exps_row->total_general_cheque_saving) > 0)
						$borrower_cheque_saving += ($exps_row->payment_in - $exps_row->total_general_cheque_saving);
					else
						$borrower_cheque_saving += 0;
				}
				$loan_cheque_saving_count += $borrower_cheque_saving;
			}
			$sav_row .= '<tr><td style="padding-left:20px;">Borrowers</td><td align="right">'.$borrower_cash_saving.'</td>
						<td align="right">'.$borrower_cheque_saving.'</td></tr>';
			
			$employee_cash_saving = 0;
			$employee_cheque_saving = 0;
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'saving' and cap_by = 'employee' and payment_method= 'cash'
			) as total_cheque_saving
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'saving' and cap_by = 'employee' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in - $exps_row->total_cheque_saving) > 0)
						$employee_cash_saving += ($exps_row->payment_in - $exps_row->total_cheque_saving);
					else
						$employee_cash_saving += 0;
				}
				$loan_cash_saving_count += $employee_cash_saving;
			}
			
			
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'saving' and cap_by = 'employee' and payment_method= 'cheque'
			) as total_cheque_saving
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'saving' and cap_by = 'employee' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in - $exps_row->total_cheque_saving) > 0)
						$employee_cheque_saving += ($exps_row->payment_in - $exps_row->total_cheque_saving);
					else
						$employee_cheque_saving += 0;
				}
				$loan_cheque_saving_count += $employee_cheque_saving;
			}
			
			
			$sav_row .= '<tr><td style="padding-left:20px;">Employees</td><td align="right">'.$employee_cash_saving.'</td>
					<td align="right">'.$employee_cheque_saving.'</td></tr>';
			
			$conf_sav_row = '';
			$borrower_cash_conf_saving = 0;
			$borrower_cheque_conf_saving = 0;
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cash'
			) as total_cash_conf_saving,
			(
			SELECT IFNULL(sum(payment_out),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and trans_from = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cash'
			) as total_conf_cash_expense
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in + $exps_row->total_conf_cash_expense) > 0)
						$borrower_cash_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cash_expense)- $exps_row->total_cash_conf_saving);
					else
						$borrower_cash_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cash_expense));
				}
				$cash_conf_saving_amount_count += $borrower_cash_conf_saving;
			}
			
		
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cheque'
			) as total_cheque_conf_saving,
			(
			SELECT IFNULL(sum(payment_out),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and trans_from = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cheque'
			) as total_conf_cheque_expense
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'conf_saving' and cap_by = 'borrower' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in + $exps_row->total_conf_cheque_expense) > 0)
						$borrower_cheque_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cheque_expense) - $exps_row->total_cheque_conf_saving);
					else
						$borrower_cheque_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cheque_expense));
				}
				$cheque_conf_saving_amount_count += $borrower_cheque_conf_saving;
			}
			
			
			
			$conf_sav_row .= '<tr><td style="padding-left:20px;">Borrowers</td>
						<td align="right">'.$borrower_cash_conf_saving.'</td>
						<td align="right">'.$borrower_cheque_conf_saving.'</td></tr>';
			
			$employee_cash_conf_saving = 0;
			$employee_cheque_conf_saving = 0;
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'conf_saving' and cap_by = 'employee' and payment_method= 'cash'
			) as total_cash_conf_saving,
			(
			SELECT IFNULL(sum(payment_out),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and trans_from = 'conf_saving' and cap_by = 'employee' and payment_method= 'cash'
			) as total_conf_cash_expense
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'conf_saving' and cap_by = 'employee' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in + $exps_row->total_conf_cash_expense) > 0)
						$employee_cash_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cash_expense) - $exps_row->total_cash_conf_saving);
					else
						$employee_cash_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cash_expense));
				}
				$cash_conf_saving_amount_count += $employee_cash_conf_saving;
			}
			
			
			
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in,
			(
			SELECT IFNULL(sum(payment_in),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and trans_from = 'conf_saving' and cap_by = 'employee' and payment_method= 'cheque'
			) as total_cheque_conf_saving,
			(
			SELECT IFNULL(sum(payment_out),0)
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'expense' and trans_from = 'conf_saving' and cap_by = 'employee' and payment_method= 'cheque'
			) as total_conf_cheque_expense
			FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'conf_saving' and cap_by = 'employee' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{ 
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					if(($exps_row->payment_in + $exps_row->total_conf_cheque_expense) > 0)
						$employee_cheque_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cheque_expense) - $exps_row->total_cheque_conf_saving);
					else
						$employee_cheque_conf_saving += (($exps_row->payment_in + $exps_row->total_conf_cheque_expense));
				}
				$cheque_conf_saving_amount_count += $employee_cheque_conf_saving;
			}
			
			
			$conf_sav_row .= '<tr><td style="padding-left:20px;">Employees</td>
							<td align="right">'.$employee_cash_conf_saving.'</td>
							<td align="right">'.$employee_cheque_conf_saving.'</td></tr>';
			
			
			$charges_row = '';
			$borrower_cash_charges = 0;
			$borrower_cheque_charges = 0;
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and cap_by = 'borrower' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$borrower_cash_charges += $exps_row->payment_in;
				}
				$total_cash_charges += $borrower_cash_charges;
			}
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and cap_by = 'borrower' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$borrower_cheque_charges += $exps_row->payment_in;
				}
				$total_cheque_charges += $borrower_cheque_charges;
			}
			$charges_row .= '<tr><td style="padding-left:20px;">Borrowers</td>
							<td align="right">'.$borrower_cash_charges.'</td>
							<td align="right">'.$borrower_cheque_charges.'</td></tr>';
			
			$employee_cash_charges = 0;
			$employee_cheque_charges = 0;
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and cap_by = 'employee' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$employee_cash_charges += $exps_row->payment_in;
				}
				$total_cash_charges += $employee_cash_charges;
			}
			$expenses_query = "SELECT IFNULL(sum(payment_in),0) as payment_in FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'charges' and cap_by = 'employee' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$employee_cheque_charges += $exps_row->payment_in;
				}
				$total_cheque_charges += $employee_cheque_charges;
			}
				$charges_row .= '<tr><td style="padding-left:20px;">Employees</td>
				<td align="right">'.$employee_cash_charges.'</td>
				<td align="right">'.$employee_cheque_charges.'</td></tr>';
			
			
			$withdraw_row = '';
			$borrower_cash_withdraw = 0;
			$borrower_cheque_withdraw = 0;
			$expenses_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'withdraw' and trans_from != 'charges' and cap_by = 'borrower' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$borrower_cash_withdraw += $exps_row->payment_out;
				}
				$total_withdraw += $borrower_cash_withdraw;
			}
			$expenses_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'withdraw' and trans_from != 'charges' and cap_by = 'borrower' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$borrower_cheque_withdraw += $exps_row->payment_out;
				}
				$total_withdraw_cheque += $borrower_cheque_withdraw;
			}
			$withdraw_row .= '<tr><td style="padding-left:20px;">Borrowers</td>
					<td align="right">'.$borrower_cash_withdraw.'</td>
					<td align="right">'.$borrower_cheque_withdraw.'</td></tr>';
			
			$employee_cash_withdraw = 0;
			$employee_cheque_withdraw = 0;
			$expenses_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'withdraw' and trans_from != 'charges' and cap_by = 'employee' and payment_method= 'cash'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$employee_cash_withdraw += $exps_row->payment_out;
				}
				$total_withdraw += $employee_cash_withdraw;
			}
			$expenses_query = "SELECT IFNULL(sum(payment_out),0) as payment_out FROM `capital_account` 
			where payment_date between '".$start_date."' and '".$end_date."' $where 
			and trans_type = 'withdraw' and trans_from != 'charges' and cap_by = 'employee' and payment_method= 'cheque'";
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				
				foreach($today_expenses_detail->result() as $exps_row)
				{
					$employee_cheque_withdraw += $exps_row->payment_out;
				}
				$total_withdraw_cheque += $employee_cheque_withdraw;
			}
			$withdraw_row .= '<tr><td style="padding-left:20px;">Employees</td>
							<td align="right">'.$employee_cash_withdraw.'</td>
							<td align="right">'.$employee_cheque_withdraw.'</td></tr>';
			
			
		  ?>
			
         
         <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table <!--table-striped-->">
              <thead>
                <tr>
                  <th style="width:30%;">Received</th>
				  <th style="text-align:right;width:10%;">Cash</th>
				  <th style="text-align:right;width:10%">Bank</th>
				  <th style="width:30%;">Payment</th>
				  <th style="text-align:right;width:10%;">Cash</th>
				  <th style="text-align:right;width:10%">Bank</th>
				</tr>
              </thead>
              <tbody>
			  
				<tr>
					<td>Opening Balance</td>
					<td style="text-align:right;"><?php echo $capital_account;$total_cash_income += $capital_account; ?></td>
					<td style="text-align:right;"><?php echo $bank_opening_balance; $total_cheque_income += $bank_opening_balance;?></td>
					<td>Loan</td>
					<td style="text-align:right;">
						<?php 
							echo $total_loan_count; 
							$total_cash_expenses += $total_loan_count;
						?></td>
					<td style="text-align:right;">
						<?php 
							echo $total_bank_loan_count; 
							$total_cheque_expenses += $total_bank_loan_count;
						?>
					</td>
				</tr>
				
				<tr>
					<td>Saving
						<table style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th style="text-align:right;">Cash</th>
									<th style="text-align:right;">Bank</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $sav_row; ?>
							</tbody>
						</table>
					</td>
					<td style="text-align:right;">
						<?php 
							echo $loan_cash_saving_count; 
							$total_cash_income += $loan_cash_saving_count;
						?>
					</td>
					<td style="text-align:right;">
						<?php 
							echo $loan_cheque_saving_count; 
							$total_cheque_income += $loan_cheque_saving_count;
						?>
					</td>
					<td>Withdraw
						<table style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th style="text-align:right;">Cash</th>
									<th style="text-align:right;">Bank</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $withdraw_row; ?>
							</tbody>
						</table>
					</td>
					<td style="text-align:right;">
						<?php 
							echo $total_withdraw; 
							$total_cash_expenses += $total_withdraw;
						?>
					</td>
					<td style="text-align:right;">
						<?php 
							echo $total_withdraw_cheque;
							$total_cheque_expenses += $total_withdraw_cheque;
						?>
					</td>
				</tr>
				
				<tr>
					<td>Confidential Saving
						<table style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th style="text-align:right;">Cash</th>
									<th style="text-align:right;">Bank</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $conf_sav_row; ?>
							</tbody>
						</table>
					</td>
					<td style="text-align:right;"><?php echo $cash_conf_saving_amount_count; 
												$total_cash_income += $cash_conf_saving_amount_count;?></td>
					<td style="text-align:right;"><?php 
											echo $cheque_conf_saving_amount_count; 
											$total_cheque_income += $cheque_conf_saving_amount_count; ?></td>
					<td>Expenses</td>
					<td style="text-align:right;">
						<?php 
							echo $total_expenses_cash; 
							$total_cash_expenses += $total_expenses_cash;
						?>
					</td>
					<td style="text-align:right;">
						<?php 
							echo $total_expenses_cheque;
							$total_cheque_expenses += $total_expenses_cheque;
						?>
					</td>
				</tr>
				<tr>
					<td>Charges
						<table style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th style="text-align:right;">Cash</th>
									<th style="text-align:right;">Bank</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $charges_row; ?>
							</tbody>
						</table>
					</td>
					<td style="text-align:right;"><?php echo $total_cash_charges;
											$total_cash_income += $total_cash_charges;?></td>
					<td style="text-align:right;"><?php echo $total_cheque_charges; 
											$total_cheque_income += $total_cheque_charges;?></td>
					<td></td>
					<td style="text-align:right;"></td>
					<td style="text-align:right;"></td>
				</tr>
				<tr>
					<td>Loan EMI</td>
					<td style="text-align:right;"><?php echo $loan_cash_emi_count;
											$total_cash_income += $loan_cash_emi_count;?></td>
					<td style="text-align:right;"><?php echo $loan_cheque_emi_count; 
											$total_cheque_income += $loan_cheque_emi_count;?></td>
					<td></td>
					<td style="text-align:right;"></td>
					<td style="text-align:right;"></td>
				</tr>
				
              </tbody>
			  <tfoot>
				<tr>
					<td style="text-align:right;"><strong>Total Income</strong></td>
					<td style="text-align:right;"><strong><?php echo $total_cash_income; ?></strong></td>
					<td style="text-align:right;"><strong><?php echo $total_cheque_income;?></strong></td>
					<td style="text-align:right;"><strong>Total Expenses</strong></td>
					<td style="text-align:right;"><strong><?php echo $total_cash_expenses; ?></strong></td>
					<td style="text-align:right;"><strong><?php echo $total_cheque_expenses; ?></strong></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:right;"><strong>Closing Balance</strong></td>
					<td style="text-align:right; border-top: 1px solid; border-bottom: 1px solid;"><strong><?php echo ($total_cash_income - $total_cash_expenses); ?></strong></td>
					<td style="text-align:right; border-top: 1px solid; border-bottom: 1px solid;"><strong><?php echo ($total_cheque_income - $total_cheque_expenses); ?></strong></td>
					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:right;"><strong>Total Closing Balance (Cash + Bank)</strong></td>
					<td style="border-bottom: 1px solid;"></td>
					<td style="text-align:right; border-top: 1px solid; border-bottom: 1px solid;"><strong><?php echo ($total_cash_income - $total_cash_expenses) + ($total_cheque_income - $total_cheque_expenses); ?></strong></td>
					
				</tr>
			  </tfoot>
            </table>
          </div>
        </div>
		
		
	
        </div>
		</section><!-- /.content -->
		
		
		
		<section class="content">
			 <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
			
			 <a href="#" onclick="window.print();return false;" class="btn btn-primary pull-right">Print <i class="fa fa-print"></i> </a>
             
            </div>
          </div>
		</section>
		
  </div><!-- /.content-wrapper -->
  