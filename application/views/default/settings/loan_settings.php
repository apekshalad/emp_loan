
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
     <?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
?> 
<script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
	
	
	$('.edit_entry').click(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).attr('data-id');
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_loan_type_entry_callback_func')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  $('#loan_type_title').val(data.loan_title);
				  $('.loan_type_id').val(data.loan_type_id);
				  $('#save_publish').attr('name','edit_entry').text('Save Entry');
				  $('.full_sreeen_overlay').hide();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
			return false;
		});
});
</script> 
<?php 
	if(isset($options_list) && $options_list->num_rows()>0)
	{
		
		foreach($options_list->result() as $row)
		{
			${$row->option_key} = $row->option_value;
		}
	}
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Loan Settings </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('settings/loan_settings',$attributes); 
			
			?>
			<input type="hidden" name="user_id" class="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">	
			<input type="hidden" name="loan_type_id" class="loan_type_id" value="">	
			<div class="row">
			<div class="col-md-8">   
			   
			<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Loan Settings</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
                </div><!-- /.box-header -->
				
				
                 
				  
				  <div class="box-body">
				
				<?php echo validation_errors(); 
					if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
				
				
				
				<div class="row">
					
					<div class="col-md-6">
						<div class="form-group">
						  <label for="loan_type_title">Loan Type Title</label>
						  <input type="text" class="form-control input-sm" required="required" id="loan_type_title" name="loan_type_title" placeholder="Enter Loan Type Title">					  
						</div>
					</div>
				<!--
					<div class="col-md-6">
						<div class="form-group">
						  <label for="interestRate">Interest Rate ( % per year)</label>
						  <input type="text" class="form-control input-sm" required="required" id="interestRate" name="interestRate" placeholder="Enter Interest Rate, 18 per year">					  
						</div>
					</div>
				-->
				
			
				</div>
				<!--
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
						  </select>
						  
						</div>
					</div>
				</div>
				-->
				
			  </div><!-- /.box-body -->
			  
              </div>
			  
			  
		  </div>
		  
		  <div class="col-md-4">
			  <div class="box box-primary">
				  <div class="box-header with-border">
					  <h3 class="box-title"> Status</h3>
					  <div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					  </div>
					</div>
					 
					 <div class="box-footer">
						<button name="submit" type="submit" class="btn btn-primary pull-right" id="save_publish">Save Changes</button>
					  </div>
				</div>
		  </div>
		  
		  
		  </div>
		  
			</form>
			
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
                        <th width="65px" class="pad-right-5">Loan Type Title</th>
						<th width="15px" class="pad-right-5" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php  if ( isset($loan_type_list) && $loan_type_list->num_rows() > 0)
						   {		
							$i = 1;
							foreach ($loan_type_list->result() as $row)
							{ 
								
					?>						
										  <tr>
											
											<td><?php echo  $i++; ?></td>
											<td><?php echo ucfirst($row->loan_title); ?></td>
											<td class="action_block">
												
												<a href="#" title="Edit" class="edit_entry" data-id="<?php echo $myHelpers->EncryptClientId($row->loan_type_id); ?>">
												<i class="fa fa-edit fa-2x"></i></a>
												<a href="<?php $segments = array('settings','delete','loan_settings',$myHelpers->EncryptClientId($row->loan_type_id)); 
												echo site_url($segments);?>" title="Delete" class="delete-record"><i class="fa fa-remove fa-2x"></i></a>
											</td>
										  </tr>
					<?php 	}
						}	
					?>                      
                      
                     
                     
					 
                    </tbody>
                    
                  </table>
                </div>
          </div><!-- /.box -->
			
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
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
      