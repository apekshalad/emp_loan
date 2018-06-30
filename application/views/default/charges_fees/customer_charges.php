<?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
?>
<script>

$(document).ready(function () {
	
   $('#date_picker').datepicker({
		format: "mm/dd/yyyy",
		Default: true,
		pickDate: true,
		autoclose : true
   });
   
  var client_list_changed = false;
  
   $('.edit_entry').click(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).attr('data-id');
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_customer_charges_entry_callback_func')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  $('.client_list').find('option').attr("disabled",true);
				  $('.client_list').val(data.client_ID).removeAttr('required');
				  $('.client_list option[value='+data.client_ID+']').attr('selected','selected');
				  $('.client_list').trigger("change");
				  $('#date_picker').val(data.payment_date);
				  $('#amount').val(data.amount);
				  $('#cap_acc_id').val(data.cap_acc_id);
				  $('#remarks').val(data.remarks);
				  $('#save_publish').attr('name','edit_entry').text('Save Entry');
				  $('#payment_mode').val(data.payment_mode);
				  $('#accountType').find('option').attr("disabled",true);
				  $('#accountType').val(data.trans_from);
				  $('#total_available_balance').html(data.total_current_saving);
				  $('.full_sreeen_overlay').hide();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
			return false;
		});
   
   $('.client_list').change(function() {

	   if($(this).val() != '')
	   {   
		   $('#accountType').val('').attr('disabled',false);
	   }
	   else	
	   {
		   client_list_changed = false;
		   $('#accountType').val('').attr('disabled',true);
		   $('#payment_mode').val('cash').attr('disabled',true);
	   }
	   $('#total_available_balance').html('0');
   });
   
   
   $('#accountType').change(function() {
	   
	   if($(this).val() != '')
	   {   
		   $('#payment_mode').val('cash').attr('disabled',false);
		   $('#payment_mode').trigger('change');
	   }
	   else	
	   {
		   $('#payment_mode').val('cash').attr('disabled',true);
		   $('#total_available_balance').html(0);
		   $('#amount').val('').attr('readonly',true);
	   }
		
			return false;
		});
		
		$('#payment_mode').change(function() {
			
			var account_type = $('#accountType').val();
			var user_id = $('.client_list').val();
			var payment_mode = $(this).val();
			if(payment_mode != '')
			{  
				$('.full_sreeen_overlay').show();
				$.ajax({
				  url: "<?php echo site_url(array('ajax','fetch_available_bal_on_customer_charges_page_callback_func')); ?>",
				  type: "POST",
				  data:{account_type: account_type, id : user_id, payment_mode:payment_mode},
				  cache: false,
				  success: function(data){
					  $('#total_available_balance').html(data.total_current_saving);
					  $('.full_sreeen_overlay').hide();
					  $('#amount').val('').attr('readonly',false);
					  client_list_changed = true;
				  }
				});
			}
			else
			{
				//alert('Please Select Payment Mode.');
				$('#total_available_balance').html(0);
				$('#amount').val('').attr('readonly',true);
			}
			return false;
		});
		
		$( "#amount" ).focusout(function() {
				if(client_list_changed)
				{
					var total_available_balance = $('#total_available_balance').html();
					if($(this).val() > parseInt(total_available_balance))
					{
						alert("You cann't exceed the amount from Current Available Balance.");
						$(this).val(0);
					}
				}
		 });	
   
});

</script>

      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Customer Charges </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('charges_fees/customer_charges',$attributes); ?>
			   <input type="hidden" name="cap_acc_id" id="cap_acc_id" value="">
			<div class="row">
			<div class="col-md-8">   
			   
			<!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Charges Information</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
				
					<?php echo validation_errors(); ?>
				
                  <div class="box-body">
                    <div class="row">
						<div class="col-md-6">
							<div class="form-group">
							  <label for="Client">Client/Employee</label>
							  <select class="form-control select2 input-sm client_list" data-trans_type="conf_saving" name="client_ID" required id="Client">	
									<option value="">Select Client/Employee</option>
									
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
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="date_picker">Date</label>
							  <input type="text" class="form-control" required="required"  id="date_picker" name="pay_date" placeholder="Enter Date"  value="<?php if(isset($_POST['pay_date'])) echo $_POST['pay_date']; else { echo date('m/d/Y',time()); } ?>">
							</div>
						</div>
						
					</div>	
					
					
					
					<div class="row">
						
						<div class="col-md-3">
							<div class="form-group">
							  <label for="accountType">Account Type</label>
							  <select class="form-control input-sm" disabled="disabled" name="account_type" required id="accountType">	
									<option value="">Select Account</option>
									
									<option value="saving">Saving Account</option>
									<option value="conf_saving">Confidential Saving Account</option>
							  </select>
							</div>
						</div>
						<div class="col-md-3">
							<label for="payment_mode">Payment Mode</label>
							<select class="form-control" id="payment_mode" disabled="disabled" name="payment_mode">
								<option value="cash">Cash</option>
								<option value="cheque">Cheque</option>
							</select>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label for="available_balance">Available Balance</label>
							  <span id="total_available_balance" class="form-control">0</span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
							  <label for="amount">Amount</label>
							  <input type="text" class="form-control" readonly required="required" value="<?php if(isset($_POST['amount'])) echo $_POST['amount']; ?>" id="amount" name="amount" placeholder="Enter Amount">
							</div>
						</div>
						
						
					</div>	
					
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
							  <label for="remarks">Remarks</label>
							  <textarea id="remarks" class="form-control"  name="remarks" placeholder="Enter Remarks"></textarea>
							</div>
						</div>
					</div>
					
				</div>	
					
                    
                  </div>
                
              </div>
			  
		  
		  
		  <div class="col-md-4">
		  <div class="box box-primary">
			  <div class="box-header with-border">
                  <h3 class="box-title"> Status</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
				  </div>
                </div><!-- /.box-header -->
				 <!--<div class="box-body">
					<span > Current Status : </span> Draft
				 </div>-->
			  	 <div class="box-footer">
                                     <?php   if($this->session->userdata('offday') == date('j')){
                                          ?><a href="javascript:" data-toggle="modal" data-target="#myModal" class="btn btn-danger pull-right">Add Entry</a>
                                         <?php } else{?>
					<button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Add Entry</button>
                                    <?php }?>
					
                  </div>
			  </div><!-- /.box -->	  
		  </div><!-- end col-md-4-->
		  </div>
		    </form>
			
			
			 <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
            
			
            <div class="box-body">
               <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        
                        <th width="15px" class="pad-right-5" >S.No.</th>
                        <th width="65px" class="pad-right-5">Client Name</th>
						<th width="65px" class="pad-right-5" >Charge On</th>
						<th width="65px" class="pad-right-5" >Amount <?php echo $myHelpers->config->item('cur_symbol'); ?></th>
						<th width="65px" class="pad-right-5" >Payment Mode</th>
						<th width="65px" class="pad-right-5" >Remarks</th>
						<th width="88px" class="pad-right-5" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
<?php  if ( isset($query) && $query->num_rows() > 0)
	   {		
		$i = 1;
		foreach ($query->result() as $row)
		{ 
			
?>						
                      <tr>
                        
						<td><?php echo  $i++; ?></td>
                        <td> <?php echo  ucfirst($row->first_name).' '.ucfirst($row->last_name); ?></td>
                        <td><?php echo  date('d-M-Y',$row->payment_date); ?></td>
						<td><?php echo  $row->payment_in; ?></td>
						<td><?php echo  ucfirst($row->payment_method); ?></td>
						<td><?php echo  $row->payment_log; ?></td>
						<td class="action_block">
							<a href="#" title="Edit" class="edit_entry" data-id="<?php echo $myHelpers->EncryptClientId($row->cap_acc_id); ?>">
							<i class="fa fa-edit fa-2x"></i></a>
							
							<a href="<?php $segments = array('charges_fees','delete','customer_charges',$myHelpers->EncryptClientId($row->cap_acc_id)); 
							echo site_url($segments);?>" title="Delete" class="delete-record"><i class="fa fa-remove fa-2x"></i></a>
							
							<!--
							<?php $segments = array('charges_fees','delete','customer_charges',$myHelpers->EncryptClientId($row->cap_acc_id));?>
							<a href="javascript:confirm_status('<?php echo site_url($segments);?>');"" title="Delete" class="delete-record"><i class="fa fa-remove fa-2x"></i></a>
							-->
						</td>
                      </tr>
<?php 	}
	}	?>                      
                      
                     
                     
					 
                    </tbody>
                    
                  </table>
                </div>
          </div><!-- /.box -->
			
			
        </section><!-- /.content -->
		
		
		
		
		
      </div><!-- /.content-wrapper -->
   <!-- DataTables -->
<?php 
	echo script_tag("themes/$theme/plugins/datatables/jquery.dataTables.min.js");
	echo script_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.min.js");
?>	  
  
  
	   <script>
      $(function () {
        //$("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": false
        });
		
		
		$('.content-box .select-all').click(function () {
			//alert($(this).is(':checked'));
			if ($(this).is(':checked'))
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', true);
			else
			{
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', false); 
				$(this).parent().parent().parent().parent().find(':checkbox').removeAttr('checked'); 
			}	
		});
      });
    </script>     