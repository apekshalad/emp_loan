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



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description"  content=""/>
<meta name="keywords" content=""/>
<meta name="robots" content="ALL,FOLLOW"/>
<meta name="Author" content="AIT"/>
<meta http-equiv="imagetoolbar" content="no"/>
<title><?php echo $title; ?></title>

<?php
//echo link_tag('css/reset.css');
//echo link_tag('css/screen.css');
?>
<!--[if IE 7]>
<?php //echo link_tag('css/ie7.css'); ?>
<![endif]-->	

<?php
//echo script_tag('js/jquery.js');
//echo script_tag('js/cufon.js');
//echo script_tag('js/Geometr231_Hv_BT_400.font.js');
//echo script_tag('js/script.js');
?>

  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<?php
	/*css*/	
	echo link_tag("themes/$theme/bootstrap/css/bootstrap.min.css");
	echo link_tag("themes/$theme/css/AdminLTE.min.css");
	echo link_tag("themes/$theme/plugins/iCheck/square/blue.css");
?>
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<style>
p.error_msg{
	text-align:center;
	color:#f00;
	border: 1px solid #f00;
    padding: 5px;
}
p.success_msg{
	text-align:center;
	border: 1px solid #4acc83;
    color: #4acc83;
    padding: 5px;
}
</style>
</head>

<body class="hold-transition login-page">
	<div class="login-box">
      <div class="login-logo">        
       <?php 
            $query = "select * from options  where option_key='website_logo' ";
            $result = $myHelpers->Common_model->commonQuery($query);
            
            $logo_img = "";		
        	if($result->num_rows() > 0)
        	{
        		$row = $result->row();
        		$logo_img = $row->option_value;
        	}
            
            if($logo_img!='')
            {
                ?>
                <span class="logo-lg"> <img src="<?php echo base_url();?>uploads/<?php echo $logo_img; ?>" /></span>
                <?php
            }
            else
            {
            	$query = "select * from options  where option_key='custum_text' ";
            	$result = $myHelpers->Common_model->commonQuery($query);
            	
            	$custum_text = "";		
            	if($result->num_rows() > 0)
            	{
            		$row = $result->row();
            		$custum_text = $row->option_value;
            	}
                ?>
                <span class="logo-lg"> <?php echo $custum_text; ?></span>
                <?php
            }  
            ?>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
		<?php 
		if(isset($_SESSION['msg']) & !empty($_SESSION['msg'])) { 
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
		}
		?>
			<?php 	$attributes = array('name' => 'login_form','onSubmit'=>'return validate_login_form(this)');		 			
			echo form_open('logins/login',$attributes); ?>
			  
			  <div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="Username" size="25" name="username" required>
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			  </div>
			  
			  <div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="Password" size="25" name="userpass" required>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			  
			  <div class="row">
				<div class="col-xs-8">
				  &nbsp;
				</div>
				<div class="col-xs-4">
				  <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
			  </div>
			  
			</form>
		
      </div>
    </div>
	
	
<?php 

echo script_tag("themes/$theme/plugins/jQuery/jQuery-2.1.4.min.js");
echo script_tag("themes/$theme/bootstrap/js/bootstrap.min.js");
echo script_tag("themes/$theme/plugins/iCheck/icheck.min.js");
?>
 <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
		
		 $('p.error_msg,p.success_msg').delay(5000).fadeOut('slow');
		
      });
    </script>

</body>
</html>
