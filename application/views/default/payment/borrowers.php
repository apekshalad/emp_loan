
<script>
var id;
 $(document).ready(function () { 
		
	$('.client_list').on('change',function(){
			$('.full_sreeen_overlay').show();
			id = $(this).val(); 
			$.ajax({
				url: '<?php echo site_url();?>ajax/fetch_loan_list_callback_func',
				type: 'POST',
				
				success: function (res) {
					$('.loan_detail').hide();
					$('#Loan').html(res.loan_detail);
					$('.full_sreeen_overlay').hide();
				},
				data: {id : id},
			});
				
		 });
		
     
		
		$('#Loan').on('change',function(){
				$('.full_sreeen_overlay').show();
				id = $(this).val(); 
				$.ajax({
					url: '<?php echo site_url();?>ajax/fetch_loan_detail_callback_func',
					type: 'POST',
					
					success: function (res) {
						if(res.loan_detail != '')
						{
						 
							$.each(res.loan_detail, function(index, element) {
								if(index == 'loan_id')
									$('.pay_now_model').attr('data-id',element);
								else if(index == 'has_more_due_emi')
								{
									if(element == 'no')
									$('.pay_now_model').hide();
								}
								//else if(index == 'transaction_list')
									//$('.transaction_detail').html(element);
								else
									$('.'+index).html(element);
							});
							$('.loan_detail').fadeIn();
						}
						else
						{
							$('.loan_detail').hide();
						}
						
						$('.full_sreeen_overlay').hide();
					},
					data: {id : id},
				});
					
			 });
		
		$('.pay_now_model').click(function() {
			$('.full_sreeen_overlay').show();
			var loan_id = $(this).attr('data-id');
			var user_id = "<?php echo $this->session->userdata('user_id');?>";
			if(user_id && user_id == 0)
			{
				window.location.href = '<?php echo site_url(); ?>logins';
			}
			
			$.ajax({
				url: '<?php echo site_url();?>ajax/open_pay_now_model_callback_func',
				type: 'POST',
				success: function (res) {
					$('.model_wrapper').html(res);
					$('.modal').show();
					$('.full_sreeen_overlay').hide();
				},
				data: {'loan_id' : loan_id},
				cache: false
				
			});
			return false;
		});
    }); 
	
    function progress(e){
        if(e.lengthComputable){
           $('#'+id+'_progress').show();
            $('progress').attr({value:e.loaded,max:e.total});
        }
    }
	
</script>
<style>
.loan_detail .form-group{
	margin-bottom:5px;
}
</style>
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Borrowers Payment </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			/* echo '<pre>';
			 print_r($_SESSION);
			 echo '</pre>';*/
			 
			 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('payment/borrowers',$attributes); ?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
			
			<div class="row">
			<div class="col-md-12">   
				
			<!-- general form elements -->
              <div class="box box-primary">
				
                <div class="box-header with-border">
                  <h3 class="box-title">Borrower's Details</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
                  <div class="box-body">
                    
						<div class="col-md-6">
							<div class="form-group">
							  <label for="Client">Client/Id</label>
							  <select class="form-control select2 input-sm client_list" required id="Client">	
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
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="Loan">Loan</label>
							  <select class="form-control"  name="loan" required id="Loan">
								<option value="">Select Loan</option>
							  </select>
							</div>
						</div>
					
						<div class="loan_detail" style="display:none;">
							
							<div class="col-md-12">
								<div class="box-header with-border">
								  <h3 class="box-title">Client Details</h3>
								</div><br>
							</div>
							
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Name</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="client_name"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <label>ID</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="client_id"></span>
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Mobile No.</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="client_mobile"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <label>Address</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="client_address"></span>
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="box-header with-border">
								  <h3 class="box-title">Loan Details</h3>
								</div><br>
							</div>
							
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Loan Date</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="loan_date"></span>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <label>Principal Amount</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="principal_amount"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Interest Rate ( % per year)</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="interest_rate"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									  <label>Loan Type</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="loan_type"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Total Installments</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="total_installment"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									  <label>Payment Terms</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="payment_terms"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3"> 
									<div class="form-group">
									  <label>Total Interest Amount</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="interest_amount"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									  <label>Emi Amount</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="emi_amount"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Due Installments</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="due_installments"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									  <label>Net Amount</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="net_amount"></span>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
									  <label>Total Amount Deposited</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="total_amount_deposite"></span>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
									  <label>Outstanding Amount</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
									  <span class="outstanding_amount"></span>
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group">
                                                                             <?php   if($this->session->userdata('offday') == date('j')){
                                                                    ?><a href="javascript:" data-toggle="modal" data-target="#myModal" class="btn btn-block btn-danger">Pay Next Installment</a>
                                                                        <?php } else{?>
									  <a data-id="" class="pay_now_model btn btn-block btn-default">Pay Next Installment</a>
                                                                        <?php }?>
                                                                        </div>
								</div>
							</div>
						<!--
							<div class="col-md-12">
								<div class="box-header with-border">
								  <h3 class="box-title">Payment Details</h3>
								</div><br>
							</div>
							<div class="col-md-12">
								<div class="col-md-1">
									<div class="form-group">
									  <label>S. No.</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									  <label>Payment Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									  <label>Due Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									  <label>EMI Amount</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									  <label>Deposited EMI Aount</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
									  <label>Undeposited Amount</label>
									</div>
								</div>
							</div>
							<div class="transaction_detail"></div>
							-->
						</div>
					
					
                    
                  </div>
                
              </div>
		</div><!-- end col-md-8-->
		  
		  
		  </div><!-- end row 1-->	  
		  
		  
		  
			  
			  </form>
        </section>
      </div>