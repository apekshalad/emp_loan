<?php $this->load->view("default/header-top");?>
<?php $this->load->view("default/sidebar-left");?>

<?php

	$user_id = $myHelpers->session->userdata('user_id');
	$user_type = $myHelpers->session->userdata('user_type');

	function getWidgetAmounts($myHelpers , $trans_type){
	
		$user_id = $myHelpers->session->userdata('user_id');
		$user_type = $myHelpers->session->userdata('user_type');
		
		$total_current_saving = 0;
		$total_current_deposit = 0;
		$total_current_withdraw = 0;
		$total_current_charges = 0;
		
		if($user_type == "user")
		{
			$whr_client = " and client_ID = $user_id";
		}else {
			$whr_client = " ";
		}
		
		if(isset($trans_type) && $trans_type == 'conf_saving')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'conf_saving' $whr_client ";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			
			$query = "select sum(ca.payment_out) as total_current_expense from capital_account ca
			where ca.trans_type = 'expense' and ca.trans_from = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_expense;
			}
			
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges'  and ca.trans_from = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		else if(isset($trans_type) && $trans_type == 'withdraw')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and ca.trans_from = 'conf_saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
			
		}
		//employee_saving
		else if(isset($trans_type) && $trans_type == 'employee_saving')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'saving' and  ca.cap_by = 'employee' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and  ca.cap_by = 'employee' $whr_client ";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and ca.trans_from = 'saving' and  ca.cap_by = 'employee' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		else if(isset($trans_type) && $trans_type == 'saving')
		{
			$query = "select sum(ca.payment_in) as total_current_deposit from capital_account ca
			where ca.trans_type = 'saving' and  ca.cap_by = 'borrower' $whr_client ";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_deposit;
			}
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' and  ca.cap_by = 'borrower' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and ca.trans_from = 'saving' and  ca.cap_by = 'borrower' $whr_client ";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
		}
		else
		{
			$query = "select sum(ca.payment_in) as total_current_saving from capital_account ca
			where ca.trans_type = 'saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_deposit += $row->total_current_saving;
				//$total_current_saving += $row->total_current_saving;
			}
			
			
			$query = "select sum(ca.payment_out) as total_current_withdraw from capital_account ca
			where ca.trans_type = 'withdraw' and ca.trans_from = 'saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_withdraw += $row->total_current_withdraw;
			}
			//$total_current_saving = ($total_current_deposit - $total_current_withdraw);
			
			$query = "select sum(ca.payment_in) as total_current_charges from capital_account ca
			where ca.trans_type = 'charges' and ca.trans_from = 'saving' $whr_client";
			$result = $myHelpers->Common_model->commonQuery($query);
			if($result->num_rows() > 0)
			{
				$row = $result->row();
				$total_current_charges += $row->total_current_charges;
			}
			
			$total_current_saving = ($total_current_deposit - ($total_current_withdraw + $total_current_charges));
			
		}
		
		return $total_current_saving;
	
	}

?>


      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>		Dashboard 	  </h1>

	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	  </ol>
	</section>

	<!-- Main content -->
	<section class="content">

		<div class="row">
            
			<?php if($myHelpers->has_widget_access("running_loans_widget"))	
			  	  {
			?>
			<?php $this->load->view("$theme/widgets/running_loans_widget.php"); ?>
			<?php } ?>
			
			<?php if($myHelpers->has_widget_access("running_loan_amount_widget"))	 {?>
			<?php $this->load->view("$theme/widgets/running_loan_amount_widget.php"); ?>
			<?php } ?>
			
			<?php if($myHelpers->has_widget_access("total_loan_amount_widget"))	 {?>
			<?php $this->load->view("$theme/widgets/total_loan_amount_widget.php"); ?>
			<?php } ?>
			
			<?php if($myHelpers->has_widget_access("total_outstanding_amount_widget"))	 {?>
			<?php $this->load->view("$theme/widgets/total_outstanding_amount_widget.php"); ?>
             <?php } ?>
			
			
			<?php 
			$general_saving = 0;
			$employee_saving = 0;
			$conf_saving = 0;
			$withdraw = 0;
			?>
			
			
			<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
				  /*if (isset($total_general_saving_amount) && $total_general_saving_amount->num_rows() > 0)
				  { 
						foreach($total_general_saving_amount->result() as $row)
						{
							echo ($row->total_general_saving - $row->total_general_withdraw);
							$general_saving += ($row->total_general_saving - $row->total_general_withdraw);
			
						}
						
				  }
				  else
				  {
					echo 0;
				  }*/
				  	echo $general_saving = getWidgetAmounts($myHelpers , "saving");
				  
				  ?></h3>
                  <p>Total General Saving</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('saving','borrowers'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			
			<?php if( $user_type == 'admin') {?>
			
			<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
				  /*if (isset($total_employee_saving_amount) && $total_employee_saving_amount->num_rows() > 0)
				  { 
						foreach($total_employee_saving_amount->result() as $row)
						{
							echo ($row->total_employee_saving - $row->total_employee_withdraw);
							$employee_saving += ($row->total_employee_saving - $row->total_employee_withdraw);
			
						}
						
				  }
				  else
				  {
					echo 0;
				  }*/
				  echo $employee_saving = getWidgetAmounts($myHelpers , "employee_saving");
				  ?></h3>
                  <p>Total Employee Saving</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('saving','employers'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			
			<?php } ?>
			
			<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
				  /*if (isset($total_conf_saving_amount) && $total_conf_saving_amount->num_rows() > 0)
				  { 
						foreach($total_conf_saving_amount->result() as $row)
						{
							echo ($row->total_conf_saving - $row->total_conf_saving_withdraw);
							$conf_saving += ($row->total_conf_saving - $row->total_conf_saving_withdraw);
						}
						
				  }
				  else
				  {
					echo 0;
				  }*/
				  echo $conf_saving = getWidgetAmounts($myHelpers , "conf_saving");
				  ?></h3>
                  <p>Total Confidential Saving</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('saving','other_dpst'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			
			<!--
			<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
				  /*
				  if (isset($total_withdraw_amount) && $total_withdraw_amount->num_rows() > 0)
				  { 
						foreach($total_withdraw_amount->result() as $row)
						{
							echo $row->total_withdraw;
							$withdraw += $row->total_withdraw;
						}
						
				  }
				  else
				  {
					echo 0;
				  }*/
				  ?></h3>
                  <p>Total Withdraw</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a class="small-box-footer" href="<?php //$segments = array('saving','withdraw'); 
							//echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			-->
			
			<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
						echo (($general_saving + $employee_saving + $conf_saving ) - $withdraw);
					?></h3>
                  <p>Total Saving</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('saving','borrowers'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
			
			
			<?php if( $user_type == 'admin') {?>
			
			 <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php 
				  if (isset($total_client_list) && $total_client_list->num_rows() > 0)
				  { echo $total_client_list->num_rows(); }
				  else
					echo 0;
				  ?></h3>
                  <p>Clients</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('client','manage'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
			
			<?php } ?>
			
          </div><!-- /.row -->
          <?php if( $user_type == 'admin' || $user_type == 'manager') {?>
            <div class="row">
                <div class="col-md-3">                
                    <a href="<?php $segments = array('client','add_new'); echo site_url($segments);?>" class="btn btn-primary btn-block">Add Client</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('branch','add_new'); echo site_url($segments);?>" class="btn btn-primary btn-block"> Add New Branch</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('payment','my_payment'); echo site_url($segments);?>" class="btn btn-primary btn-block">Add Payment</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('loan','add_new'); echo site_url($segments);?>" class="btn btn-primary btn-block"> Add New Loan</a>                   
                </div>                
            </div>            
            <div class="row p-10"style="padding-top:15px; padding-bottom: 17px;">
                <div class="col-md-3">                
                    <a href="<?php $segments = array('client','manage'); echo site_url($segments);?>" class="btn btn-primary btn-block"> Manage Clients</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('branch','manage'); echo site_url($segments);?>" class="btn btn-primary btn-block">Manage Branches</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('payment','my_payment'); echo site_url($segments);?>" class="btn btn-primary btn-block">My Payments</a>                   
                </div>
                <div class="col-md-3">                
                    <a href="<?php $segments = array('loan','manage'); echo site_url($segments);?>" class="btn btn-primary btn-block">Manage Loans</a>                   
                </div>                
            </div>
            <?php } ?>
	  <div class="box box-default">
		<div class="box-header with-border">
		  <h3 class="box-title">Daily Cashbook</h3>
		  <div class="box-tools pull-right">
			
		  </div>
		</div><!-- /.box-header -->
		<div class="box-body">
		  <div class="row">
			<div class="
			<?php if($this->session->userdata('user_id') == 1) echo 'col-md-6'; else echo 'col-md-12';?>  table-responsive">
			  <label>Today's Loan</label>
			  
			  <table class="table" style=" box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Acc. No.</th>
                      <th>Loan Date</th>
                      <th>Principle (Tk.)</th>
					  <th>EMI</th>
					  <th>Outstanding</th>
				    </tr>
					
                    <?php
					$i=0;
					if (isset($today_loan_detail) && $today_loan_detail->num_rows() > 0)
					{					
						foreach ($today_loan_detail->result() as $row)
						{ $i++;
						$date = $row->loan_date;
						$end_date = strtotime("+".$row->time_periods." months" , $date);
						
						$total_amount_deposite  = $row->total_amount_deposite;
						$principal_amount  = $row->principal_amount;
						$outstanding_amount = ($row->time_periods * $row->emi_amount)  - $total_amount_deposite ;
						
				?>

						 
							<tr>
							  <td><?php echo $i;?></td>
							  <td><?php echo $row->customer_acc_no; ?></td>
							  <td><?php echo date("d/m/Y",$row->loan_date); ?></td>
							  <td><?php echo $row->principal_amount; ?></td>
							  <td><?php echo $row->emi_amount; ?></td>
							  <td><?php echo $outstanding_amount; ?></td>
							</tr>
				<?php	}
					}
					else
					{
						echo '<tr><td colspan="8">There is no record found.</td></tr>';
					}
				?>
                  </table>
                
			  
			</div><!-- /.col -->
			
			<?php 
			if($this->session->userdata('user_id') == 1){
			?>
			<div class="col-md-6 table-responsive">
				<label>Today's Total Cash Received </label>
				<table class="table" style=" box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Acc. No.</th>
					  <th>Loan Date</th>
                      <th>Principle (Tk.)</th>
					  <th>EMI</th>
					  <th>Deposite Amount</th>
					</tr>
					
                    <?php
					$i=0;
					if (isset($today_cash_received_detail) && $today_cash_received_detail->num_rows() > 0)
					{		
						$grand_total = 0;
						foreach ($today_cash_received_detail->result() as $row)
						{ 
							
							$i++;
							$grand_total += ($row->deposit_amount); 
							if($i > 10)
								continue;
				?>

						 
							<tr>
							  <td><?php echo $i;?></td>
							  <td><?php echo $row->customer_acc_no; ?></td>
							  <td><?php echo date("d/m/Y",$row->loan_date); ?></td>
							  <td><?php echo $row->principal_amount; ?></td>
							  <td><?php echo $row->emi_amount; ?></td>
							  <td><?php echo ($row->deposit_amount); ?></td>
							</tr>
				<?php	}
						echo '
							 <tr>
							  <td colspan="4"></th>
							  <th>Grand Total</th>
							  <th>'.$grand_total.'</th>
							</tr>
						';
					}
					else
					{
						echo '<tr><td colspan="8">There is no record found.</td></tr>';
					}
				?>
                  </table>
			</div>
			
			<?php } ?>
			
		  </div><!-- /.row -->
		</div><!-- /.box-body -->
		
	  </div>
      	
	
	<!--
	  <div class="row">

		<div class="col-md-6">



		  <div class="box box-danger">

			<div class="box-header">

			  <h3 class="box-title">Running Personal Loan</h3>

			</div>

			<div class="box-body table-responsive no-padding">
                  <table class="table">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Acc. No.</th>
                      <th>Loan Date</th>
                      <th>Principle (Rs.)</th>
					  <th>EMI</th>
					  <th>Outstanding</th>
					  <th>Penalty Deposited</th>
					  <th>Undeposite Penalty</th>
                    </tr>
					
                    <?php
					$i=0;
					if (isset($running_personal_loan_detail) && $running_personal_loan_detail->num_rows() > 0)
					{					
						foreach ($running_personal_loan_detail->result() as $row)
						{ $i++;
						$date = $row->loan_date;
						$end_date = strtotime("+".$row->time_periods." months" , $date);
						
						$total_amount_deposite  = $row->total_amount_deposite;
						$principal_amount  = $row->principal_amount;
						$outstanding_amount = $principal_amount  - $total_amount_deposite ;
						
				?>

						 
							<tr>
							  <td><?php echo $i;?></td>
							  <td><?php echo $row->customer_acc_no; ?></td>
							  <td><?php echo date("d/m/Y",$row->loan_date); ?></td>
							  <td><?php echo $row->principal_amount; ?></td>
							  <td><?php echo $row->emi_amount; ?></td>
							  <td><?php echo $outstanding_amount; ?></td>
							  <td><?php echo $row->total_penalty_deposite; ?></td>
							  <td><?php echo $row->total_penalty_undeposite; ?></td>
							</tr>
				<?php	}
					}
					else
					{
						echo '<tr><td colspan="8">There is no record found.</td></tr>';
					}
				?>
                  </table>
                </div>

		  </div>

		</div>

		<div class="col-md-6">

		  <div class="box box-primary">

			<div class="box-header">

			  <h3 class="box-title">Running Vehicle Loan</h3>

			</div>

			<div class="box-body table-responsive no-padding">
                  <table class="table">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Acc. No.</th>
                      <th>Loan Date</th>
                      <th>Principle (Rs.)</th>
					  <th>EMI</th>
					  <th>Outstanding</th>
					  <th>Penalty Deposited</th>
					  <th>Undeposite Penalty</th>
                    </tr>
					
                    <?php
					$i=0;
					if (isset($running_vehicle_loan_detail) && $running_vehicle_loan_detail->num_rows() > 0)
					{					
						foreach ($running_vehicle_loan_detail->result() as $row)
						{ $i++;
						$date = $row->loan_date;
						$end_date = strtotime("+".$row->time_periods." months" , $date);
						
						$total_amount_deposite  = $row->total_amount_deposite;
						$principal_amount  = $row->principal_amount;
						$outstanding_amount = $principal_amount  - $total_amount_deposite ;
						
				?>

						 
							<tr>
							  <td><?php echo $i;?></td>
							  <td><?php echo $row->customer_acc_no; ?></td>
							  <td><?php echo date("d/m/Y",$row->loan_date); ?></td>
							  <td><?php echo $row->principal_amount; ?></td>
							  <td><?php echo $row->emi_amount; ?></td>
							  <td><?php echo $outstanding_amount; ?></td>
							  <td><?php echo $row->total_penalty_deposite; ?></td>
							  <td><?php echo $row->total_penalty_undeposite; ?></td>
							</tr>
				<?php	}
					}
					else
					{
						echo '<tr><td colspan="8">There is no record found.</td></tr>';
					}
				?>
                  </table>
                </div>

		  </div>

		</div>

	  </div>

	-->

	</section>

  </div>

     