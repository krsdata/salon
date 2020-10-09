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
				<h1 class="h3 mb-3">Auto Engage</h1>
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
							<div class="card-header" style="margin-left:10px;">
							<div class="row">
								<div class="col-md-8">
									<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#tab-1">Marketing</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-2">Business Ops</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-3">Staff</a>
										</li>
									</ul>
								</div>
								<div class="col-md-4">
								<button type="submit" class="btn btn-primary float-md-middle" data-toggle="modal" data-target="#ModalCreateTrigger" >New Trigger</button>

								</div>
							</div>
								
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header">
														<h4></h4>
													</div>
													<div class="card-body">
														<table class="datatables-basic table table-hover" style="width:100%;">
															<thead>
																<th> S. No.</th>
																<th>Trigger Name</th>
																<th>Trigger Description</th>
																<th>SMS/WA</th>
																<th>Outlet Applicable</th>
																<th>frequency Type</th>
																<th>frequency Details</th>
																<th>Action</th>
															</thead>
															<tbody>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>									
									<div class="tab-pane" id="tab-2" role="tabpanel">
										<div class="row">
											<div class="col-md-12">
												<div class="card">
													<div class="card-header">
														<h4></h4>
													</div>
													<div class="card-body">
													<table class="datatables-basic table table-hover" style="width:100%;">
															<thead>
																<th> S. No.</th>
																<th>Trigger Name</th>
																<th>Trigger Description</th>
																<th>SMS/WA</th>
																<th>Outlet Applicable</th>
																<th>frequency Type</th>
																<th>frequency Details</th>
																<th>Action</th>
															</thead>
															<tbody>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane" id="tab-3" role="tabpanel">
										<div class="card">
											<div class="card-header">											
												<div class="row">
													<div class="col-md-10">
														<h3></h3>
													</div>
													<div class="col-md-2">
													<button class="btn btn-primary" onclick="exportTableToExcel('availableStock','Product Stock')"><i class="fa fa-file-export"></i>Download</button>
													</div>
												</div>
											</div>
											<div class="card-body">
											<table class="datatables-basic table table-hover" style="width:100%;">
															<thead>
																<th> S. No.</th>
																<th>Trigger Name</th>
																<th>Trigger Description</th>
																<th>SMS/WA</th>
																<th>Outlet Applicable</th>
																<th>frequency Type</th>
																<th>frequency Details</th>
																<th>Action</th>
															</thead>
															<tbody>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
															</tbody>
														</table>
											</div>
										</div>
									</div>									
								</div>
							</div>
						</div>	
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-9">
										<h5 class="card-title">New Configuration</h5>
									</div>
									
								</div>
								
							</div>
							<div class="card-body">
								<form method="POST" action="" id="autoEngage">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label>Select Trigger Type</label>
											<select class="form-control" name="trigger">
												<option value="Daily_Update_Admin">Daily Update Admin</option>
												<option value="Daily_Update_Expert">Daily Update Expert</option>
												<option value="Appointment_Reminder">Appointment Reminder</option>
												<option value="Package_Expiry">Package Expiry</option>
											    <option value="Birthday">Birthday</option>
												<option value="Anniversary">Anniversary</option>	
											</select>
										</div>
										<!--<div class="form-group col-md-3">-->
										<!--	<label>Days to trigger</label>-->
										<!--	<input type="number" name="day_to_trigger" class="form-control" required />-->
										<!--</div>-->
										<!--<div class="form-group col-md-3">-->
										<!--	<label>Offer</label>-->
										<!--	<input type="text" name="offer" class="form-control" required />-->
										<!--</div>-->
										<div class="form-group col-md-6">
											<label>Outlet</label>
											<select name="business_outlet_id" class="form-control">
												<option value="" selected>Select Outlet</option>
												<?php
													foreach ($business_outlet_details as $outlet) {
														echo "<option value=".$outlet['business_outlet_id'].">".$outlet['business_outlet_name']."</option>";
													}
												?>
											</select>	
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									
									</div>
								</form>
							</div>
						</div>	
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-9">
										<h5 class="card-title">Send Pending Amount Message</h5>
									</div>									
								</div>								
							</div>
							<div class="card-body">
								<form method="POST" action="#" id="pendingAmountSms">
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Select Outlet</label>
											<select name="business_outlet_id" class="form-control">
												<!-- <option value="" selected>Select Outlet</option> -->
												<?php
													foreach ($business_outlet_details as $outlet) {
														echo "<option value=".$outlet['business_outlet_id'].">".$outlet['business_outlet_name']."</option>";
													}
												?>
											</select>	
										</div>
										<div class="form-group col-md-3 mt-4">
											<button type="submit" class="btn btn-warning">Send SMS</button>
										</div>
									</div>
								</form>
							</div>
						</div>	
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
							<div class="row">
								<div class="col-md-9">
									<h5 class="card-title">Active Trigger Campaigns</h5>
									</div><div class="col-md-3">
										<button class="btn btn-primary mb-2" onclick="exportTableToExcel('triggerTable','Trigger')"><i class="fa fa-file-export"></i>Export</button>
									</div>
								</div>
								
							</div>
							<div class="card-body">
								<table class="table table-striped datatables-basic" style="width:100%" id="triggerTable">
									<thead>
										<tr>
											<th>Sno.</th>
											<!--<th>Trigger Id</th>-->
											<th>Outlet</th>
											<th>Trigger Type</th>
											<th>Days to Trigger</th>
											<!--<th>Offer Details</th>-->
											<th>Actions</th>
										</tr>
									</thead>
										<tbody>
										<?php
													$index = 1;
													foreach ($triggers as $trigger):
												?>
												<tr>
													<td><?=$index;?></td>
													<!--<td><?=$trigger['auto_engage_id']?></td>-->
													<td><?=$trigger['business_outlet_name']?></td>
													<td><?=$trigger['trigger_type']?></td>
													<td><?=$trigger['trigger_days']?></td>
													<!--<td><?=$trigger['offer_type']?></td>-->
													<td class="table-action">
														<!--<button type="button" class="btn editTrigBtn" auto_engage_id="<?=$trigger['auto_engage_id']?>">-->
														<!--	<i class="align-middle" data-feather="edit-2"></i>-->
														<!--</button>-->
														<!--  -->
														<?php
														if($trigger['is_active'] == 1){
													?>
														<button type="button" class="btn btn-success deleteTrigBtn" auto_engage_id="<?=$trigger['auto_engage_id']?>" is_active="0">
															<i class="align-middle" data-feather="unlock"></i>
														</button>
													<?php
														}
														else{
													?>
														<button type="button" class="btn btn-danger deleteTrigBtn" auto_engage_id="<?=$trigger['auto_engage_id']?>" is_active="1">
															<i class="align-middle" data-feather="lock"></i>
														</button>
													<?php
														}
													?>
														<!--  -->													
													</td>
												</tr>	
												<?php	
														$index = $index + 1;	
													endforeach;
												?>
								
										</tbody>
								</table>
							</div>
						</div>	
					</div>
					
				
					<!-- modal -->
						<div class="modal" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Success</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<p class="mb-0" id="SuccessModalMessage"><p>
									</div>
								</div>
							</div>
						</div>
						<div class="modal" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Error</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<p class="mb-0" id="ErrorModalMessage"></p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						<div class="modal" id="ModalEditTrigger" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title  text-white font-weight-bold">Edit Trigger</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
											<form method="POST" action="" id="editTrigger">
												<div class="form-row">
													<div class="form-group col-md-3">
														<label>Select Trigger Type</label>
														<select class="form-control" name="trigger">
															<!-- <option value="Birthday">Birthday</option>
															<option value="Anniversary">Anniversary</option> -->
															<option value="Daily Update Expert">Daily Update Expert</option>
															<!-- <option value="Appointment Reminder">Appointment Reminder</option>
															<option value="Package Expiry">Package Expiry</option>
															<option value="Pending Amount">Pending Amount</option> -->
														</select>
													</div>
													<div class="form-group col-md-3">
														<label>Days to trigger</label>
														<input type="number" name="day_to_trigger" class="form-control" required />
													</div>
													<div class="form-group col-md-3">
														<label>Offer</label>
														<input type="number" name="offer" class="form-control" required />
													</div>
													<div class="form-group col-md-3">
														<label>Outlet</label>
														<select name="business_outlet_id" class="form-control">
															<option value="" selected>Select Outlet</option>
															<?php
																foreach ($business_outlet_details as $outlet) {
																	echo "<option value=".$outlet['business_outlet_id'].">".$outlet['business_outlet_name']."</option>";
																}
															?>
														</select>	
													</div>
												</div>
												<div class="form-row">
													<div class="form-group col-md-3">
														<input type="hidden" name="auto_engage_id" />
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
												
												</div>
											</form>
												<div class="alert alert-dismissible feedback" role="alert">
													<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin:0px;">
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
						<div class="modal fade" id="ModalCreateTrigger" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header" style="background-color:#47bac1;">
												<h5 class="modal-title text-white">Create Trigger</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body m-3">
												<div class="row">
													<div class="col-md-12">
														<form id="CreateTrigger" method="POST" action="#">
															<div class="form-row">
																<div class="form-group col-md-4">
																	<label>Trigger Name</label>
																	<input type="text" class="form-control" placeholder="Trigger Name" name="trigger_name">
																</div>
																<div class="form-group col-md-4">
																	<label>Trigger Discription</label>
																	<input type="text" class="form-control" placeholder="Description"  name="business_outlet_firm_name">
																</div>															
																<div class="form-group col-md-4">
																	<label>Mode</label>
																	<select name="mode" class="form-control">
																		<option value="">SMS</option>
																		<option value="">WA</option>
																	</select>
																</div>
															</div>
															<div class="form-row">
																<div class="form-group col-md-4">
																	<label>Frequency Type</label>
																	<input type="text" class="form-control" placeholder="Frequncy Type" name="business_outlet_address">
																</div>															
																<div class="form-group col-md-4">
																	<label>Frequency Details</label>
																	<input type="email" class="form-control" placeholder="Frequency detail" name="business_outlet_email">
																</div>
																<div class="form-group col-md-4">
																	<label>Message Text</label>
																	<textarea type="text" class="form-control" placeholder="" name="business_outlet_mobile" style="height:130px;"></textarea>
																</div>
															</div>															
															<button type="submit" class="btn btn-primary">Submit</button>
														</form>
														<div class="alert alert-dismissible feedback" role="alert">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin:0px;">
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
					<!-- end -->
					<?php
						}
					?>
				</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
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
<script type="text/javascript">
	$(document).ready(function(){
		
		$(".datatables-basic").DataTable({
			responsive: true
		});
	  	
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
	  	
  	$("#autoEngage").validate({
	  	errorElement: "div",
	    rules: {
	        "trigger" : {
            required : true
	        }
	       // "day_to_trigger" : {
	       //   required : true
	       // },
	       // "offer" : {
	       // 	required : true
	       // }
	    },
	    submitHandler: function(form) {
				var formData = $("#autoEngage").serialize();
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddTrigger",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
								var message2 = data.message;
								var title2 = "";
								var type = "success";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
              }
              else if (data.success == 'false'){                   
          	    var message2 = data.message;
								var title2 = "";
								var type = "error";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
              }
            },
            error: function(data){
    					$('.feedback').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				});
			},
		});
        
        //Send Pending Amount SMS	
		$("#pendingAmountSms").validate({
	  	errorElement: "div",
	    rules: {
	        "business_outlet_id" : {
            required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#pendingAmountSms").serialize();
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/SendPendingAmountSms",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
								var message2 = data.message;
								var title2 = "";
								var type = "success";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
              }
              else if (data.success == 'false'){                   
          	    var message2 = data.message;
								var title2 = "";
								var type = "error";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
              }
            },
            error: function(data){
    					$('.feedback').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				});
			},
		});
        
		//Edit TrigBtn
		$(document).on('click','.editTrigBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        auto_engage_id : $(this).attr('auto_engage_id')
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/GetTrigger", parameters)
      .done(function(data, textStatus, jqXHR) { 
				$("#editTrigger select[name=trigger]").append("<option value="+data.trigger_type+" selected='selected'>"+data.trigger_type+"</option>");
        $("#editTrigger select[name=trigger]").attr('value',data.trigger_type);
        $("#editTrigger input[name=day_to_trigger]").attr('value',data.trigger_days);
        $("#editTrigger input[name=offer]").attr('value',data.offer_type);
        $("#editTrigger input[name=auto_engage_id]").attr('value',data.auto_engage_id);
       
        $("#ModalEditTrigger").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });


		$("#editTrigger").validate({
	  	errorElement: "div",
	    rules: {
	        "trigger" : {
            required : true
	        },
	        "day_to_triggre" : {
	          required : true
	        },
	        "offer" : {
	        	required : true
	        },
	        "auto_engage_id" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#editTrigger").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditTrigger",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditTrigger").modal('hide');
								var message2 = data.message;
								var title2 = "";
								var type = "success";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
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
		// Delete Triggger
		$(document).on('click','.deleteTrigBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
				
      var parameters = {
        auto_engage_id : $(this).attr('auto_engage_id'),
				is_active			 : $(this).attr('is_active')
			
      };
			$.ajax({
				url: "<?=base_url()?>BusinessAdmin/CancelTrigger",
				data: parameters,
				type: "POST",
				// crossDomain: true,
				cache: false,
				// dataType : "json",
				success: function(data) {
					if(data.success == 'true'){ 
						var message2 = data.message;
						var title2 = "";
						var type = "success";
						toastr[type](message2, title2, {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
						setTimeout(function () { location.reload(1); }, 1000);
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
