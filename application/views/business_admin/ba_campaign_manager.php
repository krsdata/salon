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
				<!-- <h1 class="h3 mb-3">XXXXXXX Management</h1> -->
				<div class="row">
					<?php
					if (empty($business_outlet_details)) {
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
					if (!isset($selected_outlet)) {
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
					} else {
					?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">List Of Campaigns Runs</h5>
									<button style="float:right;margin:10px" id="newcampaign" class="btn btn-success">New Campaign</button>
								</div>
								<div class="card-body">
									<table class="table table-striped datatables-basic" name="list_campaign">
										<thead>
											<tr>
												<th>S.No</th>
												<th>Run Date</th>
												<th>Campaign Name</th>
												<th>Mode</th>
												<th>Message Script</th>
												<th>Target Base</th>
												<th>Conversions</th>
												<th>Conversion %</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 0;
											if (isset($campaigns)) {
												foreach ($campaigns as $key => $value) {

											?>
													<tr>
														<td><?= $i = $i + 1; ?></td>
														<td><?= $value['rundate'] ?></td>
														<td><?= $value['campaign_name'] ?></td>
														<td><?= $value['campaign_mode'] ?></td>
														<td><?= $value['message'] ?></td>
														<td><?= $value['recipient'] ?> Customer</td>
														<td></td>
														<td></td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal" id="ModalAddEmployee" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Campaign Manager</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true" class="text-white">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<form id="AddEmployee" method="POST" action="#" enctype="multipart/form-data">
													<div class="smartwizard-arrows-primary wizard wizard-primary">
														<ul>
															<li><a href="#arrows-primary-step-1">Campaign Details<br /></a></li>
															<li><a href="#arrows-primary-step-2"> Campaign Audience<br /></a></li>
															<!-- <li><a href="#arrows-primary-step-3">Other Details<br/></a></li> -->
														</ul>
														<div>
															<div id="arrows-primary-step-1" class="">
																<div class="form-row">
																	<div class="col-md-12" style="text-align:center;padding:10px">
																		<select class="form-control" name="ftype">
																			<option selected>SMS</option>
																		</select>
																	</div>
																</div>
																<div class="form-row">
																	<div class="col-md-12" style="text-align:center;padding:10px">
																		<input type="text" name="camapign_name" id="camapign_name" class="form-control" placeholder="Campaign Name">
																	</div>
																</div>
																<div class="form-row">
																	<div class="col-md-12" style="text-align:center;padding:10px">
																		<textarea rows="4" name="message" id="message" class="form-control" placeholder="Enter the Message Text"></textarea>
																	</div>
																</div>
																<div class="form-row">
																	<div class="col-md-12" style="padding:15px">
																		<button type="button" class="btn btn-primary" id="addname" class="form-control">Name</button>
																	</div>
																</div>
																<div class="form-row">
																	<div class="col-md-12" style="text-align:center;padding:10px">
																		<input type="text" name="name" id="name" placeholder="Name" value="Dear" class="form-control" hidden>
																	</div>
																</div>
															</div>
															<div id="arrows-primary-step-2" class="">
																<div class="form-row">
																	<div class="col-md-6" style="text-align:center;padding:10px">
																		<select id="recipent" name="recipent" class="form-control">
																			<option value="All">All Customer</option>
																			<option value="New">New Customer</option>
																			<option value="Repeat">Repeat Customer</option>
																			<option value="Regular">Regular Customer</option>
																			<option value="NoRisk">No Risk Customer</option>
																			<option value="Dormant">Sleepy Customer</option>
																			<option value="Risk">Risk Customer</option>
																			<option value="Lost">Lost Customer</option>
																			<option value="Manual">Manual</option>
																			<?php if ($tags != 0) {
																				foreach ($tags as $tags_d) {
																			?>
																					<option value="<?= $tags_d['tag_id'] ?>"><?= $tags_d['rule_name'] ?></option>
																			<?php
																				}
																			}
																			?>
																			<option value="Manual">Manual</option>
																		</select>
																	</div>
																	<!-- </div>    
                                                                <div class="form-row"> -->


																	<div class="col-md-6" style="text-align:center;padding:10px">
																		<select name="wsend" id="wsend" class="form-control">
																			<option value="now" selected>Send Now</option>

																		</select>
																	</div>
																</div>
																<div class="form-row">

																	<div class="col-md-12" style="padding:10px" id="rowhide" hidden>
																		<input type="file" name="file" id="file"><br>
																		<a class="btn btn-primary" style="margin-top:10px" href="<?= base_url() ?>public\format\CampaignUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
																		<!-- <input type="text" id="fmyfile" name="fmyfile" class="form-control"> -->
																		<!-- <label><h5>Hint:#Name #MobileNo,#Name2 #MobileNo******</h5></label> -->
																	</div>
																</div>
																<div class="form-row">
																	<div class="col-md-12" style="text-align:center;padding:10px">
																		<!-- <button type="submit" class="btn btn-success" id="launch">Send</button>  -->
																		<button type="submit" class="btn btn-primary">Submit</button>
																	</div>
																</div>
															</div>
															<div id="arrows-primary-step-3" class="">

															</div>
														</div>
													</div>
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
			$(document).on('click', '#newcampaign', function(event) {
				event.preventDefault();
				this.blur(); // Manually remove focus from clicked link.

				$("#ModalAddEmployee").modal('show');


			});

			$(document).on('change', '#recipent', function(event) {
				var recipent = document.getElementById('recipent').value;
				//   alert(recipent);
				if (recipent == 'Manual') {
					$("#rowhide").removeAttr('hidden');
				} else {
					$("#rowhide").attr('hidden', true);
				}
			});
			$(document).ready(function() {

				$(".datatables-basic").DataTable({
					responsive: true
				});
			});

			$(".smartwizard-arrows-primary").smartWizard({
				theme: "arrows",
				showStepURLhash: false
			});
			$(document).on('click', '#addname', function(event) {
				//   var textarea = document.getElementById('addname').value;
				document.getElementById("message").value += " #Name# ";
			});

			$("form#AddEmployee").submit(function(form) {
				form.preventDefault();
				// form.preventDefault();
				// var pdf = new formData();
				// var file = document.getElementById("cv").files[0];
				// var cv = $('#cv').files;
				// alert(file);

				// var form = $('form')[0];
				// var data = $("#EditEmployee").serialize(); 
				// var formData = $("#EditEmployee").submit(function(form){ return; });
				var formData = new FormData(this);
				// formData.append('File',file);
				$.ajax({
					url: "<?= base_url() ?>BusinessAdmin/InsertCampaignData",
					data: formData,
					contentType: "application/octet-stream",
					enctype: 'multipart/form-data',
					contentType: false,
					processData: false,
					type: "POST",
					// crossDomain: true,
					cache: false,
					// dataType : "json",
					success: function(data) {
						if (data.success == 'true') {
							$("#ModalEditEmployee").modal('hide');
							toastr["success"](data.message, "", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function() {
								location.reload(1);
							}, 1000);
						} else if (data.success == 'false') {
							toastr["error"](data.message, "", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
						}
					},
					error: function(data) {
						toastr["error"](data.message, "", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						});
					}
				});
			});
		</script>
