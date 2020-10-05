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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
		<?php
			$reminder = array(1=>'D-60',2=>'D-30',3=>'D-7',4=>'D-5',5=>'D-3',6=>'D-1',7=>'D',8=>'D+2',9=>'D+5',10=>'D+10',11=>'D+30',12=>'D+60');
			$ongoing = array(13=>'Everyday',14=>'Alternet Day',15=>'Weekly',16=>'Monthly');
			$days = array(1=>'Monday',2=>'Tuesday',3=>'WednesDay',4=>'Thursday',5=>'Friday',6=>'Satarday',7=>'Sunday');
			$interval = array('reminder'=>$reminder,'ongoing'=>$days);
			$interval = json_encode($interval);
			$mode = array(1=>'SMS',2=>'WhatsApp');
			$frequency = array(1=>'Reminder /Approaching Type',2=>'Regular /Ongoing');
			
			$outlets = [];
			foreach ($business_outlet_details as $key => $b) {
				$outlets[$b['business_outlet_id']] = $b['business_outlet_name'];
			}			 			
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
											<a class="nav-link active" data-toggle="tab" href="#tab-1">Control SMS Activity</a>
										</li>
										<!-- <li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#tab-1">Marketing</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-2">Business Ops</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-3">Staff</a>
										</li> -->
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
																<th>Outlet Applicable</th>
																<th>Action</th>
															</thead>
															<tbody>
																<?php
																	$index = 1;
																	foreach ($business_outlet_details as $outlet) {
																		?>
																		<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Before Appointment SMS'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_1", $activity)){
																			?>
																				<button type="button" service_id="1" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="1" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																		<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Day Closing Report'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_2", $activity)){
																			?>
																				<button type="button"  service_id="2" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="2" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																			<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Generate Report SMS[Weekly]'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_3", $activity)){
																			?>
																				<button type="button"  service_id="3" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="3" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																			</tr>
																			<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Pending Amount SMS[Weekly]'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_4", $activity)){
																			?>
																				<button type="button"  service_id="4" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="4" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																			</tr>
																			<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Generate Report SMS[Monthly]'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_5", $activity)){
																			?>
																				<button type="button"  service_id="5" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="5"  class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																			</tr>
																			<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo 'Pending Amount SMS[Monthly]'?></td>
																			<td><?php echo $outlet['business_outlet_name']?></td>
																			<td><?php
																				if(in_array($outlet['business_outlet_id']."_6", $activity)){
																			?>
																				<button type="button"  service_id="6" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button"  service_id="6" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$outlet['business_outlet_id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																			</tr>
																		<?php
																	}
																?>													
															</tbody>
														</table>
														<!-- <table class="datatables-basic table table-hover" style="width:100%;">
															<thead>
																<th> S. No.</th>
																<th>Trigger Name</th>
																<th>Trigger Description</th>
																<th>SMS/WA</th>
																<th>Outlet Applicable</th>
																<th>frequency Type</th>
																<th>Start Date</th>
																<th>Expiry Date</th>
																<th>Action</th>
															</thead>
															<tbody>
																<?php
																	$index = 1;
																	if(!empty($trigger_detail)){
																		foreach ($trigger_detail as $key => $t) {
																			?>
																			<tr>
																			<td><?php echo ++$i?></td>
																			<td><?php echo $t['trigger_name']?></td>
																			<td><?php echo $t['trigger_description']?></td>
																			<td><?php echo $mode[$t['mode']]?></td>
																			<td><?php echo $outlets[$t['outlet_id']]?></td>
																			<td><?php echo $frequency[$t['set_frequency']]?></td>
																			<td><?php echo $t['start_date']?></td>
																			<td><?php echo $t['expiry_date']?></td>
																			<td><?php
																				if($t['is_active'] == 1){
																			?>
																				<button type="button" class="btn btn-success deleteSMSTrigBtn" auto_engage_id="<?=$t['id']?>" is_active="0">
																					<i class="align-middle" data-feather="unlock"></i>
																				</button>
																			<?php
																				}
																				else{
																			?>
																				<button type="button" class="btn btn-danger deleteSMSTrigBtn" auto_engage_id="<?=$t['id']?>" is_active="1">
																					<i class="align-middle" data-feather="lock"></i>
																				</button>
																			<?php
																				}
																			?></td>
																		</tr>
																			<?php
																		}
																	}
																?>
																
															</tbody>
														</table> -->
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
																<div class="form-group col-md-2">
																	<label>Trigger Name</label>
																	<input type="text" class="form-control" placeholder="Trigger Name" name="trigger_name">
																</div>
																<div class="form-group col-md-4">
																	<label>Trigger Discription</label>
																	<input type="text" class="form-control" placeholder="Description"  name="trigger_discription">
																</div>															
																<div class="form-group col-md-2">
																	<label>Mode</label>
																	<select name="mode" class="form-control">
																		<option value="1">SMS</option>
																		<option value="2">WhatsApp</option>
																	</select>
																</div>
																<div class="form-group col-md-2">
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
																<div class="form-group col-md-2">
																	<label>Reciptents</label>
																	<select name="reciptents" class="form-control">
																		<option value="1">Buisness Admin</option>
																		<option value="2">Experts</option>
																		<option value="3">Both</option>
																	</select>
																</div>
															</div>

<!-- <div class="form-row">
																

															</div> -->
															<div class="form-row">
																<!-- <div class="col-md-3" style="text-align:end;float:right;">
												From <i class="fa fa-calendar" style="color:red;"></i>
											</div> -->
											<div class="from-group col-md-3">
												<label>Set Date</label>
												<input type="text" name="dates" class="form-control pull-right">
											</div>
																<div class="form-group col-md-3">
																	<label>Frequency Type</label>
																	<select name="ftype" class="form-control">
																		<option value="1">Reminder /Approaching Type: </option>
																		<option value="2">Regular /Ongoing: </option>
																	</select>
																</div>															
																<div class="form-group col-md-3 frequency_detail" style="display:none;">
																	<label>Frequency Details</label>
																	<select multiple name="frequency_detail[]" class="form-control">
																	</select>
																</div>
																<div class="form-group col-md-3">
																	<label>Message Text</label>
																	<textarea type="text" class="form-control" placeholder="" name="message" style="height:130px;"></textarea>
																</div>
															</div>	

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
		<div id="reminder_text" style='display:none;'><?php echo $interval;?></div>
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

	function prepare_reminder(frequency = 0){
		var reminder_text = $('#reminder_text').text();
		reminder_text = JSON.parse(reminder_text);
		console.log(reminder_text.reminder);
		if(frequency == 1){
			$('[name="frequency_detail[]"]').empty();
			var $dropdown = $('[name="frequency_detail[]"]');
			$.each(reminder_text.reminder, function(index,value) {
				console.log(index,' ======== ',value)
			    $dropdown.append($("<option />").val(index).text(value));
			});
		}else if(frequency == 2){
			$('[name="frequency_detail[]"]').empty();
			var $dropdown = $('[name="frequency_detail[]"]');
			$.each(reminder_text.ongoing, function(index,value) {
				console.log(index,' ======== ',value)
			    $dropdown.append($("<option />").val(index).text(value));
			});
		}
		$('.frequency_detail').show();
	}
	prepare_reminder(1);

	$('[name="ftype"]').change(function(){
		//console.log($(this).val());
		prepare_reminder($(this).val());
	});

	$("input[name=\"from_date\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
    format: 'YYYY-MM-DD'
		}
	});
  $("input[name=\"to_date\"]").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		locale: {
    format: 'YYYY-MM-DD'
		}
	});
  $('input[name="dates"]').daterangepicker();

  // Wait for the DOM to be ready
$(function() {
  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form#CreateTrigger").validate({
    // Specify validation rules
    rules: {
      // The key name on the left side is the name attribute
      // of an input field. Validation rules are defined
      // on the right side
      trigger_name: "required",
      trigger_discription: "required",
      mode: "required",
      business_outlet_id:"required",
      dates:"required",
      ftype:"required",
      frequency_detail:"required",
      message:"required",
    },
    // Specify validation error messages
    messages: {
      trigger_name: "Trigger Name is required",
      trigger_discription: "Trigger Description is required",
      mode: "Trigger Mode is required",
      business_outlet_id:"Business Outlet required",
      dates:"Start and Expiry date is required",
      ftype:"Frequency Type is required",
      frequency_detail:"Frequency Details is required",
      message:"Message is required",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
     

    	var formData = $("#CreateTrigger").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>BusinessAdmin/AddMessageTrigger",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$('form#CreateTrigger')[0].reset();
            	$('#ModalCreateTrigger').modal('hide'); 
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
            	setTimeout(function(){
            		window.location.reload();
            	},2000);
						
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




    }
  });
});


$(document).on('click','.deleteSMSTrigBtn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
				
      var parameters = {
        auto_engage_id : $(this).attr('auto_engage_id'),
				is_active			 : $(this).attr('is_active'),
				service_id			 : $(this).attr('service_id')
			
      };
			$.ajax({
				url: "<?=base_url()?>BusinessAdmin/ActivateSMSTrigger",
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
		
	
</script>
