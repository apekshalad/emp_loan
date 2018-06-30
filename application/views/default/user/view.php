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
		$principal_amount = $row->principal_amount;
		$interest_rate = $row->interest_rate;
		$net_amount = $row->net_amount;
		$time_periods = $row->time_periods;
		$loan_type = $row->loan_type;
		$loan_date = $row->loan_date;
		$emi_amount = $row->emi_amount;
		$client_father_name = $row->client_father_name;
		$client_mother_name = $row->client_mother_name;
		$photo_url = $row->client_photo_proof;
		$id_url = $row->client_id_proof;
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
		$loan_type = '';
		$emi_amount = '';
		$loan_date = '';
		$client_father_name = '';
		$client_mother_name = '';
		$photo_url = '';
		$id_url = '';
		$client_id = '';
	}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> View Loan </h1>
	  
	</section>

	<section class="content">
        <div class="invoice">
         
          <div class="row invoice-info">
            
			 <div class="col-xs-12 table-responsive">
				<table class="table ">
				  <thead>
					<tr>
					  <th colspan=2 style="text-align:center;">Client Details </th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td>
							<strong>Client Name : </strong>
						</td>
						<td>
							<?php echo $customer_name; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Client Account No. : </strong>
						</td>
						<td>
							<?php echo $customer_acc_no; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Father/Spouse Name : </strong>
						</td>
						<td>
							<?php echo $client_father_name; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Mother Name : </strong>
						</td>
						<td>
							<?php echo $client_mother_name; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Client Mobile No. : </strong>
						</td>
						<td>
							<?php echo $customer_mobile; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Client Address : </strong>
						</td>
						<td>
							<?php echo $customer_address; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<strong>Photo Attachment : </strong><br>
						
							<?php if(isset($photo_url) && !empty($photo_url)) { ?>
									<img src="<?php echo base_url().'uploads/client/'.$photo_url; ?>" height="auto" width="80">
								<?php } ?>
						</td>
					
						<td>
							<strong>ID Attachment: </strong>
						<br>
							<?php if(isset($id_url) && !empty($id_url)) { ?>
								<img src="<?php echo base_url().'uploads/client/'.$id_url; ?>" height="auto" width="80">
							<?php } ?>
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
                  <th>S.No.</th>
				  <th>Loan Date</th>
                  <th>Principle Amount</th>
				  <th>EMI</th>
				  <th>Current Savings</th>
				  <th>Total Savings</th>
				  <th>Withdrawn</th>
				  <th>Profit</th>
				  <th>Due Installments</th>
                </tr>
              </thead>
              <tbody>
			 <?php 
				$sql = "select * from loans 
						inner join clients on clients.client_ID = loans.client_ID
						where clients.client_ID = $client_id 
						order by loans.loan_id DESC";
				$loan_data = $myHelpers->Common_model->commonQuery("$sql");
				if($loan_data->num_rows()>0) { 
				$i=0;
				foreach($loan_data->result() as $payment_row)
				{$i++;
				
				
				$emi_paid = 0;
				if(isset($row->loan_id) && !empty($row->loan_id))
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
				  <td>-</td>
				  <td>-</td>
				  
				  <td>-</td>
				  <td>-</td>
                  <td><?php echo ($payment_row->time_periods - $emi_paid); ?></td>
                </tr>
			 <?php } }else{
				  echo ' <tr><td colspan="9">No data available in table</td></tr>';
			  } ?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

         

         
        
  </div><!-- /.content-wrapper -->
  </section><!-- /.content -->
   </div>