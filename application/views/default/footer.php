<?php 

/*js*/
//echo script_tag("themes/$theme/plugins/jQuery/jQuery-2.1.4.min.js");

echo script_tag("themes/$theme/bootstrap/js/bootstrap.min.js");
echo script_tag("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js");
echo script_tag("themes/$theme/plugins/select2/select2.full.min.js");
echo script_tag("themes/$theme/plugins/input-mask/jquery.inputmask.js");
echo script_tag("themes/$theme/plugins/input-mask/jquery.inputmask.date.extensions.js");
echo script_tag("themes/$theme/plugins/input-mask/jquery.inputmask.extensions.js");

echo script_tag("themes/$theme/plugins/moment/moment.js");

echo script_tag("themes/$theme/plugins/daterangepicker/daterangepicker.js");
//echo script_tag("themes/$theme/plugins/datepicker/bootstrap-datepicker.js");
echo script_tag("themes/$theme/plugins/colorpicker/bootstrap-colorpicker.min.js");
echo script_tag("themes/$theme/plugins/timepicker/bootstrap-timepicker.min.js");
echo script_tag("themes/$theme/plugins/slimScroll/jquery.slimscroll.min.js");
//echo script_tag("themes/$theme/plugins/iCheck/icheck.min.js");
echo script_tag("themes/$theme/plugins/fastclick/fastclick.min.js");
echo script_tag("themes/$theme/js/app.min.js");
echo script_tag("themes/$theme/js/demo.js");

echo script_tag("themes/$theme/plugins/preety_photo/jquery.prettyPhoto.js");

?>

 <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});

        //Money Euro
        $("[data-mask]").inputmask();

        //Date range picker
        $('#reservation').datepicker({ format: 'm/d/yyyy'});
        $('#loanDate').datepicker({ format: 'dd/mm/yyyy'});
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment(),
              endDate: moment()
            },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		  $('#daterange-btn input.date_rang').val(start.format('M/D/YYYY')+ '-' + end.format('M/D/YYYY'));
		  if($('.statement_client_list').length)
		  {
			  $('.statement_client_list').change();
		  }
		  if($('.statement_date_between_block').length)
		  {
			  $('.statement_date_between_block .s_date').html(start.format('D/M/YYYY'));
			  $('.statement_date_between_block .e_date').html(end.format('D/M/YYYY'));
		  }
        }
        );

        //iCheck for checkbox and radio inputs
        /*$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });*/
        //Red color scheme for iCheck
       /* $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });*/
        //Flat red color scheme for iCheck
       /* $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
         radioClass: 'iradio_flat-green'
        });*/

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
		
		//$("a[rel^='prettyPhoto']").prettyPhoto();
		$(".gallery a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
      });
      
      function readNotification()
      {
        $.ajax({
			url: '<?php echo site_url();?>ajax/mark_read',
			type: 'POST',
			success: function() {
				
			},
			data: {client_id : <?php echo $this->session->userdata('user_id');?>},
			cache: false
		});  
      }
    </script>

	 <footer class="main-footer no-print">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
		&nbsp;&nbsp;&nbsp;&nbsp;
        <!--<strong>Powered by <a href=""> </a>.</strong> All rights reserved.-->
      </footer>
	  
	  <style>
	  @keyframes blink {
			0% {
			  opacity: .2;
			}
			20% {
			  opacity: 1;
			}
			100% {
			  opacity: .2;
			}
		}
	  .loading_inner_block span{
		  
			   animation-name: blink;
			animation-duration: 1.4s;
			animation-iteration-count: infinite;
			animation-fill-mode: both;
		}

		.loading_inner_block span:nth-child(2) {
			animation-delay: .2s;
		}

		.loading_inner_block span:nth-child(3) {
			animation-delay: .4s;
		}
	  </style>
	  <div style="position: fixed; width: 100%; height: 100%; background-color: rgb(51, 51, 51); opacity: 0.7; left: 0px; top: 0px; right: 0px; bottom: 0px; z-index: 2000; display: none;" class="full_sreeen_overlay">
			<span style="
			    line-height: 60px;
				font-size: 32px;
				color: #fff;
				text-align: center;
				width: 100%;
				display: block;
				vertical-align: middle;
				height: 60px;
				position: relative;
				top: 45%;" class="loading_inner_block">Please Wait <span>.</span><span>.</span><span>.</span></span>
		</div>
          
         <div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
       <div class="modal-content" style="border-radius: 5px;">
           <div id="ezAlerts-header" class="modal-header alert-danger" style="padding: 15px; border-top-left-radius: 5px; border-top-right-radius: 5px;">
               <button id="close-button" type="button" class="close" data-dismiss="modal">
                   <span aria-hidden="true">x</span>
                   <span class="sr-only">Close</span></button>
                   <h4 id="ezAlerts-title" class="modal-title">Attention</h4>
           </div>
           <div id="ezAlerts-body" class="modal-body">
               <div id="ezAlerts-message">hello world</div>
                   
           </div>
           <div id="ezAlerts-footer" class="modal-footer">
               <button class="btn btn-danger">Ok</button>
           </div>
       </div>
   </div>
  </div>