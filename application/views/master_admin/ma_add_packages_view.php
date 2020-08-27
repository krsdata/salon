<?php
	$this->load->view('master_admin/ma_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('master_admin/ma_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('master_admin/ma_top_nav_view');
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
						}else{
					?>
					<div class="col-md-12">
					<div class="row">
						 <div class="col-md-6">
							<label for="Outlets-select">Outlets</label>
							 
								<select id="Outlets-select" class="form-control float-right">
								<option value="">Select Outlet</option>
								 <?php foreach($business_outlet_details as  $key => $outletDetails): 
								 $selected = ($selectedOutlets==$outletDetails['business_outlet_id']) ? 'selected="selected"' : "";
								 ?>
								  <option value="<?php echo $outletDetails['business_outlet_id']; ?>" <?php echo $selected; ?>><?php echo $outletDetails['business_outlet_name']; ?></option>
								  <?php endforeach; ?>
								</select>
							
						 </div>	
					</div>
								
						<div class="card">
							<div class="card-header">
							  
								<div class="row">
									<div class="col-md-6">
										<h5 class="card-title">Active Packages</h5>
									</div>	
									<div class="col-md-6">
										 <button class="btn btn-success float-right"  id="openAssignPackages" style="margin-left:1vw;"><i class="fas fa-caret-square-right"></i> Assign Package</button>
										<button class="btn btn-success float-right"  id="openAddPackageWindow" ><i class="fas fa-fw fa-plus"></i>Create New Package</button>
									
										
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
										
										<div class="modal" id="ModalAssignPackage" tabindex="-1" role="dialog" aria-hidden="true">
											 <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title text-white font-weight-bold">Assign Packages</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span class="text-white" aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body m-3">
														    <div class="row">
																<div class="col-md-12">
																	<form id="AssignPackageToOutlet" method="POST" action="#">
																		 
																		   <div class="row">
																			<div class="form-group col-md-12">
																				<label >Packages</label>
																				<select id="assign-package-select" name="assign_package_select[]" multiple="multiple" class="form-control float-right">
																					<option value="">Select Packages</option>
																				</select>
																			</div>

																		  </div>
																		   <div class="row">
																			<div class="form-group col-md-12">
																				<label >Outlets</label>
																				<select id="assign-Outlets-select" name="assign_Outlets_select[]" multiple="multiple" class="form-control float-right">
																					 <?php foreach($business_outlet_details as  $key => $outletDetails): 
																					 ?>
																					  <option value="<?php echo $outletDetails['business_outlet_id']; ?>" <?php echo $selected; ?>><?php echo $outletDetails['business_outlet_name']; ?></option>
																					  <?php endforeach; ?>
																				</select>
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
																	<input type="hidden" id="association_id" value="0" name="association_id" />
																	<div class="row">
																		<div class="form-group col-md-3">
																			<label>Package Name</label>
																			<input type="text" class="form-control" placeholder="Package Name" value="" name="salon_package_name" autofocus>
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
																	 <div class="form-group col-md-12">
																		<label >Outlets</label>
																			<select  name="salon_package_outlet[]" id="salon_package_outlet_ids"  multiple="multiple" class="form-control">
																				 <?php foreach($business_outlet_details as  $key => $outletDetails): ?>
																				  <option value="<?php echo $outletDetails['business_outlet_id']; ?>" <?php echo $selected; ?>><?php echo $outletDetails['business_outlet_name']; ?></option>
																				  <?php endforeach; ?>
																			</select>
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
																							<label>Sub-Category</label>
																							
																						    <select id="service_sub_category_id" name="service_sub_category_id" multiple >
																								<?php if(!empty($categories)){
																									foreach($categories as $key=>$category): ?>
																									   <optgroup label="<?php echo $category['category_name']; ?>">
																										<?php if(!empty($sub_categories)){
																											foreach($sub_categories as $subcategoryDetails){
																												if($subcategoryDetails['sub_category_category_id']==$category['category_id']){
																													echo '<option value="'.$subcategoryDetails['sub_category_id'].'">'.$subcategoryDetails['sub_category_name'].'</option>';
																												}		
																											}
																											//$keyValues = array_search($category['category_id'], array_column($sub_categories, 'sub_category_category_id')); 
																											//$subCat[$category['category_id']] =  
																										} 
																										
																										?>
																									</optgroup>
																									<?php endforeach;
																								}
																								
																								?>
																								
																							</select>
																							
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Service</label>
																							<select class="" id="service_id" name="service_id[]" multiple="multiple" temp="Service">
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

										<!--MODAL AREA END-->		
										<table class="table table-striped datatables-basic" id="customers-table" style="width:100%">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Package Name</th>
													<th>Package Type</th>
													<th>Creation Date</th>
													<!--<th>Current Status</th>-->
													<th>Price</th>
													<th>GST</th>
													<th>Total</th>
													<th>Validity (Months)</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody id="package-content"></tbody>
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
	$this->load->view('master_admin/ma_footer_view');
?>
<script type="text/javascript">
	$(document).on('click','.package-edit-btn',function(event) {
		event.preventDefault();	
			$("#ModalAddPackage").find('.modal-title').html('Edit Package'); 
			 var packageAssociationId = $(this).attr('salon_package_association_id');
			 $("#association_id").val(packageAssociationId);
			 /* Get Details from db */
			  var parameters = {pck_association_id:packageAssociationId};
				$.getJSON("<?=base_url()?>MasterAdmin/GetPackageDetailsById", parameters)
				.done(function(data, textStatus, jqXHR) {
					console.log(data.outletIds);
					 if(data!=null && data!='NULL'){
					 
							  $("#ModalAddPackage input[name=salon_package_name]").attr('value',data.salon_package_name);
							  $("#ModalAddPackage input[name=salon_package_price]").attr('value',data.salon_package_price);
							  $("#ModalAddPackage input[name=salon_package_gst]").attr('value',data.service_gst_percentage);
							  $("#ModalAddPackage input[name=salon_package_upfront_amt]").attr('value',data.salon_package_upfront_amt);
							  $("#ModalAddPackage input[name=salon_package_validity]").attr('value',data.salon_package_validity);
							  $("#ModalAddPackage input[name=virtual_wallet_money_absolute]").attr('value',data.virtual_wallet_money);
							 // Not exist in db	
							 //$("#ModalAddPackage input[name=virtual_wallet_money_percentage]").attr('value',data.virtual_wallet_money_percentage);
							 

							 $('[id=packageType] option').filter(function() { 
								return ($(this).text() == data.salon_package_type_selected); //To select dropdown record
							 }).prop('selected', true);
								
							
							 if(data.outletIds.length>0){	
								$.each(data.outletIds, function( index, selectedId ) {
								   $('#salon_package_outlet_ids option[value="'+selectedId+'"]').attr("selected", "selected");
								});	
							 }
							 
							 $('#salon_package_outlet_ids').multiSelect('destroy').multiSelect();	
							 $('#salon_package_outlet_ids')
							  if(data.salon_package_type=='Services' || data.salon_package_type=='Discount' || data.salon_package_type_selected=='salon_package_type_selected'){
								   togglePackage();
							  }else if(data.salon_package_type=='Wallet'){
								  
								  
							  }
		
							/*var options = ""; 
								for(var i=0;i<data.length;i++){
									options += "<option value="+data[i].salon_package_id+">"+data[i].salon_package_name+"</option>";
								}
								$('#assign-package-select').html("").html(options);
								$('#assign-package-select').multiSelect();
							 */
					
					 }else{
						 //window.location.reload();
					 }
				   
				})

				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				});
			 $('#ModalAddPackage').modal({ show: true });
			 
		});
		
    $(document).ready(function() {  
	   
		 $('#salon_package_outlet_ids').multiSelect();
		 $('#assign-Outlets-select').multiSelect();
		 $('#service_id').multiSelect();
		
	
		$("#openAddPackageWindow").on('click', function () {
			$("#ModalAddPackage").find('.modal-title').html('Add Package'); 
			 $('#ModalAddPackage').modal({ show: true });
		});
		
		
		$("#openAssignPackages").on('click', function () {
			 
			 var parameters = {};
			
				$.getJSON("<?=base_url()?>MasterAdmin/GetALLMasterPackages", parameters)
				.done(function(data, textStatus, jqXHR) {
					 if(data.length>0){	
							var options = ""; 
								for(var i=0;i<data.length;i++){
									options += "<option value="+data[i].salon_package_id+">"+data[i].salon_package_name+"</option>";
								}
								
								$('#assign-package-select').html("").html(options);
								$('#assign-package-select').multiSelect();
							/*
							 htmlContent += '<tr>';
							 htmlContent += '<td>'+(i+1)+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_name+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_type+'</td>';
							 
							 htmlContent += '<td>'+data[i].salon_package_date+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_price+'</td>';
							 htmlContent += '<td>'+(data[i].service_gst_percentage*data[i].salon_package_price/100)+'</td>';
							 htmlContent += '<td>'+(data[i].salon_package_price+(data[i].service_gst_percentage*data[i].salon_package_price/100))+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_validity+'</td>';
							
							 htmlContent += '<td class="table-action">';
							if(data[i].is_active==1){
								 htmlContent += '<button type="button" class="btn btn-success package-deactivate-btn" salon_package_id="'+data[i].salon_package_id+'"><i class="align-middle" data-feather="package"></i></button>';
							}else{
								 htmlContent += '<button type="button" class="btn btn-danger package-activate-btn" salon_package_id="'+data[i].salon_package_id+'"><i class="align-middle" data-feather="package"></i></button>';
							}
							 htmlContent += '</td></tr>';	 */			
					
					 }
				})

				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				}); 
			 
		});
		
		$("#openAssignPackages").on('click', function () {
			 $('#ModalAssignPackage').modal({ show: true });
		});
	 });
	
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
		$('#service_sub_category_id').multiSelect();
	}
	
	function togglePackage_main(){
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
	
	/*$(".datatables-basic").DataTable({
		responsive: true
	}); */
	
	
	function initiateTable(outletId=0){
		
		$("#customers-table").DataTable().destroy();
		$('#customers-table').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?=base_url()?>MasterAdmin/GetMasterPackages",
					"type": "POST", 
					"data" : {'outletId' : outletId}
				},
				"columns": [
				    { "data": "salon_package_id" },
				 	{ "data": "salon_package_name" },
					{ "data": "salon_package_type" },
					{ "data": "salon_package_date" },
					{ "data": "salon_package_price" },
					{ "data": "service_gst_percentage" },
					{ "data": "total" },
					{ "data": "salon_package_validity" },
					{ "data": "package_active_status" }
				]
		});
	}
			
			
			
	$(document).on('change',"#Outlets-select", function(e){
				
				initiateTable($(this).val());
				 /*var parameters = {
					'outlet_id' :  $(this).val()
				};
				var htmlContent="";
				$.getJSON("<?=base_url()?>MasterAdmin/GetMasterPackages", parameters)
				.done(function(data, textStatus, jqXHR) {
					 if(data.length>0){	
						for(var i=0;i<data.length;i++){
							
							 htmlContent += '<tr>';
							 htmlContent += '<td>'+(i+1)+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_name+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_type+'</td>';
							 
							 htmlContent += '<td>'+data[i].salon_package_date+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_price+'</td>';
							 htmlContent += '<td>'+(data[i].service_gst_percentage*data[i].salon_package_price/100)+'</td>';
							 htmlContent += '<td>'+(data[i].salon_package_price+(data[i].service_gst_percentage*data[i].salon_package_price/100))+'</td>';
							 htmlContent += '<td>'+data[i].salon_package_validity+'</td>';
							
							 htmlContent += '<td class="table-action">';
							if(data[i].is_active==1){
								 htmlContent += '<button type="button" class="btn btn-success package-deactivate-btn" salon_package_id="'+data[i].salon_package_id+'"><i class="align-middle" data-feather="package"></i></button>';
							}else{
								 htmlContent += '<button type="button" class="btn btn-danger package-activate-btn" salon_package_id="'+data[i].salon_package_id+'"><i class="align-middle" data-feather="package"></i></button>';
							}
							 htmlContent += '</td></tr>';				
						}
					 }else{
						 htmlContent +='<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No data available in table</td>d></tr>';
					 }
					
				    $("#package-content").html(htmlContent);
					//var json = JSON.stringify(data);
					//initiateTable("customers-table", json);
				})

				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				}); */
		});
		
		$(document).ready(function() {
			initiateTable($("#Outlets-select option:selected").val());
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
			
			
			//AssignPackageToOutlet
			
			$("#AssignPackageToOutlet").validate({
				errorElement: "div",
				rules: {
					"assign_package_select[]" : {
						required : true
					},
					"assign_Outlets_select[]" : {
						required : true
					}
				},
				submitHandler: function(form) {
					var formData = $("#AssignPackageToOutlet").serialize(); 
					$.ajax({
						url: "<?=base_url()?>MasterAdmin/MasterAdminAssignPackage",
						data: formData,
						type: "POST",
						// crossDomain: true,
						cache: false,
						// dataType : "json",
						success: function(data) {
							if(data.success == 'true'){ 
								$("#AssignPackageToOutlet").modal('hide');
									toastr["success"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1500
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
			
			$("#AddPackage").validate({
				errorElement: "div",
				rules: {
					"salon_package_name" : {
						required : true,
						maxlength : 50
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
					
					"salon_package_outlet[]" : {
						required : true
					},
					
					"salon_package_validity" : {
						required : true,
						digits : true
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
						url: "<?=base_url()?>MasterAdmin/MasterAdminAddPackage",
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetCategoriesByCategoryType", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetCategoriesByCategoryType", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetCategoriesByCategoryType", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetServicesBySubCatId", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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
			
			$(document).on('change',"#serviceTable tr:last select[name=service_sub_category_id]",function(e){
				var parameters = {
					'sub_category_id' :  $(this).val()
				};
				$.getJSON("<?=base_url()?>MasterAdmin/GetServicesBySubCatMultipleIds", parameters)
				.done(function(data, textStatus, jqXHR) {
					console.log(data);
						var options = "<option value='' selected></option>"; 
						
						for(var i=0;i<data.length;i++){
							options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
						}
						console.log(options);
					  
						$("#serviceTable tr:last select[temp=Service]").html("").html(options);
						$("#service_id").multiSelect('destroy').multiSelect();
						
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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

		
			$(document).on('change',"#serviceTable tr:last select[temp=Service]",function(e){
				var parameters = {
					'service_id' :  $(this).val()
				};
				// alert($(this).val());
				$.getJSON("<?=base_url()?>MasterAdmin/GetServicePriceById", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetServicePriceById", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
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
				$.getJSON("<?=base_url()?>MasterAdmin/GetServicesBySubCatMultipleIds", parameters)
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
					"salon_package_association_id" : $(this).attr('salon_package_association_id'),
					"activate" : 'false',
					"deactivate" : 'true'
				};
				$.ajax({
					url: "<?=base_url()?>MasterAdmin/ChangePackageStatus",
					data: parameters,
					type: "POST",
					// crossDomain: true,
					cache: false,
					// dataType : "json",
					success: function(data) {
								if(data.success == 'true'){
									$("#SuccessModalMessage").html("").html(data.message);
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
					"salon_package_association_id" : $(this).attr('salon_package_association_id'),
					"activate" : 'true',
					"deactivate" : 'false'
				};
				
				$.ajax({
					url: "<?=base_url()?>MasterAdmin/ChangePackageStatus",
					data: parameters,
					type: "POST",
					// crossDomain: true,
					cache: false,
					// dataType : "json",
					success: function(data) {
								if(data.success == 'true'){
									$("#SuccessModalMessage").html("").html(data.message);
									$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
										$("#SuccessModalMessage").html("").html(data.message);
									}).on('hidden.bs.modal', function (e) {
											window.location.reload();
									});
								}
							}
			});
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
