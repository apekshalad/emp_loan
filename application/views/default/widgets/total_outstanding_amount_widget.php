<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php 
				  if (isset($total_outstanding_amount) && $total_outstanding_amount->num_rows() > 0)
				  { 
						foreach($total_outstanding_amount->result() as $row)
						{
							echo ($row->total_net_amount - $row->total_deposit_amount);
						}
						
				  }
				  else
					echo 0;
				  ?></h3>
                  <p>Total Outstanding Amount</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('loan','manage'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>