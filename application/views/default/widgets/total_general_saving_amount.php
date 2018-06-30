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