<?php $this->load->view("default/header-top");?>
  
  <?php $this->load->view("default/sidebar-left");?>
  
	
<script>
 $(document).ready(function () { 
		
		$('.client_list').on('change',function(){
			$('.full_sreeen_overlay').show();
			var id = $(this).val(); 
			$.ajax({
				url: '<?php echo site_url();?>ajax/fetch_client_detail_callback_func',
				type: 'POST',
				
				success: function (res) {
                                      console.log(res);
					if(res.client_detail != "")
					{
						$('.client_id').val(res.client_detail.client_ID);
						$('.client_name').val(res.client_detail.client_name);
						$('.client_acc_no').val(res.client_detail.client_acc_no);
						$('.client_mobile').val(res.client_detail.client_mobile);
						$('.client_address').val(res.client_detail.client_address);
                                              
                                                $('.ClientAge').val(res.client_detail.age);
						$('.ClientNominee').val(res.client_detail.nominee);
						$('.ClientNomAge').val(res.client_detail.age_of_nominee);
						$('.ClientRelNom').val(res.client_detail.relation_with_nominee);
						$('.ClientRemarks').val(res.client_detail.remarks);
                                                $('.Clientsaving').val(res.client_detail.gen_saving);
						$('.Clientconfsaving').val(res.client_detail.conf_saving);
					}
					else
					{
						$('.client_id').val('');
						$('.client_name').val('');
						$('.client_acc_no').val('');
						$('.client_mobile').val('');
						$('.client_address').val('');
                                                 $('.ClientAge').val('');
						$('.ClientNominee').val('');
						$('.ClientNomAge').val('');
						$('.ClientRelNom').val('');
						$('.ClientRemarks').val('');
                                                 $('.Clientsaving').val('');
						$('.Clientconfsaving').val('');
					}
					$('.full_sreeen_overlay').hide();
				},
                                error: function (data){
                                    console.log(data);
                                },
				data: {id : id},
				
			});
				
		 });
		
		$('.form').submit(function() {
			if($('.client_id').val() == '')
			{
				alert('You have to select atleast 1 client to proceed.');
				return false;
			}
		});
		
    }); 
   
    
	
</script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Add New Loan </h1>
	  
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- form start -->
		   <!-- <form role="form">-->
		 <?php 
		$attributes = array('name' => 'add_form_post','class' => 'form');		 			
		echo form_open_multipart('loan/add_new',$attributes); ?>
		   
		<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
		
		<div class="row">
		<div class="col-md-8">   
		   
		<!-- general form elements -->
		  <div class="box box-primary">
			<div class="box-header with-border">
			  <h3 class="box-title">Loan Details</h3>
			  <div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
			  </div>
			</div><!-- /.box-header -->
			  <div class="box-body">
				
				<!--<div class="form-group">
				  <label >Account No.</label>
				  <input type="text" class="form-control input-sm" value="1729" disabled="disabled">
				</div>-->
				
				<div class="row">
				<div class="col-md-6">
				
					<div class="form-group">
					  <label for="loanDate">Loan Date</label>
					  <div class="input-group">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
					  <input type="text" class="form-control input-sm" required="required" id="loanDate" name="loanDate"  data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>					  
					
					  </div>
					</div>
					<input type="hidden" name="customer_ID" class="form-control input-sm" value="<?php //echo $customer_ID; ?>" readonly="readonly">
				 
				
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
					  <label for="principalAmt">Principal Amount</label>
					  <input type="text" class="form-control input-sm" required="required" id="principalAmt" name="principalAmt" placeholder="Enter Principal Amount">					  
					</div>
				</div>
				</div>
				
				
				
				<div class="row">
				
				
					<div class="col-md-6">
						<div class="form-group">
						  <label for="interestRate">Interest Rate ( % per year)</label>
						  <input type="text" class="form-control input-sm" required="required" id="interestRate" name="interestRate" placeholder="Enter Interest Rate, 18 per year">					  
						</div>
					</div>
				
				
					<div class="col-md-6">
						<div class="form-group">
						  <label for="timePeriods">Loan Type</label><br />
						  
						  <select name="loan_type" class="form-control input-sm">
								<option value="">Select Loan Type</option>
								<?php 
								
								if ( isset($loan_type_list) && $loan_type_list->num_rows() > 0)
								{		
									foreach ($loan_type_list->result() as $row)
									{
										echo "<option value='".$myHelpers->EncryptClientId($row->loan_type_id)."'>".ucfirst($row->loan_title)."</option>";
									}
								}
								?>
						  </select>
						  
						</div>
					</div>
				
				</div>
				
				<div class="row">
					<div class="col-md-6">
					<div class="form-group">
					  <label for="timePeriods">Installments Count</label>
					  <input type="text" class="form-control input-sm" required="required" id="timePeriods" name="timePeriods" placeholder="Enter Time Period, 20 or so">					  
					</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
						  <label for="timePeriods">Payment Terms</label><br />
						  
						  <select name="payment_terms" class="form-control input-sm">
								<option value="monthly">Monthly</option>
								<option value="weekly">Weekly</option>
                                <option value="daily">Daily</option>
						  </select>
						  
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<label for="payment_mode">Payment Mode</label>
						<select class="form-control" id="payment_mode" name="payment_mode">
							<option value="cash">Cash</option>
							<option value="cheque">Cheque</option>
						</select>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						  <label for="notifyCount">Notify after Installments</label><br />
						  <input type="text" class="form-control input-sm" required="required" id="notifyCount" name="notifyCount" placeholder="Enter Installments Number">	
						  
						</div>
					</div>
					
				</div>
				
				<hr>
				
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						  <label for="interestAmt">Total Interest Amount</label>
						  <input type="text" class="form-control input-sm" required="required" id="interestAmt" name="interestAmt" 
						  readonly="readonly">					  
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
						  <label for="totalAmt">Total Amount</label>
						  <input type="text" class="form-control input-sm" required="required" id="totalAmt" name="totalAmt" 
						  readonly="readonly">					  
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						  <label for="emiAmt">EMI Amount</label>
						  <input type="text" class="form-control input-sm" required="required" id="emiAmt" name="emiAmt" 
						  readonly="readonly">					  
						</div>
					</div>
		
					<div class="col-md-6">
						<div class="form-group">
						  <label for="netAmt">Net Amount</label>
						  <input type="text" class="form-control input-sm" required="required" id="netAmt" name="netAmt" 
						  readonly="readonly">					  
						</div>
					</div>
				</div>
				
				
				
				
				
			  </div><!-- /.box-body -->

		  </div><!-- /.box -->
		  
			  
		  
	  </div><!-- end col-md-8-->
	  
	  <div class="col-md-4">
		  <div class="box box-primary">
			  <div class="box-header with-border">
				  <h3 class="box-title"> Status</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
				</div><!-- /.box-header -->
				
				<div class="box-body">
					<div class="form-group">
					  <select class="form-control select2 input-sm client_list">	
							<option value="">Select Client/ID</option>
							<?php if(isset($client_list) && $client_list->num_rows() > 0)
							{
								foreach($client_list->result() as $client_row)
								{
									echo '<option value="'.$myHelpers->EncryptClientId($client_row->user_id).'">
											'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' ('.ucfirst($client_row->acc_no).')</option>';
								}
							}
							?>
							
					  </select>
					</div>
					<input class="client_id" name="client_id" value="" type="hidden">
					<div class="form-group">
					  <label for="">Client Name</label>
					  <input type="text" class="form-control input-sm client_name" name="ClientName" readonly="readonly">				  
					</div>
					<div class="form-group">
					  <label for="">Client Account No.</label>
					  <input type="text" class="form-control input-sm client_acc_no" readonly="readonly">				  
					</div>
					<div class="form-group">
					  <label for="">Client Mobile No.</label>
					  <input type="text" class="form-control input-sm client_mobile" name="ClientMobile" readonly="readonly">	
					</div>
					<div class="form-group">
					  <label for="">Client Address</label>
					  <textarea style="width:100%;" class="form-control input-sm client_address" readonly="readonly"></textarea>
					</div>
                                        <div class="form-group">
					  <label for="">Client Age</label>
					  <input type="text" class="form-control input-sm ClientAge" name="ClientAge" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client Nominee</label>
					  <input type="text" class="form-control input-sm ClientNominee" name="ClientNominee" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client Age of nominee</label>
					  <input type="text" class="form-control input-sm ClientNomAge" name="ClientNomAge" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client's Relation with nominee</label>
					  <input type="text" class="form-control input-sm ClientRelNom" name="ClientRelNom" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client Remarks</label>
					  <input type="text" class="form-control input-sm ClientRemarks" name="ClientRemarks" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client General Savings</label>
					  <input type="text" class="form-control input-sm Clientsaving" name="Clientsaving" readonly="readonly">	
					</div>
                                        <div class="form-group">
					  <label for="">Client Confidential Savings</label>
					  <input type="text" class="form-control input-sm Clientconfsaving" name="Clientconfsaving" readonly="readonly">	
					</div>
				 </div>
				
				 <div class="box-footer">
                                     <?php  if($this->session->userdata('offday') == date('j')){
                                                                    ?><a href="javascript:" data-toggle="modal" data-target="#myModal" class="btn btn-danger pull-right">Save Publish</a>
                                                                        <?php } else{?>
					<button name="submit" type="submit" class="btn btn-primary pull-right">Save Publish</button>
                                                                        <?php }?>
				  </div>
			  </div><!-- /.box -->	 
			  
			  	
			
	  </div><!-- end col-md-4-->
	  
	  
	  </div><!-- end row-->	  
		  
		  </form>
	</section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  
  
  
  
  
<script type="text/javascript">

$(document).ready(function(){
	
	//Datemask dd/mm/yyyy
	$("#loanDate").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

	
	
	
	var principalAmt;
	var timePeriods;
	var interestRate;
	var netAmt;
	var fileCharge; 
	var advanceEmi = 'yes';
	
	$("#principalAmt").on("change",function(){
		principalAmt = $(this).val();
		calculateEMI();
	});	
	
	$("#timePeriods").on("change",function(){
		timePeriods = $(this).val();
		calculateEMI();
	});
	
	$("#interestRate").on("change",function(){
		interestRate = $(this).val();
		calculateEMI();
	});
	
	
	function calculateEMI(){
		var mainInterest ; //= ( principalAmt  * interestRate) / 100;
		var totalAmount ;//= parseInt(totalInterest) + parseInt(principalAmt);
		
			mainInterest = (( principalAmt  * interestRate) / 100);
			totalAmount = (mainInterest) + Math.round(principalAmt);
			
			EMI = (totalAmount / (timePeriods));
		
		if(!isNaN(mainInterest) && !isNaN(EMI))
		{
			$("#emiAmt").val(Math.round(EMI));
			//$("#interestAmt").val(Math.round(mainInterest));
			$("#interestAmt").val((Math.round(EMI)*timePeriods) - Math.round(principalAmt));
			//$("#totalAmt").val(Math.round(totalAmount));
			$("#totalAmt").val(Math.round(EMI)*timePeriods);
			//$("#netAmt").val(Math.round(totalAmount));
			$("#netAmt").val(Math.round(EMI)*timePeriods);
		}
		else
		{
			$("#emiAmt").val('');
			$("#interestAmt").val('');	
			$("#totalAmt").val('');
			$("#netAmt").val('');
			
		}	
			
	}
	
});

</script>	  
  