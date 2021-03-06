<!-- DataTables -->
<?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
echo link_tag("themes/$theme/plugins/datepicker/datepicker3.css");
echo script_tag("themes/$theme/plugins/datepicker/bootstrap-datepicker.js");
?>
 <script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
 
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
				//$('.content-wrapper').append(res);
				
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
</script>   
<style type="text/css">
.fa-2x {
    font-size: 1.2em;
}
.w62 { width:62px;}
.pad-right-5 { padding-right:5px!important; }
</style>
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Manage Missed Payments </h1>
          <!--<ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Advanced Elements</li>
          </ol>-->
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              
            </div><!-- /.box-header -->
            <div class="box-body">
               <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
				/*	 echo '<pre>';
			 print_r($_SESSION);
			 echo '</pre>';*/
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <!--
						<th width="10px" class="bSortable"><input type="checkbox" class="checkbox select-all" /></th>
                        -->
						
                        <th width="75px" class="pad-right-5" >S No.</th>
                        <th>Name</th>
                        <th class="pad-right-5" >Mobile No.</th>
						<th class="pad-right-5">Loan Date</th>
						<th class="pad-right-5">Principal (<i class="fa fa-money"></i>)</th>
						<th class="pad-right-5">Installments</th>
						<th class="pad-right-5">EMI (<i class="fa fa-money"></i>)</th>
						<th class="pad-right-5">Missed EMI</th>
						<th class="pad-right-5">Last Due Date</th>
						<th class="pad-right-5" >Pay EMI</th>
						<th width="150px" class="pad-right-5" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
<?php  if ($query->num_rows() > 0)
	   {					
		$i=1;
		foreach ($query->result() as $row)
		{ 
			$time_periods = ($row->time_periods);
			
			$total_loan_payment_count = $myHelpers->Common_model->commonQuery("
					select * from loan_payments 
					where loan_payments.loan_id = $row->loan_id and deposit_amount > 0");
			 
			 $emi_paid = $total_loan_payment_count->num_rows();
			
			$cur_time = time();
			if(!empty($row->next_due_date))
			{
				$due_date = $row->next_due_date;
			}
			else
			{
				$due_date = $row->loan_date;
			} 
			//$cur_time = strtotime('-3 day', $cur_time);
			//echo date('d-M-Y',$due_date).' '.date('d-M-Y',$cur_time).'<br>';
			$datediff = ($cur_time - $due_date);
			$due_days = floor($datediff/(60*60*24));
			if($due_days > 0)
			{ 
				$due_months = round($datediff/(30*60*60*24));
				$new_due_date = strtotime("+$due_months month", $due_date);
				$datediff = ($cur_time - $new_due_date);
				$due_days = floor($datediff/(60*60*24));
				if($due_days <= 0)
					$due_months = $due_months-1;
				if($due_months > 0)
				{
					
				
			
?>						
                      <tr>
                        
						<td><?php echo  $i++; ?></td>
                        <td> <?php echo  $row->client_name; ?></td>
                        <td><?php echo  $row->client_mobile; ?></td>
						<td><?php echo  date('d/m/Y',$row->loan_date); ?></td>
						<td><?php echo  $row->principal_amount; ?></td>
						<td><?php echo  $row->time_periods; ?></td>
						<td><?php echo  $row->emi_amount; ?></td>
						<td><strong><?php echo  $due_months; ?></strong></td>
						<td><?php echo  date('d/m/Y',$due_date); ?></td>
						<td> 
							<?php 
								if((($time_periods  - $emi_paid) > 0) )
								{
                                                                    if($this->session->userdata('offday') == date('j')){
                                                                    ?><a href="javascript:" data-toggle="modal" data-target="#myModal" title="Pay Now"><i class="fa fa-credit-card fa-2x"></i></a>
                                                                        <?php } else{?>
								<a class="pay_now_model" data-id="<?php echo $myHelpers->EncryptClientId($row->loan_id); ?>" 
								href="#" title="Pay Now" ><i class="fa fa-credit-card fa-2x"></i></a>
                                                                    <?php }}else if(((($time_periods ) - $emi_paid) == 0 )){
								echo '<span style="color:red;">Closed</span>';
							}?>
						 </td>
						<td class="action_block">
							
							<a href="<?php $segments = array('loan','invoice',$myHelpers->EncryptClientId($row->loan_id)); 
							echo site_url($segments);?>" title="Invoice">Invoice</a>
							
						</td>
                      </tr>
<?php 	
				}
			}
		}
	}	?>                      
                      
                     
                     
					 
                    </tbody>
                    
                  </table>
                </div>
          </div><!-- /.box -->

          <!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

	  <!-- DataTables -->
<?php 
	echo script_tag("themes/$theme/plugins/datatables/jquery.dataTables.min.js");
	echo script_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.min.js");
?>	  
    <!-- SlimScroll -->
<?php 
	echo script_tag("themes/$theme/plugins/slimScroll/jquery.slimscroll.min.js");
?>
	<!-- FastClick -->
<?php 
	echo script_tag("themes/$theme/plugins/fastclick/fastclick.min.js");
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