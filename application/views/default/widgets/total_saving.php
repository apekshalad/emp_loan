<?php $this->load->view("$theme/widgets/total_general_saving_amount.php"); ?>
			
			<?php $this->load->view("$theme/widgets/total_employee_saving_amount.php"); ?>
			
			<?php $this->load->view("$theme/widgets/total_conf_saving_amount.php"); ?>
			
			<?php $this->load->view("$theme/widgets/total_general_saving_amount.php"); ?>
			
			
			<?php $this->load->view("$theme/widgets/total_saving_amount.php"); ?>

<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php 
				  global $general_saving, $employee_saving , $conf_saving , $withdraw;
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