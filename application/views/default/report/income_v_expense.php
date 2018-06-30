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
	  <h1>  Income VS Expense </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
						<?php 
					$attributes = array('name' => 'add_form_post','class' => 'form');		 			
					echo form_open_multipart('report/income_v_expense',$attributes); ?>							
							<div class="row">
                                <div class="col-md-3">
            						<div class="form-group" >
            						  <label for="timePeriods">Select Branch</label><br />            						  
            						  <select id="branch_id" name="branch_id" class="form-control select2 input-sm">
            								<option value="">Select Branch</option>            							
                                            <?php 
                                            $sqlPro=$this->db->select("*")->from("branches")->get()->result();
                            				foreach($sqlPro as $sqlprow)
                                            {
                                                $selected = '';
                                                if($sqlprow->id == $_REQUEST['branch_id'])
                                                {
                                                    $selected = 'selected="selected"';
                                                }
                            					?>
                    							<option value="<?php echo $sqlprow->id?>" <?php echo $selected;?>><?php echo $sqlprow->branch_name?> / <?php echo $sqlprow->branch_code?></option>
                                                <?php 
                                            }?>
            						  </select>   
            						</div>
            					</div>
								
								<div class="col-md-6">
									<div class="form-group specific_date_container" >
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
                 Income VS Expense <small style="display:inline;">&nbsp;&nbsp;DATE BETWEN 
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
					echo 'Date : '.date('d/m/Y',time()); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
		  
	
          <?php 
		  
		  
		  $total_saving = 0;
		  $total_conf_saving = 0;
		  $total_charges = 0;
		  $total_withdraw = 0;
		  $total_expenses = 0;
		  $total_income = 0;
		  $total_expense_count = 0;
		  $total_loans = 0;
		  $total_emi = 0;
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
				
				
				if($this->session->userdata('user_id') != 1)
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
                
                $branch_id = $this->input->post('branch_id');
                if($branch_id)
                {
                    $where  .= " AND us.branch_id = ".$branch_id." ";
                    $where1 .= " AND us.branch_id = ".$branch_id." ";
                    $where2 .= " AND us.branch_id = ".$branch_id." ";
                }
				
			}
			else
			{
				
				$s_date_explode = explode('/',date('m/d/Y',time()));
				$start_date = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
				$end_date = mktime(23,59,59.999,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
				
				if($this->session->userdata('user_id') != 1)
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
			
			$saving_query = "SELECT sum(payment_in) as payment_in FROM `capital_account` ca inner join users us on ca.client_ID = us.user_id
			where ca.payment_date between '".$start_date."' and '".$end_date."' $where 
			and ca.trans_type = 'saving'";
			
			$today_saving_detail = $this->Common_model->commonQuery($saving_query);
			if($today_saving_detail->num_rows() > 0)
			{
				$today_saving_detail_row = $today_saving_detail->row();
				$total_saving += $today_saving_detail_row->payment_in;
			}
			
			$conf_saving_query = "SELECT sum(payment_in) as payment_in FROM `capital_account` ca inner join users us on ca.client_ID = us.user_id
			where ca.payment_date between '".$start_date."' and '".$end_date."' $where 
			and ca.trans_type = 'conf_saving'";
			
			$today_conf_saving_detail = $this->Common_model->commonQuery($conf_saving_query);
			if($today_conf_saving_detail->num_rows() > 0)
			{
				$today_conf_saving_detail_row = $today_conf_saving_detail->row();
				$total_conf_saving += $today_conf_saving_detail_row->payment_in;
			}
			
			$charges_query = "SELECT sum(payment_in) as payment_in FROM `capital_account` ca inner join users us on ca.client_ID = us.user_id
			where ca.payment_date between '".$start_date."' and '".$end_date."' $where 
			and ca.trans_type = 'charges'";
			
			$today_charges_detail = $this->Common_model->commonQuery($charges_query);
			if($today_charges_detail->num_rows() > 0)
			{
				$today_charges_detail_row = $today_charges_detail->row();
				$total_charges += $today_charges_detail_row->payment_in;
			}
			
			$withdraw_query = "SELECT sum(payment_out) as payment_out FROM `capital_account` ca inner join users us on ca.client_ID = us.user_id
			where ca.payment_date between '".$start_date."' and '".$end_date."' $where 
			and ca.trans_type = 'withdraw'";
			
			$today_withdraw_detail = $this->Common_model->commonQuery($withdraw_query);
			if($today_withdraw_detail->num_rows() > 0)
			{
				$today_withdraw_detail_row = $today_withdraw_detail->row();
				$total_withdraw += $today_withdraw_detail_row->payment_out;
			}
			
			$expenses_query = "SELECT sum(payment_out) as payment_out FROM `capital_account` ca inner join users us on ca.client_ID = us.user_id
			where ca.payment_date between '".$start_date."' and '".$end_date."' $where 
			and ca.trans_type = 'expense'";
			
			$today_expenses_detail = $this->Common_model->commonQuery($expenses_query);
			if($today_expenses_detail->num_rows() > 0)
			{
				$today_expenses_detail_row = $today_expenses_detail->row();
				$total_expenses += $today_expenses_detail_row->payment_out;
			}
			
			$loan_query = "select sum(principal_amount) as principal_amount
			from loans 
            inner join users us on loans.client_ID = us.user_id
			where loans.loan_date between '".$start_date."' and '".$end_date."' $where
			";
            //echo $loan_query;die;
			$today_loan_detail = $this->Common_model->commonQuery($loan_query);
			if($today_loan_detail->num_rows() > 0)
			{
				$today_loan_detail_row = $today_loan_detail->row();
				$total_loans += $today_loan_detail_row->principal_amount;
			}
			
			$emi_query = "select *,sum(deposit_amount) as total_emi_received
			from 
			loan_payments lp 
            inner join users us on lp.client_ID = us.user_id
			where  lp.payment_date between '".$start_date."' and '".$end_date." $where'
			group by lp.loan_id
			";
			$today_emi_detail = $this->Common_model->commonQuery($emi_query);
			if($today_emi_detail->num_rows() > 0)
			{
				$today_emi_detail_row = $today_emi_detail->row();
				$total_emi += $today_emi_detail_row->total_emi_received;
			}
			
		  ?>
		 <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table"><!--table-striped-->
              <thead>
                <tr>
                  <th>S.No.</th>
				  <th>Income</th>
				  <th align="right" style="text-align:right;">Amount</th>
				  <th>S.No.</th>
				  <th>Expenses</th>
				  <th align="right" style="text-align:right;">Amount</th>
				</tr>
              </thead>
              <tbody>
				<tr>
					<td>1</td>
					<td>Saving </td>
					<td align="right"><?php echo $total_saving; 
						$total_income += $total_saving;?>
					</td>
					<td>1</td>
					<td>Withdraw
					</td>
					<td align="right"><?php echo $total_withdraw; 
								$total_expense_count += $total_withdraw;
					?></td>
				</tr>
				<tr>
					<td>2</td>
					<td>Confidential Saving </td>
					<td align="right"><?php echo $total_conf_saving; 
						$total_income += $total_conf_saving;
					?></td>
					<td>2</td>
					<td>Expenses</td>
					<td align="right"><?php echo $total_expenses; 
								$total_expense_count += $total_expenses;
					?></td>
				</tr>
				<tr>
					<td>3</td>
					<td>Charges</td>
					<td align="right"><?php echo $total_charges; 
						$total_income += $total_charges;
					?></td>
					<td>3</td>
					<td>Loan</td>
					<td align="right">
					<?php echo $total_loans; 
								$total_expense_count += $total_loans;
					?>
					</td>
				</tr>
				<tr>
					<td>4</td>
					<td>EMI</td>
					<td align="right"><?php echo $total_emi; 
						$total_income += $total_emi;
					?></td>
					<td></td>
					<td></td>
					<td align="right"></td>
				</tr>
			  </tbody>
			  <tfoot>
				<tr>
					<td></td>
					<td style="text-align:right;"><strong>Total Income</strong></td>
					<td align="right"><strong><?php echo $total_income;?></strong></td>
					<td></td>
					<td style="text-align:right;"><strong>Total Expenses</strong></td>
					<td align="right"><strong><?php echo $total_expense_count; ?></strong></td>
				</tr>
				<tr>
					<td></td>
					<td style="text-align:right;"><strong>Loss</strong></td>
					<td align="right"><strong><?php 
					$loss = ($total_expense_count - $total_income); 
						if($loss > 0)
							echo $loss;
						else
							echo '0';
					?></strong></td>
					<td></td>
					<td style="text-align:right;"><strong>Profit</strong></td>
					<td align="right"><strong>
					<?php $profit = ($total_income - $total_expense_count); 
						if($profit > 0)
							echo $profit;
						else
							echo '0';
					?></strong></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td align="right" style="border-top: 1px solid; border-bottom: 1px solid;"><strong><?php echo $total_income;?></strong></td>
					<td></td>
					<td></td>
					<td align="right" style="border-top: 1px solid; border-bottom: 1px solid;"><strong><?php echo $total_income; ?></strong></td>
				</tr>
			  </tfoot>
            </table>
          </div>
        </div>
			
	
			
        </div>
		<br>
		<div class="row no-print">
            <div class="col-xs-12">
			
			 <a href="#" onclick="window.print();return false;" class="btn btn-primary pull-right">Print <i class="fa fa-print"></i> </a>
             
            </div>
          </div>
		  
		</section><!-- /.content -->
		
		
		
		
          
		
		
  </div><!-- /.content-wrapper -->
  