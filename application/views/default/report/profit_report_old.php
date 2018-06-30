<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

	
	
	<script>
	
	function prettyPhotoLoad()
	{
		 $("a[rel^='prettyPhoto']").prettyPhoto();    
	}
	
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
				  url: "<?php echo site_url(array('ajax','fetch_profit_report_callback_func')); ?>",
				  type: "POST",
				  data:{id: id,date_rang:date_rang},
				  cache: false,
				  success: function(data){
					  
					  $('.profile_name').html(data.first_name+' '+data.last_name);
					  $('.profile_mobile_no').html(data.mobile_no);
					  $('.profile_address').html(data.address);
					  $('.profile_photo').html(data.photo_url);
					  $('.profile_id').html(data.id_url);
					  $('.profile_email').html(data.email);
					  
					  $('.profit_table tbody').html(data.loan_row);
					  $('.statement_list').show();
					  $('.full_sreeen_overlay').hide();
					  prettyPhotoLoad();
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
	  <h1> Profit Report </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
							
							<div class="row">
								
								<div class="col-md-4">
									
									  <div class="form-group">

										  <label for="Client">Borrower/Employee</label>
										  <select class="form-control select2 input-sm statement_client_list " name="client_ID" required id="Client">	
											<option value="">Select Borrower/Employee</option>
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
        <section class="content statement_list" style="display:none;">
			<div class="invoice">
          <!-- title row -->
          
		  <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                Profit Report
				<small class="pull-right">Date: 
				<?php 
					echo date('d/m/Y',time()); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
		
		<div class="statement_profile_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Profile
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 profile_table">
				  <div class="row">
					  <div class="col-xs-3">
							<strong>Name</strong>
					  </div>
					  <div class="col-xs-3 profile_name">
							
					  </div>
				  
					  <div class="col-xs-3">
							<strong>Mobile No.</strong>
					  </div>
					  <div class="col-xs-3 profile_mobile_no">
							
					  </div>
				  </div>
				  
				  <div class="row">
					  <div class="col-xs-3">
							<strong>Address</strong>
					  </div>
					  <div class="col-xs-3 profile_address">
							
					  </div>
				  
					  <div class="col-xs-3">
							<strong>Email</strong>
					  </div>
					  <div class="col-xs-3 profile_email">
							
					  </div>
				  </div>
				  
				  <div class="row">
					  <div class="col-xs-3">
							<strong>Photo</strong>
					  </div>
					  <div class="col-xs-3 profile_photo gallery">
							
					  </div>
				 
					  <div class="col-xs-3">
							<strong>ID</strong>
					  </div>
					  <div class="col-xs-3 profile_id gallery">
							
					  </div>
				  </div>
			  </div>
		</div>
		
		<div class="statement_loan_block">
			  <div class="row">
				<div class="col-xs-12 box-header">
				  <h3 class="box-title">
				   Profit Details
				  </h3>
				</div>
			  </div>
			  
			<div class="row">
			  <div class="col-xs-12 table-responsive">
				<table class="table table-striped profit_table">
				  <thead>
					<tr>
					  <th>S.No.</th>
					  <th>Loan Date</th>
					  <th>Principal Amount (<i class="fa fa-inr"></i>)</th>
					  <th>Net Amount (<i class="fa fa-inr"></i>)</th>
					  <th>Loan Type</th>
					  <th>EMI Amount (<i class="fa fa-inr"></i>)</th>
					  <th>Interest Rate</th>
					  <th>Total EMi Paid</th>
					  <th>Interest Amount (<i class="fa fa-inr"></i>)</th>
					</tr>
				  </thead>
				  <tbody>
				  </tbody>
				</table>
			  </div>
			</div>
		</div>
		
			
        </div>
		</section><!-- /.content -->
		
		
  </div><!-- /.content-wrapper -->
  