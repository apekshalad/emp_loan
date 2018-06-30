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
   var global_client_id = '';
   $('.edit_entry').click(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).attr('data-id');
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_deposit_saving_entry_callback_func')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  global_client_id = data.client_ID;
				  $('.client_list').find('option').attr("disabled",true);
				  $('.client_list').val(data.client_ID).removeAttr('required');
				  $('.client_list option[value='+data.client_ID+']').attr('selected','selected')
				  $('.client_list').trigger("change");
				  $('#date_picker').val(data.payment_date);
				  $('#payment_mode').val(data.payment_mode);
				  $('#amount').val(data.amount);
				  $('#cap_acc_id').val(data.cap_acc_id);
				  $('#total_current_saving').html(data.total_current_saving);
				  $('#remarks').val(data.remarks);
				  $('#save_publish').attr('name','edit_entry').text('Save Entry');
				  
				  $('.per_mode,.fix_mode').hide();
				  $('.per_mode,.fix_mode').find('input').removeAttr('required');
				   $('.per_mode,.fix_mode').find('input').val('');
				  $('#ProfitOption').val(data.profit_type);
				  if(data.profit_type == 'per')
				   {
					   //$('.per_mode').find('input').val(data.profit_value).attr('required','required');
					   $('.per_mode').show();
				   }
				   else if(data.profit_type == 'fix')
				   {	  
						//$('.fix_mode').find('input').val(data.profit_value).attr('required','required');
						$('.fix_mode').show();
				   }
				  $('.full_sreeen_overlay').hide();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
			return false;
		});
   
   
   $('.client_list').change(function() {
			var id = $(this).val();
			if(id == '' || id == null)
			{
				id = global_client_id; 
			}
			if(id != '' && id != null)
			{
				$('#payment_mode').val('cash').attr('disabled',false);
				$('#payment_mode').trigger('change');
			}
			else 
			{
				$('#payment_mode').val('cash').attr('disabled',true);
				$('#amount').val('').attr('readonly',true);
			}
			return false;
		});
		
		$('#payment_mode').change(function() {
			$('.full_sreeen_overlay').show();
			var id = $('.client_list').val();
			var payment_mode = $(this).val();
			var trans_type = $('.client_list').attr('data-trans_type');
			if(payment_mode != '' && payment_mode != null)
			{
				
				$.ajax({
				  url: "<?php echo site_url(array('ajax','fetch_borrower_current_saving_callback_func')); ?>",
				  type: "POST",
				  data:{id: id,trans_type:trans_type,payment_mode:payment_mode},
				  cache: false,
				  success: function(data){
					  
					  $('#total_current_saving').val(data.total_current_saving);
					  $('#total_current_saving_span').html(data.total_current_saving);
					  $('#amount').val('').attr('readonly',false);
					  $('.full_sreeen_overlay').hide();
					  
					  $("html, body").animate({ scrollTop: 0 }, "slow");
				  }
				});
			}
			else
			{
				$('#total_current_saving').val('0');
				$('#total_current_saving_span').html('0');
				$('.full_sreeen_overlay').hide();
				$('#amount').val('').attr('readonly',true);
			}
			return false;
		});
   
   $('.profit_type').change(function() {
	   $('.full_sreeen_overlay').show();
	   var mode = $(this).val();
	   $('.per_mode,.fix_mode').hide();
	   $('.per_mode,.fix_mode').find('input').removeAttr('required');
	   if(mode == 'per')
	   {
		   $('.per_mode').find('input').attr('required','required');
		   $('.per_mode').show();
	   }
	   else if(mode == 'fix')
	   {	  
			$('.fix_mode').find('input').attr('required','required');
			$('.fix_mode').show();
	   }
	   $('.full_sreeen_overlay').hide();
   });
   
   if($("input[name=dpst_option]").is(':checked'))
		{
			if($("input[name=dpst_option]:checked").val()  == 'general')
			{	$('#dpst_general_block').fadeIn();	}
			
			if($("input[name=dpst_option]:checked").val()  == 'profit')
			{	$('#dpst_profit_block').fadeIn();	}
			
			
		}
		
		
		$("input[name=dpst_option]").on('change',function(i){
			//alert('');
			if($(this).val()  == 'general')
			{	
				$('#dpst_profit_block').hide();
				$('#dpst_general_block').fadeIn();	
			}
			
			if($(this).val()  == 'profit')
			{	
				$('#dpst_general_block').hide();
				$('#dpst_profit_block').fadeIn();	
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
          <h1> Deposits </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('saving/other_dpst',$attributes); ?>
			   <input type="hidden" name="cap_acc_id" id="cap_acc_id" value="">
			<div class="row">
			<div class="col-md-8">   
			   
			<!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Deposit's Information</h3>
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
						
						<div class="col-md-6">
							<label for="payment_mode">Payment Mode</label>
							<select class="form-control" id="payment_mode" name="payment_mode" disabled>
								<option value="cash">Cash</option>
								<option value="cheque">Cheque</option>
							</select>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="total_current_saving">Total Deposits </label>
							  <span id="total_current_saving_span" class="form-control">0</span>
							  <input type="hidden" name="total_current_saving" id="total_current_saving" value="0" />
							</div>
						</div>

					</div>	
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
							  <label for="other_dpst_option">Deposit Options </label>
							  
							  <div class="row">
							  <div class="col-md-6">
							  <input type="radio" id="dpst_general" name="dpst_option" value="general" checked="checked"  />&nbsp;&nbsp;
							  <label for="dpst_general">General Deposit </label>
							  </div>
							  
							  <div class="col-md-6">
							  <input type="radio" id="dpst_profit" name="dpst_option"  value="profit"  />&nbsp;&nbsp;
							  <label for="dpst_profit">Deposit Profit</label>
							  </div>
							  </div>
							  
							</div>
						</div>
					</div>
					
					<div class="row" id="dpst_general_block"  style="display:none;">
						<div class="col-md-6">
							<div class="form-group">
							  <label for="amount">Amount</label>
							  <input type="text" readonly class="form-control" value="<?php if(isset($_POST['amount'])) echo $_POST['amount']; ?>" id="amount" name="amount" placeholder="Enter Amount">
							</div>
						</div>
						
					</div>
					
					
					
					<div class="row" id="dpst_profit_block" style="display:none;">
						<div class="col-md-6">
							<div class="form-group">
							  <label for="ProfitOption">Add Profit</label>
							  <select class="form-control input-sm profit_type" name="profit_type" id="ProfitOption">	
									<option value="">Select Profit Mode</option>
									<option value="per">Percentage(%)</option>
									<option value="fix">Fixed</option>
							  </select>
							</div>
						</div>
						
						<div class="col-md-6 per_mode" style="display:none;">
							<div class="form-group">
							  <label for="ProfitPer">Enter Profit Percentage</label>
							  <div class="input-group">
								  <input type="text" class="form-control" value="<?php if(isset($_POST['ProfitPer'])) echo $_POST['ProfitPer']; ?>" id="ProfitPer" name="ProfitPer" placeholder="Enter Profit Percentage">
								  <span class="input-group-addon"><i class="fa fa-percent"></i></span>
							  </div>
							</div>
						</div> 
						
						<div class="col-md-6 fix_mode" style="display:none;">
							<div class="form-group">
							  <label for="ProfitFix">Enter Fixed Profit Amount</label>
							  <input type="text" class="form-control" value="<?php if(isset($_POST['ProfitFix'])) echo $_POST['ProfitFix']; ?>" id="ProfitFix" name="ProfitFix" placeholder="Enter Fixed Profit Amount">
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
						<th width="65px" class="pad-right-5" >Payment Date</th>
						<th width="65px" class="pad-right-5" >Amount (<i class="fa fa-money"></i>)</th>
						<!--<th width="65px" class="pad-right-5" >Remarks</th>-->
						<th width="65px" class="pad-right-5" >Profit Mode</th>
						<th width="65px" class="pad-right-5" >Profit Amount (<i class="fa fa-money"></i>)</th>
						<th width="65px" class="pad-right-5" >Payment Mode</th>
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
                        <td><?php echo  date('d/m/Y',$row->payment_date); ?></td>
						<td><?php echo  $row->payment_in; ?></td>
						<!--<td><?php echo  $row->payment_log; ?></td>-->
						<td><?php 
							if($row->profit_type == 'per')
							{
								echo 'Percentage ('.$row->profit_value.'%)';
							}
							else if($row->profit_type == 'fix')
							{
								echo 'Fixed';
							}
							else
								echo '-';
						?></td>
						<td><?php 
						if($row->profit_type == 'per' || $row->profit_type == 'fix')
							{
								echo $row->profit_amount;
							}
							else
								echo '-';
						 ?></td>
						<td><?php echo  ucfirst($row->payment_method); ?></td>
						<td class="action_block">
							<a href="#" title="Edit <?php echo $row->cap_acc_id; ?>" class="edit_entry" data-id="<?php echo $myHelpers->EncryptClientId($row->cap_acc_id); ?>">
							<i class="fa fa-edit fa-2x"></i></a>
							<a href="<?php $segments = array('saving','delete','other_dpst',$myHelpers->EncryptClientId($row->cap_acc_id)); 
							echo site_url($segments);?>" title="Delete" class="delete-record"><i class="fa fa-remove fa-2x"></i></a>
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