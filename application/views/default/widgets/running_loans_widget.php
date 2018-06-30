<div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php 
				  if (isset($running_loan_detail) && $running_loan_detail->num_rows() > 0)
				  { 
						$i = 0;
						foreach($running_loan_detail->result() as $row)
						{
							$total_loan_payment_count = $this->Common_model->commonQuery("
							select * from loan_payments 
							where loan_payments.loan_id = $row->loan_id and deposit_amount > 0");
							if($row->time_periods == $total_loan_payment_count->num_rows())
							{
								continue;
							}
							$i++;
						}
						echo $i;
				  }
				  else
					echo 0;
				  ?></h3>
                  <p>Running Loan</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a class="small-box-footer" 
				href="<?php $segments = array('loan','manage'); 
							echo site_url($segments);?>">View All <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->