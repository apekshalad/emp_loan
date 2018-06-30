<style type="text/css" media="print">
/*@page {
    size: auto; */  /* auto is the initial value */
    /*margin: 0; */ /* this affects the margin in the printer settings */
/*}*/
</style>
  <?php $this->load->view("default/header-top");?>
  
  <?php $this->load->view("default/sidebar-left");?>

<?php 
	if($customer_loan_detail->num_rows() > 0)
	{
		$row = $customer_loan_detail->row();
		
		//echo "<pre>";
		//print_r($row);
		//echo "</pre>";
		
		$loan_id = $row->loan_id;
		$customer_ID  =  $row->client_ID;
		$customer_name = $row->client_name;
		$customer_address = $row->client_address;
		$customer_mobile = $row->client_mobile;
		$customer_acc_no = $row->client_acc_no;
		$principal_amount = $row->principal_amount;
		$interest_rate = $row->interest_rate;
		$net_amount = $row->net_amount;
		$time_periods = $row->time_periods;
		//$loan_no = $row->loan_no;
		$loan_type = $row->loan_type_text;
		$loan_date = $row->loan_date;
		$emi_amount = $row->emi_amount;
		$photo_url = $row->client_photo_proof;
		$total_amount_deposite = $row->total_amount_deposite;
	}
	else
	{
		$customer_name = '';
		$customer_address = '';
		$customer_mobile = '';
		$customer_acc_no = '';
		$principal_amount = '';
		$interest_rate = '';
		$net_amount = '';
		$time_periods = '';
		//$loan_no = '';
		$loan_type = '';
		$emi_amount = '';
		$loan_date = '';
	}
	
	

				//$totalInterest = round(( $principal_amount  * $interest_rate) / 100);
				$totalInterest = ($emi_amount * $time_periods) - $principal_amount;
				$total_amount = $principal_amount + $totalInterest;
				$netAmt = $principal_amount  -  ((1*$emi_amount));
	$deposited_amount = 0;
	$sql = "select * from loan_payments 
			where loan_id = $loan_id";
	
	$loan_payments_data = $myHelpers->Common_model->commonQuery("$sql");
	if($loan_payments_data->num_rows() > 0)
	{
		foreach($loan_payments_data->result() as $loan_payments_row)
		{
			
			$deposited_amount += round($loan_payments_row->deposit_amount);
		}	
	}
	?>
	<style>
	.gallery > a {
		float: left;
		margin-right: 20px;
	}
	</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Transaction Invoice </h1>
	  
	</section>

	<!-- Main content -->
        <section class="content">
			<div class="invoice">
          
          <!-- info row -->
          <div class="row invoice-info">
            
			 <div class="col-xs-12 table-responsive">
				<table class="table ">
				  <thead>
					<tr>
					  <th>Client Details </th>
					  <th>Account Details </th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td>
							<address class="gallery">
								<?php if(isset($photo_url) && !empty($photo_url)) { ?>
									<a title="<?php echo $customer_name; ?>" href="<?php echo base_url().'uploads/client/'.$photo_url; ?>" rel="prettyPhoto">
									<img src="<?php echo base_url().'uploads/client/'.$photo_url; ?>" height="150" width="150">
									</a>
								<?php } ?>
								<strong><?php echo $customer_name; ?></strong><br>
								<?php echo $customer_address; ?><br>
								Phone: <?php echo $customer_mobile; ?><br>
								Email: 
							  </address>
						</td>
						
						
						<td>
							<b>Account No.:</b> <?php echo $customer_acc_no; ?><br>
							  <b>Account Opening Date:</b> <?php echo date('d/m/Y',$loan_date); ?><br>
							  <b>Account End Date:</b> 
							  <?php  
								echo $end_date = date("d/m/Y", strtotime('+'.$time_periods.' month', $loan_date));
							  ?><br>
							  <b>Loan Type:</b> <?php echo ucfirst($loan_type); ?><br>
						</td>
					</tr>
				  </tbody>
				</table>
			</div>
			
			
            
			
            
          </div><!-- /.row -->

         <!-- Table row -->
         <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  
				  <th>S. No.</th>
				  <th>Payment Date</th>
				  <th>Due Date</th>
				  <th>EMI <?php echo $myHelpers->config->item('cur_symbol'); ?> </th>
				  <th>Deposited EMI <?php echo $myHelpers->config->item('cur_symbol'); ?> </th>
				  <th>Dued <?php echo $myHelpers->config->item('cur_symbol'); ?> </th>
				  <th>Deposited <?php echo $myHelpers->config->item('cur_symbol'); ?> </th>
				  <th>Undeposited <?php echo $myHelpers->config->item('cur_symbol'); ?> </th>
                </tr>
              </thead>
              <tbody>
			 <?php 
			  $i=0;
			  $total_deposited = 0;
			  $total_emi = 0;
			  $total_amount_due = 0;
			  $total_amount_deposited = 0;
			  $total_undeposited = 0;
				if(isset($loan_payment_detail) && $loan_payment_detail->num_rows() > 0) { 
				
				foreach($loan_payment_detail->result() as $loan_payment_detail_row) {$i++;
				 $total_undeposited_row = 0; 
				 $total_emi += $loan_payment_detail_row->emi_amount;
				 $total_deposited += $loan_payment_detail_row->deposit_amount;
				 $amount_due = ($loan_payment_detail_row->emi_amount);
				 $total_amount_due += ($loan_payment_detail_row->emi_amount);
				 $amount_deposited = ($loan_payment_detail_row->deposit_amount);
				 $total_amount_deposited += ($loan_payment_detail_row->deposit_amount);
			 
				if($loan_payment_detail_row->due_date > $loan_payment_detail_row->entry_date)
						$advance_payment_month = ' (<span style="color:green;">Advanced</span>)';
					else
						$advance_payment_month = "";
				
			 ?>
                <tr>
                  
				  <td><?php echo $i; ?></td>
				  <td><?php echo date('d/m/Y',$loan_payment_detail_row->payment_date); ?></td>
				  <td><?php echo date('d/m/Y',$loan_payment_detail_row->due_date); ?></td>
				  <td><?php echo $loan_payment_detail_row->emi_amount; ?></td>
				  <td><?php echo $loan_payment_detail_row->deposit_amount; 
						echo $advance_payment_month; ?></td>
				  <td><?php echo ($loan_payment_detail_row->emi_amount); ?></td>
				  <td><?php echo ($loan_payment_detail_row->deposit_amount); ?></td>
				  <td><?php 
					$total_undeposited_row = ($amount_due - $amount_deposited);
					$total_undeposited += $total_undeposited_row;
				  echo $total_undeposited_row; 
						 ?></td>
				</tr>
				<?php } ?>
				
				<tr>
                  <td colspan=2>&nbsp;</td>
				  <td><strong>Total</strong></td>
				  <td><strong><?php echo $total_emi; ?></strong></td>
				  <td><strong><?php echo $total_deposited; ?></strong></td>
				  <td><strong><?php echo $total_amount_due; ?></strong></td>
				  <td><strong><?php echo $total_amount_deposited; ?></strong></td>
				  <td><strong><?php echo $total_undeposited; ?></strong></td>
				</tr>
				
				
				
				<?php }
				else{
				  echo ' <tr><td colspan="8">No data available in table</td></tr>';
				}?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

          <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
              <p class="lead">About Company</p>
               <?php if((isset($company_title) && !empty($company_title)) || 
					(isset($company_address) && !empty($company_address)) ||
					(isset($company_tel) && !empty($company_tel)) || 
					(isset($company_email) && !empty($company_email))
					) { 
			  ?>
              <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; text-align:center;">
				 <strong>
					<?php if(isset($company_title) && !empty($company_title)) 
					echo $company_title; ?></strong><br><br>
				 <span>
					<?php if(isset($company_address) && !empty($company_address)) 
					echo $company_address; ?>
				 </span><br>
				  <span>
					<?php if(isset($company_tel) && !empty($company_tel)) 
					echo 'Tel. : '.$company_tel; ?>
				 </span><br>
				 <span>
					<?php if(isset($company_email) && !empty($company_email)) 
					echo 'Email : '.$company_email; ?>
				 </span>
				
				 
              </p>
			  <?php } ?>
            </div><!-- /.col -->
			
			
            <div class="col-xs-6">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th style="width:50%">Principal Amount:</th>
                    <td><?php echo $principal_amount; ?></td>
                  </tr>
                  
				   
				  <tr>
                    <th>Interest (<?php echo $interest_rate; ?>%)</th>
                    <td><?php echo $totalInterest; ?></td>
                  </tr>
				   <tr>
                    <th>Installments </th>
                    <td><?php echo $emi_amount; ?></td>
                  </tr>
                  <tr>
                    <th>Total Amount:</th>
                    <td><?php echo $total_amount; ?></td>
                  </tr>
				 
                </table>
              </div>
            </div>
          </div><!-- /.row -->

          <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
			
			  <!--invoice-print.html-->
			  
              <a href="#" onclick="window.print();return false;" class="btn btn-primary pull-right">Print <i class="fa fa-print"></i> </a>
              
            </div>
          </div>
		  </div>
        </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  