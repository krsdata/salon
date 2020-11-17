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
				<h1 class="h3 mb-3">Package Management</h1>
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
										<h5 class="card-title">Active Packages</h5>
									</div>								
									<div class="col-md-6">
										<button class="btn btn-success float-right" data-toggle="modal" data-target="#ModalAddPackage" style="margin-left:10vw;"><i class="fas fa-fw fa-plus"></i>Create New Package</button>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="tab-content" >
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
										<!--MODAL AREA-->
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
													<div class="modal-footer">
														<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										<div class="modal" id="ModalAddPackage" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title text-white font-weight-bold">Add Package</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span class="text-white" aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<div class="row">
															<div class="col-md-12">
																<form id="AddPackage" method="POST" action="#">
																	<div class="row">
																		<div class="form-group col-md-3">
																			<label>Package Name</label>
																			<input type="text" class="form-control" placeholder="Package Name" name="salon_package_name" autofocus>
																		</div>
																		<div class="form-group col-md-3">
																			<label>Price(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Package Price" name="salon_package_price">
																		</div>
																		<div class="form-group col-md-3">
																			<label>GST</label>
																			<input type="number" class="form-control" placeholder="GST" name="salon_package_gst" step=".1">
																		</div>
																		<div class="form-group col-md-3">
																			<label>Total Amount(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Upfront Amount in Rs." name="salon_package_upfront_amt" readonly>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-6">
																			<label>Validity</label>
																			<input type="number" class="form-control" placeholder="Period in Months" name="salon_package_validity" min="1" max="99">
																		</div>
																		<div class="form-group col-md-6">
																			<label>Package Type</label><br>
																			<select id="packageType" class="form-control" name="salon_package_type" onchange="togglePackage(this.value)" required>
																				<option value="Wallet">Virtual Wallet</option>
																				<option value="Services">Services</option>
																				<option value="Discount">Discount</option>
																				<option value="Service_SubCategory_Bulk">Bulk Service Subcategory</option>
																				<option value="Discount_SubCategory_Bulk">Bulk Discount Subcategory</option>
																				<option value="Service_Category_Bulk">Bulk Service Category</option>
																				<option value="Discount_Category_Bulk">Bulk Discount Category with Price</option>
																				<option value="special_membership">Special Membership</option>
																			</select>
																		</div>
																	</div>
																	<div id="Wallet" class="row">
																		<div class="form-group col-md-6">
																			<label>Virtual Wallet Loading</label>
																			<input type="number" class="form-control" placeholder="Virtual Wallet Absolute Amount(in Rs)" name="virtual_wallet_money_absolute" min="0">&ensp;&ensp;&ensp;
																			<input type="number" class="form-control" placeholder="Virtual Wallet Loading in %" name="virtual_wallet_money_percentage" min="0">
																		</div>		
																	</div>
																	<div id="Services">
																		<table id="serviceTable" class="table table-hover table-bordered">
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
																					<td>
																						<div class="form-group">
																							<label>Price</label>
																							<input type="text" class="form-control" name="service_price_inr[]" temp="service_price_inr" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_service[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowService">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																	<div id="Discount">
																		<table id="discountTable" class="table table-hover table-bordered">
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
																					<td>
																						<div class="form-group">
																							<label>Price</label>
																							<input type="text" class="form-control" name="service_price_inr[]" temp="service_price_inr">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Discount</label>
																							<input type="number" min="0" max="100" class="form-control" name="discount[]" temp="Discount">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_discount[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		
																		<button type="button" class="btn btn-success" id="AddRowDiscount">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowDiscount">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																			
																	</div>
																	<div id="Service_SubCategory_Bulk">
																		<table id="serviceSubCategoryBulkTable" class="table table-hover table-bordered">
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
																							<select class="form-control" name="service_sub_category_bulk[]" temp="Service_SubCategory_Bulk">
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_service_subcategory_bulk[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowServiceSubCategoryBulk">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowServiceSubCategoryBulk">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																	<div id="Discount_SubCategory_Bulk">
																		<table id="discountSubCategoryBulkTable" class="table table-hover table-bordered">
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
																							<select class="form-control" name="service_sub_category_bulk[]" temp="Discount_SubCategory_Bulk">
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Discount</label>
																							<input type="number" min="0" max="100" class="form-control" name="discount_subcategory_bulk[]" temp="Discount">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_discount_subcategory_bulk[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		
																		<button type="button" class="btn btn-success" id="AddRowDiscountSubCategoryBulk">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowDiscountSubCategoryBulk">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>			
																	</div>
																	<!-- bulk category  -->
																	<div id="Service_Category_Bulk">
																		<table id="serviceCategoryBulkTable" class="table table-hover table-bordered">
																			<tbody>
																				<tr>
																					<td>
																						<div class="form-group">
																							<label>Category</label>
																							<select class="form-control" name="service_category_bulk[]">
																								<option value="" selected></option>
																								<?php
																									foreach ($categories as $category) {
																										echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																									}
																								?>
																							</select>
																						</div>
																					</td>
																					<!-- <td>
																						<div class="form-group">
																							<label>Sub-Category</label>
																							<select class="form-control" name="service_sub_category_bulk[]" temp="Service_SubCategory_Bulk">
																							</select>
																						</div>
																					</td> -->
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_service_category_bulk[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowServiceCategoryBulk">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowServiceCategoryBulk">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																	<div id="Discount_Category_Bulk">
																		<table id="discountCategoryBulkTable" class="table table-hover table-bordered">
																			<tbody>
																				<tr>
																					<td>
																						<div class="form-group">
																							<label>Category</label>
																							<select class="form-control" name="discount_service_category_bulk[]">
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
																							<label>Service Price</label>
																							<input type="number" class="form-control" name="service_price_greater_than[]" temp="Discount_Category_Bulk" placeholder="Enter Service Price for Discount">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Discount</label>
																							<input type="number" min="0" max="100" class="form-control" name="discount_category_bulk[]" temp="Discount" placeholder="Discount %">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_discount_category_bulk[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		
																		<button type="button" class="btn btn-success" id="AddRowDiscountCategoryBulk">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowDiscountCategoryBulk">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>			
																	</div>
																	<!--end -->
																	<!-- special membership -->
																	<div id="special_membership">
																		<div class="row">
																			<div class="form-group col-md-4">
																			<label>Monthly Discount</label>
																			<input type="text" name="package_monthly_discount" min="0"  class="form-control" placeholder="Monthly Discount in Rs">
																			</div>
																			<div class="form-group col-md-4">
																				<label>Birthday Discount</label>
																				<input type="number" min="0"  class="form-control" name="birthday_discount"  placeholder="Birthday Discount in Rs">
																			</div>
																			<div class="form-group col-md-4">
																				<label>Annivaersary Discount</label>
																				<input type="number" min="0"  class="form-control" name="anniversary_discount" placeholder="Anniversary Discount in Rs">
																			</div>
																		</div>
																		<table id="specialMembershipTable1" class="table">
																			<tbody>
																				<tr>
																					<td>
																						<div class="form-group">
																							<!-- <label>Cat. Type</label> -->
																							<select class="form-control" name="category_type1[]">
																								<option value="" selected>Category Type</option>
																								<option value="Service">Service</option>
																								<option value="Products">Product</option>
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Min Price</label> -->
																							<input type="text" class="form-control" name="min_price1[]" temp="min_price1" placeholder="Min Price">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Max Price</label> -->
																							<input type="text" class="form-control" min="0" name="max_price1[]"  placeholder="Max Price" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Discount</label> -->
																							<input type="number" min="0" max="100" class="form-control" name="special_discount1[]"  placeholder="Discount % ">
																						</div>
																					</td>
																					
																				</tr>																				
																			</tbody>
																		</table>																		
																		<button type="button" class="btn btn-success mb-1" id="AddRowSpecialMembership1"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership1"><i class="fa fa-trash" aria-hidden="true"></i></button>			
																		<table id="specialMembershipTable2" class="table">
																			<tbody>	
																				<tr>
																					<td>
																						<div class="form-group">
																							<!-- <label>Cat. Type</label> -->
																							<select class="form-control" name="category_type2">
																								<option value="" selected>Category Type</option>
																								<option value="Service">Service</option>
																								<option value="Products">Product</option>
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Category</label> -->
																							<select class="form-control" name="special_category_id2[]" temp="special_category_id2">
																								
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Min Price</label> -->
																							<input type="text" class="form-control" name="min_price2[]" temp="min_price" placeholder="Min Price">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Max Price</label> -->
																							<input type="text" class="form-control" min="0" name="max_price2[]"  placeholder="Max Price" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Discount</label> -->
																							<input type="number" min="0" max="100" class="form-control" name="special_discount2[]"  placeholder="Discount % ">
																						</div>
																					</td>
																					
																				</tr>																			
																			</tbody>
																		</table>																		
																		<button type="button" class="btn btn-success mb-1" id="AddRowSpecialMembership2"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership2"><i class="fa fa-trash" aria-hidden="true"></i></button>
																		<table id="specialMembershipTable3" class="table">
																			<tbody>
																				<tr>
																					<td>
																						<div class="form-group">
																							<!-- <label>Cat. Type</label> -->
																							<select class="form-control" name="category_type3">
																								<option value="" selected>Category Type</option>
																								<option value="Service">Service</option>
																								<option value="Products">Product</option>
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Category</label> -->
																							<select class="form-control" name="special_category_id3">
																								
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Sub-Cat.</label> -->
																							<select class="form-control" name="special_sub_category_id3[]" temp="special_sub_category_id3">
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Min Price</label> -->
																							<input type="text" class="form-control" name="min_price3[]" temp="min_price" placeholder="Min Price">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Max Price</label> -->
																							<input type="text" class="form-control" min="0" name="max_price3[]"  placeholder="Max Price" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Discount</label> -->
																							<input type="number" min="0" max="100" class="form-control" name="special_discount3[]"  placeholder="Discount % ">
																						</div>
																					</td>
																					
																				</tr>																			
																			</tbody>
																		</table>																		
																		<button type="button" class="btn btn-success mb-1" id="AddRowSpecialMembership3"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership3"><i class="fa fa-trash" aria-hidden="true"></i></button>
																		<table id="specialMembershipTable4" class="table">
																			<tbody>
																				<tr>
																					<td>
																						<div class="form-group">
																							<!-- <label>Cat. Type</label> -->
																							<select class="form-control" name="category_type4">
																								<option value="" selected>Category Type</option>
																								<option value="Service">Service</option>
																								<option value="Products">Product</option>
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Category</label> -->
																							<select class="form-control" name="special_category_id4">
																								
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Sub-Cat.</label> -->
																							<select class="form-control" name="special_sub_category_id4">
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Service</label> -->
																							<select class="form-control" name="special_service_id4[]" temp="Service">
																							</select>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Min Price</label> -->
																							<input type="text" class="form-control" name="min_price4[]" temp="min_price" placeholder="Min Price">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Max Price</label> -->
																							<input type="text" class="form-control" min="0" name="max_price4[]"  placeholder="Max Price" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<!-- <label>Discount</label> -->
																							<input type="number" min="0" max="100" class="form-control" name="special_discount4[]"  placeholder="Discount % ">
																						</div>
																					</td>
																					
																				</tr>																				
																			</tbody>
																		</table>																		
																		<button type="button" class="btn btn-success mb-1" id="AddRowSpecialMembership4"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership4"><i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																	<!-- end -->
																	
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
										
										<div class="modal" id="ModalEditPackage" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title text-white font-weight-bold">Edit Package</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span class="text-white" aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<div class="row">
															<div class="col-md-12">
																<form id="EditPackage" method="POST" action="#">
																	<div class="row">
																		<div class="form-group col-md-3">
																			<label>Package Name</label>
																			<input type="text" class="form-control" placeholder="Package Name" name="salon_package_name" id="salon_package_name">
																		</div>
																		<div class="form-group col-md-3">
																			<label>Price(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Package Price" name="salon_package_price">
																		</div>
																		<div class="form-group col-md-3">
																			<label>GST</label>
																			<input type="number" class="form-control" placeholder="GST" name="salon_package_gst">
																		</div>
																		<div class="form-group col-md-3">
																			<label>Total Amount(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Upfront Amount in Rs." name="salon_package_upfront_amt" readonly>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-6">
																			<label>Validity</label>
																			<input type="number" class="form-control" placeholder="Validity in Months" name="salon_package_validity" min="1" max="99" readonly>
																		</div>
																		<div class="form-group col-md-6">
																			<label>Package Type</label><br>
																			<input type="text" class="form-control" name="salon_package_type" readonly>
																		</div>
																	</div>
																	<div id="EditDiscount">
																		<table id="editDiscountTable" class="table table-hover table-bordered">
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
																					<td>
																						<div class="form-group">
																							<label>Price</label>
																							<input type="text" class="form-control" name="service_price_inr[]" temp="service_price_inr">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Discount</label>
																							<input type="number" min="0" max="100" class="form-control" name="discount[]" temp="Discount">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_discount[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		
																		<button type="button" class="btn btn-success" id="AddRowEditDiscount">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowEditDiscount">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																			
																	</div>
																	<input type="hidden" name="salon_package_id">
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

										<div class="modal" id="ModalEditServicePackage" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title text-white font-weight-bold">Edit Package</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span class="text-white" aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<div class="row">
															<div class="col-md-12">
																<form id="EditServicePackage" method="POST" action="#">
																	<div class="row">
																		<div class="form-group col-md-3">
																			<label>Package Name</label>
																			<input type="text" class="form-control" placeholder="Package Name" name="salon_package_name" id="salon_package_name">
																		</div>
																		<div class="form-group col-md-3">
																			<label>Price(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Package Price" name="salon_package_price">
																		</div>
																		<div class="form-group col-md-3">
																			<label>GST</label>
																			<input type="number" class="form-control" placeholder="GST" name="salon_package_gst">
																		</div>
																		<div class="form-group col-md-3">
																			<label>Total Amount(Rs.)</label>
																			<input type="number" class="form-control" placeholder="Upfront Amount in Rs." name="salon_package_upfront_amt" readonly>
																		</div>
																	</div>
																	<div class="row">
																		<div class="form-group col-md-6">
																			<label>Validity</label>
																			<input type="number" class="form-control" placeholder="Validity in Months" name="salon_package_validity" min="1" max="99" readonly>
																		</div>
																		<div class="form-group col-md-6">
																			<label>Package Type</label><br>
																			<input type="text" class="form-control" name="salon_package_type" readonly>
																		</div>
																	</div>
																	<div id="EditServices">
																		<table id="editServiceTable" class="table table-hover table-bordered">
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
																					<td>
																						<div class="form-group">
																							<label>Price</label>
																							<input type="text" class="form-control" name="service_price_inr[]" temp="service_price_inr" >
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Count</label>
																							<input type="number" class="form-control" name="count_service[]" temp="Count" value="1" min="1" max="25">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowEditService">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowEditService">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>
																	<input type="hidden" name="salon_package_id">
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

										<!--MODAL AREA END-->		
										<table class="table table-striped datatables-basic" style="width:100%">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Package Name</th>
													<th>Package Type</th>
													<th>Creation Date</th>
													<!--<th>Current Status</th>-->
													<th>Gross Price</th>
													<th>GST(Rs.)</th>
													<th>MRP</th>
													<th>Validity</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
											<?php
												$count = 0; 
												foreach($packages as $package):
												$count = $count + 1;
											?>
											<tr>
												<td><?=$count?></td>
												<td><?=$package['salon_package_name']?></td>
												<td><?=$package['salon_package_type']?></td>
												<td><?=$package['salon_package_date']?></td>
												<td><?=$package['salon_package_price']?></td>
												<td><?=$package['service_gst_percentage']*$package['salon_package_price']/100?></td>
												<td><?=($package['salon_package_price']+($package['service_gst_percentage']*$package['salon_package_price']/100))?></td>
												<td><?=$package['salon_package_validity']?></td>
												<td class="table-action">
												<?php
													if($package['is_active'] == 1){
												?>
													<button type="button" class="btn btn-info package-edit-btn"  salon_package_id="<?=$package['salon_package_id']?>">
														<i class="align-middle" data-feather="edit"></i>
													</button>
													<button type="button" class="btn btn-success package-deactivate-btn" salon_package_id="<?=$package['salon_package_id']?>">
														<i class="align-middle" data-feather="package"></i>
													</button>
												<?php
													}
													else{
												?>
													<button type="button" class="btn btn-info disabled" salon_package_id="<?=$package['salon_package_id']?>">
														<i class="align-middle" data-feather="edit"></i>
													</button>
													<button type="button" class="btn btn-danger package-activate-btn" salon_package_id="<?=$package['salon_package_id']?>">
														<i class="align-middle" data-feather="package"></i>
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
	function togglePackage(){
		var selectedElement=$('#packageType option:selected').val();
		

		$("#Services").hide();
		$("#Discount").hide();
		$("#Wallet").hide();

		$("#Service_SubCategory_Bulk").hide();
		$("#Discount_SubCategory_Bulk").hide();
		$("#Service_Category_Bulk").hide();
		$("#Discount_Category_Bulk").hide();
		$("#special_membership").hide();
		
		if(selectedElement=="Wallet"){	
			$("#Wallet").show();
		}

		if(selectedElement=="Services"){	
			$("#Services").show();
		}

		if(selectedElement=="Discount"){
			$("#Discount").show();
		}

		if(selectedElement == "Service_SubCategory_Bulk"){
			$("#Service_SubCategory_Bulk").show();
		}

		if(selectedElement == "Discount_SubCategory_Bulk"){
			$("#Discount_SubCategory_Bulk").show();
		}
		if(selectedElement == "Discount_Category_Bulk"){
			$("#Discount_Category_Bulk").show();
		}
		if(selectedElement == "Service_Category_Bulk"){
			$("#Service_Category_Bulk").show();
		}
		if(selectedElement == "special_membership"){
			$("#special_membership").show();
		}
	}
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

		$("#Wallet").show();
		$("#Services").hide();
		$("#Discount").hide();
		$("#Service_SubCategory_Bulk").hide();
		$("#Discount_SubCategory_Bulk").hide();
		$("#Service_Category_Bulk").hide();
		$("#Discount_Category_Bulk").hide();
		$("#special_membership").hide();

	//
		package_base_price=0;
		gst=0;
		package_total_value=0;
		$("#AddPackage input[name=salon_package_gst]").on('input',function(){

			package_base_price =parseFloat($("#AddPackage input[name=salon_package_price]").val());
			gst = parseFloat($("#AddPackage input[name=salon_package_gst]").val());
			package_total_value= package_base_price;
			package_total_value = Math.round(package_base_price+(package_base_price*gst/100));
			$("#AddPackage input[name=salon_package_upfront_amt]").val(package_total_value);
			// if(parseInt($(this).val()) < service_total_value){
				
			// 	$("#AddPackage input[name=service_price_inr]").val(base_price);
			// 	$("#AddPackage input[name=service_total_value]").val(service_total_value);
			// 	service_gst_percentage=$("#AddPackage input[name=service_gst_percentage]").val();
			// 	$("#AddPackage input[name=service_discount_percentage]").val(0);
			// }
			// else if(parseInt($(this).val()) >= service_total_value){
			// 	alert("Please enter less value than overall value!");
			// 	$("#AddPackage input[name=service_discount_absolute]").val(parseInt(0));
			// 	$("#AddPackage input[name=service_total_value]").val(base_price*qty);
			// }	
			// else{
			// 	$("#AddPackage input[name=service_discount_absolute]").val(parseInt(0));
			// 	$("#AddPackage input[name=service_total_value]").val(base_price*qty);
			// }
			});


			$("#AddPackage input[name=virtual_wallet_money_absolute]").on('input',function(){
				if(parseInt($(this).val()) >0){
					$("#AddPackage input[name=virtual_wallet_money_percentage]").val(0);
				}
			});
			$("#AddPackage input[name=virtual_wallet_money_percentage]").on('input',function(){
				if(parseInt($(this).val()) >0){
					$("#AddPackage input[name=virtual_wallet_money_absolute]").val(0);
				}
			});
	//
			$("#AddPackage").validate({
				errorElement: "div",
				rules: {
					"salon_package_name" : {
						required : true
					},
					"salon_package_price" : {
						required : true
					},
					"salon_package_gst" : {
						required : true
					},
					"salon_package_type" :{
						required : true,
						maxlength : 50
					},
					"salon_package_upfront_amt" : {
						required : true
					},
					"salon_package_validity" : {
						required : true
					},
					"virtual_wallet_money_absolute" : {
						digits : true		
					},
					"virtual_wallet_money_percentage" : {
						digits : true
					}
				},
				submitHandler: function(form) {
					var formData = $("#AddPackage").serialize(); 
					// alert("Ashok");
					$.ajax({
						url: "<?=base_url()?>BusinessAdmin/BusinessAdminAddPackage",
						data: formData,
						type: "POST",
						// crossDomain: true,
						cache: false,
						// dataType : "json",
						success: function(data) {
							if(data.success == 'true'){ 
								$("#ModalAddPackage").modal('hide');
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
			// Special Membership
			// table1
			$("#AddRowSpecialMembership1").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable1 tr").length;
				
				rowno = rowno+1;
				
				$("#specialMembershipTable1 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type1[]\"><option value=\"service\" selected>Service</option><option value=\"otc\">Products</option></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price1[]\" placeholder=\"Min price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price1[]\" placeholder=\"Max price\"></div></td><td><div class=\"form-group\"><input type=\"number\" min=\"0\" placeholder=\"Discount % \" class=\"form-control\" name=\"special_discount1[]\"></div></td></td></tr>");
			});

			$("#DeleteRowSpecialMembership1").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable1 tr").length;
				if(rowno > 1){
					$('#specialMembershipTable1 tr:last').remove();
				}
			});
			// table2
			$(document).on('change',"#specialMembershipTable2 tr:last select[name=category_type2]", function(e){
				var parameters = {
					'category_type' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetCategoriesByCategoryType", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].category_id+">"+data[i].category_name+"</option>";
						}
						$("#specialMembershipTable2 tr:last select[temp=special_category_id2]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			$("#AddRowSpecialMembership2").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable2 tr").length;
				
				rowno = rowno+1;
				
				$("#specialMembershipTable2 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type2\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id2[]\" temp=\"special_category_id2\"> </select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price2[]\" placeholder=\"Min price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price2[]\" placeholder=\"Max price\"></div></td><td><div class=\"form-group\"><input type=\"number\" min=\"0\" placeholder=\"Discount %\" class=\"form-control\" name=\"special_discount2[]\"></div></td></td></tr>");
			});

			$("#DeleteRowSpecialMembership2").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable2 tr").length;
				if(rowno > 1){
					$('#specialMembershipTable2 tr:last').remove();
				}
			});

			// table3
			$(document).on('change',"#specialMembershipTable3 tr:last select[name=category_type3]", function(e){
				var parameters = {
					'category_type' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetCategoriesByCategoryType", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].category_id+">"+data[i].category_name+"</option>";
						}
						$("#specialMembershipTable3 tr:last select[name=special_category_id3]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#specialMembershipTable3 tr:last select[name=special_category_id3]", function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
					.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#specialMembershipTable3 tr:last select[temp=special_sub_category_id3]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$("#AddRowSpecialMembership3").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable3 tr").length;
				
				rowno = rowno+1;
				
				$("#specialMembershipTable3 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type3\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id3\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_sub_category_id3[]\" temp=\"special_sub_category_id3\"></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price3[]\" placeholder=\"Min price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price3[]\" placeholder=\"Max price\"></div></td><td><div class=\"form-group\"><input type=\"number\" min=\"0\" placeholder=\"Discount %\" class=\"form-control\" name=\"special_discount3[]\"></div></td></td></tr>");
			});

			$("#DeleteRowSpecialMembership3").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable3 tr").length;
				if(rowno > 1){
					$('#specialMembershipTable3 tr:last').remove();
				}
			});
			
			// table4
			$(document).on('change',"#specialMembershipTable4 tr:last select[name=category_type4]", function(e){
				var parameters = {
					'category_type' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetCategoriesByCategoryType", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].category_id+">"+data[i].category_name+"</option>";
						}
						$("#specialMembershipTable4 tr:last select[name=special_category_id4]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#specialMembershipTable4 tr:last select[name=special_category_id4]", function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#specialMembershipTable4 tr:last select[name=special_sub_category_id4]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#specialMembershipTable4 tr:last select[name=special_sub_category_id4]", function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
						$("#specialMembershipTable4 tr:last select[temp=Service]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$("#AddRowSpecialMembership4").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable4 tr").length;
				
				rowno = rowno+1;
				
				$("#specialMembershipTable4 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type4\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id4\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_sub_category_id4\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_service_id4[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price4[]\" placeholder=\"Mini price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price4[]\" placeholder=\"Max price\"></div></td><td><div class=\"form-group\"><input type=\"number\" min=\"0\" placeholder=\"Discount %\" class=\"form-control\" name=\"special_discount4[]\"></div></td></td></tr>");
			});

			$("#DeleteRowSpecialMembership4").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable4 tr").length;
				if(rowno > 1){
					$('#specialMembershipTable4 tr:last').remove();
				}
			});
			 
			// end special membership

			$(document).on('change',"#serviceSubCategoryBulkTable tr:last select[name=service_category_id]", function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#serviceSubCategoryBulkTable tr:last select[temp=Service_SubCategory_Bulk]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			 
			
			$(document).on('change',"#discountSubCategoryBulkTable tr:last select[name=service_category_id]",function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#discountSubCategoryBulkTable tr:last select[temp=Discount_SubCategory_Bulk]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			
			$("#AddRowServiceSubCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceSubCategoryBulkTable tr").length;
				
				rowno = rowno+1;
				
				$("#serviceSubCategoryBulkTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_bulk[]\" temp=\"Service_SubCategory_Bulk\"></select></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_service_subcategory_bulk[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></select></div></td></tr>");
			});
			// Add Row in Service CAtegory bulk
			$("#AddRowServiceCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceCategoryBulkTable tr").length;
				
				rowno = rowno+1;
				
				$("#serviceCategoryBulkTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_service_subcategory_bulk[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></select></div></td></tr>");
			});


			$("#DeleteRowServiceCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceCategoryBulkTable tr").length;
				if(rowno > 1){
					$('#serviceCategoryBulkTable tr:last').remove();
				}
			});

			$("#AddRowDiscountCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountCategoryBulkTable tr").length;
				
				rowno = rowno+1;
				
				$("#discountCategoryBulkTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Service Price</label><input type=\"number\" class=\"form-control\" name=\"service_price_greater_than[]\" temp=\"Discount_Category_Bulk\"></div></td><td><div class=\"form-group\"><label>Discount</label><input type=\"number\" min=\"0\" max=\"100\" class=\"form-control\" name=\"discount_category_bulk[]\" temp=\"Discount\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_discount_category_bulk[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></div></td></tr>");
			});

			$("#DeleteRowDiscountCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountCategoryBulkTable tr").length;
				if(rowno > 1){
					$('#discountCategoryBulkTable tr:last').remove();
				}
			});

			$("#DeleteRowServiceSubCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceSubCategoryBulkTable tr").length;
				if(rowno > 1){
					$('#serviceSubCategoryBulkTable tr:last').remove();
				}
			});
			
			
			$("#AddRowDiscountSubCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountSubCategoryBulkTable tr").length;
				
				rowno = rowno+1;
				
				$("#discountSubCategoryBulkTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_bulk[]\" temp=\"Discount_SubCategory_Bulk\"></select></div></td><td><div class=\"form-group\"><label>Discount</label><input type=\"number\" min=\"0\" max=\"100\" class=\"form-control\" name=\"discount_subcategory_bulk[]\" temp=\"Discount\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_discount_subcategory_bulk[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></div></td></tr>");
			});

			$("#DeleteRowDiscountSubCategoryBulk").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountSubCategoryBulkTable tr").length;
				if(rowno > 1){
					$('#discountSubCategoryBulkTable tr:last').remove();
				}
			});
			

			$(document).on('change',"#serviceTable tr:last select[name=service_category_id]",function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
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
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
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
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicePriceById", parameters)
				.done(function(data, textStatus, jqXHR) {			
						$("#serviceTable tr:last input[temp=service_price_inr]").val(data[0].service_price_inr);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			//service price for discount package
			$(document).on('change',"#discountTable tr:last select[temp=Service]",function(e){
				var parameters = {
					'service_id' :  $(this).val()
				};
				// alert($(this).val());
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicePriceById", parameters)
				.done(function(data, textStatus, jqXHR) {			
						$("#discountTable tr:last input[temp=service_price_inr]").val(data[0].service_price_inr);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});


			$(document).on('change',"#discountTable tr:last select[name=service_category_id]",function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#discountTable tr:last select[name=service_sub_category_id]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#discountTable tr:last select[name=service_sub_category_id]",function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
						$("#discountTable tr:last select[temp=Service]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$("#AddRowService").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceTable tr").length;
				
				rowno = rowno+1;
				
				$("#serviceTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_id\"></select></div></td><td><div class=\"form-group\"><label>Service</label><select class=\"form-control\" name=\"service_id[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\" ><label>Price</label><input type=\"text\" class=\"form-control\" name=\"service_price_inr\" temp=\"service_price_inr\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_service[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></select></div></td></tr>");
			});

			$("#DeleteRowService").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#serviceTable tr").length;
				if(rowno > 1){
					$('#serviceTable tr:last').remove();
				}
			});

			$("#AddRowDiscount").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountTable tr").length;
				
				rowno = rowno+1;
				
				$("#discountTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_id\"></select></div></td><td><div class=\"form-group\"><label>Service</label><select class=\"form-control\" name=\"service_id[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\" ><label>Price</label><input type=\"text\" class=\"form-control\" name=\"service_price_inr\" temp=\"service_price_inr\"></div></td><td><div class=\"form-group\"><label>Discount</label><input type=\"number\" min=\"0\" max=\"100\" class=\"form-control\" name=\"discount[]\" temp=\"Discount\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_discount[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></div></td></tr>");
			});

			$("#DeleteRowDiscount").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#discountTable tr").length;
				if(rowno > 1){
					$('#discountTable tr:last').remove();
				}
			});

			$(document).on('click','.package-deactivate-btn',function(event) {
				event.preventDefault();
				this.blur(); // Manually remove focus from clicked link.
				var parameters = {
					"salon_package_id" : $(this).attr('salon_package_id'),
					"activate" : 'false',
					"deactivate" : 'true'
				};
				$.ajax({
					url: "<?=base_url()?>BusinessAdmin/ChangePackageStatus",
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

			$(document).on('click','.package-activate-btn',function(event) {
				event.preventDefault();
				this.blur(); // Manually remove focus from clicked link.
				var parameters = {
					"salon_package_id" : $(this).attr('salon_package_id'),
					"activate" : 'true',
					"deactivate" : 'false'
				};
				
				$.ajax({
					url: "<?=base_url()?>BusinessAdmin/ChangePackageStatus",
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

			$(document).on('click',".package-edit-btn", function(e){
				var parameters = {
					'salon_package_id' :  $(this).attr('salon_package_id')
				};
				
				$.getJSON("<?=base_url()?>BusinessAdmin/GetPackage", parameters)
				.done(function(data, textStatus, jqXHR) {
					if(data.salon_package_type=="Services"){
						$("#ModalEditServicePackage input[name=salon_package_name]").attr('value',data.salon_package_name);
        		$("#ModalEditServicePackage input[name=salon_package_price]").val(data.salon_package_price);
        		$("#ModalEditServicePackage input[name=salon_package_gst]").attr('value',data.service_gst_percentage);
						$("#ModalEditServicePackage input[name=salon_package_upfront_amt]").attr('value',data.salon_package_upfront_amt);
						$("#ModalEditServicePackage input[name=salon_package_validity]").attr('value',data.salon_package_validity);
						$("#ModalEditServicePackage input[name=salon_package_type]").attr('value',data.salon_package_type);
						$("#ModalEditServicePackage input[name=salon_package_id]").attr('value',data.salon_package_id);
						$("#ModalEditServicePackage").modal('show');
					}else if(data.salon_package_type=="Discount"){
						$("#ModalEditPackage input[name=salon_package_name]").attr('value',data.salon_package_name);
        		$("#ModalEditPackage input[name=salon_package_price]").val(data.salon_package_price);
        		$("#ModalEditPackage input[name=salon_package_gst]").attr('value',data.service_gst_percentage);
						$("#ModalEditPackage input[name=salon_package_upfront_amt]").attr('value',data.salon_package_upfront_amt);
						$("#ModalEditPackage input[name=salon_package_validity]").attr('value',data.salon_package_validity);
						$("#ModalEditPackage input[name=salon_package_type]").attr('value',data.salon_package_type);
						$("#ModalEditPackage input[name=salon_package_id]").attr('value',data.salon_package_id);
						$("#ModalEditPackage").modal('show');
					}else{
						alert(data.salon_package_type +" type package can not be edited. ")
						return false;
					}        		
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			//Edit ServiceTypePackage
			$("#EditServicePackage input[name=salon_package_gst]").on('input',function(){
				var package_base_price =parseFloat($("#EditServicePackage input[name=salon_package_price]").val());
				var gst = parseFloat($("#EditServicePackage input[name=salon_package_gst]").val());
				var package_total_value= package_base_price;
				package_total_value = Math.round(package_base_price+(package_base_price*gst/100));
				$("#EditServicePackage input[name=salon_package_upfront_amt]").val(package_total_value);
			});
			$(document).on('change',"#editServiceTable tr:last select[name=service_category_id]",function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#editServiceTable tr:last select[name=service_sub_category_id]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#editServiceTable tr:last select[name=service_sub_category_id]",function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
					
						$("#editServiceTable tr:last select[temp=Service]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});
			$(document).on('change',"#editServiceTable tr:last select[temp=Service]",function(e){
				var parameters = {
					'service_id' :  $(this).val()
				};
				// alert($(this).val());
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicePriceById", parameters)
				.done(function(data, textStatus, jqXHR) {			
						$("#editServiceTable tr:last input[temp=service_price_inr]").val(data[0].service_price_inr);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$("#AddRowEditService").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#editServiceTable tr").length;
				
				rowno = rowno+1;
				
				$("#editServiceTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_id\"></select></div></td><td><div class=\"form-group\"><label>Service</label><select class=\"form-control\" name=\"service_id[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\" ><label>Price</label><input type=\"text\" class=\"form-control\" name=\"service_price_inr\" temp=\"service_price_inr\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_service[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></select></div></td></tr>");
			});

			$("#DeleteRowEditService").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#editServiceTable tr").length;
				if(rowno > 1){
					$('#editServiceTable tr:last').remove();
				}
			});


			//end

			$("#EditPackage input[name=salon_package_gst]").on('input',function(){

			var package_base_price =parseFloat($("#EditPackage input[name=salon_package_price]").val());
			var gst = parseFloat($("#EditPackage input[name=salon_package_gst]").val());
			var package_total_value= package_base_price;
			package_total_value = Math.round(package_base_price+(package_base_price*gst/100));
			$("#EditPackage input[name=salon_package_upfront_amt]").val(package_total_value);
			
			});

				//service price for discount package
				$(document).on('change',"#editDiscountTable tr:last select[temp=Service]",function(e){
				var parameters = {
					'service_id' :  $(this).val()
				};
				// alert($(this).val());
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicePriceById", parameters)
				.done(function(data, textStatus, jqXHR) {			
						$("#editDiscountTable tr:last input[temp=service_price_inr]").val(data[0].service_price_inr);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});


			$(document).on('change',"#editDiscountTable tr:last select[name=service_category_id]",function(e){
				var parameters = {
					'category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
						}
						$("#editDiscountTable tr:last select[name=service_sub_category_id]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$(document).on('change',"#editDiscountTable tr:last select[name=service_sub_category_id]",function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
				.done(function(data, textStatus, jqXHR) {
						var options = "<option value='' selected></option>"; 
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
						$("#editDiscountTable tr:last select[temp=Service]").html("").html(options);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			});

			$("#AddRowEditDiscount").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#editDiscountTable tr").length;
				
				rowno = rowno+1;
				
				$("#editDiscountTable tr:last").after("<tr><td><div class=\"form-group\"><label>Category</label><select class=\"form-control\" name=\"service_category_id\"><option value=\"\" selected></option> <?php foreach ($categories as $category) { echo "<option value=".$category['category_id'].">".$category['category_name']."</option>"; }?></select></div></td><td><div class=\"form-group\"><label>Sub-Category</label><select class=\"form-control\" name=\"service_sub_category_id\"></select></div></td><td><div class=\"form-group\"><label>Service</label><select class=\"form-control\" name=\"service_id[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\" ><label>Price</label><input type=\"text\" class=\"form-control\" name=\"service_price_inr\" temp=\"service_price_inr\"></div></td><td><div class=\"form-group\"><label>Discount</label><input type=\"number\" min=\"0\" max=\"100\" class=\"form-control\" name=\"discount[]\" temp=\"Discount\"></div></td><td><div class=\"form-group\"><label>Count</label><input type=\"number\" class=\"form-control\" name=\"count_discount[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"25\"></div></td></tr>");
			});

			$("#DeleteRowEditDiscount").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#editDiscountTable tr").length;
				if(rowno > 1){
					$('#editDiscountTable tr:last').remove();
				}
			});

		$("#EditPackage").validate({
	  	errorElement: "div",
	    rules: {
	        "salon_package_name" : {
            required : true
	        },
	        "salon_package_price" : {
	          required : true
	        },
	        "salon_package_gst" : {
	        	required : true
	        },
	        "salon_package_upfront_amt" : {
	        	required : true
	        },
	        "salon_package_validity" : {
	        	required : true
	        },
	        "salon_package_type" : {
	        	required : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#EditPackage").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditPackage",
		        data: formData,
		        type: "POST",
						cache: false,
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditPackage").modal('hide');
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

		$("#EditServicePackage").validate({
	  	errorElement: "div",
	    rules: {
	        "salon_package_name" : {
            required : true
	        },
	        "salon_package_price" : {
	          required : true
	        },
	        "salon_package_gst" : {
	        	required : true
	        },
	        "salon_package_upfront_amt" : {
	        	required : true
	        },
	        "salon_package_validity" : {
	        	required : true
	        },
	        "salon_package_type" : {
	        	required : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#EditServicePackage").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditServicePackage",
		        data: formData,
		        type: "POST",
						cache: false,
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditServicePackage").modal('hide');
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

		});
</script>
<script>
	$(function() {
		// Line chart
		new Chart(document.getElementById("chartjs-dashboard-line"), {
			type: "line",
			data: {
				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
				datasets: [{
					label: "Sales ($)",
					fill: true,
					backgroundColor: "transparent",
					borderColor: window.theme.primary,
					data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
				}, {
					label: "Orders",
					fill: true,
					backgroundColor: "transparent",
					borderColor: window.theme.tertiary,
					borderDash: [4, 4],
					data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
				}]
			},
			options: {
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				tooltips: {
					intersect: false
				},
				hover: {
					intersect: true
				},
				plugins: {
					filler: {
						propagate: false
					}
				},
				scales: {
					xAxes: [{
						reverse: true,
						gridLines: {
							color: "rgba(0,0,0,0.05)"
						}
					}],
					yAxes: [{
						ticks: {
							stepSize: 500
						},
						display: true,
						borderDash: [5, 5],
						gridLines: {
							color: "rgba(0,0,0,0)",
							fontColor: "#fff"
						}
					}]
				}
			}
		});
	});
</script>

<script>
	function exportTableToExcel(tableID, filename = 'customerlist'){
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
