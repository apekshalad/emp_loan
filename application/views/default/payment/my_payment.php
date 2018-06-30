<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

	
	
	<script>
	
	$(document).ready(function () {
		
		 
		$('#date_picker').datepicker({
			format: "mm/dd/yyyy",
			Default: true,
			pickDate: true,
			autoclose : true
	   });
		
		$('.statement_client_list').change(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).val();
			var date_rang = $('#daterange-btn input.date_rang').val();
			if(id != '' && id != null)
			{
				
				$.ajax({
				  url: "<?php echo site_url(array('ajax','fetch_statement_list_callback_func')); ?>",
				  type: "POST",
				  data:{id: id,date_rang:date_rang},
				  cache: false,
				  success: function(data){
					  $('.emi_table tbody').html(data.emi_row);
					  $('.loan_table tbody').html(data.loan_row);
					  $('.saving_table tbody').html(data.saving_row);
					  $('.conf_saving_table tbody').html(data.conf_saving_row);
					  $('.withdraw_table tbody').html(data.withdraw_row);
					  $('.statement_list').show();
					  $('.full_sreeen_overlay').hide();
				  }
				});
				
			}
			else
			{
				$('.statement_list').hide();
				$('.full_sreeen_overlay').hide();
			}
			return false;
		});
		
	});
	
	</script>
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
	  <h1> My Payments </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
							
							<div class="row">
								
								
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="Client">Date</label>
										
										<div class="input-group">
										  <button type="button" class="btn btn-default pull-right" id="daterange-btn">
											<span>
											<?php echo date('F j, Y',time()).' - '.date('F j, Y',time());?>
											</span>
											&nbsp;<i class="fa fa-caret-down"></i>
											<input type="hidden" name="date_rang" class="date_rang">
										  </button>
										</div>
									 </div>
								</div>
								<div class="col-md-6" style="visibility: hidden;">
									<div class="form-group">
										<label for="Client">Borrower/Employee</label>
										<select class="form-control select2 input-sm statement_client_list " name="client_ID" required id="Client">	
													<?php if(isset($client_list) && $client_list->num_rows() > 0)
													{
														echo '<optgroup label="Clients">';
														foreach($client_list->result() as $client_row)
														{
															$acc_text ='';
															if(isset($client_row->acc_no) && !empty($client_row->acc_no))
																$acc_text = '('.$client_row->acc_no.')';
															echo '<option value="'.$myHelpers->EncryptClientId($client_row->user_id).'">
															'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' '.$acc_text.'</option>';
														}
														echo '</optgroup>';
													}
													?>
													
													<?php if(isset($employee_list) && $employee_list->num_rows() > 0)
													{
														echo '<optgroup label="Employees">';
														foreach($employee_list->result() as $employee_row)
														{
															$acc_text ='';
															if(isset($employee_row->acc_no) && !empty($employee_row->acc_no))
																$acc_text = '('.$employee_row->acc_no.')';
															echo '<option value="'.$myHelpers->EncryptClientId($employee_row->user_id).'">
															'.ucfirst($employee_row->first_name).' '.ucfirst($employee_row->last_name).' '.$acc_text.'</option>';
														}
														echo '</optgroup>';
													}
													?>
										  </select>
								</div>
							</div>	
								
				</div>
			</div>
	</section>
	
	<!-- Main content -->
        <section class="content statement_list">
			<div class="invoice">
          <!-- title row -->
          
		  <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                My Payments 
				<small class="statement_date_between_block" style="display:inline;">&nbsp;&nbsp;DATE BETWEN 
				<strong class="s_date"><?php echo date('d/m/Y',time());?></strong> TO 
				<strong class="e_date"><?php echo date('d/m/Y',time());?></strong></small>
                <small class="pull-right">Date: 
				<?php 
					echo date('d/m/Y',time()); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
		  
		  
		<div class="statement_emi_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   EMI
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped emi_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Loan Date</th>
					  <th>Principle Amount</th>
					  <th>Payment Date</th>
					  <th>Amount (<i class="fa fa-money"></i>)</th>
					  <th>Payment Method</th>
					  <th>Remarks</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(isset($emi_row) && !empty($emi_row)) echo $emi_row;?>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
		<div class="statement_loan_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Loan
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped loan_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Date</th>
					  <th>Amount (<i class="fa fa-money"></i>)</th>
					  <th>Payment Method</th>
					  <th>Loan Type</th>
					  <th>Installment Count</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(isset($loan_row) && !empty($loan_row)) echo $loan_row;?>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
		<div class="statement_saving_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Saving
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped saving_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Date</th>
					  <th>Amount (<i class="fa fa-money"></i>)</th>
					  <th>Payment Method</th>
					  <th>Remarks</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(isset($saving_row) && !empty($saving_row)) echo $saving_row;?>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
		<div class="statement_conf_saving_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Confidential Saving
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped conf_saving_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Date</th>
					  <th>Amount (<i class="fa fa-money"></i>)</th>
					  <th>Profit Mode</th>
					  <th>Payment Method</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(isset($conf_saving_row) && !empty($conf_saving_row)) echo $conf_saving_row;?>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
		<div class="statement_withdraw_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Withdraw
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped withdraw_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Date</th>
					  <th>Amount (<i class="fa fa-money"></i>)</th>
					  <th>Payment Method</th>
					  <th>Remarks</th>
					</tr>
				  </thead>
				  <tbody>
					<?php if(isset($withdraw_row) && !empty($withdraw_row)) echo $withdraw_row;?>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
			
        </div>
		</section><!-- /.content -->
		
		
  </div><!-- /.content-wrapper -->
  