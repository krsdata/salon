<?php
	$this->load->view('cashier/cashier_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('cashier/cashier_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('cashier/cashier_top_nav_view');
		?>	
		<div class="container-fluid p-0">
			<div class="row" style="margin:20px;">

				<div class="col-md-12">
					<div class="tab">
						<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder;">
							<li class="nav-item ml-2"><a class="nav-link active" href="#primary-tab-1" data-toggle="tab" role="tab">Calendar View</a></li>
							<li class="nav-item"><a class="nav-link" href="#primary-tab-2" data-toggle="tab" role="tab">Customer</a></li>
							<!-- <li class="nav-item"><a class="nav-link" href="#primary-tab-3" data-toggle="tab" role="tab">Expert</a></li> -->
							<li class="nav-item"><a class="nav-link" href="#primary-tab-4" data-toggle="tab" role="tab">Expert Wise Weekly</a></li>
						</ul>				
						<div class="tab-content">
							<div class="tab-pane active" id="primary-tab-1" role="tabpanel">
								<div class="row" style="margin:20px;">	
									<div class="col-md-12">
										<div class="tab">
											<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight:bolder;">
												<li class="nav-item ml-2"><a class="nav-link active" href="#primary-calTab-1" data-toggle="tab" role="tab">Marks-Retech Calendar</a></li>
												<li class="nav-item ml-2"><a class="nav-link" href="#primary-calTab-2" data-toggle="tab" role="tab">Facebook Appointment Calendar</a></li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="primary-calTab-1" role="tabpanel">
													<div class="row" style="margin:20px;">
														<div class="card">
															<div class="card-header">
																<div class="row">
																	<div class="col-md-4">
																		<button class="btn btn-success" data-toggle="modal" data-target="#ModalAddAppointment" id="add_appointment_btn"><i class="fa fa-plus"></i>Add Appointment</button>
																	</div>
																</div>
															</div>																
															<div class="card-body">
																<div id="appointment_calendar_view"></div>
															</div>																						
														</div>
													</div>
												</div>
												<div class="tab-pane " id="primary-calTab-2" role="tabpanel">
													<div class="row" style="margin:20px;">
														<div class="card">
															<div class="card-header">
																<div class="row">
																	<div class="col-md-12">
																		<h5 class="card-title">Facebook Appointment</h5>
																	</div>
																	<div class="col-md-2">
												
																	</div>
																</div>
															</div>																
															<div class="card-body">
															    <?php
															        if(isset($facebook) && !empty($facebook))
															        {
															            $id =  $facebook[0]['calendar_id'];
															          ?>
															         	<div id="facebook_appointment_calendar_view"></div>  
															                
															         	
															         	<?php
															        }
															        else{
															            $id =  '';
															            ?>
															            <h5>Calendar Has Not been Integrated.</h5>
															            <?php
															        }
															    ?>
															    
															</div>																						
														</div>
													</div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
							<div class="tab-pane" id="primary-tab-2" role="tabpanel">
								<div class="row my-2">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
												<div class="row">
													<div class="col-md-3">
															<button class="btn btn-success" data-toggle="modal" data-target="#ModalAddAppointment"><i class="fa fa-plus"></i>Add Appointment</button>
													</div>
												</div>
											</div>
											<div class="card-body">
												<table id="" class="table table-striped datatables-buttons" style="width:100%">
													<thead>
														<tr>
															<th>Customer Name <br/> <small>Mobile No.</small></th>
															<?php
																$tday = date('Y-m-d');
																for($i=0;$i<7;$i++):
															?>
															<th><?=date('Y-m-d', strtotime( "$tday + ".$i." day" ))?></th>
															<?php
																endfor;
															?>
														</tr>
													</thead>
													<tbody>			
													<?php
														foreach ($appointments as $result):
															if($result['appointment_date'] < date('Y-m-d', strtotime( "$tday + 7 day" )) && $result['appointment_date'] >= date('Y-m-d') && $result['appointment_status']==1){
													?>
													<tr>
														<td>
															<?php ?>
																<?=$result['customer_name'] ?><br/><small><?=$result['customer_mobile']?></small>
																
															<?php
														
															?>
															
														</td>
														<?php
															for($i=0;$i<7;$i++):
														?>
														<td>
															<?php if($result['appointment_date'] == date('Y-m-d', strtotime( "$tday + ".$i." day" ))):?>								
															<a class="provideAppointmentDetails" appointment_id="<?=$result['appointment_id']?>" appointment_date="<?=$result['appointment_date']?>" appointment_start_time="<?php echo substr($result['appointment_start_time'],0,5);?>" appointment_end_time="<?php echo substr($result['appointment_end_time'],0,5);?>" expert_id="<?=$result['employee_id']?>" service_name="<?=$result['service_name']?>" service_id="<?=$result['service_id']?>" customer_name="<?=$result['customer_name']?>"        
																customer_mobile="<?=$result['customer_mobile']?>" remarks="<?=$result['remarks']?>" data-toggle="tooltip" title="<?=$result['service_name']?>">
																
																<div class="appointmentCard">
																	<?=$result['customer_name']?><br>
																	<?php echo date("g:i a",strtotime($result['appointment_start_time']));?>
																	
																</div>
															
															</a>	
															<?php
																endif;
															?>
														</td>
														<?php
															endfor;
														?>
													</tr>
													<?php	
														}
														endforeach;
													?>													
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>									
							</div>
							<div class="tab-pane" id="primary-tab-3" role="tabpanel">
								<div class="row my-2">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
												<div class="row">
													<div class="col-md-2">
															<button class="btn btn-success" data-toggle="modal" data-target="#ModalAddAppointment"><i class="fa fa-plus"></i>Add Appointment</button>
													</div>
												</div>
											</div>
											<div class="card-body">
												<table id="" class="table table-striped datatables-buttons" style="width:100%">
													<thead>
														<th>Expert Name</th>
														<th>Customer Name</th>
														<th>Appointment Date</th>
														<th>Appointment Time</th>
														<th>Actions</th>
													</thead>
													<?php 
														foreach($appointments_today as $appointment){
														?>
														<tr>
															<td><?=$appointment['employee_first_name']?></td>
															<td><?=$appointment['customer_name']?></td>	
															<td><?=$appointment['appointment_date']?></td>
															<td><?php echo substr($appointment['appointment_start_time'],0,5);?></td>
															<td>
																<button type="button" class="btn btn-primary provideAppointmentDetails" appointment_id="<?=$appointment['appointment_id']?>" customer_name="<?=$appointment['customer_name']?>" customer_mobile="<?=$appointment['customer_mobile']?>" appointment_date="<?=$appointment['appointment_date']?>" appointment_start_time="<?php echo substr($appointment['appointment_start_time'],0,5);?>" appointment_end_time="<?php echo substr($appointment['appointment_start_time'],0,5);?>" service_name="<?=$result['service_name']?>" service_id="<?=$result['service_id']?>" expert_id="<?=$appointment['employee_id']?>"><i class="fa fa-pen"></i>
																</button>
															</td>
														</tr>
														<?php
													}
													?>
												</table>
											</div>
										</div>
									</div>
								</div>	
							</div>
							<div class="tab-pane" id="primary-tab-4" role="tabpanel">
								<div class="row my-2">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
												<div class="row">
													<div class="col-md-3">
															<button class="btn btn-success" data-toggle="modal" data-target="#ModalAddAppointment"><i class="fa fa-plus"></i>Add Appointment</button>
													</div>
													<!-- <form action="#" method="GET" id="ExpertWiseAppointment" class="form-inline">
														<div class="form-row">
															<div class="form-group col-md-3">
																<label></label>
																<input type="date" name="app_date" class="form-control" min="<?php echo date('Y-m-d');?>" id="app_date"/>
															</div>
														</div>
													</form> -->
												</div>
											</div>
											<div class="card-body">
												<table id="" class="table table-striped datatables-buttons" style="width:100%">
													<thead>
														<tr>
															<th>Expert Name</th>
															<?php
																$tday = date('Y-m-d');
																for($i=0;$i<7;$i++):
															?>
															<th><?=date('Y-m-d', strtotime( "$tday + ".$i." day" ))?></th>
															<?php
																endfor;
															?>
														</tr>
													</thead>
													<tbody>			
													<?php
														foreach ($appointments as $result):
															if($result['appointment_date'] < date('Y-m-d', strtotime( "$tday + 7 day" )) && $result['appointment_date'] >= date('Y-m-d') && $result['appointment_status']==1){
													?>
													<tr>
														<td>
															<?=$result['employee_first_name'] ?>
														</td>
														<?php
															for($i=0;$i<7;$i++):
														?>
														<td>
															<?php if($result['appointment_date'] == date('Y-m-d', strtotime( "$tday + ".$i." day" ))):?>								
															<a class="provideAppointmentDetails" appointment_id="<?=$result['appointment_id']?>" appointment_date="<?=$result['appointment_date']?>" appointment_start_time="<?php echo substr($result['appointment_start_time'],0,5);?>" appointment_end_time="<?php echo substr($result['appointment_end_time'],0,5);?>" expert_id="<?=$result['employee_id']?>" service_name="<?=$result['service_name']?>" service_id="<?=$result['service_id']?>" customer_name="<?=$result['customer_name']?>"        
																customer_mobile="<?=$result['customer_mobile']?>" remarks="<?=$result['remarks']?>" data-toggle="tooltip" title="<?=$result['service_name']?>">
																
																<div class="appointmentCard">
																	<?=$result['customer_name']?><br>
																	<?php echo date("g:i a",strtotime($result['appointment_start_time']));?>
																	
																</div>															
															</a>	
															<?php
																endif;
															?>
														</td>
														<?php
															endfor;
														?>
													</tr>
													<?php	
														}
														endforeach;
													?>													
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>									
							</div>
						</div>
					</div> 
				</div>	
					<!-- modal area starts-->
					
					<?php
						$this->load->view('cashier/cashier_success_modal_view');
						$this->load->view('cashier/cashier_error_modal_view');
					?>

					<div class="modal" id="ModalAddAppointment" tabindex="-1" role="dialog" aria-hidden="true">	
						<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white">Book Appointment</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="tab">
										<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder;">
											<li class="nav-item ml-2"><a class="nav-link active" href="#appointment_1" data-toggle="tab" role="tab">Existing Customer</a></li>
											<li class="nav-item"><a class="nav-link" href="#appointment_2" data-toggle="tab" role="tab">New Customer</a></li>
										</ul>				
										<div class="tab-content">
											<div class="tab-pane active" id="appointment_1" role="tabpanel">
												<div class="row">	
													<div class="col-md-12">
														<form method="POST" action="#" id="AddAppointment">
															<div class="form-row">
																<div class="form-group col-md-3">
																	<input type="text" class="form-control" name="customer_mobile" placeholder="Mobile Number"  id="customer_mobile" />
																	<span class="input-group-append">
																	</span>
																</div>
																
																<div class="form-group col-md-3">
																	<input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Name"  />
																</div>
																<div class="form-group col-md-3">
																	<input type="text" name="customer_title" id="customer_title" class="form-control" required>
																</div>
																<div class="form-group col-md-3">
																	<input type="text" class="form-control date" name="appointment_date" />
																</div>
																
															</div>
															<div class="form-row">
																<table id="serviceTable" class="table" style="border-top:0px!important;">
																	<tbody>
																		<tr>
																			<td>
																				<div class="form-group">
																					<label>Category</label>
																					<select class="form-control" name="service_category_id">
																						<option value="" selected></option>
																						<?php
																							foreach ($categories as $category) {
																								echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																							}
																						?>
																					</select>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<label>Sub-Category</label>
																					<select class="form-control" name="service_sub_category_id">
																					</select>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<label>Service</label>
																					<select class="form-control" name="service_id[]" temp="Service">
																					</select>
																				</div>
																			</td>
																		</tr>
																	</tbody>
																</table>
																<!-- <button type="button" class="btn btn-success" id="AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																<button type="button" class="btn btn-danger" id="DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button> -->
															</div>
															<div class="form-row">
                                                                <div class="form-group col-md-3">
                                                                    Select Expert
																	<select name="expert_id" class="form-control">
																		<option value="none">Select Expert</option>
																		<?php
																			foreach ($experts  as $expert):
																		?>
																		<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
																		<?php	
																			endforeach;
																		?>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	Appointment Time
																	<input type="time" class="form-control" name="appointment_start_time" id="start_time">
																	<input type="hidden" class="form-control" name="service_est_time" id="service_est_time">
																</div>																	
																<div class="form-group col-md-3">
																	End Time
																	<input type="time" class="form-control" name=" appointment_end_time" id="end_time" required>
																</div>
																<div class="form-group col-md-3">
																	Appointment Mode
																	<select name="appointment_mode" class="form-control">
																		<option value="Call">Call</option>
																		<option value="Walkin">Walkin</option>
																		<option value="Message">Message</option>
																		<option value="Email">Email</option>
																		<option value="Facebook">Facebook</option>
																	</select>
																</div>																				
															</div>
															
															<div class="form-row">
																<div class="form-group col-md-12">
																	Remarks
																	<textarea name="remarks" class="form-control"></textarea>
																</div>
															</div>
															<input type="hidden" name="business_outlet_name" value="<?=$cashier_details['business_outlet_name']?>" />
															<input type="hidden" name="sender_id" value="<?=$cashier_details['business_outlet_sender_id']?>" />
															<input type="hidden" name="api_key" value="<?=$cashier_details['api_key']?>" />
															<button type="submit" class="btn btn-primary mt-1">Submit</button>						
														</form>
														<div class="alert alert-dismissible feedback mt-2" role="alert">
															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
															<div class="alert-message">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="appointment_2" role="tabpanel">
											    <div class="row">
													<div class="col-md-12">
													    <form method="POST" action="#" id="AddAppointment1">
												            <div class="form-row">
																<div class="form-group col-md-3">
																	<input type="text" class="form-control" name="customer_mobile" placeholder="Mobile Number" />															
																</div>
																<div class="form-group col-md-3">
																	<input type="text" class="form-control" name="customer_name"  placeholder="Name"  />
																</div>
																<div class="form-group col-md-3">
																	<select class="form-control" name="customer_title" required>
																		<option value="" desabled="desabled">Select Gender</option>
																		<option value="Mr.">Male</option>
																		<option value="Ms.">Female</option>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	<input type="text" class="form-control date" name="appointment_date" />
																</div>
																
															</div>
															<div class="form-row">
																<table id="serviceTable1" class="table" style="border-top:0px;">
																	<tbody>
																		<tr>
																			<td>
																				<div class="form-group">
																					<label>Category</label>
																					<select class="form-control" name="service_category_id">
																						<option value="" selected></option>
																						<?php
																							foreach ($categories as $category) {
																								echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																							}
																						?>
																					</select>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<label>Sub-Category</label>
																					<select class="form-control" name="service_sub_category_id">
																					</select>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<label>Service</label>
																					<select class="form-control" name="service_id[]" temp="Service">
																					</select>
																				</div>
																			</td>
																		</tr>
																	</tbody>
																</table>
															</div>
															<div class="form-row">
																<div class="form-group col-md-3">
																	Select Expert
																	<select name="expert_id" class="form-control">
																		<option value="none">Select Expert</option>
																		<?php
																			foreach ($experts  as $expert):
																		?>
																		<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
																		<?php	
																			endforeach;
																		?>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	Appointment Time
																	<input type="time" name="appointment_start_time" class="form-control" id="new_start_time" required>
																	<input type="hidden" name="new_service_est_time" id="new_service_est_time">
																</div>																	
																<div class="form-group col-md-3">
																	End Time
																	<input type="time" name="appointment_end_time" class="form-control" id="new_end_time" required>
																</div>
																<div class="form-group col-md-3">
																	Appointment Mode
																	<select name="appointment_mode" class="form-control">
																		<option value="Call">Call</option>
																		<option value="Walkin">Walkin</option>
																		<option value="Message">Message</option>
																		<option value="Email">Email</option>
																		<option value="Facebook">Facebook</option>
																	</select>
																</div>																				
															</div>
															<div class="form-row">
																<div class="form-group col-md-12">
																	Remarks
																	<textarea name="remarks" class="form-control"></textarea>
																</div>
															</div>
															<input type="hidden" name="business_outlet_name" value="<?=$cashier_details['business_outlet_name']?>" />
															<input type="hidden" name="sender_id" value="<?=$cashier_details['business_outlet_sender_id']?>" />
															<input type="hidden" name="api_key" value="<?=$cashier_details['api_key']?>" />
															<button type="submit" class="btn btn-primary mt-1">Submit</button>						
														</form>
														<div class="alert alert-dismissible feedback mt-2" role="alert">
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
							</div>
						</div>
					</div>

					<div class="modal" id="ModalEditAppointment" tabindex="-1" role="dialog"  aria-modal="true">
						<div class="modal-dialog modal-md" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white">Edit Appointment</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body ">
									<form method="POST" action="#" id="editAppointment">
										<div class="form-row">	
											<div class="form-group col-md-6">
												<label>Name</label>
												<input type="text" name="customer_name" id="edit_customer_name" class="form-control" readonly/>
											</div>																																			
											<div class="form-group col-md-6">
												<label>Mobile No</label>
												<input type="tel" class="form-control" id="edit_customer_mobile" name="customer_mobile" readonly>
											</div>																		
										</div>
										<div class="form-row">
											<div class="form-group col-md-6">
												<label>Appointment Date</label>
												<input type="date" class="form-control" name="appointment_date" value="<?php date('Y-m-d')?>" id="e_appointment_date" min="<?php echo date('Y-m-d');?>" required/>
											</div> 
											<div class="form-group col-md-6">
												<label>Expert</label>
												<select name="expert_id" id="e_expert" class="form-control">
												<?php
												foreach ($experts  as $expert):
											?>
											<option value="<?=$expert['employee_id']?>"><?=$expert['employee_nick_name']?></option>
											<?php	
												endforeach;
											?>
												</select>
											</div>										
										</div>
										<div class="form-row">
											<div class="form-group col-md-6">
												<label>Appointment Time</label>													
												<input type="time" class="form-control" name="appointment_start_time" id="e_appointment_start_time"  required>
											</div>	
											<div class="col-md-6">
												<label>End Time</label>													
                                                <input type="time" class="form-control" name="appointment_end_time" id="e_end_time"  required>
											</div>
											
										</div>											
										<div class="form-row">	
										<div class="form-group col-md-4">
												Category
												<select name="category_id" id="edit_category_id" class="form-control">
													<?php
														foreach ($categories  as $key=>$value):
													?>
													<option value="<?=$value['category_id']?>"><?=$value['category_name']?></option>
													<?php	
														endforeach;
													?>
												</select>
											</div>
											<div class="form-group col-md-4">
												Sub-category
												<select name="sub_category_id" id="edit_sub_category_id" class="form-control">
													
												</select>
											</div>																			
											<div class="form-group col-md-4">
												Service
												<select name="service_id" id="edit_service_id" class="form-control">
													
													</select>
											</div>
																															
										</div>
										<div class="form-row">
											<div class="form-group col-md-12">
												Remarks
												<textarea name="remarks" class="form-control" id="remarks"></textarea>
											</div>
										</div>
										<div class="form-row">
											<input type="hidden" name="appointment_id" id="appointment_id" /> 
											<input type="hidden" name="customer_id" id="customer_id" /> 
											<input type="hidden" name="business_outlet_name" value="<?=$cashier_details['business_outlet_name']?>" />
											<input type="hidden" name="sender_id" value="<?=$cashier_details['business_outlet_sender_id']?>" />
											<input type="hidden" name="api_key" value="<?=$cashier_details['api_key']?>" />
											<div class="form-group col-md-2">
												<button class="btn btn-primary btn-md" id="AddDataToCart">Billing</button>
											</div>
											<div class="form-group col-md-2">
												<button type="submit" class="btn btn-success btn-md">Update</button>
											</div>
											<div class="form-group col-md-2">
												<button type="button" class="btn btn-danger btn-md" id="cancelAppointment">Cancel</button>
											</div>
											<div class="form-group col-md-2">
												<button  type="button" id="reminder" class="btn btn-warning btn-md">Reminder</button>
											</div>
											<div class="form-group col-md-2">		
												<button type="" class="btn btn-success" ><i class="fa fa-users"></i> Invite</button>	
											</div>
										</div>
									</form>
									<div class="alert alert-dismissible feedback mt-2" role="alert">
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
					<!-- end Modal -->
				</div>				
			</div>				
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
<script type="text/javascript">
	//Add Appointment Time VAlidation
	$(document).on('change',"#end_time",function(e){
			var start_time= $("#start_time").val();
			var end_time=$("#end_time").val();
			if(start_time< end_time){
				return true;
			}else{
				alert("End time should be greater than start time.");
				return false;
			}
			
		});
		$(document).on('change',"#new_end_time",function(e){
			var start_time= $("#new_start_time").val();
			var end_time=$("#new_end_time").val();
			if(start_time< end_time){
				return true;
			}else{
				alert("End time should be greater than start time.");
				return false;
			}
			
		});
	//end
	//Edit Appointment Time VAlidation
	$(document).on('change',"#e_end_time",function(e){
			var start_time= $("#e_appointment_start_time").val();
			var end_time=$("#e_end_time").val();
			if(start_time< end_time){
				return true;
			}else{
				alert("End time should be greater than start time.");
				return false;
			}
			
		});
	//end
	
	$(document).on('click',".fc-day",function(e){
		$("#add_appointment_btn").click();
	});
	
	$(".date").daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
    	format: 'YYYY-MM-DD'
		}
	});

	// Add Appointment
	$("#AddAppointment").validate({
		errorElement: "div",
		rules: {	
			"customer_mobile" : {
				required : true,
				maxlength : 10,
				minlength : 10
			},
			"appointment_date" : {
				required : true
			},
			"appointment_start_time" :{
				required : true
			},
			"appointment_end_time" : {
				required : true
			},
			'expert_id' : {
				required : true
			}
		},
		submitHandler: function(form) {
			var formData = $("#AddAppointment").serialize(); 
			
			$.ajax({
				url: "<?=base_url()?>Cashier/AddAppointment",
				data: formData,
				type: "POST",
				// crossDomain: true,
				cache: false,
				// dataType : "json",
				success: function(data) {
					if(data.success == 'true'){
						$("#ModalAddAppointment").modal('hide'); 
						toastr["success"](data.message,"", {
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
		}
	});

	$("#AddAppointment1").validate({
		errorElement: "div",
		rules: {	
			"customer_mobile" : {
				required : true,
				maxlength : 10,
				minlength : 10
			},
			"appointment_date" : {
				required : true
			},
			"appointment_start_time" :{
				required : true
			},
			"appointment_end_time" : {
				required : true
			},
			'expert_id' : {
				required : true
			}
		},
		submitHandler: function(form) {
			var formData = $("#AddAppointment1").serialize(); 
			
			$.ajax({
				url: "<?=base_url()?>Cashier/AddAppointment",
				data: formData,
				type: "POST",
				// crossDomain: true,
				cache: false,
				// dataType : "json",
				success: function(data) {
					if(data.success == 'true'){
						$("#ModalAddAppointment").modal('hide'); 
						toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 2000
						});
						setTimeout(function () { location.reload(1); }, 2000);
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

	//Update Appointment
	$("#editAppointment").validate({
		errorElement: "div",
		rules: {
				"appointment_start_time" : {
					required : true
				},
				"appointment_date" : {
					required : true
				},
				"service_name" : {
					required : true,
					maxlength : 100
				}
		},
		submitHandler: function(form) {
					var formData = $("#editAppointment").serialize(); 
					$.ajax({
						url: "<?=base_url()?>Cashier/UpdateAppointment",
						data: formData,
						type: "POST",
						// crossDomain: true,
						cache: false,
						// dataType : "json",
						success: function(data) {
							if(data.success == 'true'){
								$("#ModalEditAppointment").modal('hide'); 
								var message1 = data.message;
								var title = "";
								var type = "success";
								toastr[type](message1, title, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
									// location.reload();
							}
							else if (data.success == 'false'){   
								// $("#ModalAddAppointment").modal('hide');                 
								// var message1 = data.message;
								// var title = "";
								// var type = "error";
								// toastr[type](message1, title, {
								// 	positionClass: "toast-top-right",
								// 	progressBar: "toastr-progress-bar",
								// 	newestOnTop: "toastr-newest-on-top",
								// 	rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								// 	timeOut: 2000
								// });
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

	//Get Appointment date wise 
	$("#app_date").on('change',function(e){
		var parameters = {
			'appointmnet_date' :  $(this).val()
		};
		$.getJSON("<?=base_url()?>Cashier/GetWeeklyAppointment", parameters)
		.done(function(data, textStatus, jqXHR) {
				var options = "<option value='' selected></option>"; 
				for(var i=0;i<data.length;i++){
					alert(data[i].appointment_id);
					// options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
				}
				// $("#edit_sub_category_id").html("").html(options);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});

	//Onchange select Category and subcategory
	$("#edit_category_id").on('change',function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#edit_sub_category_id").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			
			$("#edit_sub_category_id").on('change',function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						var price=0;
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
						$("#edit_service_id").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			$("#edit_service_id").on('change',function(e){
			var parameters = {
				'service_id' :  $(this).val()
			};
			$.getJSON("<?=base_url()?>Cashier/GetAppointmentServices", parameters)
			.done(function(data, textStatus, jqXHR) {
				$("#e_service_price_inr").val(data['message'].service_price_inr);
				$("#e_service_est_time").val(data['message'].service_est_time);
			})
		//			
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});
	//Add Services Data into Table
	function AddData(){
		var service_name = document.getElementById("edit_service_id").value;
		var service_est_time = document.getElementById("e_service_est_time").value;
		var service_price_inr = document.getElementById("e_service_price_inr").value;         
		var rows = "";
		rows += "<tr><td>1</td><td>" + service_name + "</td><td>" + service_est_time + "</td><td>" + service_price_inr + "</td><td><i class='fa fa-trash'></i></td></tr>";
		$(rows).appendTo("#appointmentServices tbody");
  
  }
	$("#DeleteRow").click(function(event){
  	event.preventDefault();
    this.blur();
    var rowno = $("#appointmentServices tr").length;
    if(rowno > 1){
    	$('#appointmentServices tr:last').remove();
  	}
  });	
	//Cancel Appointment	
	$(document).on('click','#cancelAppointment',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
				
      var parameters = {
        appointment_id : $("#appointment_id").val(),
				customer_mobile:$("#customer_mobile").val(),
				business_outlet_name:"<?=$cashier_details['business_outlet_name']?>",
				sender_id:"<?=$cashier_details['business_outlet_sender_id']?>",
				api_key:"<?=$cashier_details['api_key']?>",
				customer_name:$("#customer_name").val(),
				appointment_date:$("#e_appointment_date").val(),
				appointment_time:$("#e_appointment_start_time").val()
      };
			// alert(parameters);
					$.ajax({
						url: "<?=base_url()?>Cashier/CancelAppointment",
						data: parameters,
						type: "POST",
						// crossDomain: true,
						cache: false,
						// dataType : "json",
						success: function(data) {
							if(data.success == 'true'){ 
								$("#ModalEditAppointment").modal('hide'); 
								var message2 = data.message;
								var title2 = "";
								var type = "error";
								toastr[type](message2, title2, {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 2000
								});
								setTimeout(function () { location.reload(1); }, 2000);
									// location.reload();
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

		/*Shubham*/
		
		$(document).on('click','#AddDataToCart',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
				
      var parameters = {
        appointment_id : $("#appointment_id").val()
      };

			$.ajax({
				url: "<?=base_url()?>Cashier/AddToCartFromAppointment",
				data: parameters,
				type: "POST",
				// crossDomain: true,
				cache: false,
				// dataType : "json",
				success: function(data) {
					if(data.success == 'true'){ 
						$("#ModalEditAppointment").modal('hide'); 
						
						//If you want to redirect to billing board then modify this url
						//window.location.href = "<?=base_url()?>Cashier/PerformBilling/{customer_id}";

						//otherwise
						window.location.href = "<?=base_url()?>Cashier/Dashboard";
					}
					else if (data.success == 'false'){
						$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						}); 
					}  
				},
				error: function(data){
					$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e){
						$("#ErrorModalMessage").html("").html("There is some problem. Please try later!");
					}).on('hidden.bs.modal', function (e) {
							window.location.reload();
					}); 
				}
			});
		});	
		/*Shubham*/	


	//Cancel Appointment	
	$(document).on('click','.provideAppointmentDetails',function(event) {
		event.preventDefault();
		this.blur(); // Manually remove focus from clicked link.
		$("#editAppointment input[name=customer_name]").val($(this).attr('customer_name'));
		$("#editAppointment input[name=customer_mobile]").val($(this).attr('customer_mobile'));
		$("#editAppointment input[name=appointment_id]").val($(this).attr('appointment_id'));
		$("#editAppointment input[name=appointment_date]").val($(this).attr('appointment_date'));
		$("#e_appointment_start_time").append("<option value="+$(this).attr('appointment_start_time')+" selected='selected'>"+$(this).attr('appointment_start_time')+"</option>");
		$("#e_end_time").append("<option value="+$(this).attr('appointment_end_time')+" selected='selected'>"+$(this).attr('appointment_end_time')+"</option>");
		$("#edit_service_id").append("<option selected='selected' value="+$(this).attr('service_id')+">"+$(this).attr('service_name')+"</option>");
		 
		$("#editAppointment select[name=expert_id]").val($(this).attr('expert_id'));
		$("#remarks").val($(this).attr('remarks'));
				
		$("#ModalEditAppointment").modal('show');	
	});


	//send Reminder SMS
	$("#reminder").click(function() {
		// event.preventDefault();
		
		var customer_name = $("input#customer_name").val();
		var customer_mobile = $("input#customer_mobile").val();
		var business_outlet_name = "<?=$cashier_details['business_outlet_name']?>";
		var sender_id = "<?=$cashier_details['business_outlet_sender_id']?>";
		var api_key = "<?=$cashier_details['api_key']?>";
		var appointment_date = $("input#e_appointment_date").val();
		var appointment_start_time = $("select#e_appointment_start_time").val();
		// alert(appointment_start_time);
		// return false;
		$.ajax({
			type: "POST",
			url: "<?=base_url()?>Cashier/SendReminderSms",
			dataType: 'json',
			data: {sender_id:sender_id,api_key:api_key,customer_name:customer_name, customer_mobile: customer_mobile,business_outlet_name:business_outlet_name,appointment_date:appointment_date,appointment_start_time:appointment_start_time},
			success: function(data) {
				if(data.success == 'true'){
					$("#ModalEditAppointment").modal('hide'); 
					var message3 = data.message;
					var title3 = "";
					var type = "success";
					toastr[type](message3, title3, {
						positionClass: "toast-top-right",
						progressBar: "toastr-progress-bar",
						newestOnTop: "toastr-newest-on-top",
						rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
						timeOut: 2000
					});
					setTimeout(function () { location.reload(1); }, 2000);
				// location.reload();
			}
			}
		});
	});
	
	//
		//functionality for getting the dynamic input data
    $("#customer_mobile").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
    },
    {
      source: SearchCustomer,
      templates: {
        empty: "No Customer Found!",
        suggestion: _.template("<p><%- customer_name %>, <%- customer_mobile %></p>")
      }
    });
       
    var to_fill = "";
	var to_fill2 = "";
	var to_fill3 = "";

    $("#customer_mobile").on("typeahead:selected", function(eventObject, suggestion, name) {
         var loc1 = "#customer_mobile";
		var loc2 = "#customer_name";
		var loc3 = "#customer_title";
      to_fill = suggestion.customer_mobile;
			to_fill2 = suggestion.customer_name;
			to_fill3 = suggestion.customer_title;
      setVals(loc1,to_fill,suggestion.customer_id);
			setVals(loc2,to_fill2,suggestion.customer_id);
			setVals(loc3,to_fill3,suggestion.customer_id);
    });

    $("#customer_mobile").blur(function(){
      $("#customer_mobile").val(to_fill);
			$("#customer_name").val(to_fill2);
      to_fill = "";
			to_fill2 = "";
			to_fill3 = "";
    });

    function SearchCustomer(query, cb){
      var parameters = {
        query : query
      };

      $.ajax({
        url: "<?=base_url()?>Cashier/GetCustomerData",
        data: parameters,
        type: "GET",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
        global : false,
    		success: function(data) {
         	cb(data.message);
        },
        error: function(data){
					console.log("Some error occured!");
        }
			});
    }  

    function setVals(element,fill,customer_id){
      $(element).attr('value',fill);
      $(element).val(fill);
    }


</script>
<script>
	$("input[name=\"daterange\"]").daterangepicker({
		opens: "left"
	});
	
	$(document).on('change',"#serviceTable tr:last select[name=service_category_id]",function(e){
  	var parameters = {
  		'category_id' :  $(this).val()
  	};
  	$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId", parameters)
    .done(function(data, textStatus, jqXHR) {
    		var options = "<option value='' selected></option>"; 
     		for(var i=0;i<data.length;i++){
     			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
     		}
     		$("#serviceTable tr:last select[name=service_sub_category_id]").html("").html(options);
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });

  $(document).on('change',"#serviceTable tr:last select[name=service_sub_category_id]",function(e){
  	var parameters = {
  		'sub_category_id' :  $(this).val()
  	};
  	$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatId", parameters)
    .done(function(data, textStatus, jqXHR) {
    		var options = "<option value='' selected></option>"; 
     		for(var i=0;i<data.length;i++){
     			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
     		}
     		$("#serviceTable tr:last select[temp=Service]").html("").html(options);
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });
    
    $(document).on('change',"#serviceTable tr:last select[temp=Service]",function(e){
  	var parameters = {
  		'service_id' :  $(this).val()
  	};
		// alert($(this).val());
  	$.getJSON("<?=base_url()?>Cashier/GetServiceByServiceId", parameters)
    .done(function(data, textStatus, jqXHR) {
			var service_est_time=0;
    		// var service_est_time = data[0].service_est_time;
				if(data[0].service_est_time==null){
					// alert("Service Time is Null");
					// service_est_time= data[0].service_est_time;
					
				}else{
				// 	alert( data[0].service_est_time);
					service_est_time= data[0].service_est_time;
				// 	alert(service_est_time);
					$("#service_est_time").val(service_est_time);
				}
				
     		
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });
  
  $(document).on('blur','#start_time',function(e){
		// alert($("#serviceTable tr:last select[temp=Service]").val());
		// $("#end_time").val();
		var cur_time=$(this).val();
		var est_time=$("#service_est_time").val();
		var cur_time_minute = cur_time.split(':');
		var end_time= cur_time_minute[0]+":"+(Number(cur_time_minute[1])+Number(est_time));
		var service_est_time= Number(est_time);
		$("#end_time").val(end_time);
		// document.getElementById("end_time").value =end_time;
	});
  
  
    
	// new customer appointment
	$(document).on('change',"#serviceTable1 tr:last select[name=service_category_id]",function(e){
  	var parameters = {
  		'category_id' :  $(this).val()
  	};
  	$.getJSON("<?=base_url()?>Cashier/GetSubCategoriesByCatId", parameters)
    .done(function(data, textStatus, jqXHR) {
    		var options = "<option value='' selected></option>"; 
     		for(var i=0;i<data.length;i++){
     			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
     		}
     		$("#serviceTable1 tr:last select[name=service_sub_category_id]").html("").html(options);
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });

  $(document).on('change',"#serviceTable1 tr:last select[name=service_sub_category_id]",function(e){
  	var parameters = {
  		'sub_category_id' :  $(this).val()
  	};
  	$.getJSON("<?=base_url()?>Cashier/GetServicesBySubCatId", parameters)
    .done(function(data, textStatus, jqXHR) {
    		var options = "<option value='' selected></option>"; 
     		for(var i=0;i<data.length;i++){
     			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
     		}
     		$("#serviceTable1 tr:last select[temp=Service]").html("").html(options);
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });
  
  $(document).on('change',"#serviceTable1 tr:last select[temp=Service]",function(e){
  	var parameters = {
  		'service_id' :  $(this).val()
  	};
		// alert($(this).val());
  	$.getJSON("<?=base_url()?>Cashier/GetServiceByServiceId", parameters)
    .done(function(data, textStatus, jqXHR) {
			var service_est_time=0;
				if(data[0].service_est_time==null){
					service_est_time=0;					
				}else{
					service_est_time= data[0].service_est_time;
				// 	alert(service_est_time);
					$("#new_service_est_time").val(service_est_time);
				}     		
  	})
  	.fail(function(jqXHR, textStatus, errorThrown) {
      console.log(errorThrown.toString());
 		});
  });

	$(document).on('blur','#new_start_time',function(e){
		var cur_time=$(this).val();
		var est_time=$("#new_service_est_time").val();
// 		alert(est_time);
		var cur_time_minute = cur_time.split(':');
		var end_time= cur_time_minute[0]+":"+(Number(cur_time_minute[1])+Number(est_time));
		$("#new_end_time").val(end_time);
		// document.getElementById("end_time").value =end_time;
	});

	// 

  $("#AddRowService").click(function(event){
  	event.preventDefault();
    this.blur();
    var rowno = $("#serviceTable tr").length;
    
    rowno = rowno+1;
    
    $("#serviceTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_id\"></select></div></td><td><div class=\"form-group\"><label>Service</label><select class=\"form-control\" name=\"service_id[]\" temp=\"Service\"></select></div></td></tr>");
  });

  $("#DeleteRowService").click(function(event){
  	event.preventDefault();
    this.blur();
    var rowno = $("#serviceTable tr").length;
    if(rowno > 1){
    	$('#serviceTable tr:last').remove();
  	}
  });
</script>
<script>
	
	var events = <?php echo json_encode($clndr_aptmnt_data) ?>;
	
	$('#appointment_calendar_view').fullCalendar({
		eventClick: function (event,jsEvent,view) {
			$("#edit_customer_name").val(event.title);
			$("#customer_id").val(event.customer_id);
			$("#edit_customer_mobile").val(event.customer_mobile);			   
			$("#e_expert").append("<option selected='selected' value="+event.expert_id+">"+event.expert_name+"</option>");
			$("#e_appointment_date").val(moment(event.date).format('YYYY-MM-DD'));
    	   // $("#e_appointment_start_time").append("<option value="+(event.start_time).substr(0,5)+" selected='selected'>"+(event.start_time).substr(0,5)+"</option>");
    	    $("#e_appointment_start_time").val(event.start_time);
// 			$("#e_end_time").append("<option value="+(event.end_time).substr(0,5)+" selected='selected'>"+(event.end_time).substr(0,5)+"</option>");
            $("#e_end_time").val(event.end_time);
			$("#edit_service_id").append("<option selected='selected' value="+event.service_id+">"+event.description+"</option>");
			$("#edit_category_id").append("<option selected='selected' >"+event.category_name+"</option>");  
			$("#edit_sub_category_id").append("<option selected='selected' value="+event.service_sub_category_id+">"+event.sub_category_name+"</option>");
			$("#appointment_id").val(event.appointment_id);
			$("#remarks").val(event.remarks);
			
			$("#ModalEditAppointment").modal('show');   
		},
		eventSources: [{events: []}],
		header: {
			left: 'prev,next today',
			center: 'month,agendaWeek,agendaDay,list',
			right: 'title'
		},
		defaultDate: Date.now(),
		navLinks: true,
		editable: true,
		eventLimit: true,
		// timeFormat: 'H(:mm)',
		timeZone: 'local',
		events:events,
		eventRender: function(event, element) { 
// 			element.find('.fc-time').append("<br/>"); 
// 			element.find('.fc-title').append("<br/>" + event.description);
			$(element).tooltip({title: event.description});
			element.css('background-color', event.eventColor);
			element.css('border-color', event.eventColor);
		} 		
	});
</script>

<script>
	$(function() {
		// Datatables basic
		$(".datatables-basic").DataTable({
			responsive: true
		});
		// Datatables with Buttons
		var datatablesButtons = $(".datatables-buttons").DataTable({
			responsive: true,
			lengthChange: !1,
			// buttons: ["copy", "print"]
		});
		datatablesButtons.buttons().container().appendTo(".datatables-buttons_wrapper .col-md-6:eq(0)");
		// Datatables with Multiselect
		var datatablesMulti = $(".datatables-multi").DataTable({
			responsive: true,
			select: {
				style: "multi"
			}
		});
	});

	$(function() {
		// Pie chart
		var options = {
			chart: {
				height: 350,
				type: "donut",
			},
			dataLabels: {
				enabled: false
			},
			labels:["Call","Message","Email","Social Media","Walkin","Queue"],
			series: [44, 5, 13, 33,10,5]
		}
		var chart = new ApexCharts(
			document.querySelector("#apexcharts-pie"),
			options
		);
		chart.render();
	});
</script>

<script>
	var input = document.getElementById("customer_mobile");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
		event.preventDefault();
		document.getElementById("SearchCustomerButton").click();
		}
	});
	
</script>
<script>
var id = '<?=$id?>';
	 document.addEventListener('DOMContentLoaded', function()  {
                var calendarEl = document.getElementById('facebook_appointment_calendar_view');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    header:{
                        left:'prev,next today',
                        center:'title',
                        right:'month,agendaWeek,agendaDay'
                    },
                    
//                    Selectable:true;
//                    SelectHelper:true;
                    plugins: [ 'dayGrid','googleCalendar' ],
                  googleCalendarApiKey: 'AIzaSyAQPodd4De4NCFVca8IqsYgXG3YDiGJ0gw',
                  eventSources: [
                    {
                      googleCalendarId: id 
                    }
                  ],
                });

                    calendar.render();
                  });
</script>
