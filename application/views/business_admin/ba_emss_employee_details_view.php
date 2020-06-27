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
				<h1 class="h3 mb-3">Employee Management</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="modal" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Success</h5>
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
						<div class="modal" id="ModalAddEmployee" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Employee Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true" class="text-white">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<form id="AddEmployee" method="POST" action="#" enctype="multipart/form-data">
													<div  class="smartwizard-arrows-primary wizard wizard-primary">
														<ul>
															<li><a href="#arrows-primary-step-1">Personal Details<br /></a></li>
															<li><a href="#arrows-primary-step-2">Professional Details<br /></a></li>
															<li><a href="#arrows-primary-step-3">Other Details<br/></a></li>
														</ul>
														<div>
															<div id="arrows-primary-step-1" class="">
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>First Name</label>
																		<input type="text" class="form-control" placeholder="First Name" name="employee_first_name">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Last Name</label>
																		<input type="text" class="form-control" placeholder="Last Name" name="employee_last_name">
																	</div>		
																	<div class="form-group col-md-4">
																		<label>Nick Name</label>
																		<input type="text" class="form-control" placeholder="Nick Name" name="employee_nick_name">
																	</div>														
																	
																</div>									
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Email</label>
																		<input type="email" class="form-control" placeholder="Email ID" name="employee_email">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Mobile</label>
																		<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="employee_mobile">
																	</div>
																	
																	
																	<div class="form-group col-md-4">
																	<label>Outlet</label>
																	<select name="employee_business_outlet" class="form-control">
																		<option value="" selected>Select Outlet</option>
																		<?php
																			foreach ($business_outlet_details as $outlet):
																		?>
																		<option value="<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></option>
																		<?php		
																			endforeach;
																		?>
																	</select>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Expertise</label>
																		<input type="text" class="form-control" name="employee_expertise" placeholder="Expertise">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Role</label>
																	<select name="employee_role" class="form-control">
																			<option value="" selected>Select Role</option>
																			<option value="Cashier">Cashier</option>
																			<option value="Expert">Expert</option>	
																		</select>
																	</div>
																
																	<div class="form-group col-md-4">
																		<label>Set Password (Optional for expert)</label>
																		<input type="text" class="form-control" name="employee_password" placeholder="Password">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Date of Joining</label>
																		<input type="text" class="form-control" name="employee_date_of_joining" placeholder="Date Of Joining">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-12">
																		<label>Address</label>
																		<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="employee_address">
																	</div>
																</div>
															</div>
															<div id="arrows-primary-step-2" class="">
																<div class="form-row">
																	<div class="form-group col-md-2">
																		<label>Salary P/M</label>
																		<input type="number" class="form-control" placeholder="Gross Salary" name="employee_gross_salary" readonly>
																			
																	</div>
																	
																	<div class="form-group col-md-2">
																		<label>Basic</label>
																		<input type="number" class="form-control" placeholder="Basic Salary" name="employee_basic_salary" min="0" required>
																	</div>																
																	<div class="form-group col-md-1">
																		<label>PF</label>
																		<input type="number" class="form-control" placeholder="PF" name="employee_pf" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Gratuity</label>
																		<input type="number" class="form-control" placeholder="Gratuity" name="employee_gratuity" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Others</label>
																		<input type="number" class="form-control" placeholder="Others" name="employee_others" min="0">
																	</div>
																	<div class="form-group col-md-1">
																		<label>PT</label>
																		<input type="number" class="form-control" placeholder="PT" name="employee_pt" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Income Tax</label>
																		<input type="number" class="form-control" placeholder="Income Tax" name="employee_it" min="0">
																	</div>
																</div>									
																<div class="form-row">
																	<div class="form-group col-md-3">
																		<label>Over Time P/H</label>
																		<input type="number" class="form-control" placeholder="Rupees/hour" name="employee_over_time_rate">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Working Hours</label>
																		<input type="number" class="form-control" placeholder="hour" name="employee_work_hour">
																	</div>
																	
																</div>
																<div class="form-row">
																	<div class="col-md-12">
																			<table id="Addweekofftable" class="table table-striped">
																				<thead><label>Week-Off</label>
																					
																				</thead>
																				<tbody>
																					<tr>
																						<td>
																							<select name="year[]" float="left" class="form-control" style="width:100px;">
																									<option disabled selected>Year</option>
																									<option value="2020">2020</option>
																									<option vlaue="2021">2021</option>
																							</select>
																						</td>
																						<td>	
																							<select name="month_name[]"  class="form-control" style="width:100px;" >
																									<option selected disabled>Month</option>
																									<option value="01">January</option>
																									<option value="02">February</option>
																									<option value="03">March</option>
																									<option value="04">April</option>
																									<option value="05">May</option>
																									<option value="06">June</option>
																									<option value="07">July</option>
																									<option value="08">August</option>
																									<option value="09">September</option>
																									<option value="10">October</option>
																									<option value="11">November</option>
																									<option value="12">December</option>
																							</select>
																						</td>
																						<td>
																							<input type="text"  name="employee_weekoff[]"  class="form-control date">
																						</td>
																						<td>
																							<input type="text"  name="employee_weekoff[]"  class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]"  class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]"  class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]"  class="form-control date">
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<button type="button" class="btn btn-success" id="AAddRowWeekOff">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
																			<button type="button" class="btn btn-danger" id="ADeleteRowWeekOff">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																</div>
																<div class="form-row">
																	
																	<div class="form-group col-md-3">
																		<label>Aadhar Number</label>
																		<input type="number" class="form-control" name="employee_aadhar_number" placeholder="Aadhar Number" min="0">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Total Experience</label>
																			<input type="number" class="form-control" name="employee_experience">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-12">
																		<div id="experience">
																			<table id="experienceTable" class="table table-hover">
																				<tbody>
																					<tr>
																						<td>
																							<div class="form-group">
																								<label>Employer</label>
																								<input type="text" name="employer[]" class="form-control" >
																							</div>
																						</td>
																						
																						<td>
																							<div class="form-group">
																								<label>From</label>
																								<input type="date" class="form-control" name="from_date[]">
																							</div>
																						</td>
																						<td>
																							<div class="form-group">
																								<label>To</label>
																								<input type="date" class="form-control" name="to_date[]">
																							</div>
																						</td>
																						<!-- <td><button type="button" class="btn btn-success" id="AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button></td>
																						<td><button type="button" class="btn btn-danger" id="DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button></td> -->
																					</tr>
																				</tbody>
																			</table>
																			<button type="button" class="btn btn-success" id="AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																			<button type="button" class="btn btn-danger" id="DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																		</div>
																	</div>
																</div>																
															</div>
															<div id="arrows-primary-step-3" class="">
																<div class="form-row">																	
																	<div class="form-group col-md-3">
																	<label>Total Certification</label>
																		<input type="number" name="no_of_certification" class="form-control">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-12">
																	<div id="certification">
																			<table id="certificationTable" class="table table-hover">
																				<tbody>
																					<tr>
																						<td>
																							<div class="form-group">
																								<label>Certificate Name</label>
																								<input type="text" name="certification_name[]" class="form-control" >
																							</div>
																						</td>
																						
																						<td>
																							<div class="form-group">
																								<label>From</label>
																								<input type="date" class="form-control" name="start_date[]">
																							</div>
																						</td>
																						<td>
																							<div class="form-group">
																								<label>To</label>
																								<input type="date" class="form-control" name="end_date[]">
																							</div>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<button type="button" class="btn btn-success" id="AddRowCertification">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																			<button type="button" class="btn btn-danger" id="DeleteRowCertification">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																		</div>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-3">
																		<label>Account Number</label>
																		<input type="number" class="form-control" name="employee_account_number" placeholder="Account Number">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Holder Name</label>
																		<input type="text" class="form-control" name="employee_account_holder_name" placeholder="Account Holder Name">
																	</div>																
																	<div class="form-group col-md-3">
																		<label>Bank Name</label>
																		<input type="text" class="form-control" name="employee_bank_name" placeholder="Bank Name">
																	</div>
																	<div class="form-group col-md-3">
																		<label>IFSC Code </label>
																		<input type="text" class="form-control" name="employee_ifsc" placeholder="IFSC Code">
																	</div>
																</div>
																	<div class="form-row">
																		<div class="form-group col-md-3">
																		<label>Attachment</label>
																		<input type="file" name="cv" accept=".docx,.pdf,.doc"/>
																		</div>
																	</div>
																	<div class="form-group">																	
																		<div class="row">
																			<div class="col-md-9"></div>
																			<div class="col-md-3 mt-2">
																				<button type="submit" class="btn btn-primary">Submit</button>
																			</div>
																		</div>																	
																	</div>																
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

						<!--Edit Modal  -->
						<div class="modal" id="ModalEditEmployee" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Employee Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true" class="text-white">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12">
												<form id="EditEmployee" method="POST" action="#" enctype="multipart/form-data">
													<div  class="smartwizard-arrows-primary wizard wizard-primary">
														<ul>
															<li><a href="#arrows-primary-step-1">Personal Details<br /></a></li>
															<li><a href="#arrows-primary-step-2">Professional Details<br /></a></li>
															<li><a href="#arrows-primary-step-3">Other Details<br/></a></li>
														</ul>
														<div>
															<div id="arrows-primary-step-1" class="">
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>First Name</label>
																		<input type="text" class="form-control" placeholder="First Name" name="employee_first_name">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Last Name</label>
																		<input type="text" class="form-control" placeholder="Last Name" name="employee_last_name">
																	</div>																
																	<div class="form-group col-md-4">
																		<label>Nick Name</label>
																		<input type="text" class="form-control" placeholder="Nick Name" name="employee_nick_name">
																	</div>	
																</div>									
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Email</label>
																		<input type="email" class="form-control" placeholder="Email ID" name="employee_email">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Mobile</label>
																		<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="employee_mobile">
																	</div>
																	
																	
																	<div class="form-group col-md-4">
																	<label>Outlet</label>
																	<select name="employee_business_outlet" class="form-control">
																		<option value="" selected>Select Outlet</option>
																		<?php
																			foreach ($business_outlet_details as $outlet):
																		?>
																		<option value="<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></option>
																		<?php		
																			endforeach;
																		?>
																	</select>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Expertise</label>
																		<input type="text" class="form-control" name="employee_expertise" placeholder="Expertise">
																	</div>
																	<div class="form-group col-md-4">
																		<label>Role</label>
																	<select name="employee_role" class="form-control">
																			<option value="" selected>Select Role</option>
																			<option value="Cashier">Cashier</option>
																			<option value="Expert">Expert</option>	
																		</select>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-4">
																		<label>Date of Joining</label>
																		<input type="text" class="form-control" name="employee_date_of_joining" placeholder="Date Of Joining">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-12">
																		<label>Address</label>
																		<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="employee_address">
																	</div>
																</div>
															</div>
															<div id="arrows-primary-step-2" class="">
																<div class="form-row">
																	<div class="form-group col-md-2">
																		<label>Salary P/M</label>
																		<input type="number" class="form-control" placeholder="Gross Salary" name="employee_gross_salary" readonly>
																	</div>
																	
																	<div class="form-group col-md-2">
																		<label>Basic</label>
																		<input type="number" class="form-control" placeholder="Basic Salary" name="employee_basic_salary" min="0">
																	</div>																
																	<div class="form-group col-md-1">
																		<label>PF</label>
																		<input type="number" class="form-control" placeholder="PF" name="employee_pf" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Gratuity</label>
																		<input type="number" class="form-control" placeholder="Gratuity" name="employee_gratuity" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Others</label>
																		<input type="number" class="form-control" placeholder="Others" name="employee_others" min="0">
																	</div>
																	<div class="form-group col-md-1">
																		<label>PT</label>
																		<input type="number" class="form-control" placeholder="PT" name="employee_pt" min="0">
																	</div>
																	<div class="form-group col-md-2">
																		<label>Income Tax</label>
																		<input type="number" class="form-control" placeholder="Income Tax" name="employee_it" min="0">
																	</div>
																</div>									
																<div class="form-row">
																	<div class="form-group col-md-3">
																		<label>Over Time P/H</label>
																		<input type="number" class="form-control" placeholder="Rupees/hour" name="employee_over_time_rate">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Working Hours</label>
																		<input type="number" class="form-control" placeholder="Hour" name="employee_work_hour">
																	</div>
																</div>
																<hr>
																
																<div class="form-row">
																			<table cellpadding="15" id="weekofftable" class="form-group">
																				<thead>&ensp;&ensp;<label>Week-Off</label>&ensp;&ensp;&ensp;&ensp;
																					
																				</thead>
																				<tbody style="float:right;text-align:center">
																					<tr>
																						<td>
																							
																								<select name="year[]" id="year" float="left" class="form-control" style="width:100px;">
																									<option disabled selected>Year</option>
																									<option value="2020">2020</option>
																									<option vlaue="2021">2021</option>
																								</select>
																							
																						</td>
																						<td>	
																							
																								<select name="month_name[]" id="month" class="form-control" style="width:100px;" >
																									<option selected disabled>Month</option>
																									<option value="01">January</option>
																									<option value="02">February</option>
																									<option value="03">March</option>
																									<option value="04">April</option>
																									<option value="05">May</option>
																									<option value="06">June</option>
																									<option value="07">July</option>
																									<option value="08">August</option>
																									<option value="09">September</option>
																									<option value="10">October</option>
																									<option value="11">November</option>
																									<option value="12">December</option>
																								</select>
																							
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]" id="w1" class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]" id="w2" class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]" id="w3" class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]" id="w4" class="form-control date">
																						</td>
																						<td>
																							<input type="text" name="employee_weekoff[]" id="w5" class="form-control date">
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<!-- <button type="button" class="btn btn-success" id="AddRowWeekOff">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
																			<button type="button" class="btn btn-danger" id="DeleteRowWeekOff">Delete <i class="fa fa-trash" aria-hidden="true"></i></button> -->
																</div>
																<div>
																	<span id="notValid" style="color:red"></span>
																</div>
																<div class="form-row">
																	
																	<div class="form-group col-md-3">
																		<label>Aadhar Number</label>
																		<input type="number" class="form-control" name="employee_aadhar_number" placeholder="Aadhar Number" min="0" data-mask="">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Total Experience</label>
																			<input type="number" class="form-control" name="employee_experience">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-12">
																		<div id="Edit_experience">
																			<table id="Edit_experienceTable" class="table table-hover">
																				<tbody>
																				<tr>
																						<td><label id="employer"></label></td>
																					</tr>
																					<tr>
																						<td>
																							<div class="form-group">
																								<label>Employer</label>
																								<input type="text" name="employer[]" class="form-control" >
																							</div>
																						</td>
																						
																						<td>
																							<div class="form-group">
																								<label>From</label>
																								<input type="date" class="form-control" name="from_date[]">
																							</div>
																						</td>
																						<td>
																							<div class="form-group">
																								<label>To</label>
																								<input type="date" class="form-control" name="to_date[]">
																							</div>
																						</td>
																						<!-- <td><button type="button" class="btn btn-success" id="AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button></td>
																						<td><button type="button" class="btn btn-danger" id="DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button></td> -->
																					</tr>
																				</tbody>
																			</table>
																			<button type="button" class="btn btn-success" id="Edit_AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																			<button type="button" class="btn btn-danger" id="Edit_DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																		</div>
																	</div>
																</div>																
															</div>
															<div id="arrows-primary-step-3" class="">
																<div class="form-row">																	
																	<div class="form-group col-md-3">
																	<label>Total Certification</label>
																		<input type="number" name="no_of_certification" class="form-control">
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-12">
																	<div id="certification">
																			<table id="Edit_certificationTable" class="table table-hover">
																				<tbody>
																					<tr>
																						<td><label id="certificates"></label></td>
																					</tr>
																					<tr>
																						<td>
																							<div class="form-group">
																								<label>Certificate Name</label>
																								<input type="text" name="certification_name[]" id="certificate" class="form-control" >
																							</div>
																						</td>
																						
																						<td>
																							<div class="form-group">
																								<label>From</label>
																								<input type="date" class="form-control" name="start_date[]">
																							</div>
																						</td>
																						<td>
																							<div class="form-group">
																								<label>To</label>
																								<input type="date" class="form-control" name="end_date[]">
																							</div>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<button type="button" class="btn btn-success" id="Edit_AddRowCertification">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																			<button type="button" class="btn btn-danger" id="Edit_DeleteRowCertification">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																		</div>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group col-md-3">
																		<label>Account Number</label>
																		<input type="number" class="form-control" name="employee_account_number" placeholder="Account Number">
																	</div>
																	<div class="form-group col-md-3">
																		<label>Holder Name</label>
																		<input type="text" class="form-control" name="employee_account_holder_name" placeholder="Account Holder Name">
																	</div>																
																	<div class="form-group col-md-3">
																		<label>Bank Name</label>
																		<input type="text" class="form-control" name="employee_bank_name" placeholder="Bank Name">
																	</div>
																	<div class="form-group col-md-3">
																		<label>IFSC Code </label>
																		<input type="text" class="form-control" name="employee_ifsc" placeholder="IFSC Code">
																	</div>
																</div>
																<br>
																	<div class="form-row">
																		<div class="form-group col-md-6">
																			<label>Attachment</label>
																			<input type="file" name="cv" id="cv" accept=".docx,.pdf,.doc"/>
																		</div>
																	<!-- <div>
																	<div class="form-row">		 -->
																		<div class="form-group col-md-6" id="dwnfile" hidden>
																			<label id="file_nameu"></label>
																			<a class="btn btn-primary" href="" id="dwnload" download>
																				Download
																			</a>
																		</div>
																	</div>
																	<div class="form-group">																	
																		<div class="row">
																			<div class="col-md-8"></div>
																			<div class="col-md-3 mt-2">
																			<input class="form-control" type="hidden" id="emp_id" name="employee_id" readonly="true">
																				<button type="submit" class="btn btn-primary" style="float:right">Submit</button>
																			</div>
																		</div>																	
																	</div>																
																</div>
														</div>
													</div>	
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">					
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
									<div class="row">
											<div class="col-md-6">
											<h5 class="card-title">Added Employees</h5>
											</div>								
											<div class="col-md-6">
											<button class="btn btn-success float-right" data-toggle="modal" data-target="#ModalAddEmployee"><i class="fas fa-fw fa-plus"></i>Add Expert</button>
											</div>
										</div>
										
									</div>
									<div class="card-body">
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
														<p class="mb-0" id="SuccessModalMessage"></p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										<table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Employee Name</th>
													<th>Type</th>
													<th>Address</th>
													<th>Mobile</th>
													<th>Joining Date</th>
													<th style="width: 15%;">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
													foreach ($business_admin_employees as $employee):
												?>
												<tr>
													<td><?=$employee['employee_first_name']?> <?=$employee['employee_last_name']?></td>
													<td><?=$employee['employee_role']?></td>
													<td><?=$employee['employee_address']?></td>
													<td><?=$employee['employee_mobile']?></td>
													<td><?=$employee['employee_date_of_joining']?></td>
													<td class="table-action">
														<button type="button" class="btn btn-primary employee-edit-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
													<?php
														if($employee['employee_is_active'] == 1){
													?>
														<button type="button" class="btn btn-success employee-deactivate-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="user-x"></i>
														</button>
													<?php
														}
														else{
													?>
														<button type="button" class="btn btn-danger employee-activate-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="user-plus"></i>	
														</button>
													<?php
														}
													?>
													</td>
												</tr>	
												<?php		
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
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script type="text/javascript">
		$("input[name=\"employee_date_of_joining\"]").daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
	      		format: 'YYYY-MM-DD'
			}
		});
		
		$(".date").daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			// autoUpdateInput: false,
			locale: {
	      		format: 'YYYY-MM-DD'
			}
		});

		$(".smartwizard-arrows-primary").smartWizard({
			theme: "arrows",
			showStepURLhash: false
		});

	$("#AddRowService").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#experienceTable tr").length;
      
      rowno = rowno+1;
      
      $("#experienceTable tr:last").after("<tr><td><div class=\"form-group\"><label>Employer</label><input type=\"text\" name=\"employer[]\" class=\"form-control\"></div></td><td><div class=\"form-group\"><label>From</label><input type=\"date\" class=\"form-control\" name=\"from_date[]\" ></div></td><td><div class=\"form-group\"><label>Months</label><input type=\"date\" class=\"form-control\" name=\"to_date[]\" ></select></div></td></tr>");
    });

    $("#DeleteRowService").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#experienceTable tr").length;
      if(rowno > 1){
      	$('#experienceTable tr:last').remove();
    	}
    });


//edit Employee Add row experience
$("#Edit_AddRowService").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Edit_experienceTable tr").length;
      
      rowno = rowno+1;
      
      $("#Edit_experienceTable tr:last").after("<tr><td><div class=\"form-group\"><label>Employer</label><input type=\"text\" name=\"employer[]\" class=\"form-control\"></div></td><td><div class=\"form-group\"><label>From</label><input type=\"date\" class=\"form-control\" name=\"from_date[]\" ></div></td><td><div class=\"form-group\"><label>Months</label><input type=\"date\" class=\"form-control\" name=\"to_date[]\" ></select></div></td></tr>");
    });

    $("#Edit_DeleteRowService").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Edit_experienceTable tr").length;
      if(rowno > 1){
      	$('#Edit_experienceTable tr:last').remove();
    	}
    });
//end
//edit Certificate
$("#Edit_AddRowCertification").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Edit_certificationTable tr").length;
      
      rowno = rowno+1;
      
      $("#Edit_certificationTable tr:last").after("<tr><td><div class=\"form-group\"><label>Certificate Name</label><input type=\"text\" name=\"certification_name[]\" class=\"form-control\"></div></td><td><div class=\"form-group\"><label>From</label><input type=\"date\" class=\"form-control\" name=\"start_date[]\" ></div></td><td><div class=\"form-group\"><label>Months</label><input type=\"date\" class=\"form-control\" name=\"end_date[]\" ></select></div></td></tr>");
    });

    $("#Edit_DeleteRowCertification").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Edit_certificationTable tr").length;
      if(rowno > 1){
      	$('#Edit_certificationTable tr:last').remove();
    	}
    });
//end	
		$("#AddRowCertification").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#certificationTable tr").length;
      
      rowno = rowno+1;
      
      $("#certificationTable tr:last").after("<tr><td><div class=\"form-group\"><label>Certificate Name</label><input type=\"text\" name=\"certification_name[]\" class=\"form-control\"></div></td><td><div class=\"form-group\"><label>From</label><input type=\"date\" class=\"form-control\" name=\"start_date[]\" ></div></td><td><div class=\"form-group\"><label>Months</label><input type=\"date\" class=\"form-control\" name=\"end_date[]\" ></select></div></td></tr>");
    });

    $("#DeleteRowCertification").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#certificationTable tr").length;
      if(rowno > 1){
      	$('#certificationTable tr:last').remove();
    	}
    });

</script>
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
		$(document).on('click','.close',function(){
			setTimeout(function () { location.reload(1); }, 500);
		})
		//Total Salary
		$("#AddEmployee input[name=employee_basic_salary]").on('input',function(){		
			emp_basic_salary = parseInt($("#AddEmployee input[name=employee_basic_salary]").val());
			emp_total_salary = emp_basic_salary;
			$("#AddEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#AddEmployee input[name=employee_pf]").on('input',function(){		
			emp_basic_salary = parseInt($("#AddEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#AddEmployee input[name=employee_pf]").val());
			emp_total_salary = emp_basic_salary+emp_pf;
			$("#AddEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#AddEmployee input[name=employee_gratuity]").on('input',function(){		
			emp_basic_salary = parseInt($("#AddEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#AddEmployee input[name=employee_pf]").val());
			emp_gratuity = parseInt($("#AddEmployee input[name=employee_gratuity]").val());
			emp_total_salary = emp_basic_salary+emp_pf+emp_gratuity;
			$("#AddEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#AddEmployee input[name=employee_others]").on('input',function(){		
			emp_basic_salary = parseInt($("#AddEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#AddEmployee input[name=employee_pf]").val());
			emp_gratuity = parseInt($("#AddEmployee input[name=employee_gratuity]").val());
			emp_others = parseInt($("#AddEmployee input[name=employee_others]").val());
			emp_total_salary = emp_basic_salary+emp_pf+emp_gratuity+emp_others;
			$("#AddEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		//
		//Total Salary EditEmployee
		$("#EditEmployee input[name=employee_basic_salary]").on('input',function(){		
			emp_basic_salary = parseInt($("#EditEmployee input[name=employee_basic_salary]").val());
			emp_total_salary = emp_basic_salary;
			$("#EditEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#EditEmployee input[name=employee_pf]").on('input',function(){		
			emp_basic_salary = parseInt($("#EditEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#EditEmployee input[name=employee_pf]").val());
			emp_total_salary = emp_basic_salary+emp_pf;
			$("#EditEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#EditEmployee input[name=employee_gratuity]").on('input',function(){		
			emp_basic_salary = parseInt($("#EditEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#EditEmployee input[name=employee_pf]").val());
			emp_gratuity = parseInt($("#EditEmployee input[name=employee_gratuity]").val());
			emp_total_salary = emp_basic_salary+emp_pf+emp_gratuity;
			$("#EditEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		$("#EditEmployee input[name=employee_others]").on('input',function(){		
			emp_basic_salary = parseInt($("#EditEmployee input[name=employee_basic_salary]").val());
			emp_pf = parseInt($("#EditEmployee input[name=employee_pf]").val());
			emp_gratuity = parseInt($("#EditEmployee input[name=employee_gratuity]").val());
			emp_others = parseInt($("#EditEmployee input[name=employee_others]").val());
			emp_total_salary = emp_basic_salary+emp_pf+emp_gratuity+emp_others;
			$("#EditEmployee input[name=employee_gross_salary]").val(emp_total_salary);			
		});
		//
		$("form#AddEmployee").submit(function(form) {
   			 form.preventDefault(); 
	  	
				// var formData = $("#AddEmployee").serialize();
				
				var formData = new FormData(this);
				// formData.append('#cv',cv);
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddEmssEmployee",
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
              if(data.success == 'true'){ 
              	$("#ModalAddEmployee").modal('hide');
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
		
		});

		
		$("form#EditEmployee").submit(function(form) {
   			 form.preventDefault();    
			
				var formData = new FormData(this);
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditEmployee",
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
              if(data.success == 'true'){
              	$("#ModalEditEmployee").modal('hide');
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
				toastr["error"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
              }
            },
            error: function(data){
				toastr["error"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								}); 
            }
				});
		});

	$(document).on('click','.employee-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
            employee_id : $(this).attr('employee_id')
      };
      $.getJSON("<?=base_url()?>BusinessAdmin/GetEmployee", parameters)
      .done(function(data, textStatus, jqXHR) { 
				$("#EditEmployee input[name=employee_first_name]").attr('value',data.employee_first_name);
				$("#EditEmployee input[name=employee_last_name]").attr('value',data.employee_last_name);
				$("#EditEmployee input[name=employee_nick_name]").attr('value',data.employee_nick_name);
				$("#EditEmployee input[name=employee_address]").attr('value',data.employee_address);
				$("#EditEmployee input[name=employee_email]").attr('value',data.employee_email);
				$("#EditEmployee input[name=employee_mobile]").attr('value',data.employee_mobile);
				$("#EditEmployee select[name=employee_role]").append('<option selected>'+data.employee_role+'</option>');
				// $("#EditEmployee select[name=employee_role]").attr('option',data.employee_role);
				$("#EditEmployee input[name=employee_expertise]").attr('value',data.employee_expertise);
				$("#EditEmployee input[name=employee_date_of_joining]").attr('value',data.employee_date_of_joining);
				$("#EditEmployee select[name=employee_business_outlet]").val(data.employee_business_outlet);
      			$("#EditEmployee input[name=employee_id]").attr('value',data.employee_id);
				$("#EditEmployee input[name=employee_gross_salary]").attr('value',data.employee_gross_salary);
				$("#EditEmployee input[name=employee_basic_salary]").attr('value',data.employee_basic_salary);
				$("#EditEmployee input[name=employee_pf]").attr('value',data.employee_pf);
				$("#EditEmployee input[name=employee_pt]").attr('value',data.employee_pt);
				$("#EditEmployee input[name=employee_it]").attr('value',data.employee_income_tax);
				$("#EditEmployee input[name=employee_gratuity]").attr('value',data.employee_gratuity);
				$("#EditEmployee input[name=employee_others]").attr('value',data.employee_others);
				$("#EditEmployee input[name=employee_over_time_rate]").attr('value',data.employee_over_time_rate);
				$("#EditEmployee input[name=employee_work_hour]").attr('value',data.working_hours);
				$("#EditEmployee input[name=employee_aadhar_number]").attr('value',data.employee_aadhar_number);
				$("#EditEmployee input[name=employee_experience]").attr('value',data.employee_experience);
				$("#EditEmployee input[name=no_of_certification]").attr('value',data.no_of_certification);
				$("#EditEmployee input[name=employee_account_number]").attr('value',data.employee_account_number);
				$("#EditEmployee input[name=employee_account_holder_name]").attr('value',data.employee_account_holder_name);
				$("#EditEmployee input[name=employee_bank_name]").attr('value',data.employee_bank_name);
				$("#EditEmployee input[name=employee_ifsc]").attr('value',data.employee_ifsc);
					
				if(data.weeksoff == null || data.weeksoff == ''){
					
				}else{
					data.weeksoff=data.weeksoff.replace("[",'');
					data.weeksoff=data.weeksoff.replace("]",'');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					data.weeksoff=data.weeksoff.replace('"','');
					var res=data.weeksoff.split(",");
					// alert(res.length);
					for(var i=0;i<res.length;i++){
						var a=res[i];
						document.getElementById("w"+(i+1)).value= a;
						// alert(a);
					}
					$("#year").val(data.year);
					
					$("#month").val(data.month);
				}
				if(data.file_path==null || data.file_path==''){
					
				}else{
					<?php ?>
					$("#dwnfile").attr('hidden',false);
					// document.getElementById("dwnload").href = data.file_path;
				    // 	alert(data.file_path);
					$("#dwnload").attr("href","<?=base_url()?>/"+data.file_path);
					
					var fname = data.file_path.split("/");
					document.getElementById('file_nameu').innerHTML= fname[1];
					// document.getElementById("dwnload").download = data.file_path;
  					
				}

				
				var certificate=data.certification_name;
				var start_date=data.start_date;
				var end_date=data.end_date;
				// alert(data.certification_name);
				if(certificate=='null' || start_date=='null' || end_date=='null' || certificate=='' || certificate==null || certificate=='[""]' )
				{
					document.getElementById("certificates").innerHTML ="You have not mentioned any certification.";
				}
				else{
					certificate=certificate.replace("[",'');
					certificate=certificate.replace("]",'');
					certificate=certificate.replace('"','');
					certificate=certificate.replace('"','');
					certificate=certificate.replace('"','');
					certificate=certificate.replace('"','');
					
					start_date=start_date.replace("[",'');
					start_date=start_date.replace("]",'');
					start_date=start_date.replace('"','');
					start_date=start_date.replace('"','');
					start_date=start_date.replace('"','');
					start_date=start_date.replace('"','');

					end_date=end_date.replace("[",'');
					end_date=end_date.replace("]",'');
					end_date=end_date.replace('"','');
					end_date=end_date.replace('"','');
					end_date=end_date.replace('"','');
					end_date=end_date.replace('"','');
		
				// alert(certificate);
  				document.getElementById("certificates").innerHTML ="Certificate Name : "+certificate+" from "+start_date+" to "+end_date;
				}

				//employer

				var employer=data.employer;
				var from_date=data.from_date;
				var to_date=data.to_date;
				// alert(employer);
				if(employer=='[""]' || from_date=='[""]' || to_date=='[""]' || employer=='null' || employer=='' || employer==null)
				{
					document.getElementById("employer").innerHTML ="You Have Not Mentioned Any Employer Details.";
				}
				else{
					employer=employer.replace("[",'');
					employer=employer.replace("]",'');
					employer=employer.replace('"','');
					employer=employer.replace('"','');
					employer=employer.replace('"','');
					employer=employer.replace('"','');
					
					from_date=from_date.replace("[",'');
					from_date=from_date.replace("]",'');
					from_date=from_date.replace('"','');
					from_date=from_date.replace('"','');
					from_date=from_date.replace('"','');
					from_date=from_date.replace('"','');

					to_date=to_date.replace("[",'');
					to_date=to_date.replace("]",'');
					to_date=to_date.replace('"','');
					to_date=to_date.replace('"','');
					to_date=to_date.replace('"','');
					to_date=to_date.replace('"','');
		
				// alert(certificate);
  				document.getElementById("employer").innerHTML =" Employer : "+employer+" from "+from_date+" to "+to_date;
				}
  				// x.innerHTML = Array.isArray(weekoff);
				// $("#weekoff").text("You have Declared "+data.employee_weekoff[0]+" as Weekly-Off");
				
        $("#ModalEditEmployee").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.employee-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "employee_id" : $(this).attr('employee_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/ChangeEmployeeStatus",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						toastr["error"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
          }
        }
			});
    });
	$(document).on('click','.employee-activate-btn',function(event) {
    // $('.employee-activate-btn').click(function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
	  
      var parameters = {
        "employee_id" : $(this).attr('employee_id'),
        "activate" : 'true',
        "deactivate" : 'false'
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/ChangeEmployeeStatus",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						toastr["success"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
          }
        }
			});
    });

  });
</script>


<!-- limit the check in checkboxes -->
<script type="text/javascript">
$('input[type=checkbox]').change(function(e){
   if ($('input[type=checkbox]:checked').length >3) {
        $(this).prop('checked', false)
        // alert("allowed only 2");
		document.getElementById('notValid').innerHTML="Only Two WeekOff Can be Given"
		return false;	
   }
})

	$("#AddRowWeekOff").click(function(event){
    	event.preventDefault();
      this.blur();
	
      var rowno = $("#weekofftable tr").length;
        // alert(rowno);
      rowno = rowno+1;
      
	  $("#Addweekofftable tr:last").after("<tr><td><select name=\"year[]\"  class=\"form-control\"><option disabled selected>Year</option><option value=\"2020\">2020</option><option vlaue=\"2021\">2021</option></select></td><td><select name=\"month_name[]\"  class=\"form-control\" style=\"width:100px;\" ><option selected disabled>Month</option><option value=\"01\">January</option><option value=\"02\">February</option><option value=\"03\">March</option><option value=\"04\">April</option><option value=\"05\">May</option><option value=\"06\">June</option><option value=\"07\">July</option><option value=\"08\">August</option><option value=\"09\">September</option><option value=\"10\">October</option><option value=\"11\">November</option><option value=\"12\">December</option></select></td><td><input type=\"text\" name=\"employee_weekoff[]\" class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\" class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td></tr>");
    });

    $("#DeleteRowWeekOff").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#weekofftable tr").length;
      if(rowno > 1){
      	$('#weekofftable tr:last').remove();
    	}
    });

	$("#AAddRowWeekOff").click(function(event){
    	event.preventDefault();
      this.blur();
	
      var rowno = $("#weekofftable tr").length;
        // alert(rowno);
      rowno = rowno+1;
      
      $("#Addweekofftable tr:last").after("<tr><td><select name=\"year[]\"  class=\"form-control\"><option disabled selected>Year</option><option value=\"2020\">2020</option><option vlaue=\"2021\">2021</option></select></td><td><select name=\"month_name[]\"  class=\"form-control\" style=\"width:100px;\" ><option selected disabled>Month</option><option value=\"01\">January</option><option value=\"02\">February</option><option value=\"03\">March</option><option value=\"04\">April</option><option value=\"05\">May</option><option value=\"06\">June</option><option value=\"07\">July</option><option value=\"08\">August</option><option value=\"09\">September</option><option value=\"10\">October</option><option value=\"11\">November</option><option value=\"12\">December</option></select></td><td><input type=\"text\" name=\"employee_weekoff[]\" class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\" class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td><td><input type=\"text\" name=\"employee_weekoff[]\"  class=\"form-control date\"></td></tr>");
    });

    $("#ADeleteRowWeekOff").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Aweekofftable tr").length;
      if(rowno > 1){
      	$('#weekofftable tr:last').remove();
    	}
    });

	$("#month").on('change',function(e){
		// alert($(this).val());
    	var parameters = {
    		'month' :  $(this).val(),
			'year' : $('#year').val(),
			'employee_id':$('#emp_id').val()
    	};
		
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetEmployeeweekoff", parameters)
      .done(function(data, textStatus, jqXHR) {
      		// alert(data[0].id);
			  if(data == 0){
				for(var i=0;i<5;i++){
						document.getElementById("w"+(i+1)).value= '';
						// alert(a);
					}
			  }else{	
				  	var weeksoff=data[0].weekoff;
					weeksoff=weeksoff.replace("[",'');
					weeksoff=weeksoff.replace("]",'');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					weeksoff=weeksoff.replace('"','');
					var res=weeksoff.split(",");
					for(var i=0;i<res.length;i++){
						var a=res[i];
						document.getElementById("w"+(i+1)).value= a;
						// alert(a);
					}
					// alert(res.length);
			  }
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>