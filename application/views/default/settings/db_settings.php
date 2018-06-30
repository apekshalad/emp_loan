
<?php $this->load->view("default/header-top");?>

<?php $this->load->view("default/sidebar-left");?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> DB Settings </h1>
          
        </section>

        <!-- Main content -->
        <section class="content">
			<!-- form start -->
               <!-- <form role="form">-->
             <?php 
			$attributes = array('name' => 'add_form_post','class' => 'form');		 			
			echo form_open_multipart('settings/db_settings',$attributes); 
			
			?>
			<div class="row">
			<div class="col-md-12">   
			   
			<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">DB Settings</h3>
				  <div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
                </div><!-- /.box-header -->
				
				
                  <div class="box-body">
                    
					<?php echo validation_errors(); 
					if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
					?>
					
					
					
					<div class="form-group">
                    	  <button name="submit" type="submit" id="save" class="btn btn-success pull-right">
                            <i aria-hidden="true" class="fa fa-database"></i> Create Database Backup
                          </button>
					</div>
					
					
					
					
					
                  </div>

              </div>
			  
			  
		  </div>
		  
		  
		  </div>
		  
			</form>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      