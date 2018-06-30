<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Report</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<?php
	/*css*/	
	echo link_tag("themes/$theme/bootstrap/css/bootstrap.min.css");
	echo link_tag("themes/$theme/css/AdminLTE.min.css");
?>
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body onload="window.print();">
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i> PLmanager, Inc.
              <small class="pull-right">Date: <?php echo date('d/m/Y',time()); ?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
		<?php 
		if(isset($customer_detail) && $customer_detail->num_rows() > 0 )
		{
			$cus_row = $customer_detail->row();
			$cus_name = $cus_row->customer_name;
			$cus_add = $cus_row->customer_address;
			$cus_mob = $cus_row->customer_mobile;
			$cus_acc = $cus_row->customer_acc_no;
		}
		?>
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            Customer Details
            <address>
              <strong><?php if(isset($cus_name)) echo $cus_name; ?></strong><br>
              <?php if(isset($cus_add)) echo $cus_add; ?><br>
              Rajasthan, India 334001<br>
              Phone: (+91) <?php if(isset($cus_mob)) echo $cus_mob; ?><br>
              Email: info@customer.com
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br>
              Email: john.doe@example.com
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>Invoice #007612</b><br>
            <br>
            <b>Total Loan:</b> <?php echo $total_loan; ?><br>
            <b>Payment Due:</b> 2/22/2014<br>
            <b>Account:</b> <?php if(isset($cus_acc)) echo $cus_acc; ?>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>S. No.</th>
                  <th>Loan #</th>
				  <th>Loan Type</th>
				  <th>Loan Date</th>
				  <th>EMI</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
			  <?php 
			  $grand_total = 00;
			  if(isset($p_loan_list) && $p_loan_list->num_rows() > 0) { 
			  $i=0;
			  foreach($p_loan_list->result() as $row ) { $i++;?>
                <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $row->loan_no; ?></td>
				  <td><?php echo $row->loan_type; ?></td>
                  <td><?php echo $row->loan_date; ?></td>
				  <td><?php echo $row->emi_amount; ?></td>
				  <td><?php echo '<i class="fa fa-inr"></i> '.$row->principal_amount;  $grand_total +=$row->principal_amount; ?></td>
                </tr>
			  <?php } }
			  else{
				  echo ' <tr><td colspan="7">No data available in table</td></tr>';
			  }?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6">
            <p class="lead">Payment Methods:</p>
            <img src="../../dist/img/credit/visa.png" alt="Visa">
            <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
            <img src="../../dist/img/credit/american-express.png" alt="American Express">
            <img src="../../dist/img/credit/paypal2.png" alt="Paypal">
            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
          </div><!-- /.col -->
          <div class="col-xs-6">
            <p class="lead">Amount Due 2/22/2014</p>
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td><i class="fa fa-inr"></i> <?php echo $grand_total; ?></td>
                </tr>
                <tr>
                  <th>Tax (9.3%)</th>
                  <td>$10.34</td>
                </tr>
                <tr>
                  <th>Shipping:</th>
                  <td>$5.80</td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td>$265.24</td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

  </body>
</html>
