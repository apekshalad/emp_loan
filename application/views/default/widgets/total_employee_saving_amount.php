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