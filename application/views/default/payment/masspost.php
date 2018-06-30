<style type="text/css">


@media print{
	
	.content-wrapper {width:100%;}
	.content, .invoice .row .col-xs-12{padding:0;}
	.invoice{
		padding:0;
		/*border:1px solid #ccc;*/
		/*margin-left:-230px;*/
	}
	.invoice .row{
		margin-left:0;
		margin-right:0;
	}
	.content-wrapper{
		margin-left:0px;
	}
}
.table > tbody > tr > td {
     vertical-align: middle;
}
</style>

<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

	
	
	<script>
	
	$(document).ready(function () {
		
		
		
		
		
		
	});
	
	</script>
<style>
.grand_total {
    border-bottom: 1px dashed;
    border-top: 1px dashed;
    margin-top: 5px;
    padding-bottom: 10px;
    padding-top: 10px;
}
@media print{
	.cash_book_detail .col-md-12{
		padding:0px;
	}
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Post Payment  </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
						<?php 
					$attributes = array('name' => 'add_form_post','class' => 'form');		 			
					echo form_open_multipart('payment/masspost',$attributes); ?>
							
							<div class="row">
                                 <div class="col-md-3">
							<div class="form-group">
							  <label for="Client">Client/Id</label>
                                                          <select class="form-control select2 input-sm client_list" name="client"  id="Client">	
									<option value="">Select Client/ID</option>
									<?php if(isset($client_list) && $client_list->num_rows() > 0)
										{
											foreach($client_list->result() as $client_row)
											{
                                                                                            $select='';
                                                                                           if(isset($_POST['client'])){
                                                                                               if($_POST['client']==$client_row->user_id) $select='selected';
                                                                                           }
												echo '<option value="'.($client_row->user_id).'" '.$select.'>
											'.ucfirst($client_row->first_name).' '.ucfirst($client_row->last_name).' ('.ucfirst($client_row->acc_no).')</option>';
											}
											
										}
										?>
									
							  </select>
							</div>
						</div>
                                                            <div class="col-md-3">
            						<div class="form-group" >
            						  <label for="timePeriods">Select Branch</label><br />
            						  <select id="branch_id" name="branch_id" class="form-control select2 input-sm">
            								<option value="">Select Branch</option>
                							<?php 
                                            $sqlPro=$this->db->select("*")->from("branches")->get()->result();
                            				foreach($sqlPro as $sqlprow)
                                            {
                                                $selected = '';
                                                if($sqlprow->id == $_REQUEST['branch_id'])
                                                {
                                                    $selected = 'selected="selected"';
                                                }
                            					?>
                    							<option value="<?php echo $sqlprow->id?>" <?php echo $selected;?>><?php echo $sqlprow->branch_name?> / <?php echo $sqlprow->branch_code?></option>
                                                <?php 
                                            }?>
            						  </select>
            						  
            						</div>
            					</div>
                                                    <div class="col-md-3">
            						<div class="form-group" >
            						  <label for="timePeriods">Select Account Managent</label><br />
            						  <select name="account_manager" id="account_manager" class="form-control select2 input-sm">
                      	<option value="">Select Account Manager</option>
                      	<?php 
                            if(isset($manage) && $manage->num_rows() > 0)
							{
								foreach($manage->result() as $manages)
								{
                                   $selected = '';
                                                if($manages->user_id == $_REQUEST['account_manager'])
                                                {
                                                    $selected = 'selected="selected"';
                                                }


								 $name='';
									if($myHelpers->global->get_user_meta($manages->user_id,'first_name'))
											$name = $myHelpers->global->get_user_meta($manages->user_id,'first_name').' '.$myHelpers->global->get_user_meta($manages->user_id,'last_name');
									echo '<option value="'.$manages->user_id.'" '.$selected.'>
											'.ucfirst($name).'</option>';
								}
							}
							?>
                      </select>
            						  
            						</div>
            					</div>        
            					
            					
								
								<div class="col-md-3">
									<div class="form-group specific_date_container" >
                                          <label for="timePeriods">&nbsp;</label><br />            	
										<div class="input-group">

										  <div class="input-group-addon">

											<i class="fa fa-calendar"></i>

										  </div>

										  <input type="text" class="form-control pull-right" id="reservation" name="loan_dates" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { $date = date('m/d/Y',time()); echo $date;} ?>">

										</div><!-- /.input group -->

									  </div>
								</div>							
								
								<div class="col-md-12">
									<div class="">
									  <input type="submit" class="btn" name="submit" >					  
									</div>
								</div>
							 </div>
						</form>
				</div>
			</div>
	</section>
	
	<!-- Main content -->
        <section class="content">
			<div class="invoice">
          <!-- title row -->
          
		 

         	
			  <!-- title row -->
         

          <!-- Table row -->
         <div class="row">
             <form method="post">
          <div class="col-xs-12 table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                    <th style="width: 10%">S.No.</th>
                      <th style="width: 30%">Name</th>
                      <th>Payment</th>
                   
					  <th><?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { $date = date('m/d/Y',time()); echo $date;} ?></th>
					  <th>This Month (<i class="fa fa-money"></i>)</th>
					 
                                        
                </tr>
              </thead>
              <tbody>
                  <?php $is=1; if($_POST['client']!=''){
                      $clntId=$_POST['client'];
                      $name=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'first_name'))->row()->meta_value;
                      $acc_no=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'acc_no'))->row()->meta_value;
                     $s_date_explode = explode('/',$_POST['loan_dates']);
		    $time = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
                    
                    $loan=$this->db->select('payment_id, deposit_amount')->order_by('payment_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time))->limit('1')->get('loan_payments')->row();
                    $sumloan=$this->db->select('COALESCE(SUM(deposit_amount),0) as sumloan',false)->get_where('loan_payments', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m")))->row()->sumloan;
                    
                    $gsaving=$this->db->select("payment_in , cap_acc_id")->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'saving'))->limit('1')->get('capital_account')->row();
                    $sumgsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'saving'))->row()->sumloan;
                   
                    $csaving=$this->db->select('payment_in, cap_acc_id')->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'conf_saving'))->limit('1')->get('capital_account')->row();
                    $sumcsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'conf_saving'))->row()->sumloan;
                    
                    ?>
                  <tr>
                      <td rowspan="3">1</td>
                      <td rowspan="3"><h4><?= $name?></h4><h5 style="color: #C95A5A">A/c - <?= $acc_no?></h5></td>
                      <td>Loan Payment</td>
                      <td>
                           <input type="text" value="<?php if($loan->deposit_amount) echo $loan->deposit_amount; else echo '0'; ?>" name="loan_amount<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($loan->payment_id) echo $loan->payment_id; else echo '0'; ?>" name="loan_id<?php echo $is;?>" />
                        </td>
                      <td><?= $sumloan?></td>
                     
                  </tr>
                  <tr>
                      <td>General Savings</td>
                      <td>
                           <input type="text" value="<?php if($gsaving->payment_in) echo $gsaving->payment_in; else echo '0'; ?>" name="gnrl_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($gsaving->cap_acc_id) echo $gsaving->cap_acc_id; else echo '0'; ?>" name="gnrl_id<?php echo $is;?>" />
                         </td>
                      <td><?= $sumgsaving?></td>
                     
                  </tr>
                  <tr>
                     <td>Confidential Savings</td>
                      <td>
                          <input type="text" value="<?php if($csaving->payment_in) echo $csaving->payment_in; else echo '0'; ?>" name="conf_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($csaving->cap_acc_id) echo $csaving->cap_acc_id; else echo '0'; ?>" name="conf_id<?php echo $is;?>" />
                      </td>
                      <td><?= $sumcsaving?>
                   <input type="hidden" value="<?php   echo $clntId;?>" name="client<?php echo $is;?>" />
              <input type="hidden" name="loan_dates<?php echo $is;?>" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { $date = date('m/d/Y',time()); echo $date;} ?>"/>
                
                      </td>
                   
                  </tr>
                  <?php $is++; }?>
                                    <?php if(($_POST['branch_id'])!=''){
                                        $clients=$this->db->select('user_id')->where('branch_id',$_POST['branch_id'])->where('user_type','user')->get('users')->result();
                     if(!empty($clients)){
                          foreach ($clients as $clnt){
                              
                          
                                        $clntId=$clnt->user_id;
                      $name=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'first_name'))->row()->meta_value;
                      $acc_no=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'acc_no'))->row()->meta_value;
                     $s_date_explode = explode('/',$_POST['loan_dates']);
		    $time = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
                    
                    $loan=$this->db->select('payment_id, deposit_amount')->order_by('payment_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time))->limit('1')->get('loan_payments')->row();
                    $sumloan=$this->db->select('COALESCE(SUM(deposit_amount),0) as sumloan',false)->get_where('loan_payments', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m")))->row()->sumloan;
                    
                    $gsaving=$this->db->select("payment_in , cap_acc_id")->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'saving'))->limit('1')->get('capital_account')->row();
                    $sumgsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'saving'))->row()->sumloan;
                   
                    $csaving=$this->db->select('payment_in, cap_acc_id')->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'conf_saving'))->limit('1')->get('capital_account')->row();
                    $sumcsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'conf_saving'))->row()->sumloan;
                    
                    ?>
                  <tr>  
                      <td rowspan="3">1</td>
                      <td rowspan="3"><h4><?= $name?></h4><h5 style="color: #C95A5A">A/c - <?= $acc_no?></h5></td>
                      <td>Loan Payment</td>
                      <td>
                           <input type="text" value="<?php if($loan->deposit_amount) echo $loan->deposit_amount; else echo '0'; ?>" name="loan_amount<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($loan->payment_id) echo $loan->payment_id; else echo '0'; ?>" name="loan_id<?php echo $is;?>" />
                        </td>
                      <td><?= $sumloan?></td>
                     
                  </tr>
                  <tr>
                      <td>General Savings</td>
                      <td>
                           <input type="text" value="<?php if($gsaving->payment_in) echo $gsaving->payment_in; else echo '0'; ?>" name="gnrl_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($gsaving->cap_acc_id) echo $gsaving->cap_acc_id; else echo '0'; ?>" name="gnrl_id<?php echo $is;?>" />
                         </td>
                      <td><?= $sumgsaving?></td>
                     
                  </tr>
                  <tr>
                     <td>Confidential Savings</td>
                      <td>
                          <input type="text" value="<?php if($csaving->payment_in) echo $csaving->payment_in; else echo '0'; ?>" name="conf_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($csaving->cap_acc_id) echo $csaving->cap_acc_id; else echo '0'; ?>" name="conf_id<?php echo $is;?>" />
                      </td>
                      <td><?= $sumcsaving?>
                      <input type="hidden" value="<?php   echo $clntId;?>" name="client<?php echo $is;?>" />
              <input type="hidden" name="loan_dates<?php echo $is;?>" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { $date = date('m/d/Y',time()); echo $date;} ?>"/>
             
                      </td>
                    
                  </tr>
                  <?php $is++; }
                      }}?>
                                <?php if(($_POST['account_manager'])!=''){
                                        $clients=$this->db->select('client_ID')->where('account_managent',$_POST['account_manager'])->get('clients')->result();
                     if(!empty($clients)){
                          foreach ($clients as $clnt){
                              
                          
                                        $clntId=$this->db->select('user_id')->get_where('user_meta', array('meta_value'=>$clnt->client_ID))->row()->user_id;;
                      $name=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'first_name'))->row()->meta_value;
                      $acc_no=$this->db->select('meta_value')->get_where('user_meta', array('user_id'=>$clntId,'meta_key'=>'acc_no'))->row()->meta_value;
                     $s_date_explode = explode('/',$_POST['loan_dates']);
		    $time = mktime(00,00,00.00,$s_date_explode[0],$s_date_explode[1],$s_date_explode[2]);
                    
                    $loan=$this->db->select('payment_id, deposit_amount')->order_by('payment_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time))->limit('1')->get('loan_payments')->row();
                    $sumloan=$this->db->select('COALESCE(SUM(deposit_amount),0) as sumloan',false)->get_where('loan_payments', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m")))->row()->sumloan;
                    
                    $gsaving=$this->db->select("payment_in , cap_acc_id")->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'saving'))->limit('1')->get('capital_account')->row();
                    $sumgsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'saving'))->row()->sumloan;
                   
                    $csaving=$this->db->select('payment_in, cap_acc_id')->order_by('cap_acc_id', 'DESC')->where( array('client_ID'=>$clntId,'payment_date'=>$time, 'trans_type'=>'conf_saving'))->limit('1')->get('capital_account')->row();
                    $sumcsaving=$this->db->select('COALESCE(SUM(payment_in),0) as sumloan',FALSE)->get_where('capital_account', array('client_ID'=>$clntId,'MONTH(FROM_UNIXTIME(payment_date))'=>date("m"),'trans_type'=>'conf_saving'))->row()->sumloan;
                    
                    ?>
                  <tr>  
                      <td rowspan="3">1</td>
                      <td rowspan="3"><h4><?= $name?></h4><h5 style="color: #C95A5A">A/c - <?= $acc_no?></h5></td>
                      <td>Loan Payment</td>
                      <td>
                           <input type="text" value="<?php if($loan->deposit_amount) echo $loan->deposit_amount; else echo '0'; ?>" name="loan_amount<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($loan->payment_id) echo $loan->payment_id; else echo '0'; ?>" name="loan_id<?php echo $is;?>" />
                        </td>
                      <td><?= $sumloan?></td>
                     
                  </tr>
                  <tr>
                      <td>General Savings</td>
                      <td>
                           <input type="text" value="<?php if($gsaving->payment_in) echo $gsaving->payment_in; else echo '0'; ?>" name="gnrl_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($gsaving->cap_acc_id) echo $gsaving->cap_acc_id; else echo '0'; ?>" name="gnrl_id<?php echo $is;?>" />
                         </td>
                      <td><?= $sumgsaving?></td>
                     
                  </tr>
                  <tr>
                     <td>Confidential Savings</td>
                      <td>
                          <input type="text" value="<?php if($csaving->payment_in) echo $csaving->payment_in; else echo '0'; ?>" name="conf_saving<?php echo $is;?>" />
                          <input type="hidden" value="<?php if($csaving->cap_acc_id) echo $csaving->cap_acc_id; else echo '0'; ?>" name="conf_id<?php echo $is;?>" />
                      </td>
                      <td><?= $sumcsaving?>
                      <input type="hidden" value="<?php   echo $clntId;?>" name="client<?php echo $is;?>" />
              <input type="hidden" name="loan_dates<?php echo $is;?>" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { $date = date('m/d/Y',time()); echo $date;} ?>"/>
             
                      </td>
                    
                  </tr>
                  <?php $is++;}
                      }}?>
              </tbody>
            </table>
          </div><!-- /.col -->
          
          <div class="col-xs-12 ">
              <input type="hidden" value="<?php echo $is;?>" name="loopsdb" />
              <input type="submit" class="btn btn-primary pull-right" name="masspostform" value="Update Entry"/>
          </div>
             </form>
        </div><!-- /.row -->

		 
		 
		 
        </div>
		</section><!-- /.content -->
		
		
		
		<section class="content">
			 <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
			
			 <a href="#" onclick="window.print();return false;" class="btn btn-primary pull-right">Print <i class="fa fa-print"></i> </a>
             
            </div>
          </div>
		</section>
		
  </div><!-- /.content-wrapper -->
  