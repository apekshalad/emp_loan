<?php 
	
	
	$query = "select * from options  where option_key='company_title'  ";
	$result = $myHelpers->Common_model->commonQuery($query);
	
	$title = "Loanify | ";		
	if($result->num_rows() > 0)
	{
		$row = $result->row();
		$title .= $row->option_value;
	}
		

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title><?php echo $title; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php
/*css*/	
echo link_tag("themes/$theme/bootstrap/css/bootstrap.min.css");
?>
    
   <link rel="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css">
	<!-- Font Awesome -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
    <!-- Ionicons -->
    <!--<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
    <?php 
    $query = "select * from options  where option_key='fevicon_icon' ";
    $result = $myHelpers->Common_model->commonQuery($query);
    
    $fav_logo_img = "";		
	if($result->num_rows() > 0)
	{
		$row = $result->row();
		$fav_logo_img = $row->option_value;
	}
    ?>
    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="<?=base_url()?>uploads/<?=$fav_logo_img?>"/>
<?php 

echo link_tag("themes/$theme/css/font-awesome.css");
echo link_tag("themes/$theme/css/ionicons.css");

echo link_tag("themes/$theme/plugins/daterangepicker/daterangepicker-bs3.css");
echo link_tag("themes/$theme/plugins/datepicker/datepicker3.css");

    echo link_tag("https://code.jquery.com/ui/1.12.1/themes/pepper-grinder/jquery-ui.css");
echo link_tag("themes/$theme/css/jquery-ui.multidatespicker.css");
echo link_tag("themes/$theme/plugins/iCheck/all.css");
echo link_tag("themes/$theme/plugins/colorpicker/bootstrap-colorpicker.min.css");
echo link_tag("themes/$theme/plugins/timepicker/bootstrap-timepicker.min.css");
echo link_tag("themes/$theme/plugins/select2/select2.min.css");
echo link_tag("themes/$theme/css/AdminLTE.min.css");
echo link_tag("themes/$theme/css/skins/_all-skins.min.css");

echo script_tag("themes/$theme/plugins/jQuery/jQuery-2.1.4.min.js");


echo link_tag("themes/$theme/plugins/preety_photo/css/prettyPhoto.css");

?>
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script>
	$('document').ready(function() {
		//$('.alert').delay(5000).fadeOut();
	});
	</script>
	
  </head>
  <body class="skin-blue sidebar-mini">
	<div class="wrapper">
	<?php $this->load->view($content);?>
	<?php $this->load->view("default/footer");?>
	<div class="model_wrapper"></div>
	<style>
		.modal-dialog {
			align-items: center;
			display: flex;
			margin: 0 auto;
			height: 100%;
			width: 100%;
		}
		.modal-content {
			width: 600px;
			margin: 0 auto;
		}
		.fa-2x {
			font-size: 1.2em;
		}
	</style>
   </div>	


  </body>

</html>

