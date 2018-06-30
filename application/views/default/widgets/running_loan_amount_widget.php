<div class="col-lg-3 col-xs-6">
              
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php 
				  if (isset($running_loan_amount_detail) && $running_loan_amount_detail->num_rows() > 0)
				  { 
						foreach($running_loan_amount_detail->result() as $row)
						{
							echo $row->total_net_amount;
						}
						
				  }
				  else
					echo 0;
				  ?></h3>
                  <p>Running Loan Amounts</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a class="small-box-footer" href="<?php $segments = array('loan','manage'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>