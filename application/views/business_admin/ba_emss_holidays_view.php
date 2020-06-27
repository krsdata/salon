<?php
	$this->load->view('business_admin/ba_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('business_admin/ba_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('business_admin/ba_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">Holidays Management</h1>
				<div class="row">
					<?php
						if(empty($business_outlet_details)){
					?>	
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Add Outlets</h5>
								</div>
								<div class="card-body">
									<p>Please add outlet to proceed.</p>
								</div>
							</div>
						</div>
					<?php
						}

						if(!isset($selected_outlet)){
					?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Select Outlet</h5>
								</div>
								<div class="card-body">
									<p>Please select outlet to proceed.</p>
								</div>
							</div>
						</div>
					<?php
						}
						else{
					?>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-6">
										<h5 class="card-title">Define Holidays</h5>
									</div>
									<div class="col-md-5">
										<button type="button" class="btn btn-success float-right"  data-toggle="modal" data-target="#createHolidays" >Create Holidays</button>	
									</div>
									<div class="col-md-1">
										<button type="button" class="btn btn-success float-right" data-toggle="modal" onclick="exportTableToExcel('HolidayDetails','Holidays')">Download</button>	
									</div>
								</div>								
							</div>
							<div class="card-body">
								<table class="table table-hover table-striped datatables-basic" id="table1" style="width: 100%;"> 
									<thead>
										<tr>
											<th>Holidy Date</th>
											<th>Holiday Name</th>
											<th style="width: 15%;">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($holiday as $holiday1){
										?>
										<tr>
											<td><?=date_format(date_create($holiday1['holiday_date']),'d-M-y')?></td>
											<td><?=$holiday1['holiday_name']?></td>
											<td>
												<button class="btn btn-primary editHoliday" holiday_id="<?=$holiday1['holiday_id']?>" holiday_date="<?=$holiday1['holiday_date']?>" holiday_name="<?=$holiday1['holiday_name']?>"><i class="fa fa-edit"></i></button>
												<button class="btn btn-danger deleteholiday" holiday_id="<?=$holiday1['holiday_id']?>" holiday_date="<?=$holiday1['holiday_date']?>" holiday_name="<?=$holiday1['holiday_name']?>"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
										<?php 
										}
										?>
									</tbody>
								</table>
							</div>

							<div class="card-body">
								<table class="table table-striped" id="HolidayDetails" style="width: 100%;" hidden> 
									<thead>
										<tr>
											<th>Holidy Date</th>
											<th>Holiday Name</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										// print_r($holiday);
										foreach($holiday as $holiday2){
										?>
										<tr>
											<td><?=$holiday2['holiday_date']?></td>
											<td><?=$holiday2['holiday_name']?></td>
										</tr>
										<?php 
										}
										?>
									</tbody>
								</table>
							</div>
						</div>	
					</div>
					<!-- modal -->
					<div class="modal" id="createHolidays" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white font-weight-bold">Add Holiday</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span class="text-white" aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body m-3">
									<div class="row">
										<div class="col-md-12">
											<form id="AddHolidays" method="POST" action="#">
												<div class="row">
													<div class="form-group col-md-12">
														<label>Select Date</label>
														<input type="date" class="form-control" name="holiday_date" autofocus>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-12">
														<label>Holiday Name</label>
														<input type="text" class="form-control" placeholder="E.g Diwali, Holi etc." name="holiday_name">
													</div>
												</div>
												<button type="submit" class="btn btn-primary mt-2">Submit</button>
												
											</form>
											<div class="alert alert-dismissible feedback" style="margin:0px;" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<div class="alert-message">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal" id="EditHolidays" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white font-weight-bold">Edit Holiday</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span class="text-white" aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body m-3">
									<div class="row">
										<div class="col-md-12">
											<form id="edit_Holiday" method="POST" action="#">
												<div class="row">
													<div class="form-group col-md-12">
														<label>Select Date</label>
														<input type="date" class="form-control" name="holiday_date">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-12">
														<label>Holiday Name</label>
														<input type="text" class="form-control" name="holiday_name">
													</div>
												</div>
												<input type="hidden" name="holiday_id">
												<button type="submit" class="btn btn-primary mt-2">Submit</button>
												
											</form>
											<div class="alert alert-dismissible feedback" style="margin:0px;" role="alert">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<div class="alert-message">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Success</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body m-3">
									<p class="mb-0" id="SuccessModalMessage"></p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
					<!--  -->
					<?php
						}
					?>
				</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>

<script type="text/javascript">
	$(document).ready(function(){
	    $(document).ajaxStart(function() {
        $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
	
	$(".datatables-basic").DataTable({
		responsive: true
	});

	$("#AddHolidays").validate({
	  	errorElement: "div",
	    rules: {
        "holiday_date" : {
          required : true
        },
        "holiday_name" :{
        	required : true
        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddHolidays").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>BusinessAdmin/Holidays",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){ 
            	$("#createHolidays").modal('hide');

							$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
								$("#SuccessModalMessage").html("").html(data.message);
							}).on('hidden.bs.modal', function (e) {
									window.location.reload();
							});
            }
            else if (data.success == 'false'){                   
        	    if($('.feedback').hasClass('alert-success')){
                $('.feedback').removeClass('alert-success').addClass('alert-danger');
              }
              else{
                $('.feedback').addClass('alert-danger');
              }
              $('.alert-message').html("").html(data.message); 
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});	
		
	});

	//EDIT holiday
	$("#edit_Holiday").validate({
	  	errorElement: "div",
	    rules: {
	        "holiday_date" : {
            required : true
	        },
	        "holiday_name" : {
            required : true,
            maxlength : 255
	        },
	        "holiday_id" : {
	          required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#edit_Holiday").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditHolidays",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#EditHolidays").modal('hide');
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									$("#SuccessModalMessage").html("").html(data.message);
								}).on('hidden.bs.modal', function (e) {
										window.location.reload();
								});
              }
              else if (data.success == 'false'){                   
          	    if($('.feedback').hasClass('alert-success')){
                  $('.feedback').removeClass('alert-success').addClass('alert-danger');
                }
                else{
                  $('.feedback').addClass('alert-danger');
                }
                $('.alert-message').html("").html(data.message); 
              }
            },
            error: function(data){
    					$('.feedback').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				});
			},
	});

		$(document).on('click','.editHoliday',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      
        $("#edit_Holiday input[name=holiday_date]").val($(this).attr('holiday_date'));
        $("#edit_Holiday input[name=holiday_name]").val($(this).attr('holiday_name'));
        $("#edit_Holiday input[name=holiday_id]").val($(this).attr('holiday_id'));
        $("#EditHolidays").modal('show');
  
    });

    $(document).on('click','.deleteholiday',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "holiday_id" : $(this).attr('holiday_id')
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/CancelHolidays",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
        }
			});
    });

</script>
<script>
  function exportTableToExcel(tableID, filename = ''){
      var downloadLink;
      var dataType = 'application/vnd.ms-excel';
      var tableSelect = document.getElementById(tableID);
      var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
      
      // Specify file name
      filename = filename?filename+'.xls':'excel_data.xls';
      
      // Create download link element
      downloadLink = document.createElement("a");
      
      document.body.appendChild(downloadLink);
      
      if(navigator.msSaveOrOpenBlob){
          var blob = new Blob(['\ufeff', tableHTML], {
              type: dataType
          });
          navigator.msSaveOrOpenBlob( blob, filename);
      }else{
          // Create a link to the file
          downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
      
          // Setting the file name
          downloadLink.download = filename;
          
          //triggering the function
          downloadLink.click();
      }
  }
</script>