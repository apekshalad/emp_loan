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
          <h1>
            Manage Loans
          </h1>
          
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
					/*
					 $next_month = mktime(0,0,0,01,31,2016);
					 echo date("m/d/Y", strtotime('+1 month', $next_month));*/
					
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        
						<th width="70px" class="pad-right-5" >Account No.</th>
						<th class="pad-right-5">Customer Name</th>
						<th class="pad-right-5">Loan Date</th>
						<th class="pad-right-5">End Date</th>
                        <th class="pad-right-5">Principal (<i class="fa fa-money"></i>)</th>
						<th class="pad-right-5">Interest (%)</th>
						<th class="pad-right-5">Time period</th>
						<th class="pad-right-5">EMI (<i class="fa fa-money"></i>)</th>
						<th class="pad-right-5">Outstanding (<i class="fa fa-money"></i>)</th>
                        <th class="pad-right-5" >Notify Count</th>
						<th class="pad-right-5" >Pay EMI</th>
						<th class="pad-right-5">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
<?php  if ($query->num_rows() > 0)
	   {					
		foreach ($query->result() as $row)
		{ 
		$time_periods = ($row->time_periods);
				$data2 = $myHelpers->Common_model->commonQuery("
						select * from loan_payments 
						where loan_payments.loan_id = $row->loan_id and deposit_amount > 0
						order by payment_id DESC 
						limit 1");
				
				if($data2->num_rows() > 0)
				{
					$loan_payment_last_row = $data2->row();
					$due_date = $loan_payment_last_row->due_date;
				}
				else
				{
					$due_date = $row->loan_date;
				}
				 $total_loan_payment_count = $myHelpers->Common_model->commonQuery("
						select * from loan_payments 
						where loan_payments.loan_id = $row->loan_id and deposit_amount > 0");
				 
				 $emi_paid = $total_loan_payment_count->num_rows();
				 
				 $p_date = $due_date;
				 
				 $e_date = time();
				 $datediff = ($e_date - $p_date);
				 $due_months = floor($datediff/(60*60*24*30));
				 if($due_months == 0 || $due_months < 0)
					$due_months = 1;
				 
				$next_month = $due_date;
				
				if($next_month > time() && $data2->num_rows() > 0)
					$due_months = 1;
				else if($next_month > time() && $data2->num_rows() == 0)
					$due_months = 0;
				
				$next_due_date = date("d/m/Y", strtotime('+'.$due_months.' month', $next_month));
				
				
				//$next_month = $due_date;
				if($data2->num_rows() > 0)
				{
					$next_month = strtotime('+1 month', $due_date);
				}
				else
				{
					$next_month = $due_date;
				}
				
				$next_due_date_after_one = $next_month;
				
				$p_date = $next_due_date_after_one;
				 $e_date = time();
				 $datediff = ($e_date - $p_date);
				 
				 $due_days = floor($datediff/(60*60*24));
				 $due_days = $due_days - 3;
				 if($due_days < 0)
					 $due_days = 0;
				 else if($due_days != 0 || $due_days > 0)
					 $due_days = $due_days;
				 
				
				 $EMI = $row->emi_amount;
				
				if($time_periods == $total_loan_payment_count->num_rows())
				 {
					 $EMI = $due_months = 0;
				 }
				
				
				$date_explode = explode('/',date('d/m/Y',time()));
				$today_date = mktime(23,59,59,$date_explode[1],$date_explode[0],$date_explode[2]);
				
				$sql = "select * from loan_payments 
						where loan_id = $row->loan_id and due_date > $today_date";
				$advance_payment_month = 0;
				$advance_payment_date = 0;				
				$advance_loan_payments_data = $myHelpers->Common_model->commonQuery("$sql");
				if($advance_loan_payments_data->num_rows() > 0)
				{
					$advance_payment_month = $advance_loan_payments_data->num_rows();
					foreach($advance_loan_payments_data->result() as $advance_row)
					{
						$advance_payment_date = $advance_row->due_date;
					}
				}
				
				if($row->payment_terms == 'weekly')
					$end_date = date("d/m/Y", strtotime('+'.$time_periods.' week', $row->loan_date));
				else
					$end_date = date("d/m/Y", strtotime('+'.$time_periods.' month', $row->loan_date));
				
				/*
				if($row->emi_type == 'flat_emi')
				{
				*/
					$interestYearly = (( $row->principal_amount  * $row->interest_rate) / 100);
					$interestMonthly = ($interestYearly / 12);
				/*
				}
				else
				{
					$interestYearly = $row->emi_amount * 12;
					$interestMonthly = $row->emi_amount;
				}
				*/				
				$totalInterest	 = (($row->time_periods ) * $interestMonthly );
				$total_amount = $row->principal_amount + $totalInterest;
				$total_amount_deposite  = $row->total_amount_deposite;
				//$outstanding_amount = round(($totalInterest + $row->principal_amount)  - $total_amount_deposite) ;
				
				$total_amount_to_be_paid = (($row->time_periods) * $row->emi_amount );
				//$outstanding_amount = round(($totalInterest + $row->principal_amount)  - $total_amount_deposite) ;
				$outstanding_amount = round(($total_amount_to_be_paid)  - $total_amount_deposite) ;
?>						
                      <tr>
						
                        <td><?php echo  $row->client_acc_no; ?></td>
						<td><?php echo  ucfirst($row->client_name); ?></td>
                        <td><?php echo  date('d/m/Y',$row->loan_date); ?></td>
						<td><?php echo  $end_date; ?></td>
						<td><?php echo  $row->principal_amount; ?></td>
						<td><?php echo  $row->interest_rate; ?></td>
						<td><?php echo  $time_periods; ?></td>
						<td><?php echo  $row->emi_amount; ?></td>
						
						
						<td><?php echo $outstanding_amount; ?></td>
                         <td><?php echo $row->notify_install_count; ?></td>
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
							echo site_url($segments);?>" title="Invoice" > Invoice</a> 
							<?php if($this->session->userdata('user_id') == 1) { ?>
							<a href="<?php $segments = array('loan','delete',$myHelpers->EncryptClientId($row->loan_id)); 
							echo site_url($segments);?>" title="Delete" class="delete-record" ><i class="fa fa-remove fa-2x"></i></a>
							<?php } ?>
						</td>
                      </tr>
<?php 	}
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