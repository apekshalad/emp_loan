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
    $("#account_manager").change(function(){

    	var id = $(this).val();
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_empolyee_salary')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  $('#amount').val(data.amount);
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
			return false;
    });
    $('.edit_entry').click(function() {
			$('.full_sreeen_overlay').show();
			var id = $(this).attr('data-id');
			$.ajax({
			  url: "<?php echo site_url(array('ajax','fetch_expense_entry_callback_func')); ?>",
			  type: "POST",
			  data:{id: id},
			  cache: false,
			  success: function(data){
				  $('#date_picker').val(data.payment_date);
				  $('#amount').val(data.amount);
				  $('#exp_id').val(data.cap_acc_id);
				  $('#remarks').val(data.remarks);
				  $('#paymentMethod').val(data.payment_method);
				   $('#show').val(data.expense_type);
				   console.log(data.expense_type);
				   if(data.expense_type=='0'){
				   	$('#gg').show();
				   	 $('#account_manager').val(data.account_manager);
				   }
				   else{
				   		$('#gg').hide();
				   }
				  $('#save_publish').attr('name','edit_entry').text('Save Entry');
				  $('.full_sreeen_overlay').hide();
				  $("html, body").animate({ scrollTop: 0 }, "slow");
			  }
			});
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
          <h1> Add Expenses </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('charges_fees/add_expenses',$attributes); ?>
			   <input type="hidden" name="exp_id" id="exp_id" value="">
			<div class="row">
			<div class="col-md-8">   
			   
			<!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Expense's Information</h3>
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
							  <label for="date_picker">Date</label>
							  <input type="text" class="form-control" required="required"  id="date_picker" name="pay_date" placeholder="Enter Date"  value="<?php if(isset($_POST['pay_date'])) echo $_POST['pay_date']; else { echo date('m/d/Y',time()); } ?>">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="paymentMethod">Expense Type</label>
							  <select id="show" name="expense_type" class="form-control  input-sm">
            								<option value="">Select Expense Type </option>
            								<option value="0" >Salary</option>
                							<?php 
                                            $sqlPro1=$this->db->select("*")->from("expense_category")->get()->result();
                            				foreach($sqlPro1 as $sqlPro2)
                                            {
                                                
                            					?>
                    							<option value="<?php echo $sqlPro2->id?>"><?php echo $sqlPro2->category_name?></option>
                                                <?php 
                                            }?>
            						  </select>
							</div>
						</div>
						
					</div>	
					
					<div class="row">
						<div class="col-md-6" style="display:none" id="gg">
							<div class="form-group">
							  <label for="paymentMethod">Employee name</label>
							  <select name="account_manager" id="account_manager" class="form-control  input-sm">
                      	<option value="">Select Employee Name</option>
                      	<?php 

                      	 $manage = $this->Common_model->commonQuery("select * from users where user_type != 'admin' order by user_id DESC");	

                            if(isset($manage) && $manage->num_rows() > 0)
							{
								foreach($manage->result() as $manages)
								{ $name='';
									if($myHelpers->global->get_user_meta($manages->user_id,'first_name'))
											$name = $myHelpers->global->get_user_meta($manages->user_id,'first_name').' '.$myHelpers->global->get_user_meta($manages->user_id,'last_name');
									echo '<option value="'.$manages->user_id.'">
											'.ucfirst($name).'</option>';
								}
							}
							?>
                      </select>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="amount">Amount</label>
							  <input type="text" class="form-control" required="required" value="<?php if(isset($_POST['amount'])) echo $_POST['amount']; ?>" id="amount" name="amount" placeholder="Enter Amount">
							</div>
						</div>
						
					</div>	
					
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
							  <label for="paymentMethod">Payment Method</label>
							  <select class="form-control input-sm" name="payment_method" id="paymentMethod">	
									<option value="cash">Cash</option>
									<option value="cheque">Cheque</option>
							  </select>
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
                        <th width="65px" class="pad-right-5" >Expense Date</th>
						<th width="65px" class="pad-right-5" >Amount <?php echo $myHelpers->config->item('cur_symbol'); ?></th>
						<th width="65px" class="pad-right-5" >Payment Mode</th>
						<th width="65px" class="pad-right-5" >Expense Type</th>
						<th width="65px" class="pad-right-5" >Employee Name</th>
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
			$accountmanagent='';
			if($myHelpers->global->get_user_meta($row->account_manager,'first_name'))
			$accountmanagent = $myHelpers->global->get_user_meta($row->account_manager,'first_name').' '.$myHelpers->global->get_user_meta($row->account_manager,'last_name');
		$expense_id=$row->expense_type;

		$expense_name=$this->db->get_where('expense_category',array('id'=>$expense_id))->row()->category_name;


		
			
?>						
                      <tr>
                        
						<td><?php echo  $i++; ?></td>
                        <td><?php echo  date('d-M-Y',$row->payment_date); ?></td>
						<td><?php echo  $row->payment_out; ?></td>
						<td><?php echo  ucfirst($row->payment_method); ?></td>
						<td><?php  if($row->expense_type==0){echo 'Salary';}else{echo $expense_name;  } ?></td>
						<td><?php echo  $accountmanagent; ?></td>
						<td><?php echo  $row->payment_log; ?></td>

						<td class="action_block">
							<a href="#" title="Edit" class="edit_entry" data-id="<?php echo $myHelpers->EncryptClientId($row->cap_acc_id); ?>">
							<i class="fa fa-edit fa-2x"></i></a>
							<a href="<?php $segments = array('charges_fees','delete','add_expenses',$myHelpers->EncryptClientId($row->cap_acc_id)); 
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
   
    $("#show").change(function(){
    	if($(this).val()==0){
        $("#gg").show();
        $("#amount").prop('disabled', true);
    }
    else { $("#gg").hide();
        $("#amount").prop('disabled', false);
    }
    });
});
</script>  