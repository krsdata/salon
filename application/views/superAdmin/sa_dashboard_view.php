<?php
	$this->load->view('superAdmin/sa_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('superAdmin/sa_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('superAdmin/sa_top_nav_view');
		?>	
		<main class="content">
			<div class="container-fluid p-0">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-3">
									<h5 class="card-title">Admin List</h5>
									</div>	
									<div class="col-md-2">
										<button class="btn btn-success float-right" data-toggle="modal" data-target="#ModalAddMasterAdmin"><i class="fas fa-fw fa-plus"></i>Master Admin</button>
									</div>								
									<div class="col-md-3">
										<button class="btn btn-success float-right" data-toggle="modal" data-target="#ModalAddAdmin"><i class="fas fa-fw fa-plus"></i>Business Admin</button>
									</div>
									 <div class="col-md-2">
										<button class="btn btn-success" data-toggle="modal" data-target="#ModalAddModule"><i class="fas fa-fw fa-plus"></i>Assign Module</button>
									</div>
									<div class="col-md-2">
										<button class="btn btn-primary" data-toggle="modal" data-target="#ModalCustomerData"><i class="fa fa-file"></i> Export Data</button>
									</div> 
								</div>							
							</div>
							<div class="card-body">								
								<table class="table table-hover table-striped datatables-basic" style="width: 100%;">
									<thead>
										<tr>
											<!--<th>Sr. No.</th>-->
											<th>Admin Id</th>
											<th>Admin Name</th>											
											<th>Mobile</th>
											<th>Admin Email</th>
											<th>Firm Name</th>
											<th>Creation Date</th>
											<th>Renewal Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$count=1;
											foreach($admin_list as $list=>$value){
											?>
											<tr>
												<!--<td><?=$count?></td>-->
												<td><?=$value['business_admin_id']?></td>
												<td><?=$value['business_admin_first_name']?> <?=$value['business_admin_last_name']?></td>
												<td><?=$value['business_admin_mobile']?></td>
												<td><?=$value['business_admin_email']?></td>
												<td><?=$value['business_admin_firm_name']?></td>
												<td><?=$value['business_admin_creation_date']?></td>
												<td><?=$value['business_admin_account_expiry_date']?></td>
												<td>
												    <button class="btn btn-success edit_admin" data-toggle="modal" data-target="#ModalEditAdmin" business_admin_id="<?=$value['business_admin_id']?>" ><i class="fa fa-pen"></i></button>
											<a href="AdminOuletDetails?var=<?=$value['business_admin_id']?>" class="btn btn-primary" >Details</a>
											<button class="btn btn-info reset_pass" data-toggle="modal" data-target="#ModalResetAdminPassword" business_admin_id="<?=$value['business_admin_id']?>" >Re-Password</button>
												</td>
											</tr>
											<?php
											$count++;
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Active Admin List</h3>
							</div>
							<div class="card-body">								
							<table class="table table-hover table-striped datatables-basic" style="width: 100%;">
								<thead>
									<tr>
										<th>Sr. No.</th>
										<th>Admin Name</th>											
										<th>Mobile</th>
										<th>Outlet Name</th>
										<th>Total Bill</th>
										<th>Last Active Date</th>
										<th>Creation Date</th>
										<th>Renewal Date</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$count=1;
										foreach($active_admin as $list=>$value){
										?>
										<tr>
											<td><?=$count?></td>
											<td><?=$value['business_admin_first_name']?></td>
											<td><?=$value['business_admin_mobile']?></td>
											<td><?=$value['business_outlet_name']?></td>
											<td><?=$value['total_bill']?></td>
											<td><?=$value['last_txn_date']?></td>
											<td><?=$value['business_admin_creation_date']?></td>
											<td><?=$value['business_admin_account_expiry_date']?></td>
										</tr>
										<?php
										$count++;
										}
									?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Master Admin List</h3>
							</div>
							<div class="card-body">								
							<table class="table table-hover table-striped datatables-basic" style="width: 100%;">
								<thead>
									<tr>
										<th>Sr. No.</th>
										<th>Name</th>											
										<th>Firm Name</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>State</th>
										<th>City</th>
										<th>Creation Date</th>
										<th>Expiry Date</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$count=1;
										foreach($master_admin as $list=>$value){
										?>
										<tr>
											<td><?=$count?></td>
											<td><?=$value['master_admin_name']?></td>											
											<td><?=$value['master_admin_firm_name']?></td>
											<td><?=$value['master_admin_mobile']?></td>
											<td><?=$value['master_admin_email']?></td>
											<td><?=$value['master_admin_state']?></td>
											<td><?=$value['master_admin_city']?></td>
											<td><?=$value['master_admin_creation_date']?></td>
											<td><?=$value['master_admin_account_expiry_date']?></td>
										</tr>
										<?php
										$count++;
										}
									?>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				<!-- modal -->
				<div class="modal" id="ModalAddMasterAdmin" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Create Master Admin</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="AddMasterAdmin" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Name</label>
											<input type="text" class="form-control" placeholder="Name" name="master_admin_name" required>
										</div>
										<div class="form-group col-md-3">
											<label>Firm Name</label>
											<input type="text" class="form-control" placeholder="Firm Name" value="" name="master_admin_firm_name" required>
										</div>									
										<div class="form-group col-md-3">
											<label>Mobile</label>
											<input type="text" class="form-control" placeholder="Mobile Number" name="master_admin_mobile" data-mask="0000000000" minlength="10" maxlength="10" required>
										</div>
										<div class="form-group col-md-3">
											<label>Email</label>
											<input type="email" class="form-control" placeholder="Email" name="master_admin_email"  minlength="10">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Password</label>
											<input type="password" class="form-control" placeholder="Password" name="master_admin_password">
										</div>											
										<div class="form-group col-md-3">
											<label>State</label>
											<select name="master_admin_state" class="form-control">
												<option value="" selected>Select State</option>
												<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
												<option value="Andhra Pradesh">Andhra Pradesh</option>
												<option value="Arunachal Pradesh">Arunachal Pradesh</option>
												<option value="Assam">Assam</option>
												<option value="Bihar">Bihar</option>
												<option value="Chandigarh">Chandigarh</option>
												<option value="Chhattisgarh">Chhattisgarh</option>
												<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
												<option value="Daman and Diu">Daman and Diu</option>
												<option value="Delhi">Delhi</option>
												<option value="Goa">Goa</option>
												<option value="Gujarat">Gujarat</option>
												<option value="Haryana">Haryana</option>
												<option value="Himachal Pradesh">Himachal Pradesh</option>
												<option value="Jammu and Kashmir">Jammu and Kashmir</option>
												<option value="Jharkhand">Jharkhand</option>
												<option value="Karnataka">Karnataka</option>
												<option value="Kerala">Kerala</option>
												<option value="Lakshadweep">Lakshadweep</option>
												<option value="Madhya Pradesh">Madhya Pradesh</option>
												<option value="Maharashtra">Maharashtra</option>
												<option value="Manipur">Manipur</option>
												<option value="Meghalaya">Meghalaya</option>
												<option value="Mizoram">Mizoram</option>
												<option value="Nagaland">Nagaland</option>
												<option value="Orissa">Orissa</option>
												<option value="Pondicherry">Pondicherry</option>
												<option value="Punjab">Punjab</option>
												<option value="Rajasthan">Rajasthan</option>
												<option value="Sikkim">Sikkim</option>
												<option value="Tamil Nadu">Tamil Nadu</option>
												<option value="Tripura">Tripura</option>
												<option value="Uttaranchal">Uttaranchal</option>
												<option value="Uttar Pradesh">Uttar Pradesh</option>
												<option value="West Bengal">West Bengal</option>
											</select>
										</div>										
										<div class="form-group col-md-3">
											<label>City</label>
											<input type="text" class="form-control" placeholder="City" name="master_admin_city">
										</div>
										<div class="form-group col-md-3">
											<label>Expiry Date</label>
											<input type="date" class="form-control" name="master_admin_account_expiry_date" min="<?= date('Y-m-d')?>">
										</div>										
									</div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
				<div class="modal" id="ModalAddAdmin" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Create Admin</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="AddAdmin" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>First Name</label>
											<input type="text" class="form-control" placeholder="First Name" name="business_admin_first_name">
										</div>
										<div class="form-group col-md-4">
											<label>Last Name</label>
											<input type="text" class="form-control" placeholder="Last Name" value="" name="business_admin_last_name">
										</div>
									
										<div class="form-group col-md-4">
											<label>Mobile</label>
											<input type="text" class="form-control" placeholder="Mobile Number" name="business_admin_mobile" data-mask="0000000000" minlength="10" maxlength="10">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>Address</label>
											<input type="text" class="form-control" placeholder="Address" name="business_admin_address">
										</div>									
										
										<div class="form-group col-md-4">
											<label>Firm Name</label>
											<input type="text" class="form-control" placeholder="Firm Name" name="business_admin_firm_name">
										</div>
										<div class="form-group col-md-4">
											<label>Expiry Date</label>
											<input type="date" class="form-control" name="business_admin_account_expiry_date">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>City</label>
											<input type="text" class="form-control" placeholder="City" name="business_admin_city">
										</div>
										<div class="form-group col-md-4">
											<label>State</label>
											<select name="business_admin_state" class="form-control">
												<option value="" selected>Select State</option>
												<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
												<option value="Andhra Pradesh">Andhra Pradesh</option>
												<option value="Arunachal Pradesh">Arunachal Pradesh</option>
												<option value="Assam">Assam</option>
												<option value="Bihar">Bihar</option>
												<option value="Chandigarh">Chandigarh</option>
												<option value="Chhattisgarh">Chhattisgarh</option>
												<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
												<option value="Daman and Diu">Daman and Diu</option>
												<option value="Delhi">Delhi</option>
												<option value="Goa">Goa</option>
												<option value="Gujarat">Gujarat</option>
												<option value="Haryana">Haryana</option>
												<option value="Himachal Pradesh">Himachal Pradesh</option>
												<option value="Jammu and Kashmir">Jammu and Kashmir</option>
												<option value="Jharkhand">Jharkhand</option>
												<option value="Karnataka">Karnataka</option>
												<option value="Kerala">Kerala</option>
												<option value="Lakshadweep">Lakshadweep</option>
												<option value="Madhya Pradesh">Madhya Pradesh</option>
												<option value="Maharashtra">Maharashtra</option>
												<option value="Manipur">Manipur</option>
												<option value="Meghalaya">Meghalaya</option>
												<option value="Mizoram">Mizoram</option>
												<option value="Nagaland">Nagaland</option>
												<option value="Orissa">Orissa</option>
												<option value="Pondicherry">Pondicherry</option>
												<option value="Punjab">Punjab</option>
												<option value="Rajasthan">Rajasthan</option>
												<option value="Sikkim">Sikkim</option>
												<option value="Tamil Nadu">Tamil Nadu</option>
												<option value="Tripura">Tripura</option>
												<option value="Uttaranchal">Uttaranchal</option>
												<option value="Uttar Pradesh">Uttar Pradesh</option>
												<option value="West Bengal">West Bengal</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label>Master Admin</label>
											<select name="master_admin" class="form-control">
												<?php
													foreach($master_admin as $admin){
												?>
													<option value="<?=$admin['master_admin_id']?>"><?=$admin['master_admin_name']?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label>User Name<small>(it must be email type)</small></label>
											<input type="email" class="form-control" placeholder="Email ID" name="business_admin_email">
										</div>
										<div class="form-group col-md-6">
											<label>Password</label>
											<input type="password" class="form-control" placeholder="Enter Password"  name="business_admin_password">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
<div class="modal" id="ModalEditAdmin" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Edit Business Admin</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="EditAdmin" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>First Name</label>
											<input type="text" class="form-control" placeholder="First Name" name="business_admin_first_name" id="business_admin_first_name">
										</div>
										<div class="form-group col-md-4">
											<label>Last Name</label>
											<input type="text" class="form-control" placeholder="Last Name" value="" name="business_admin_last_name" id="business_admin_last_name">
										</div>
									
										<div class="form-group col-md-4">
											<label>Mobile</label>
											<input type="text" class="form-control" placeholder="Mobile Number" name="business_admin_mobile" id="business_admin_mobile" data-mask="0000000000" minlength="10" maxlength="10">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>Address</label>
											<input type="text" class="form-control" placeholder="Address" name="business_admin_address" id="business_admin_address">
										</div>									
										
										<div class="form-group col-md-4">
											<label>Firm Name</label>
											<input type="text" class="form-control" placeholder="Firm Name" name="business_admin_firm_name" id="business_admin_firm_name">
										</div>
										<div class="form-group col-md-4">
											<label>Expiry Date</label>
											<input type="date" class="form-control" name="business_admin_account_expiry_date" id="business_admin_account_expiry_date">
										</div>
									</div>		
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>City</label>
											<input type="text" class="form-control" placeholder="City" name="business_admin_city">
										</div>
										<div class="form-group col-md-4">
											<label>State</label>
											<select name="business_admin_state" class="form-control">
												<option value="" selected>Select State</option>
												<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
												<option value="Andhra Pradesh">Andhra Pradesh</option>
												<option value="Arunachal Pradesh">Arunachal Pradesh</option>
												<option value="Assam">Assam</option>
												<option value="Bihar">Bihar</option>
												<option value="Chandigarh">Chandigarh</option>
												<option value="Chhattisgarh">Chhattisgarh</option>
												<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
												<option value="Daman and Diu">Daman and Diu</option>
												<option value="Delhi">Delhi</option>
												<option value="Goa">Goa</option>
												<option value="Gujarat">Gujarat</option>
												<option value="Haryana">Haryana</option>
												<option value="Himachal Pradesh">Himachal Pradesh</option>
												<option value="Jammu and Kashmir">Jammu and Kashmir</option>
												<option value="Jharkhand">Jharkhand</option>
												<option value="Karnataka">Karnataka</option>
												<option value="Kerala">Kerala</option>
												<option value="Lakshadweep">Lakshadweep</option>
												<option value="Madhya Pradesh">Madhya Pradesh</option>
												<option value="Maharashtra">Maharashtra</option>
												<option value="Manipur">Manipur</option>
												<option value="Meghalaya">Meghalaya</option>
												<option value="Mizoram">Mizoram</option>
												<option value="Nagaland">Nagaland</option>
												<option value="Orissa">Orissa</option>
												<option value="Pondicherry">Pondicherry</option>
												<option value="Punjab">Punjab</option>
												<option value="Rajasthan">Rajasthan</option>
												<option value="Sikkim">Sikkim</option>
												<option value="Tamil Nadu">Tamil Nadu</option>
												<option value="Tripura">Tripura</option>
												<option value="Uttaranchal">Uttaranchal</option>
												<option value="Uttar Pradesh">Uttar Pradesh</option>
												<option value="West Bengal">West Bengal</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label>Master Admin</label>
											<select name="master_admin" class="form-control">
												<?php
													foreach($master_admin as $admin){
												?>
													<option value="<?=$admin['master_admin_id']?>"><?=$admin['master_admin_name']?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>							
									<div class="form-row">
										<div class="form-group col-md-12">
										<input type="hidden" name="business_admin_email" id="business_admin_email">
										<!-- <input type="hidden" name="business_admin_password" id="business_admin_password"> -->
											<input type="hidden" name="business_admin_id" id="business_admin_id"/>
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
				<div class="modal" id="ModalAddOutlet" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Add Outlet</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="AddOutlet" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>Outlet Name</label>
											<input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
										</div>
										<div class="form-group col-md-4">
											<label>Address</label>
											<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
										</div>
										<div class="form-group col-md-4">
											<label>ZipCode</label>
											<input type="number" pattern="[0-9]+" maxlength="6" min="000000" max="999999" class="form-control" name="business_outlet_pincode">
										</div>
									</div>
									<div class="form-row">
									
										<div class="form-group col-md-4">
										
											<label>State</label>
											<select name="business_outlet_state" class="form-control">
												<option value="" selected>Select State</option>
												<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
												<option value="Andhra Pradesh">Andhra Pradesh</option>
												<option value="Arunachal Pradesh">Arunachal Pradesh</option>
												<option value="Assam">Assam</option>
												<option value="Bihar">Bihar</option>
												<option value="Chandigarh">Chandigarh</option>
												<option value="Chhattisgarh">Chhattisgarh</option>
												<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
												<option value="Daman and Diu">Daman and Diu</option>
												<option value="Delhi">Delhi</option>
												<option value="Goa">Goa</option>
												<option value="Gujarat">Gujarat</option>
												<option value="Haryana">Haryana</option>
												<option value="Himachal Pradesh">Himachal Pradesh</option>
												<option value="Jammu and Kashmir">Jammu and Kashmir</option>
												<option value="Jharkhand">Jharkhand</option>
												<option value="Karnataka">Karnataka</option>
												<option value="Kerala">Kerala</option>
												<option value="Lakshadweep">Lakshadweep</option>
												<option value="Madhya Pradesh">Madhya Pradesh</option>
												<option value="Maharashtra">Maharashtra</option>
												<option value="Manipur">Manipur</option>
												<option value="Meghalaya">Meghalaya</option>
												<option value="Mizoram">Mizoram</option>
												<option value="Nagaland">Nagaland</option>
												<option value="Orissa">Orissa</option>
												<option value="Pondicherry">Pondicherry</option>
												<option value="Punjab">Punjab</option>
												<option value="Rajasthan">Rajasthan</option>
												<option value="Sikkim">Sikkim</option>
												<option value="Tamil Nadu">Tamil Nadu</option>
												<option value="Tripura">Tripura</option>
												<option value="Uttaranchal">Uttaranchal</option>
												<option value="Uttar Pradesh">Uttar Pradesh</option>
												<option value="West Bengal">West Bengal</option>
											</select>
										</div>		
										<div class="form-group col-md-4">								
											<label>City</label>
											<input type="text" class="form-control" name="business_outlet_city">
										</div>
										<div class="form-group col-md-4">								
											<label>Select Business Admin</label>
											<select name="business_outlet_business_admin" class="form-control">
												<option selected="selected">Select Business Admin</option>
												<?php 
												foreach($admin_list as $admin){
													?>
													<option value="<?=$admin['business_admin_id']?>"><?=$admin['business_admin_first_name']." ".$admin['business_admin_last_name']?></option>
													<?php
												} ?>

											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
				<div class="modal" id="ModalAddModule" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Assign module To Business Admin</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="AddModule" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-2">								
											<label>Select Business Admin</label>
											<select name="business_outlet_business_admin" class="form-control">
												<option selected="selected">Select Business Admin</option>
												<?php 
												foreach($admin_list as $admin){
													?>
													<option value="<?=$admin['business_admin_id']?>"><?=$admin['business_admin_first_name']." ".$admin['business_admin_last_name']?></option>
													<?php
												} ?>
											</select>
										</div>
										<div class="form-group col-md-1">
											<label>POS</label>
											<input type="checkbox" value="1" name="module[]">
										</div>
										<!--<div class="form-group col-md-2">-->
										<!--	<label>Customer Analytics</label>-->
										<!--	<input type="checkbox" value="2" name="module[]">-->
										<!--</div>-->
										<div class="form-group col-md-2">
											<label>Appointment</label>
											<input type="checkbox" value="3" name="module[]">
										</div>
										<div class="form-group col-md-2">
											<label>Marks360</label>
											<input type="checkbox" value="9" name="module[]">
										</div>
										<div class="form-group col-md-1">
											<label>EMSS</label>
											<input type="checkbox" value="10" name="module[]">
										</div>
										<div class="form-group col-md-1">
											<label>Deals</label>
											<input type="checkbox" value="13" name="module[]">
										</div>
										<div class="form-group col-md-2">
											<label>Cust Engmt</label>
											<input type="checkbox" value="14" name="module[]">
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-12">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
								<p class="mb-0" id="SuccessModalMessage">Master Admin Added Successfully<p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal" id="centeredModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header" style="background-color:#47bac1;">
								<h5 class="modal-title text-white">Edit Details</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body m-3">
								<div class="row">
									<div class="col-md-12">
										<form id="EditOutlet" method="POST" action="#">
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Outlet Name</label>
													<input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
												</div>
												<div class="form-group col-md-4">
													<label>Firm Name</label>
													<input type="text" class="form-control" placeholder="Firm Name" value="<?=$business_admin_details['business_admin_firm_name']?>" name="business_outlet_firm_name">
												</div>
											
												<div class="form-group col-md-4">
													<label>GST IN</label>
													<input type="text" class="form-control" placeholder="GST IN" name="business_outlet_gst_in" minlength="15" maxlength="15">
												</div>
												</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Address</label>
													<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
												</div>
											
												<div class="form-group col-md-4">
													<label>Email</label>
													<input type="email" class="form-control" placeholder="Email ID" name="business_outlet_email">
												</div>
												<div class="form-group col-md-4">
													<label>Mobile</label>
													<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="business_outlet_mobile">
												</div>
											</div>
											<div class="form-row">
											<div class="form-group col-md-4">
												<label>Landline</label>
												<input type="text" class="form-control" placeholder="Landline Number" data-mask="0000000000" name="business_outlet_landline">
											</div>

												<div class="form-group col-md-4">
													<label>State</label>
													<select name="business_outlet_state" class="form-control">
														<option value="" selected>Select State</option>
														<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
														<option value="Andhra Pradesh">Andhra Pradesh</option>
														<option value="Arunachal Pradesh">Arunachal Pradesh</option>
														<option value="Assam">Assam</option>
														<option value="Bihar">Bihar</option>
														<option value="Chandigarh">Chandigarh</option>
														<option value="Chhattisgarh">Chhattisgarh</option>
														<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
														<option value="Daman and Diu">Daman and Diu</option>
														<option value="Delhi">Delhi</option>
														<option value="Goa">Goa</option>
														<option value="Gujarat">Gujarat</option>
														<option value="Haryana">Haryana</option>
														<option value="Himachal Pradesh">Himachal Pradesh</option>
														<option value="Jammu and Kashmir">Jammu and Kashmir</option>
														<option value="Jharkhand">Jharkhand</option>
														<option value="Karnataka">Karnataka</option>
														<option value="Kerala">Kerala</option>
														<option value="Lakshadweep">Lakshadweep</option>
														<option value="Madhya Pradesh">Madhya Pradesh</option>
														<option value="Maharashtra">Maharashtra</option>
														<option value="Manipur">Manipur</option>
														<option value="Meghalaya">Meghalaya</option>
														<option value="Mizoram">Mizoram</option>
														<option value="Nagaland">Nagaland</option>
														<option value="Orissa">Orissa</option>
														<option value="Pondicherry">Pondicherry</option>
														<option value="Punjab">Punjab</option>
														<option value="Rajasthan">Rajasthan</option>
														<option value="Sikkim">Sikkim</option>
														<option value="Tamil Nadu">Tamil Nadu</option>
														<option value="Tripura">Tripura</option>
														<option value="Uttaranchal">Uttaranchal</option>
														<option value="Uttar Pradesh">Uttar Pradesh</option>
														<option value="West Bengal">West Bengal</option>
													</select>
												</div>
												
												<div class="form-group col-md-4">
													<label>City</label>
													<input type="text" class="form-control" name="business_outlet_city">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>ZipCode</label>
													<input type="number" class="form-control" name="business_outlet_pincode">
												</div>
												<div class="form-group col-md-4">
													<label class="form-label">Facebook URL</label>
													<input type="text" class="form-control" name="business_outlet_facebook_url" placeholder="URL">
												</div>
												<div class="form-group col-md-4">
													<label class="form-label">Instagram URL</label>
													<input type="text" class="form-control" name="business_outlet_instagram_url" placeholder="URL">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Bill Header Message</label>
													<textarea class="form-control" rows="2" placeholder="Bill Header Message" name="business_outlet_bill_header_msg"></textarea>
												</div>
												<div class="form-group col-md-6">
													<label>Bill Footer Message</label>
													<textarea class="form-control" rows="2" placeholder="Bill footer Message" name="business_outlet_bill_footer_msg"></textarea>
												</div>
											</div>
											<div class="form-row">
											<div class="form-group col-md-6">
												<label class="form-label">Latitude</label>
												<input type="text" class="form-control" name="business_outlet_latitude" placeholder="Outlet Latitude">
											</div>
											<div class="form-group col-md-6">
												<label class="form-label">Longitude</label>
												<input type="text" class="form-control" name="business_outlet_longitude" placeholder="Outlet Longitude">
											</div>
										</div>
											<div class="form-group">
												<input class="form-control" type="hidden" name="business_outlet_id" readonly="true">
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

				<div class="modal" id="ModalCustomerData" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Download Customer Data</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="admin_data" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-6">								
											<label>Select Business Admin</label>
											<select name="business_outlet_business_admin" class="form-control" id="business_admin">
												<option selected="selected">Select Business Admin</option>
												<?php 
												foreach($admin_list as $admin){
													?>
													<option value="<?=$admin['business_admin_id']?>"><?=$admin['business_admin_first_name']." ".$admin['business_admin_last_name']?></option>
													<?php
												} ?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label>Select Business Outlet</label>
											<select name="business_outlet" class="form-control" id="business_outlet">
												
											</select>
										</div>
										<div class="form-group col-md-12">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
				<div class="modal" id="ModalResetAdminPassword" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-white">Reset Admin Password</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="modal-body">
							<form id="ChangePassword" action="#" method="POST">
									<div class="form-group col-md-12">
										<label>New password</label>
										<input type="password" class="form-control" name="new_password">
									</div>
									<div class="form-group col-md-12">
										<label>Verify password</label>
										<input type="password" class="form-control" name="confirm_new_password">
									</div>
									<div class="form-group col-md-12">
										<input type="hidden" name="business_admin_id" />
										<button type="submit" class="btn btn-primary">Reset Password</button>
									</div>
								
								</form>
								<div class="alert alert-dismissible feedback" role="alert" style="margin:0px;">
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
				<!-- end -->
			</div>	
		</main>
<?php
	$this->load->view('superAdmin/sa_footer_view');
?>
<script>
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

		//Add MasterAdmin
		$("#AddMasterAdmin").validate({
	  	errorElement: "div",
	    rules: {
	        "master_admin_name" : {
            required : true,
            maxlength : 100
	        },
	        "master_admin_firm_name" : {
	          required : true
	        },
	        "master_admin_mobile" : {
	        	required : true,
	        	maxlength : 10
	        },
	        "master_admin_password" : {
	        	required : true,
	        	maxlength : 200
	        },
	        "master_admin_account_expiry_date" : {
	        	required : true,
	        	maxlength : 10
	        },
	        "master_admin_city" : {
						required : true,
	        	maxlength : 255
	        },
	        "master_admin_state":{
						required : true,
	        	maxlength : 255
	        },
	        "master_admin_email" : {
	        	email : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#AddMasterAdmin").serialize();
				$.ajax({
		        url: "<?=base_url()?>SuperAdmin/AddMasterAdmin",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddMasterAdmin").modal('hide');
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
		//

		$("#AddAdmin").validate({
	  	errorElement: "div",
	    rules: {
	        "business_admin_first_name" : {
            required : true,
            maxlength : 100
	        },
	        "business_admin_last_name" : {
	          required : true
	        },
	        "business_admin_mobile" : {
	        	required : true,
	        	maxlength : 10
	        },
	        "business_admin_address" : {
	        	required : true,
	        	maxlength : 200
	        },
	        "business_admin_firm_name" : {
	        	required : true,
	        	maxlength : 100
	        },
	        "business_admin_expiry_date" : {
	        	required : true,
	        	maxlength : 10
	        },
	        "business_admin_city" : {
						required : true,
	        	maxlength : 255
	        },
	        "business_admin_state":{
						required : true,
	        	maxlength : 255
	        },
	        "business_admin_email" : {
	        	email : true
	        },
	        "business_admin_password":{
	        	required : true
	        }     
	    },
	    submitHandler: function(form) {
				var formData = $("#AddAdmin").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>SuperAdmin/AddAdmin",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddAdmin").modal('hide');
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
			//Edit Admin
		$("#EditAdmin").validate({
	  	errorElement: "div",
	    rules: {
	        "business_admin_first_name" : {
            required : true,
            maxlength : 100
	        },
	        "business_admin_last_name" : {
	          required : true
	        },
	        "business_admin_mobile" : {
	        	required : true,
	        	maxlength : 10,
	        },
	        "business_admin_address" : {
	        	required : true,
	        	maxlength : 200,
	        },
	        "business_admin_firm_name" : {
	        	required : true,
	        	maxlength : 100,
	        },
	        "business_admin_expiry_date" : {
	        	required : true,
	        	maxlength : 10,
	        },
	        "business_admin_email" : {
	        	email : true
	        },
	        "business_admin_password":{
	        	required : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#EditAdmin").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>SuperAdmin/EditAdmin",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
								
              	$("#ModalEditAdmin").modal('hide');
								alert(data.message);
								// $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								// 	$("#SuccessModalMessage").html("").html(data.message);
								// }).on('hidden.bs.modal', function (e) {
										window.location.reload();
								// });
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
		//Fill Admin details for Edit
		$(document).on('click','.edit_admin',function(event) {
			event.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			var parameters = {
      business_admin_id: $(this).attr('business_admin_id')
    	};
			// alert($(this).attr('business_admin_id'));
			$.getJSON("<?= base_url() ?>SuperAdmin/GetBusinessAdmin", parameters)
				.done(function(data, textStatus, jqXHR) {

				$("#EditAdmin input[name=business_admin_first_name]").val(data.business_admin_first_name);
				$("#EditAdmin input[name=business_admin_last_name]").val(data.business_admin_last_name);
				$("#EditAdmin input[name=business_admin_id]").val(data.business_admin_id);
				$("#EditAdmin input[name=business_admin_mobile]").val(data.business_admin_mobile);
				$("#EditAdmin input[name=business_admin_address]").val(data.business_admin_address);
				$("#EditAdmin input[name=business_admin_city]").val(data.business_admin_city);
				$("#EditAdmin select[name=business_admin_state]").append('<option value="'+data.business_admin_state+'" selected>'+data.business_admin_state+'</option>');
				$("#EditAdmin input[name=business_admin_firm_name]").val(data.business_admin_firm_name);
				$("#EditAdmin input[name=business_admin_account_expiry_date]").val(data.business_admin_account_expiry_date);
				$("#EditAdmin input[name=business_admin_email]").val(data.business_admin_email);
				$("#EditAdmin select[name=master_admin]").append('<option value="'+data.master_admin_id+'" selected>'+data.master_admin_name+'</option>');
				// $("#EditAdmin input[name=business_admin_email]").val(data.business_admin_email);
				// $("#EditAdmin input[name=business_admin_password]").val($(this).attr('business_admin_password'));
						
				$("#ModalEditAdmin").modal('show');	
			});

		});
	//Add outlet
	$("#AddOutlet").validate({
		errorElement: "div",
		rules: {
				"business_outlet_name" : {
					required : true,
					maxlength : 100
				},
				"business_outlet_address" : {
					required : true
				},
				"business_outlet_pincode" : {
					required : true,
					maxlength : 6,
				},
				"business_outlet_state" : {
					required : true,
					maxlength : 100,
				},
				"business_outlet_city" : {
					required : true,
					maxlength : 100,
				},
				"business_outlet_business_admin" : {
					required : true,
				},
		},
		submitHandler: function(form) {
			var formData = $("#AddOutlet").serialize(); 
			$.ajax({
					url: "<?=base_url()?>SuperAdmin/AddOutlet",
					data: formData,
					type: "POST",
					// crossDomain: true,
					cache: false,
					// dataType : "json",
					success: function(data) {
						if(data.success == 'true'){ 
							$("#ModalAddOutlet").modal('hide');
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

//AssignModule
	$("#AddModule").validate({
		errorElement: "div",
		rules: {
				"business_outlet_business_admin" : {
					required : true,
				}
		},
		submitHandler: function(form) {
			// var md=[];
			// $("input:checkbox[name=module]:checked").each(function(){
			// 		md.push($(this).val());
			// });
			var formData = $("#AddModule").serialize(); 
			$.ajax({
					url: "<?=base_url()?>SuperAdmin/AddModule",
					data: formData,
					type: "POST",
					// crossDomain: true,
					cache: false,
					// dataType : "json",
					success: function(data) {
						if(data.success == 'true'){ 
							$("#ModalAddModule").modal('hide');
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
	//
	$(document).on('click','.reset_pass',function(event) {
			event.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			// alert($(this).attr('business_admin_id'));
			$("#ChangePassword input[name=business_admin_id]").val($(this).attr('business_admin_id'));
			$("#ModalResetAdminPassword").modal('show');	
			});
	//Reset Password
	$("#ChangePassword").validate({
	  	errorElement: "div",
	    rules: {
	        "new_password" : {
            required : true
	        },
	        "confirm_new_password" : {
            required : true
	        },
					"business_admin_id":{
						required :true
					}
	    },
	    submitHandler: function(form) {
				var formData = $("#ChangePassword").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>SuperAdmin/ResetAdminPassword",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
								$("#ModalResetAdminPassword").modal('hide');
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
			},
		});

	//
	$("#business_admin").on('change',function(e){
				var parameters = {
					'business_admin_id' :  $(this).val()
				};

				$.getJSON("<?=base_url()?>SuperAdmin/GetBusinessOutlets", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].business_outlet_id+">"+data[i].business_outlet_name+"</option>";
						}
						$("#business_outlet").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
	//
	function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = '';    
    //Set Report title in first row or line
    
    CSV += ReportTitle + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            
            //Now convert each value to string and comma-seprated
            row += index + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = "MSS_";
    //this will remove the blank-spaces from the title and replace it with an underscore
    fileName += ReportTitle.replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
	}

	//
	$("#admin_data").validate({
		  	errorElement: "div",
		    rules: {
		        "business_outlet_business_admin" : {
	            required : true
		        },
		        "business_outlet" :{
		        	required : true
		        }
		    },
		    submitHandler: function(form) {
					var formData = $("#admin_data").serialize(); 
					$.ajax({
		        url: "<?=base_url()?>SuperAdmin/CustomerData",
		        data: formData,
		        type: "GET",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
	            if(data.success == 'true'){
								$("#ModalCustomerData").modal('hide');
								JSONToCSVConvertor(data.result, "Customer Data", true);
	            }
	            else if (data.success == 'false'){       
	            	$("#ErrorModalMessage").html("").html(data.message);            
	        	    $("#defaultModalDanger").modal('show');
	            }
	          },
	          error: function(data){
	  					$("#ErrorModalMessage").html("").html(data.message);            
	        	  $("#defaultModalDanger").modal('show');
	          }
					});
				},
			});
		});

</script>

