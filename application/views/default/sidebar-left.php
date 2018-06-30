<?php 
	 $user_type = $this->session->userdata('user_type');
	 
	 /*if($myHelpers->has_menu_access("payment||my_payment" , $user_type))
	 	echo "has access";
	 else
	 	echo "stop";*/	
?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
	  <!-- Sidebar user panel -->
	  
	  <!-- sidebar menu: : style can be found in sidebar.less -->
	  <ul class="sidebar-menu">
		<li class="header">MAIN NAVIGATION</li>
		<li <?php if( $class =='home'){ echo 'class="active"';}?>>
		  <a href="<?php $segments = array('main'); echo site_url($segments);?>">
			<i class="fa fa-dashboard"></i> <span>Dashboard</span>
		  </a>
		  
		</li>
		
		<?php 
		if($myHelpers->has_menu_access("client" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='client'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>CRM</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		  <?php 
		if($myHelpers->has_menu_access("client||manage" , $user_type)){  ?>
			<li <?php if($class =='client' && $func =='manage'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('client','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Clients</a></li>
				<?php  } ?>
				<?php if($myHelpers->has_menu_access("client||add_new" , $user_type)){  ?>
			<li <?php if($class =='client' && $func =='add_new'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('client','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New Client</a></li>
			<?php  } ?>
		  </ul>
		</li>
		
		<?php } ?>
        <?php 
		if($myHelpers->has_menu_access("branch" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='expenses'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Expense Category</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		<?php  if($myHelpers->has_menu_access("branch||manage" , $user_type)){  ?>
			<li <?php if($class =='expenses' && $func =='manage'){ echo "class='active'";}?>><a href="<?php $segments = array('expense','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Expense Category</a></li>
			<?php  } ?>
			<?php  if($myHelpers->has_menu_access("loan||add_new" , $user_type)){  ?>
			<li <?php if($class =='expenses' && $func =='add_new'){ echo "class='active'";}?>><a href="<?php $segments = array('expense','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New Expense Category</a></li>
			<?php  } ?>
		  </ul>
		</li>
	<?php } ?>
	
        <?php 
		if($myHelpers->has_menu_access("branch" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='branch'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Branches</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		<?php  if($myHelpers->has_menu_access("branch||manage" , $user_type)){  ?>
			<li <?php if($class =='branch' && $func =='manage'){ echo "class='active'";}?>><a href="<?php $segments = array('branch','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Branches</a></li>
			<?php  } ?>
			<?php  if($myHelpers->has_menu_access("loan||add_new" , $user_type)){  ?>
			<li <?php if($class =='branch' && $func =='add_new'){ echo "class='active'";}?>><a href="<?php $segments = array('branch','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New Branch</a></li>
			<?php  } ?>
		  </ul>
		</li>
	<?php } ?>
		
		<?php 
		if($myHelpers->has_menu_access("loan" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='loan'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Loans</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		<?php  if($myHelpers->has_menu_access("loan||manage" , $user_type)){  ?>
			<li <?php if($class =='loan' && $func =='manage'){ echo "class='active'";}?>><a href="<?php $segments = array('loan','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Loans</a></li>
			<?php  } ?>
			<?php  if($myHelpers->has_menu_access("loan||add_new" , $user_type)){  ?>
			<li <?php if($class =='loan' && $func =='add_new'){ echo "class='active'";}?>><a href="<?php $segments = array('loan','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New Loan</a></li>
			<?php  } ?>
		  </ul>
		</li>
	<?php } ?>
		
		<?php 
		if($myHelpers->has_menu_access("payment" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='payment'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Payments</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<?php 
			if($myHelpers->has_menu_access("payment||borrowers" , $user_type)){ 
			?>
			<li <?php if($class =='payment' && $func =='borrowers'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('payment','borrowers'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Borrower's Payment</a></li>
			<?php } ?>
			<?php 
			if($myHelpers->has_menu_access("payment||missed" , $user_type)){ 
			?>
			<li <?php if($class =='payment' && $func =='missed'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('payment','missed'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Missed Payments</a></li>
			<?php } ?>
			<?php 
			if($myHelpers->has_menu_access("payment||my_payment" , $user_type)){ 
			?>
			<li <?php if($class =='payment' && $func =='my_payment'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('payment','my_payment'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> My Payments</a></li>
			<?php } ?>
			<?php 
			if($myHelpers->has_menu_access("payment||file_import" , $user_type)){ 
			?>


			<li <?php if($class =='payment' && $func =='file_import'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('payment','file_import'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> File Import</a></li>
			<li <?php if($class =='payment' && $func =='masspost'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('payment','masspost'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i>Mass Posting </a></li>
			<?php } ?>
		  </ul>
		</li>
	<?php } ?>	
	
		<?php 		
		if($myHelpers->has_menu_access("saving" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='saving'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Savings</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		   <?php 
			if($myHelpers->has_menu_access("saving||borrowers" , $user_type)){ 
			?>
			<li <?php if($class =='saving' && $func =='borrowers'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('saving','borrowers'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> General Savings</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("saving||employers" , $user_type)){ 
			?>
			<li <?php if($class =='saving' && $func =='employers'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('saving','employers'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Employee's Savings</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("saving||withdraw" , $user_type)){ 
			?>
			<li <?php if($class =='saving' && $func =='withdraw'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('saving','withdraw'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Withdraw</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("saving||other_dpst" , $user_type)){ 
			?>
			<li <?php if($class =='saving' && $func =='other_dpst'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('saving','other_dpst'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Confidential Savings</a></li>
			<?php } ?>	
		  </ul>
		</li>
		<?php } ?>	
		
		<?php 		
		if($myHelpers->has_menu_access("charges_fees" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='charges_fees'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Charges & Fees</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		    <?php 
			if($myHelpers->has_menu_access("charges_fees||add_expenses" , $user_type)){ 
			?>
			<li <?php if($class =='charges_fees' && $func =='add_expenses'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('charges_fees','add_expenses'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Add Expenses</a></li>
			<?php } ?>
			 <?php 
			if($myHelpers->has_menu_access("charges_fees||customer_charges" , $user_type)){ 
			?>
			<li <?php if($class =='charges_fees' && $func =='customer_charges'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('charges_fees','customer_charges'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Customer Charages</a></li>
			<?php } ?>
		  </ul>
		</li>
		<?php } ?>	
		<?php 		
		/*if($myHelpers->has_menu_access("charges_fees" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='charges_fees'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Notification & Warning</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		    <?php 
			if($myHelpers->has_menu_access("charges_fees||add_expenses" , $user_type)){ 
			?>
			<li <?php if($class =='charges_fees' && $func =='add_expenses'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('',''); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i>Notification</a></li>
			<?php } ?>
			 <?php 
			if($myHelpers->has_menu_access("charges_fees||customer_charges" , $user_type)){ 
			?>
			<li <?php if($class =='charges_fees' && $func =='customer_charges'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('',''); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i>Warning</a></li>
			<?php } ?>
		  </ul>
		</li>
		<?php }*/ ?>	
		
		<?php 		
		if($myHelpers->has_menu_access("transactions" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='transactions'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Transactions</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			 <?php 
			if($myHelpers->has_menu_access("transactions||transfer" , $user_type)){ 
			?>
			<li <?php if($class =='transactions' && $func =='transfer'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('transactions','transfer'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Transfer</a></li>
			<?php } ?>
			<?php 
			if($myHelpers->has_menu_access("transactions||balance_sheet" , $user_type)){ 
			?>
			<li <?php if($class =='transactions' && $func =='balance_sheet'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('transactions','balance_sheet'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Balance Sheet</a></li>
			<?php } ?>
		    <?php 
			if($myHelpers->has_menu_access("transactions||capital_account" , $user_type)){ 
			?>
			<li <?php if($class =='transactions' && $func =='capital_account'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('transactions','capital_account'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Capital Account</a></li>
			<?php } ?>
		  </ul>
		</li>
		<?php } ?>	
		<?php 		
		if($myHelpers->has_menu_access("report" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='report'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-pie-chart"></i>
			<span>Report</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		   <?php 
			if($myHelpers->has_menu_access("report||statement" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='statement'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','statement'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Statement</a>
			</li>
			<?php } ?>	
			 <?php 
			if($myHelpers->has_menu_access("report||profit_report" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='profit_report'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','profit_report'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Profit Report</a>
			</li>
			<?php } ?>	
			 <?php 
			if($myHelpers->has_menu_access("report||income" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='income'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','income'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Income</a>
			</li>
			<?php } ?>	
			 <?php 
			if($myHelpers->has_menu_access("report||expenses" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='expenses'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','expenses'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Expense</a>
			</li>
			<?php } ?>	
			 <?php 
			if($myHelpers->has_menu_access("report||income_v_expense" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='income_v_expense'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','income_v_expense'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Income VS Expense</a>
			</li>
			<?php } ?>	
			 <?php 
			if($myHelpers->has_menu_access("report||profile_report" , $user_type)){ 
			?>
			<li <?php if($class =='report' && $func =='profile_report'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','profile_report'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Profile Report</a>
			</li>
			<?php } ?>
            <?php 
			if($myHelpers->has_menu_access("report||profile_report" , $user_type)){ 
			?>
			<!--<li <?php if($class =='report' && $func =='profile_report'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('report','file_import'); 
				echo site_url($segments);?>"><i class="fa fa-circle-o"></i> File Import</a>
			</li>-->
			<?php } ?>				
		  </ul>
		  
		</li>
	  <?php } ?>	
	  <?php 
		if($myHelpers->has_menu_access("designation" , $user_type))
		{
		?>
		
		<li class="treeview <?php if( $class =='designation'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Designation Management</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		<?php  if($myHelpers->has_menu_access("designation||manage" , $user_type)){  ?>
			<li <?php if($class =='designation' && $func =='manage'){ echo "class='active'";}?>><a href="<?php $segments = array('designation','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Designation</a></li>
			<?php  } ?>
			<?php  if($myHelpers->has_menu_access("loan||add_new" , $user_type)){  ?>
			<li <?php if($class =='designation' && $func =='add_new'){ echo "class='active'";}?>><a href="<?php $segments = array('designation','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New Designation</a></li>
			<?php  } ?>
		  </ul>
		</li>
	<?php } ?>
	
	   <?php	if($myHelpers->has_menu_access("user" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='user'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>User Manager</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		  <?php 
			 if($myHelpers->has_menu_access("user||manage" , $user_type)){ 
			?>
			<li <?php if($class =='user' && $func =='manage'){ echo "class='active'";}?>>
				<a href="<?php $segments = array('user','manage'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Manage Users</a></li>
		  <?php } ?>	
		  <?php 
			if($myHelpers->has_menu_access("user||add_new" , $user_type)){ 
			?>
			<li <?php if($class =='user' && $func =='add_new'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('user','add_new'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Add New User</a></li>
			<?php } ?>	
		  </ul>
		</li>
   <?php } ?>	
  <?php	if($myHelpers->has_menu_access("settings" , $user_type))
		{
		?>
		<li class="treeview <?php if( $class =='settings'){ echo 'active';}?>">
		  <a href="#">
			<i class="fa fa-user"></i>
			<span>Settings</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		    <?php 
			if($myHelpers->has_menu_access("settings||general_settings" , $user_type)){ 
			?>
		
				<li <?php if($class =='settings' && $func =='general_settings'){ echo "class='active'";}?>><a href="<?php $segments = array('settings','general_settings'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> General Setting</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("settings||day_off" , $user_type)){ 
			?>
		
				<li <?php if($class =='settings' && $func =='day_off'){ echo "class='active'";}?>><a href="<?php $segments = array('settings','day_off'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Off Day Setting</a></li>
			<?php } ?>
			<?php 
			if($myHelpers->has_menu_access("settings||sms_settings" , $user_type)){ 
			?>	
				<li <?php if($class =='settings' && $func =='sms_settings'){ echo "class='active'";}?>><a href="<?php $segments = array('settings','sms_settings'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> SMS Setting</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("settings||db_settings" , $user_type)){ 
			?>	
				<li <?php if($class =='settings' && $func =='db_settings'){ echo "class='active'";}?>><a href="<?php $segments = array('settings','db_settings'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> DB Setting</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("settings||loan_settings" , $user_type)){ 
			?>
				<li <?php if($class =='settings' && $func =='loan_settings'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('settings','loan_settings'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Loan Settings</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("settings||employee_settings" , $user_type)){ 
			?>
			<li <?php if($class =='settings' && $func =='employee_settings'){ echo "class='active'";}?>>
			<a href="<?php $segments = array('settings','employee_settings'); echo site_url($segments);?>">
			<i class="fa fa-circle-o"></i> Employee Settings</a></li>
			<?php } ?>	
			<?php 
			if($myHelpers->has_menu_access("settings||change_password" , $user_type)){ 
			?>
		
			<li <?php if($class =='settings' && $func =='change_password'){ echo "class='active'";}?>><a href="<?php $segments = array('settings','change_password'); echo site_url($segments);?>"><i class="fa fa-circle-o"></i> Change Password</a></li>
			<?php } ?>	
		  </ul>
		</li> 
	<?php } ?>		
		
	  </ul>
	</section>
	<!-- /.sidebar -->
  </aside>
