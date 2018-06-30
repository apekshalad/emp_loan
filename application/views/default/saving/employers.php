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
   
   $('.edit_entry').click(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).attr('data-id');
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_borrower_saving_entry_callback_func')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  $('#Client').find('option').attr("disabled",true);
				  $('#Client').val(data.client_ID).removeAttr('required');
				  $('.client_list option[value='+data.client_ID+']').attr('selected','selected');
				  $('.client_list').trigger("change");
				  $('#date_picker').val(data.payment_date);
				  $('#amount').val(data.amount);
				  $('#payment_mode').val(data.payment_mode);
				  $('#cap_acc_id').val(data.cap_acc_id);
				  $('#total_current_saving').html(data.total_current_saving);
				  $('#remarks').val(data.remarks);
				  $('#save_publish').attr('name','edit_entry').text('Save Entry');
				  $('.full_sreeen_overlay').hide();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
			return false;
		});
   
   
   $('.client_list').change(function() {
			var id = $(this).val();
			if(id != '' && id != null)
			{
				
				$('#payment_mode').val('cash').attr('disabled',false);
				$('#payment_mode').trigger('change');
			}
			else
			{
				$('#payment_mode').val('cash').attr('disabled',true);
				$('#amount').val('cash').attr('readonly',true);
			}
			return false;
		});
   
   $('#payment_mode').change(function() {
	$('.full_sreeen_overlay').show();
	var id = $('.client_list').val();
	var payment_mode = $(this).val();
	if(payment_mode != '' && payment_mode != null)
	{
		
		$.ajax({
		  url: "<?php echo site_url(array('ajax','fetch_borrower_current_saving_callback_func')); ?>",
		  type: "POST",
		  data:{id: id,payment_mode:payment_mode},
		  cache: false,
		  success: function(data){
			  
			  $('#total_current_saving').html(data.total_current_saving);
			  $('#amount').val('').attr('readonly',false);
			  $('.full_sreeen_overlay').hide();
			  
			  $("html, body").animate({ scrollTop: 0 }, "slow");
		  }
		});
	}
	else
	{
		$('#total_current_saving').html('0');
		$('.full_sreeen_overlay').hide();
		$('#amount').val('').attr('readonly',true);
	}
	return false;
});
   
});

</script>

      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Employee's Savings </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('saving/employers',$attributes); ?>
			   <input type="hidden" name="cap_acc_id" id="cap_acc_id" value="">
			<div class="row">
			<div class="col-md-8">   
			   
			<!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Employee's Information</h3>
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
							  <label for="Client">Client</label>
							  <select class="form-control select2 input-sm client_list" name="client_ID" required id="Client">	
									<option value="">Select Client</option>
									<?php if(isset($client_list) && $client_list->num_rows() > 0)
									{
										foreach($client_list->result() as $client_row)
										{
											$acc_text ='()';
											if(isset($client_row->acc_no) && !empty($client_row->acc_no))
												$acc_text = '('.$client_row->acc_no.')';
											echo '<option value="'.$myHelpers->EncryptClientId($client_row->user_id).'">
											'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' '.$acc_text.'</option>';
										}
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
							  <label for="total_current_saving">Total Current Savings </label>
							  <span id="total_current_saving" class="form-control">0</span>
							</div>
						</div>

					</div>	
					
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="amount">Amount</label>
							  <input type="text" class="form-control" readonly required="required" value="<?php if(isset($_POST['amount'])) echo $_POST['amount']; ?>" id="amount" name="amount" placeholder="Enter Amount">
							</div>
						</div>
					</div>
					
					<div class="form-group">
                      <label for="remarks">Remarks</label>
                      <textarea id="remarks" class="form-control"  name="remarks" placeholder="Enter Remarks"></textarea>
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
                        
                        <th width="75px" class="pad-right-5" >S.No.</th>
                        <th width="65px" class="pad-right-5">Client Name</th>
						<th width="65px" class="pad-right-5" >Payment Date</th>
						<th width="65px" class="pad-right-5" >Amount (<i class="fa fa-money"></i>)</th>
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
                        <td><?php echo  date('d/m/Y',$row->payment_date); ?></td>
						<td><?php echo  $row->payment_in; ?></td>
						<td><?php echo  ucfirst($row->payment_method); ?></td>
						<td><?php echo  $row->payment_log; ?></td>
						<td class="action_block">
							<a href="#" title="Edit" class="edit_entry" data-id="<?php echo $myHelpers->EncryptClientId($row->cap_acc_id); ?>">
							<i class="fa fa-edit fa-2x"></i></a>
							<a href="<?php $segments = array('saving','delete','employers',$myHelpers->EncryptClientId($row->cap_acc_id)); 
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