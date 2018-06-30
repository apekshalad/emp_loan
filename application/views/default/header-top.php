<header class="main-header">
        <!-- Logo -->
        <a href="<?php echo site_url();?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>L</b>fy</span>
          <!-- logo for regular state and mobile devices -->
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
                $query = "select * from options  where option_key='logo_width' ";
                $result = $myHelpers->Common_model->commonQuery($query);
                
                $logo_width = "";		
            	if($result->num_rows() > 0)
            	{
            		$row = $result->row();
            		$logo_width = $row->option_value;
            	}
                
                $query = "select * from options  where option_key='logo_height' ";
                $result = $myHelpers->Common_model->commonQuery($query);
                
                $logo_height = "";		
            	if($result->num_rows() > 0)
            	{
            		$row = $result->row();
            		$logo_height = $row->option_value;
            	}
                ?>
                <style>.logo-lg img{width: <?php echo ($logo_width!='')?$logo_width:'auto';?>;height:<?php echo ($logo_height!='')?$logo_height:'auto';?>;}</style>
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

          
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
			 <li class="dropdown notifications-menu">
                <style>.navbar-nav > .notifications-menu > .dropdown-menu > li .menu > li > a{white-space: normal;}</style>
                <?php
                $query = "select * from notification WHERE `client_id` ='".$this->session->userdata('user_id')."' AND `notify_read` ='N' ORDER BY `notify_id` DESC";
                $notifications_count = count($this->Common_model->commonQuery($query)->result());
                
                $query = "select * from notification WHERE `client_id` ='".$this->session->userdata('user_id')."' ORDER BY `notify_id` DESC LIMIT 0, 10"; //AND `notify_read` ='N' 
                $notifications = $this->Common_model->commonQuery($query)->result();
                ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="readNotification();">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning"><?php echo (($notifications_count)>0)? $notifications_count : '';?></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php
                        if(count($notifications) > 0)
                        {
                            foreach($notifications as $row)
                            {
                                ?>
                                <li>
                                    <a href="#">
                                      <i class="fa fa-fw fa-bullhorn"></i> <?php echo $row->description;?>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        else
                        {
                            ?>
                            <style>.slimScrollDiv{height: 40px !important;}</style>
                            <li>
                                <a href="#">
                                  <i class="fa fa-fw fa-bullhorn"></i> No Notifications found.
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                  </li>
                  <!--<li class="footer"><a href="#">View all</a></li>-->
                </ul>
              </li>
			  <li class=" user user-menu">
                <a style="background:rgba(0,0,0,0.1);cursor:default;">Welcome <?php echo ucfirst($this->session->userdata('user_name')); ?></a>
			  </li>
			  <?php 
				$user_type = $this->session->userdata('user_type');
				if(isset($user_type) && $user_type == 'user') { ?>
				  <li class="user user-menu">
					<a href="<?php $segments = array('payment','my_payment'); echo site_url($segments);?>">My Payments</a>
				  </li>
			  <?php } ?>
              <li class=" user user-menu">
                <a href="<?php $segments = array('logins','logout'); echo site_url($segments);?>" class="btn btn-flat ">Sign out</a>        
			  </li>
            </ul>
          </div>
        </nav>
      </header>