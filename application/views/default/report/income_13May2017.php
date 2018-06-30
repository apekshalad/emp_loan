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
	  <h1> Income  </h1>
	  
	</section>

	
	<section class="content no-print" style="min-height:auto;padding-bottom:0px;">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					 <div class="box-body">
						<?php 
					$attributes = array('name' => 'add_form_post','class' => 'form');		 			
					echo form_open_multipart('report/income',$attributes); ?>
							
							<div class="row">
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
								
								<div class="col-md-6">
									<div class="form-group specific_date_container" >
                                          <label for="timePeriods">&nbsp;</label><br />            	
										<div class="input-group">

										  <div class="input-group-addon">

											<i class="fa fa-calendar"></i>

										  </div>

										  <input type="text" class="form-control pull-right" id="reservation" name="loan_dates" value="<?php if(isset($_POST['loan_dates'])) echo $_POST['loan_dates']; else { 
										   $date = date('m/d/Y',time()); 
										  echo $date . " - ". $date;
										   } ?>">

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
          
		  <div class="row">
            <div class="col-xs-12">
				
			  <h2 class="page-header" style="text-align: center; border-bottom: 1px dashed rgb(238, 238, 238);">Income</h2>
			
              <h2 class="page-header">
                <?php if(isset($compnay_title) && !empty($compnay_title)) echo $compnay_title; else echo '&nbsp;'; ?>
                <small class="pull-right">Date: 
				<?php 
					if(isset($current_date) && !empty($current_date)) 
						echo $current_date;
					else
						echo date('m/d/Y',time()); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
		  
		  

         	
			  <!-- title row -->
          <div class="row">
            <div class="col-xs-12 box-header">
              <h3 class="box-title">
               Total Income
              </h3>
            </div><!-- /.col -->
          </div>
          

          <!-- Table row -->
         <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
					  <th>S.No.</th>
                      <th>Client Name</th>
                      <th>Income Date</th>
					  <th>Description</th>
					  <th>Income Amount (<i class="fa fa-money"></i>)</th>
					  <th>Income Mode</th> 
                                        
                </tr>
              </thead>
              <tbody>
	<?php			$grand_total = 0;
					$n=1;
					$flag = false;
					if (isset($today_income_detail) && $today_income_detail->num_rows() > 0)
					{		
						
						$flag = true;
						foreach ($today_income_detail->result() as $row)
						{ 
							$grand_total += $row->payment_in; 
							
						?>	
							
							
							
							<tr>
                               
							  <td><?php echo $n++; ?></td>
                              <td><?php echo $row->client_name;?></td>
							  <td><?php echo date('m/d/Y',$row->payment_date); ?></td>
							  <td><?php echo $row->payment_log; ?></td> 
							  <td><?php echo $row->payment_in; ?></td>
							  <td><?php 
							  if($row->trans_type == 'conf_saving')
								  echo 'Confidential Saving';
							  else
								  echo ucfirst($row->trans_type); 
							  ?></td> 
                                
                                               
							</tr>							
							
				<?php	
						
						}
						
					}
					
					
					if (isset($today_emi_detail) && $today_emi_detail->num_rows() > 0)
					{		
						
						$flag = true;
						foreach ($today_emi_detail->result() as $row)
						{ 
							$grand_total += $row->total_emi_received; 
							
						?>	
							
							
							
							<tr>
							  <td><?php echo $n++; ?></td>
                              <td><?php echo $row->client_name;?></td>
							  <td><?php echo date('m/d/Y',$row->payment_date); ?></td>
							  <td><?php echo "$row->loan_type_text EMI Received."; ?></td> 
							  <td><?php echo $row->total_emi_received; ?></td>
							  <td><?php echo "EMI";?></td>
							</tr>
							

						 
							
				<?php	
						
						}
						
					}
					
					if($flag)
					{
						echo '
							 <tr>
							  <td colspan="3"></th>
							  <th>Grand Total</th>
							  <th>'.$grand_total.'</th>
							  <th></th>
							</tr>
						';
					}
					else
					{
						echo '<tr><td colspan="6">There is no record found.</td></tr>';
					}
				    	
					
				?>
                
                                 
              </tbody>
            </table>
          </div><!-- /.col -->
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
  