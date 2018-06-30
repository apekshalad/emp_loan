<!-- DataTables -->
<?php 
echo link_tag("themes/$theme/plugins/datatables/dataTables.bootstrap.css");
echo link_tag("themes/$theme/plugins/datepicker/datepicker3.css");
echo script_tag("themes/$theme/plugins/datepicker/bootstrap-datepicker.js");
			  
?>
 <script>
 $(function() {
    $('.alert').delay(5000).fadeOut('slow');
 });
</script>     
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
          <h1>
            Manage Branches
          </h1>
          
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
					/*
					 $next_month = mktime(0,0,0,01,31,2016);
					 echo date("m/d/Y", strtotime('+1 month', $next_month));*/
					
			?> 
            </div><!-- /.box-body -->
            <div class="box-body content-box">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="70px" class="pad-right-5" >#</th>
						<th width="270px" class="pad-right-5" >Branch Name</th>
						<th width="170px" class="pad-right-5">Branch Code</th>
						<th class="pad-right-5">Branch Address</th>
						<th width="60px" class="pad-right-5">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php  
                    if ($query->num_rows() > 0)
                    {					
                		foreach ($query->result() as $row)
                		{ 	
                            ?>						
                            <tr>
                                <td><?php echo  $row->id; ?></td>
                                <td><?php echo  ucfirst($row->branch_name); ?></td>
        						<td><?php echo  $row->branch_code; ?></td>
                                <td><?php echo  $row->branch_address; ?></td>
        						<td class="action_block">
        							<?php if($this->session->userdata('user_id') == 1) { ?>
        							<a href="<?php $segments = array('branch','delete',$row->id); 
                                    echo site_url($segments);?>" title="Delete" class="delete-record" ><i class="fa fa-remove fa-2x"></i></a>
        							<?php } ?>
        						</td>
                            </tr>
                            <?php 	
                        }
                	}	
                    ?>                      
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