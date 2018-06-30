  <?php $this->load->view("default/header-top");?>
  
  <?php $this->load->view("default/sidebar-left");?>

<?php 
	if($client_loan_detail->num_rows() > 0)
	{
		$row = $client_loan_detail->row();
		
		$client_id = $row->client_ID;
		$customer_name = $row->client_name;
		$customer_address = $row->client_address;
		$customer_mobile = $row->client_mobile;
		$customer_acc_no = $row->client_acc_no;
		$client_father_name = $row->client_father_name;
		$client_mother_name = $row->client_mother_name;
		$photo_url = $row->client_photo_proof;
		$id_url = $row->client_id_proof;
		$user_id = $row->user_id;
 	    $ClientRemarks = $row->remarks;
        $ClientAge = $row->age;
        $ClientNominee = $row->nominee;
        $ClientAgeofnominee = $row->age_of_nominee;
        $ClientRelationWithnominee = $row->relation_with_nominee;

        $accountmanagent='';
			if($myHelpers->global->get_user_meta($row->account_managent,'first_name'))
			$accountmanagent = $myHelpers->global->get_user_meta($row->account_managent,'first_name').' '.$myHelpers->global->get_user_meta($row->account_managent,'last_name');
      
	}
	else
	{
		$customer_name = '';
		$customer_address = '';
		$customer_mobile = '';
		$customer_acc_no = '';
		$client_father_name = '';
		$client_mother_name = '';
		$photo_url = '';
		$id_url = '';
		$client_id = '';
		$user_id = '';
        $remarks='';
        $age = "";
        $nominee = "";
        $age_of_nominee = "";
        $relation_with_nominee = "";
        $accountmanagent = "";
	}
?>

<script>
	$(document).ready(function() {
		
			var id = $('.client_id').val();
			if(id != '' && id != null)
			{
				
				$.ajax({
				  url: "<?php echo site_url(array('ajax','fetch_borrower_current_saving_callback_func')); ?>",
				  type: "POST",
				  data:{id: id},
				  cache: false,
				  success: function(data){
					  
					  $('#total_saving').html(data.total_current_saving);
					  $('#total_withdraw').html(data.total_current_withdraw);
					  $('#total_profit').html(data.total_profit);
				  }
				});
			}
			else
			{
				$('#total_saving').html('0');
				$('#total_withdraw').html('0');
				$('#total_profit').html('0');
			}
			
	});
</script>
<style>
	.table > thead > tr > th {
		border-bottom: 1px solid #f4f4f4;
	}
	.table > tbody > tr > td {
		border: 0 none;
		
	}
	.invoice-info .col-md-12 div {
		padding-bottom: 5px;
		padding-top: 5px;
	}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> View Loan </h1>
	  
	</section>

	<section class="content">
        <div class="invoice">
         
          <div class="row invoice-info">
            <input type="hidden" class="client_id" value="<?php echo $myHelpers->EncryptClientId($user_id); ?>"> 
			 <div class="col-md-12">
				<p style="text-align: left; font-weight: normal; padding-bottom: 10px; 
				font-size: 18px;border-bottom:1px solid #f4f4f4;">Client Details</p>
			</div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Client Name :</strong></div>
				<div class="col-md-7"><?php echo $customer_name; ?></div>
			 </div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Client Account No. :</strong></div>
				<div class="col-md-7"><?php echo $customer_acc_no; ?></div>
			 </div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Father/Spouse Name :</strong></div>
				<div class="col-md-7"><?php echo $client_father_name; ?></div>
			 </div>	
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Mother Name :</strong></div>
				<div class="col-md-7"><?php echo $client_mother_name; ?></div>
			 </div>	
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Client Mobile No. :</strong></div>
				<div class="col-md-7"><?php echo $customer_mobile; ?></div>
			 </div>					 
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Client Address :</strong></div>
				<div class="col-md-7"><?php echo $customer_address; ?></div>
			 </div>	
              <div class="col-md-12">
				<div class="col-md-5"><strong>Client Age :</strong></div>
				<div class="col-md-7"><?php echo $ClientAge; ?></div>
			 </div>	
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Account Managent :</strong></div>
				<div class="col-md-7"><?php echo $accountmanagent; ?></div>
			 </div>	
              <div class="col-md-12">
				<div class="col-md-5"><strong>Client Nominee :</strong></div>
				<div class="col-md-7"><?php echo $ClientNominee; ?></div>
			 </div>	
              <div class="col-md-12">
				<div class="col-md-5"><strong>Age of nominee :</strong></div>
				<div class="col-md-7"><?php echo $ClientAgeofnominee; ?></div>
			 </div>	
              <div class="col-md-12">
				<div class="col-md-5"><strong>Relation With Nominee :</strong></div>
				<div class="col-md-7"><?php echo $ClientRelationWithnominee; ?></div>
			 </div>	
              <div class="col-md-12">
				<div class="col-md-5"><strong>Client Remarks :</strong></div>
				<div class="col-md-7"><?php echo $ClientRemarks; ?></div>
			 </div>		
			<div class="col-md-12">
				<div class="col-md-5"><strong>Photo Attachment :</strong></div>
				<div class="col-md-7">
					<div class="gallery">
						<?php if(isset($photo_url) && !empty($photo_url)) { ?>
								<a title="<?php echo $customer_name.' (Photo)'; ?>" href="<?php echo base_url().'uploads/client/'.$photo_url; ?>" rel="prettyPhoto">
								<img src="<?php echo base_url().'uploads/client/'.$photo_url; ?>" height="auto" width="80">
								</a>
						<?php } ?>
					</div>
				</div>
			 </div>			
			 <div class="col-md-12">
				<div class="col-md-5"><strong>ID Attachment :</strong></div>
				<div class="col-md-7">
					<div class="gallery">
						<?php if(isset($id_url) && !empty($id_url)) { ?>
								<a title="<?php echo $customer_name.' (ID)'; ?>" href="<?php echo base_url().'uploads/client/'.$id_url; ?>" rel="prettyPhoto">
								<img src="<?php echo base_url().'uploads/client/'.$id_url; ?>" height="auto" width="80">
								</a>
							<?php } ?>
					</div>
				</div>
			 </div>			
		
         </div>		
		
		<div class="row invoice-info">
             <div class="col-md-12">
				<p style="text-align: left; font-weight: normal; padding-bottom: 10px; 
				font-size: 18px;border-bottom:1px solid #f4f4f4;">Saving Details</p>
			</div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>General Savings :</strong></div>
				<div class="col-md-7">
							<?php 
								$total_current_saving = 0;
								$query = "select IFNULL(sum(ca.payment_in),0) as total_current_deposit from capital_account ca
								where ca.trans_type = 'saving' and client_ID = $user_id ";
								$result = $this->Common_model->commonQuery($query);
								if($result->num_rows() > 0)
								{
									$row = $result->row();
									$total_current_saving += $row->total_current_deposit;
								}
								$query = "select IFNULL(sum(ca.payment_out),0) as total_current_withdraw from capital_account ca
								where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and client_ID = $user_id ";
								$result = $this->Common_model->commonQuery($query);
								if($result->num_rows() > 0)
								{
									$row = $result->row();
									$total_current_saving -= $row->total_current_withdraw;
								}
								
								$query = "select IFNULL(sum(ca.payment_out),0) as total_current_expense from capital_account ca
								where ca.trans_type = 'expense' and ca.trans_from = 'saving' and client_ID = $user_id";
								$result = $this->Common_model->commonQuery($query);
								if($result->num_rows() > 0)
								{
									$row = $result->row();
									$total_current_saving += $row->total_current_expense;
								}
								
								$query = "select IFNULL(sum(ca.payment_in),0) as total_current_charges from capital_account ca
								where ca.trans_type = 'charges' and client_ID = $user_id and ca.trans_from = 'saving'";
								$result = $this->Common_model->commonQuery($query);
								if($result->num_rows() > 0)
								{
									$row = $result->row();
									$total_current_saving -= $row->total_current_charges;
								}
								if($total_current_saving >= 0)
									echo $total_current_saving;
								else
									echo '-';
						  ?>
				</div>
			 </div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Confidential Savings :</strong></div>
				<div class="col-md-7">
					<?php 
						$total_current_saving = 0;
						$query = "select IFNULL(sum(ca.payment_in),0) as total_current_deposit from capital_account ca
						where ca.trans_type = 'conf_saving' and client_ID = $user_id ";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_saving += $row->total_current_deposit;
						}
						$query = "select IFNULL(sum(ca.payment_out),0) as total_current_withdraw from capital_account ca
						where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' and client_ID = $user_id ";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_saving -= $row->total_current_withdraw;
						}
						
						$query = "select IFNULL(sum(ca.payment_out),0) as total_current_expense from capital_account ca
						where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $user_id";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_saving += $row->total_current_expense;
						}
						
						$query = "select IFNULL(sum(ca.payment_in),0) as total_current_charges from capital_account ca
						where ca.trans_type = 'charges' and client_ID = $user_id and ca.trans_from = 'conf_saving'";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_saving -= $row->total_current_charges;
						}
						if($total_current_saving >= 0)
							echo $total_current_saving;
						else
							echo '-';
				  ?>
				</div>
			 </div>
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Total Withdrawn :</strong></div>
				<div class="col-md-7">
					<?php 
						$total_current_withdraw = 0;
						$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
						where ca.trans_type = 'withdraw' and client_ID = $user_id ";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_withdraw += $row->total_current_withdraw;
						}
						if($total_current_withdraw >= 0)
							echo $total_current_withdraw;
						else
							echo '-';
						
					?>
				</div>
			 </div>	
			 <div class="col-md-12">
				<div class="col-md-5"><strong>Total Profit :</strong></div>
				<div class="col-md-7">
					<?php 
						$total_current_profit = 0;
						$query = "select sum(ca.payment_out) as total_current_profit from capital_account ca
						where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' and client_ID = $user_id ";
						$result = $this->Common_model->commonQuery($query);
						if($result->num_rows() > 0)
						{
							$row = $result->row();
							$total_current_profit += $row->total_current_profit;
						}
						if($total_current_profit >= 0)
							echo $total_current_profit;
						else
							echo '-';
						
					?>
				</div>
			 </div>	
			 
         </div>	
		
		
						
							
						
						
		
		
          <!-- Table row -->
         <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
				<tr>
					  <th colspan=5 style="text-align: left; font-weight: normal; font-size: 18px;">Loan Details </th>
					</tr>
                <tr>
                  <th>S.No.</th>
				  <th>Loan Date</th>
                  <th>Principle Amount</th>
				  <th>EMI</th>
				  <th>Net Amount</th>
				  <th>Total Deposite Amount</th>
				  <th>Outstanding Amount</th>
				  <th>Due Installments</th>
                </tr>
              </thead>
              <tbody>
			 <?php 
				$sql = "select * from clients 
						inner join user_meta on user_meta.meta_key = 'client_ID' and user_meta.meta_value = clients.client_ID
						inner join loans on user_meta.user_id = loans.client_ID
						where clients.client_ID = $client_id 
						order by loans.loan_id DESC";
				$loan_data = $myHelpers->Common_model->commonQuery("$sql");
				if($loan_data->num_rows()>0) { 
				$i=0;
				foreach($loan_data->result() as $payment_row)
				{$i++;
				
				
				$emi_paid = 0;
				if(isset($payment_row->loan_id) && !empty($payment_row->loan_id))
				{
				
					$sql = "select * from loan_payments 
							where loan_id = $payment_row->loan_id and deposit_amount > 0";
					
					$loan_payments_data = $myHelpers->Common_model->commonQuery("$sql");
					if($loan_payments_data->num_rows() > 0)
					{
						foreach($loan_payments_data->result() as $loan_payments_row)
						{
							
							$emi_paid ++;
						}	
					}
				}
			 ?>
                <tr>
                  <td><?php echo $i; ?></td>
				  <td><?php echo date('d/m/Y',$payment_row->loan_date); ?></td>
				  <td><?php echo $payment_row->principal_amount; ?></td>
				  <td><?php echo $payment_row->emi_amount; ?></td>
				  <td><?php echo $payment_row->net_amount; ?></td>
				  <td><?php echo $payment_row->total_amount_deposite; ?></td>
				  <td><?php echo ($payment_row->net_amount - $payment_row->total_amount_deposite); ?></td>
				  <td><?php echo ($payment_row->time_periods - $emi_paid); ?></td>
                </tr>
			 <?php } }else{
				  echo ' <tr><td colspan="5">No data available in table</td></tr>';
			  } ?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

         

         
        
  </div><!-- /.content-wrapper -->
  </section><!-- /.content -->
   </div>