<?php $this->load->view("default/header-top");?>
<?php $this->load->view("default/sidebar-left");?>
      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

	  <h1>

		404 Error


	  </h1>

	  <ol class="breadcrumb">

		<li><a href="<?php $segments = array('main'); 
							echo site_url($segments);?>"><i class="fa fa-dashboard"></i> Home</a></li>

		<li class="active">404 error</li>

	  </ol>

	</section>

	
	<!-- Main content -->

	<section class="content">
          <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content" style="padding-top: 20px;">
              <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
              <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="<?php $segments = array('main'); 
							echo site_url($segments);?>">return to dashboard</a> 
              </p>
              
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section><!-- /.content -->

  </div>

     