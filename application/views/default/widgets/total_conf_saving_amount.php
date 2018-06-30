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