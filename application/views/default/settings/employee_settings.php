<!-- DataTables -->
<?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
?>
  
<style type="text/css">
.fa-2x {
    font-size: 1.2em;
}
.w62 { width:62px;}
.pad-right-5 { padding-right:5px!important; }
</style>
      <?php $this->load->view("default/header-top");?>
      
	  <?php $this->load->view("default/sidebar-left");?>
      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Manage Employees </h1>
          <!--<ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Forms</a></li>
            <li class="active">Advanced Elements</li>
          </ol>-->
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
              
            </div><!-- /.box-header -->
            <div class="box-body">
               <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']))
					{
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
				/*	 echo '<pre>';
			 print_r($_SESSION);
			 echo '</pre>';*/
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <!--
						<th width="10px" class="bSortable"><input type="checkbox" class="checkbox select-all" /></th>
                        -->
						
                        <th width="75px" class="pad-right-5" >S No.</th>
                        <th class="pad-right-5">Full Name</th>
						<th class="pad-right-5">Username</th>
                        <th class="pad-right-5">User Type</th>
						<th class="pad-right-5" >Mobile No.</th>
						<th class="pad-right-5" >Email</th>
						<th width="150px" class="pad-right-5" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
<?php  if ($query->num_rows() > 0)
	   {		
		$n=1;
		foreach ($query->result() as $row)
		{ 
		
			
?>						
                      <tr>
                       <td><?php echo  $n++; ?></td>
						<td><?php echo  $myHelpers->global->get_user_meta($row->user_id,'first_name').' '.
										$myHelpers->global->get_user_meta($row->user_id,'last_name'); ?></td>
                        <td><?php echo  $row->user_name; ?></td>
						<td> <?php echo ucfirst($row->user_type); ?></td>
                        <td><?php echo  $myHelpers->global->get_user_meta($row->user_id,'mobile_no'); ?></td>
						<td><?php echo  $row->user_email; ?></td>
						<td class="action_block">
							
							
							<a href="<?php $segments = array('user','edit',$myHelpers->EncryptClientId($row->user_id)); 
							echo site_url($segments);?>" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
							<?php //if($row->user_type != 'user') { ?>
							<a href="<?php $segments = array('user','delete',$myHelpers->EncryptClientId($row->user_id)); 
							echo site_url($segments);?>" title="Delete" ><i class="fa fa-remove fa-2x"></i></a>
							<?php //} ?>
						</td>
                      </tr>
<?php 	}
	}	?>                      
                      
                     
                     
					 
                    </tbody>
                    
                  </table>
                </div>
          </div><!-- /.box -->

          <!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

	  <!-- DataTables -->
<?php 
	echo script_tag("themes/$theme/plugins/datatables/jquery.dataTables.min.js");
	echo script_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.min.js");
?>	  
    <!-- SlimScroll -->
<?php 
	echo script_tag("themes/$theme/plugins/slimScroll/jquery.slimscroll.min.js");
?>
	<!-- FastClick -->
<?php 
	echo script_tag("themes/$theme/plugins/fastclick/fastclick.min.js");
?>	  
  
	   <script>
      $(function () {
        //$("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": true,
          "autoWidth": false
        });
		
		
		$('.content-box .select-all').click(function () {
			//alert($(this).is(':checked'));
			if ($(this).is(':checked'))
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', true);
			else
			{
				$(this).parent().parent().parent().parent().find(':checkbox').attr('checked', false); 
				$(this).parent().parent().parent().parent().find(':checkbox').removeAttr('checked'); 
			}	
		});
      });
    </script>