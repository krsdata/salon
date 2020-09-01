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
				<h1 class="h3 mb-3">Menu Management</h1>
				<div class="row">
					<?php
						// }
						// else{
					?>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="margin-left:10px;">
								<ul class="nav nav-pills card-header-pills pull-right" role="tablist">
									<li class="nav-item">
										<a class="nav-link <?php echo ((isset($_GET['tab']) && $_GET['tab']=='1') or !isset($_GET['tab'])) ? 'active' : ''; ?>" data-toggle="tab" href="#tab-1">Service</a>
									</li>
									<li class="nav-item">
										<a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab']=='2') ? 'active' : ''; ?>" data-toggle="tab" href="#tab-2">Product</a>
									</li>
									<li class="nav-item">
										<a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab']=='3') ? 'active' : ''; ?>" data-toggle="tab" href="#tab-3">Category</a>
									</li>
									<li class="nav-item">
										<a class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab']=='4') ? 'active' : ''; ?>" data-toggle="tab" href="#tab-4">Sub-Category</a>
									</li>
									<!--<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-5">Raw Material</a>
									</li>-->
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane <?php echo ((isset($_GET['tab']) && $_GET['tab']=='1') or !isset($_GET['tab'])) ? ' show active' : ''; ?>" id="tab-1" role="tabpanel">
										<!-- upload service -->
											 <div class="row">
												 <div class="col-md-6">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddService"><i class="fas fa-fw fa-plus"></i> Add Service</button>
													<button class="btn btn-primary" id="openAssignServices"><i class="fas fa-caret-square-right"></i> Assign Service</button>
												</div>
												
												<!--<div class="col-md-2">
														<button class="btn btn-primary" onclick="exportTableToExcel('servicMenu','Menu')"><i class="fa fa-file-export"></i>Export</button>
												</div>
												<div class="col-md-4">
													<button class="btn btn-primary DownloadSubCategories"><i class="fa fa-download"></i>Sub Category</button>
													<a class="btn btn-primary" href="<?=base_url()?>public\format\ServiceUploadFormat.xlsx" download><i class="fa fa-download"></i> Format</a>
												</div>
												<div class="col-md-4">
													<form action="#" id="UploadServices">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
                                <input type="file" name="file" class="btn" /> 
                                <input type="text" name="file_contents[]" class="form-control" readonly hidden id="file_contents">
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Upload</button>
															</div>
														</div>
													</form>
												</div>
												-->	
											</div> 
											<br/>
										<!-- end -->
										
                    <table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Service Name</th>
													<th>Service Gross Price</th>
													<th>Service GST Percentage</th>
													<th>Service Estimate Time (mins)</th>
													<th>Service Description</th>
													<th>Sub-Category</th>
													<th>Category Type</th>
													<th>Category</th>
													
													<th width="100px">Actions</th>
												</tr>
											</thead>
											<tbody>
                        
											 <?php
												$index = 1;
												foreach ($services as $service):
											?>
					
											<tr>
												<td><?=$index;?></td>
												<td><?=$service['service_name']?></td>
												<td><?=$service['service_price_inr']?></td>
												<td><?=$service['service_gst_percentage']?></td>
												<td><?=$service['service_est_time']?></td>
												<td><?=$service['service_description']?></td>
												<td><?=$service['sub_category_name']?></td>
												<td><?=$service['category_type']?></td>
												<td><?=$service['category_name']?></td>
												
												<td class="table-action">
													<?php if($permission['categocategoryry_edit']==1){?>
														<button type="button" class="btn service-edit-btn" service_type="service" service_id="<?=$service['service_id']?>">
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
													<?php }else{?>
														<button type="button" class="btn service-edit-btn" service_type="service" service_id="<?=$service['service_id']?>" <?php //disabled ?>>
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
														<?php }?>
														<?php if($permission['category_delete']==1){?>
															<button type="button" class="btn service-deactivate-btn" service_type="service" service_id="<?=$service['service_id']?>">
																<i class="align-middle" data-feather="trash-2"></i>
															</button>
															<?php }else{?>
															<button type="button" class="btn service-deactivate-btn" service_type="service" service_id="<?=$service['service_id']?>" <?php // disabled ?>>
																<i class="align-middle" data-feather="trash-2"></i>
															</button>
															<?php }?>
												</td>
											</tr>	
											<?php		
													$index = $index + 1;
												endforeach;
											?> 
											</tbody>
										</table>
									</div>
									<div class="tab-pane <?php echo (isset($_GET['tab']) && $_GET['tab']=='2') ? 'active' : ''; ?>" id="tab-2" role="tabpanel">
										<!-- upload -->
											<div class="row">
												 <div class="col-md-2">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddOTC"><i class="fas fa-fw fa-plus"></i> Add Product</button>
												</div> 
												<?php /* <div class="col-md-2">
															<button class="btn btn-primary mb-2" onclick="exportTableToExcel('otcMenu','OTCMenu')"><i class="fa fa-file-export"></i>Product</button>
												</div>
												<div class="col-md-4">
													<!-- <button class="btn btn-primary DownloadCategories"><i class="fa fa-download"></i> Category</button> -->
													<button class="btn btn-primary DownloadSubCategories" ><i class="fa fa-download"></i>Sub Category</button>
													<a class="btn btn-primary" href="<?=base_url()?>public\format\OTCUploadFormat.xlsx" download><i class="fa fa-download"></i> Format</a>
												</div>	
												<div class="col-md-4">
													<form action="#" id="UploadOTC">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
																<input type="file" name="file" class="btn" />
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Upload</button>
															</div>
														</div>
													</form>
												</div>
												<?php */ ?>
											</div>
											<br/>
										<!-- end -->
                    <table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Product Name</th>
													<th>Brand Name</th>
													<th>Product Gross Price</th> 
													<th>Product GST Percentage</th> 
													<th>SKU Size</th> 
													<th>Unit</th> 
													<th>Category Type</th> 
													<th>Category</th> 
													<th>Sub-Category</th> 
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
											<?php
													$index = 1;
													foreach ($products as $product):
											?>
                        
												<tr>
													<td><?=$index;?></td>
													<td><?=$product['service_name']?></td>
													<td><?=$product['service_brand']?></td>
													<td><?=$product['service_price_inr']?></td>
													<td><?=$product['service_gst_percentage']?></td>
													<td><?=$product['qty_per_item']?></td>
													<td><?=$product['service_unit']?></td>
													<td><?=$product['category_type']?></td>
													<td><?=$product['category_name']?></td>
													<td><?=$product['sub_category_name']?></td>
													<td class="table-action">
														<?php if($permission['category_edit']==1){?>
															<button type="button" class="btn service-edit-btn" service_type="otc" service_id="<?=$product['service_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn service-edit-btn" service_type="otc" service_id="<?=$product['service_id']?>" <?php //disabled ?> >
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
															<?php }?>
															<?php if($permission['category_delete']==1){?>
																<button type="button" class="btn service-deactivate-btn" service_type="otc" service_id="<?=$product['service_id']?>">
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }else{?>
																	<button type="button" class="btn service-deactivate-btn" service_type="otc" service_id="<?=$product['service_id']?>" <?php //disabled ?>>
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }?>
													</td>
												</tr>	
												<?php		
														$index = $index + 1;
													endforeach;
												?> 
											</tbody>
										</table>
									</div>
									<div class="tab-pane <?php echo (isset($_GET['tab']) && $_GET['tab']=='3') ? 'active' : ''; ?>" id="tab-3" role="tabpanel">
										<!-- upload -->
										 
											<div class="row">
												 <div class="col-md-3">
												  <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddCategory"><i class="fas fa-fw fa-plus"></i> Add Category</button>
												</div>
												<?php  /* <div class="col-md-4">
													<a class="btn btn-primary" href="<?=base_url()?>public\format\CategoryUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
												</div>	
												<div class="col-md-4">
                        <form action="#" id="UploadCategory">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
                                <input type="file" name="file" class="btn" />
                                <input type="text" name="file_contents[]" class="form-control" readonly hidden id="category_contents">
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
															</div>
														</div>
													</form>
												</div>*/ ?>
											
											</div> 
											<br/>
										<!-- end -->
										<table class="table table-striped datatables-basic" style="width: 100%;" id="outletTableForCategory">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Category Name</th>
													<th>Category Type</th>
													<th>Description</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody >
                                            <?php
													$index = 1;
													foreach ($categories as $category):
											?>
                        
												<tr>
													<td><?=$index;?></td>
													<td><?=$category['category_name']?></td>
													<td><?=$category['category_type']?></td>
													<td><?=$category['category_description']?></td>
													<td class="table-action">
														<?php if($permission['category_edit']==1){?>
															<button type="button" class="btn category-edit-btn" category_id="<?=$category['category_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn category-edit-btn" category_id="<?=$category['category_id']?>" <?php /* disabled */ ?>>
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
															<?php }?>
															<?php if($permission['category_delete']==1){?>
																<button type="button" class="btn category-deactivate-btn" category_id="<?=$category['category_id']?>">
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }else{?>
																	<button type="button" class="btn category-deactivate-btn" category_id="<?=$category['category_id']?>" <?php /* disabled */ ?>>
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }?>
													</td>
												</tr>	
												<?php		
														$index = $index + 1;
													endforeach;
												?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane <?php echo (isset($_GET['tab']) && $_GET['tab']=='4') ? 'active' : ''; ?>" id="tab-4" role="tabpanel">
										<!-- upload -->
											<div class="row">
												 <div class="col-md-3">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddSubCategory"><i class="fas fa-fw fa-plus"></i> Add Sub-Category</button>
												</div> 
												<!--<div class="col-md-4">
													<button class="btn btn-primary DownloadCategories"><i class="fa fa-download"></i> Category</button>
													<a class="btn btn-primary" href="<?=base_url()?>public\format\SubCategoryUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
												</div>
												<div class="col-md-4">
													<form action="#" id="UploadSubCategory">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
                              <input type="file" name="file" class="btn" /> 
                                <input type="text" name="file_contents[]" class="form-control" readonly hidden id="subcategory_contents">
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Upload</button>
															</div>
														</div>
													</form>
												</div>
												-->
											</div>
											<br/>
										<!-- end -->
										<table class="table table-striped datatables-basic" style="width: 100%;" >
											<thead>
												<tr>
													<th>Sno.</th>
													<th>SubCategory Name</th>
													<th>Description</th> 
													<th>Category Type</th>
													<th>Category Name</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody >
                        
							          <?php
													$index = 1;
													foreach ($subCategories as $category):
                                      ?>
                        
												<tr>
													<td><?=$index;?></td>
													<td><?=$category['sub_category_name']?></td>
													<td><?=$category['sub_category_description']?></td>
													<td><?=$category['category_type']?></td>
													<td><?=$category['category_name']?></td>
													
													<td class="table-action">
														<?php if($permission['category_edit']==1){?>
															<button type="button" class="btn sub-category-edit-btn" sub_category_id="<?=$category['sub_category_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn sub-category-edit-btn" sub_category_id="<?=$category['sub_category_id']?>" <?php // disabled ?> >
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
															<?php }?>
															<?php if($permission['category_delete']==1){?>
																<button type="button" class="btn sub-category-deactivate-btn" sub_category_id="<?=$category['sub_category_id']?>">
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }else{?>
																	<button type="button" class="btn sub-category-deactivate-btn" sub_category_id="<?=$category['sub_category_id']?>" <?php // disabled ?>>
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }?>
													</td>
												</tr>	
												<?php		
														$index = $index + 1;
													endforeach;
												?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="tab-5" role="tabpanel">
										<!-- upload -->
											<div class="row">
												<!-- <div class="col-md-3">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddRawMaterial"><i class="fas fa-fw fa-plus"></i> Add Raw Material</button>
												</div> -->
												<div class="col-md-2">
															<button class="btn btn-primary" onclick="exportTableToExcel('rawMaterial','Raw Material')"><i class="fa fa-file-export"></i>Raw Material</button>
												</div>
												<div class="col-md-3">
													<a class="btn btn-primary" href="<?=base_url()?>public\format\RawMaterialUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
												</div>
												<div class="col-md-4">
													<form action="#" id="UploadRawMaterial">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
                                <input type="file" name="file" class="btn" />
                                <input type="text" name="file_contents[]" class="form-control" readonly hidden id="raw_file_contents">
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
															</div>
														</div>
													</form>
												</div>
													
											</div>
											<br/>
										<!-- end -->
                    <table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Outlet Name</th>
													<th>Business Admin Name</th>
													<!-- <th>Description</th> -->
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
                        <?php
                          $index = 1;
                          foreach($business_admin_details as $business_admin){
                            ?>
                            <tr>
                              <td><?=$index;?></td>
                              <td><?=$business_admin['business_outlet_name']?></td>
                              <td><?=$business_admin['business_admin_first_name']?></td>
                              <td><input type="checkbox" id="raw_material<?=$index;?>" name="business_outlet[]" business_admin_id = "<?=$business_admin['business_admin_id']?>" business_outlet_id="<?=$business_admin['business_outlet_id']?>" ></td>
                            </tr>  
                            <?php
                            $index+=1;
                          }
                        ?>
												<!-- <?php
													$index = 1;
													foreach ($categories as $category):
                        ?>
                        
												<tr>
													<td><?=$index;?></td>
													<td><?=$category['category_name']?></td>
													<td><?=$category['category_type']?></td>
													<td><?=$category['category_description']?></td>
													<td class="table-action">
														<?php if($permission['category_edit']==1){?>
															<button type="button" class="btn category-edit-btn" category_id="<?=$category['category_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn category-edit-btn" category_id="<?=$category['category_id']?>" disabled>
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
															<?php }?>
															<?php if($permission['category_delete']==1){?>
																<button type="button" class="btn category-deactivate-btn" category_id="<?=$category['category_id']?>">
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }else{?>
																	<button type="button" class="btn category-deactivate-btn" category_id="<?=$category['category_id']?>" disabled>
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
																<?php }?>
													</td>
												</tr>	
												<?php		
														$index = $index + 1;
													endforeach;
												?> -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<!-- Modals -->
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
						<div class="modal" id="ModalAddService" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title  text-white font-weight-bold">Add Service</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="AddService" method="POST" action="#">
													<div class="row">
														<div class="form-group col-md-6">
															<label>Service Name</label>
															<input type="text" class="form-control" placeholder="Service Name" name="service_name" id="service_name" autofocus>
														</div>
													
														<div class="form-group col-md-6">
															<label>Service Gross Price</label>
															<input type="text" min="0" class="form-control" placeholder="Service Gross Price" name="service_price_inr" id="service_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label>Service GST Percentage</label>
															<input type="number" step="0.5" min="0" class="form-control" placeholder="% Value Only" name="service_gst_percentage" id="service_gst_percentage">
														</div>
														<div class="form-group col-md-6">
															<label>Service Estimate Time <small>(mins)</small></label>
															<input type="number" class="form-control" placeholder="Enter time in Minutes" name="service_est_time" id="service_est_time">
														</div>
                          </div>
                          
													<div class="row">
													<div class="form-group col-md-6">
														<label>Category Type</label>
														<select class="form-control" name="Service-Category-Type" id="Service-Category-Type" onchange="GetCategory(this.value,'AddService','','dropdownOnChange')">
														<option value="">--Select Category Type--</option>
														<option value="Service">Service</option>
														<option value="Products">Product</option>
														</select>
													</div>
														<div class="form-group col-md-6">
															<label>Category</label>
															<select class="form-control Service-Category-Id" name="category_id" id="Service-Category-Id">
																<option value="" selected></option>
																<?php
																	// foreach ($categories as $category) {
																	// 	echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																	// }
																?>
															</select>
														</div>
                          </div>
												  <div class="row">
													  <div class="form-group col-md-6">
															<label>Sub-Category</label>
															<select class="form-control" name="service_sub_category_id" id="Service-Sub-Category-Id">
															</select>
													  </div>
												  </div>
													<div class="row">									
														<div class="form-group col-md-12">
															<label>Service Description</label>
															<textarea class="form-control" rows="2" placeholder="Service Description" name="service_description" id="service_description"></textarea>
														</div>
													</div>
													<div class="row">
														<div class="col-md-2">
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
						<div class="modal" id="ModalAddCategory" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Add Category</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="AddCategory" method="POST" action="#">
												    <input type="hidden" value="0" id="categoryBusinessDetails" name="categoryBusinessDetails">
													<div class="form-group">
														<label>Category Name</label>
														<input type="text" class="form-control" placeholder="Category Name" name="category_name">
													</div>
													<div class="form-group">
													  <label>Category Type</label>
													  <select name="category_type" class="form-control">
															<option value="">--Select Category Type--</option>
															<option value="Service">Service</option>
															<option value="Products">Products</option>
													  </select>
													  </div>
													<div class="form-group">
														<label>Category Description</label>
														<textarea class="form-control" rows="2" placeholder="Category Description" name="category_description"></textarea>
													</div>
													<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalAddSubCategory" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
								<div class="modal-content">
									<div class="modal-header text-white">
										<h5 class="modal-title text-white">Add SubCategory</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="AddSubCategory" method="POST" action="#">
													<div class="form-group">
														<label>SubCategory Name</label>
														<input type="text" class="form-control" placeholder="Sub Category Name" id="sub_category_name" name="sub_category_name">
													  </div>
													  <div class="form-group">
														<label>Category Type</label>
														<select name="sub_category_category_type" id="sub_category_category_type" class="form-control" onchange="GetCategory(this.value,'AddSubCategory')">
														  <option value="">--Choose Category Type--</option>
														  <option value="Service">Service</option>
														  <option value="Products">Products</option>
														</select>
													  </div>
													<div class="form-group">
														<label>Category</label>
														<select name="category_id" id="sub_category_category_id" class="form-control">
															<option value="" selected>Choose Category</option>
															<?php
																// foreach ($categories as $category) {
																// 	echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																// }
															?>
														</select>																
													</div>
													<div class="form-group">
														<label>SubCategory Description</label>
														<textarea class="form-control" rows="2" placeholder="Sub Category Description" id="sub_category_description" name="sub_category_description"></textarea>
													</div>
													<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalEditCategory" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-white">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body m-3">
                    <div class="row">
                      <div class="col-md-12">
                        <form id="EditCategory" method="POST" action="#">
                          <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" placeholder="Category Name" name="category_name">
                          </div>
                          <div class="form-group">
                            <label>Category Type</label>
                            <select name="category_type" id="category_type_id" class="form-control" required>
                            <option value="">--Select Category Type--</option>
                            <option value="Service">Service</option>
                            <option value="Products">Products</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Category Description</label>
                            <textarea class="form-control" rows="2" placeholder="Category Description" name="category_description"></textarea>
                          </div>
                          <div class="form-group">
                            <input class="form-control" type="hidden" name="category_id" readonly="true">
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
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
            <div class="modal" id="ModalEditSubCategory" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-white">Edit SubCategory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body m-3">
                    <div class="row">
                      <div class="col-md-12">
                        <form id="EditSubCategory" method="POST" action="#">
                          <div class="form-group">
                            <label>SubCategory Name</label>
                            <input type="text" class="form-control" placeholder="Sub Category Name" name="sub_category_name">
                          </div>
						 <div class="form-group">
                            <label>Category Type</label>
                            <select name="sub_category_type_id" id="sub_category_type_id" class="form-control" onchange="GetCategory(this.value,'EditSubCategory')" required>
                            <option value="">--Select Category Type--</option>
                            <option value="Service">Service</option>
                            <option value="Products">Products</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                              <option value="" selected>Choose Category</option>
                              <?php
                                /*foreach ($categories as $category) {
                                  echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
                                } */
                              ?>
                            </select>                               
                          </div>
						  <div class="form-group">
                            <label>SubCategory Description</label>
                            <textarea class="form-control" rows="2" placeholder="Sub Category Description" name="sub_category_description"></textarea>
                          </div>
                          <div class="form-group">
                            <input class="form-control" type="hidden" name="sub_category_id" readonly="true">
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalEditService" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Edit Service</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="EditService" method="POST" action="#">
													<div class="row">
														<div class="form-group col-md-6">
															<label>Service Name</label>
															<input type="text" class="form-control" placeholder="Service Name" name="service_name">
														</div>
														<div class="form-group col-md-6">
															<label>Service Gross Price</label>
															<input type="text" class="form-control" placeholder="Service Gross Price" name="service_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label>Service GST Percentage</label>
															<input type="number" class="form-control" placeholder="% Value Only" name="service_gst_percentage">
														</div>
														<div class="form-group col-md-6">
															<label>Service Estimate Time</label>
															<input type="number" class="form-control" placeholder="N Hours/Minutes" name="service_est_time">
														</div>
													</div>
													<div class="row">
													 <div class="form-group col-md-6">
														<label>Category Type</label>
														<select name="service_category_type_id" id="service_category_type_id" class="form-control" onchange="GetCategory(this.value,'EditService','','dropdownOnChange')" required>
															<option value="">--Select Category Type--</option>
															<option value="Service">Service</option>
															<option value="Products">Products</option>
														</select>
													  </div>
													  <div class="form-group">
														<label>Category</label>
														<select name="category_id" class="form-control Service-Category-Id">
														  <option value="" selected>Choose Category</option>
														  <?php
															/*foreach ($categories as $category) {
															  echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
															} */
														  ?>
														</select>                               
													  </div>
													</div>  
													<div class="form-group">
															<label>Sub-Category</label>
															<select class="form-control" name="service_sub_category_id" id="Service-Sub-Category-Id-Edit">
															</select>
													</div>
													<div class="row">
														<div class="form-group col-md-12">
															<label>Service Description</label>
															<textarea class="form-control" rows="2" placeholder="Service Description" name="service_description"></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-12">
															<input class="form-control" type="hidden" name="service_id" readonly="true">
														</div>
													</div>
													<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalAddOTCStock" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title text-white">Add OTC Stock</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="text-white">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body m-3">
                              <div class="row">
                                <div class="col-md-12">
                                  <form id="AddOTCInventory" method="POST" action="#">
                                    <div class='row'>
                                      <div class="form-group col-md-6">
                                        <label>Item Name</label>
                                        <select class="form-control" name="service_id" style="width: 100%" id="ServiceOTCId">
                                            <option value>Select...</option>
                                            <?php
                                              foreach ($otc_items as $otc):
                                            ?>
                                            <option value="<?=$otc['service_id']?>"><?=$otc['service_name']?></option>
                                            <?php
                                              endforeach;
                                            ?>
                                        </select>
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label>Brand</label>
                                        <input class="form-control" placeholder="Brand" name="otc_brand" readonly>
                                      </div>
                                    </div>
                                    <div class='row'>
                                      <div class="form-group col-md-6">
                                        <label>Entry Date</label>
                                        <input type="date" class="form-control" placeholder="Entry Date" name="otc_entry_date">
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label>Expiry Date</label>
                                        <input type="date" name="otc_expiry_date" class="form-control" placeholder="Expiry Date">
                                      </div>
                                    </div>
                                    <div class='row'>
                                      <div class="form-group col-md-6">
                                        <label>No of SKU</label>
                                        <input type="number" name="otc_sku" class="form-control" placeholder="Number of SKU">
                                      </div>
                                      <div class="form-group col-md-6">
                                        <label>Unit</label>
                                        <select class="form-control" name="otc_unit" readonly>
                                          <option value="mL">mL</option>
                                          <option value="gms">gms</option>
                                          <option value="Kg">kg</option>
                                          <option value="Ltr">ltr</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class='row'>
                                      <div class="form-group col-md-6">
                                        <label>SKU Size</label>
                                        <input type="number" name="qty_per_sku" class="form-control" placeholder="Quantity Per SKU" disabled>
                                      </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                  </form>
                                  <div class="alert alert-dismissible feedback" role="alert">
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

			 <div class="modal" id="ModalAddOTC" tabindex="-1" role="dialog" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-white">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body m-3">
                    <div class="row">
                      <div class="col-md-12">
                        <form id="AddOTCCategory" method="POST" action="#">
                          <div class="row">
                            <div class="form-group col-md-6">
                              <label>Product Name</label>
                              <input type="text" class="form-control" placeholder="Item Name" name="otc_item_name">
                            </div>
                            <div class="form-group col-md-6">
                              <label>Brand</label>
                              <input class="form-control" placeholder="Brand Name" name="otc_brand">
                            </div>                            
                          </div>
                          <div class="row">
                          <div class="form-group col-md-6">
                              <label>Category Type</label>
                              <select class="form-control" name="Otc-Category-Type" id='Otc-Category-Type' onchange="GetCategory(this.value,'AddOTCCategory','','dropdownOnChange')">
                                <option value="" selected></option>
                                <option value="Service">Service</option>
                                <option value="Products">Product</option>
                              </select>
                          </div>                            
                          <div class="form-group col-md-6">
                              <label>Category</label>
                              <select class="form-control" name="category_id" id="OTC-Category-Id">
                                <option value="" selected></option>
                                <?php
                                 /* foreach ($categories as $category) {
                                    echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
                                  } */
                                ?>
                              </select>
                          </div>
                          </div>
                          <div class="row">
                            <div class="form-group col-md-6">
                                <label>Sub-Category</label>
                                <select class="form-control" name="otc_sub_category_id" id="OTC-Sub-Category-Id">
                                </select>
                              </div>                            
                            <div class="form-group col-md-6">
                              <label>Product Gross Price</label>
                              <input type="text" class="form-control" placeholder="OTC Gross Price" name="otc_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
                            </div>
                          </div>
                          <div class="row">
                            <div class="form-group col-md-6">
                              <label>Product GST Percentage</label>
                              <input type="number" class="form-control" placeholder="% Value Only" name="otc_gst_percentage">
                            </div>                            
                            <div class="form-group col-md-6">
                              <label>SKU Size</label>
                              <input type="number" class="form-control" placeholder="Quantity Per Item" name="qty_per_item">
                            </div>
                            <!-- <div class="form-group col-md-6">
                            <label>SKU Size</label>
                            <input type="number" class="form-control" placeholder="Enter SKU Size" name="otc_sku_size">
                          </div> -->
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6">
                              <label>Unit</label>
                              <select class="form-control" name="otc_unit" >
                                <option value="mL">mL</option>
                                <option value="gms">gms</option>
                                <option value="Kg">Kg</option>
                                <option value="Ltr">Ltr</option>
                              </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                          <button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalEditOTC" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-md" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Edit OTC</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="EditOTCCategory" method="POST" action="#">
													<div class="row">
														<div class="form-group col-md-6">
															<label>Item Name</label>
															<input type="text" class="form-control" placeholder="Item Name" name="otc_item_name">
														</div>
														<div class="form-group col-md-6">
															<label>Brand</label>
															<input class="form-control" placeholder="Brand Name" name="otc_brand">
														</div>
														
													</div>
													<div class="row">
													 <div class="form-group col-md-6">
														<label>Category Type</label>
														<select name="otc_category_type_id" id="otc_category_type_id" class="form-control" onchange="GetCategory(this.value,'EditOTCCategory','','dropdownOnChange')" required>
															<option value="">--Select Category Type--</option>
															<option value="Service">Service</option>
															<option value="Products">Products</option>
														</select>
													  </div>
													  <div class="form-group col-md-6">
														<label>Category</label>
														<select name="category_id" class="form-control Service-Category-Id">
														  <option value="" selected>Choose Category</option>
														  <?php
															/*foreach ($categories as $category) {
															  echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
															} */
														  ?>
														</select>                               
													  </div>
													</div>  
													<div class="row">
														<div class="form-group col-md-6">
																<label>Sub-Category</label>
																<select class="form-control" name="otc_sub_category_id" id="OTC-Sub-Category-Id-Edit">
																</select>
														</div>
														<div class="form-group col-md-6">
															<label>OTC Gross Price</label>
															<input type="text" class="form-control" placeholder="OTC Gross Price" name="otc_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
														</div>
													</div>  
													
													<div class="row">
													<div class="form-group col-md-6">
														<label>OTC GST Percentage</label>
														<input class="form-control" placeholder="% Value Only" name="otc_gst_percentage">
													</div>
													<div class="form-group col-md-6">
														<label>SKU Size</label>
														<input class="form-control" placeholder="SKU Size" name="sku_size">
													</div>
													<div class="form-group">
													<input class="form-control" type="hidden" name="otc_service_id" readonly="true">
													</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label>Unit</label>
															<select class="form-control" name="otc_unit" id="otc_unit_edit">
																<option value="mL">mL</option>
																<option value="gms">gms</option>
																<option value="Kg">Kg</option>
																<option value="Ltr">Ltr</option>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalAddRawMaterial" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Add Raw Material</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="AddRawMaterial" method="POST" action="#">
													<div class="form-group">
														<label>Item Name</label>
														<input type="text" class="form-control" placeholder="Item Name" name="raw_material_name">
													</div>
													<div class="form-group">
														<label>Unit</label>
														<select class="form-control" name="raw_material_unit">
															<option value="mL">mL</option>
															<option value="gms">gms</option>
															<option value="Kg">Kg</option>
															<option value="Ltr">Ltr</option>
														</select>
													</div>
													<div class="form-group">
														<label>Brand</label>
														<input class="form-control" placeholder="Brand Name" name="raw_material_brand">
													</div>
													<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalEditRawMaterial" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Edit Raw Material</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="EditRawMaterial" method="POST" action="#">
													<div class="form-group">
														<label>Item Name</label>
														<input type="text" class="form-control" placeholder="Item Name" name="raw_material_name">
													</div>
													<div class="form-group">
														<label>Unit</label>
														<select class="form-control" name="raw_material_unit">
															<option value="mL">mL</option>
															<option value="gms">gms</option>
															<option value="Kg">Kg</option>
															<option value="Ltr">Ltr</option>
														</select>
													</div>
													<div class="form-group">
														<label>Brand</label>
														<input class="form-control" placeholder="Brand Name" name="raw_material_brand">
													</div>
													<div class="form-group">
														<input class="form-control" type="hidden" name="raw_material_category_id" readonly="true">
													</div>
													<button type="submit" class="btn btn-primary">Submit</button>
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
						
							<div class="modal" id="ModalAssignService" tabindex="-1" role="dialog" aria-hidden="true">
											 <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title text-white font-weight-bold">Assign Services</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span class="text-white" aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body m-3">
														    <div class="row">
																<div class="col-md-12">
																	<form id="AssignServicesToPackages" method="POST" action="#">
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
																				<label >Services</label>
																				<select id="assign-services-select" name="assign_Services_select[]" multiple="multiple" class="form-control float-right">
																				    <?php
																						foreach($services as $service):
																					?>
																					  <option value="<?php echo $service['service_id']; ?>" ><?php echo $service['service_name']; ?></option>
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
										
						<!-----END------>
          </div>
        
					<?php
						// }
					?>	
				</div>
			</div>
		</main>	
<?php
	$this->load->view('master_admin/ma_footer_view');
?>

<link href="<?=base_url()?>public/app_stack/css/multiselect/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>public/app_stack/js/multiselect/bootstrap.min.js"></script>
<link href="<?=base_url()?>public/app_stack/css/multiselect/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<script src="<?=base_url()?>public/app_stack/js/multiselect/bootstrap-multiselect.js" type="text/javascript"></script>

<script>
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
</script>
<script type="text/javascript">
 var basePath = '<?php echo base_url() ?>';
 var business_details_For_Category=[];
 /* Get business details for the category */
 $('.outletActionForCategory').on('change', function() {
        if(this.checked) {
          business_details_For_Category.push({
			 businessAdminId: $(this).attr("business_admin_id"),
			 businessOutletId: $(this).attr("business_outlet_id")
		   });
        }else{
			/* Remove the id from array */
			business_details_For_Category.filter(x => x.businessOutletId === $(this).attr("business_outlet_id")).forEach(x => business_details_For_Category.splice(business_details_For_Category.indexOf(x), 1));
		}
 });   
	
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
	  
    
	 $(document).ready(function() {  
	   
		$('#assign-services-select').multiselect({includeSelectAllOption: true,maxHeight: 150,
										buttonWidth: 150,
										numberDisplayed: 2,
										nSelectedText: 'selected'});
		$("#openAssignServices").on('click', function () {
			 var parameters = {};
				var htmlContent="";
				$.getJSON("<?=base_url()?>MasterAdmin/GetALLMasterPackages", parameters)
				.done(function(data, textStatus, jqXHR) {
					 if(data.length>0){	
							var options = ""; 
								for(var i=0;i<data.length;i++){
									options += "<option value="+data[i].salon_package_id+">"+data[i].salon_package_name+"</option>";
								}
								
								$('#assign-package-select').html("").html(options);
								$('#assign-package-select').multiselect({maxHeight: 150,
										buttonWidth: 150,
										numberDisplayed: 2,
										nSelectedText: 'selected'});
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
					
					 }else{
						 htmlContent +='<tr class="odd"><td valign="top" colspan="9" class="dataTables_empty">No data available in table</td>d></tr>';
					 }
					
				    $("#package-content").html(htmlContent);
					//var json = JSON.stringify(data);
					//initiateTable("customers-table", json);
				})

				.fail(function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown.toString());
				}); 
			 
		});
		
		$("#openAssignServices").on('click', function () {
			 $('#ModalAssignService').modal({ show: true });
		});
	 });
	 
	$(document).ready(function(){
		$("#ModalAddCategory").on('shown.bs.modal', function(){
			 $("#categoryBusinessDetails").val(JSON.stringify(business_details_For_Category));
		});
		
	});
	
																 
	$("#AssignServicesToPackages").validate({
		errorElement: "div",
		rules: {
			"assign_package_select[]" : {
				required : true
			},
			"assign_Services_select[]" : {
				required : true
			}
		},
		submitHandler: function(form) {
			var formData = $("#AssignServicesToPackages").serialize(); 
			$.ajax({
				url: "<?=base_url()?>MasterAdmin/MasterAdminAssignServices",
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
								
		
	$("#AddCategory").validate({
		errorElement: "div",
	    rules: {
	        "category_name" : {
            required : true,
            maxlength : 100
	        },
			"category_type" : {
            required : true
          }   
	    },
	    submitHandler: function(form) {
				  var formData = $("#AddCategory").serialize(); 
				  var business_details=[];
				  for(var count = 1;count< <?=count($business_admin_details);?>;count++)
				  {
					if($("#category"+count).is(":checked"))
					{
					  business_admin_id : $("#category"+count).attr("business_admin_id");
					  business_outlet_id : $("#category"+count).attr("business_outlet_id");
					  
					}
					
					console.log(business_details);    
				  }
				
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/AddCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    success: function(data){
		    var message = data.message;
			 
			  if(data.success == 'true'){ 
              	$("#ModalAddCategory").modal('hide');
				$("#SuccessModalMessage").html("").html(message);
				$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
				}).on('hidden.bs.modal', function (e) {
						//window.location.reload();
						 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=3";
				});
              }
              else if (data.success == 'false'){                   
          	    if($('.feedback').hasClass('alert-success')){
                  $('.feedback').removeClass('alert-success').addClass('alert-danger');
                }
                else{
                  $('.feedback').addClass('alert-danger');
                }
                $('.alert-message').html("").html(message); 
              }
            },
            error: function(data){
    					$('.feedback').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				}); 
			},
		});

		$("#AddSubCategory").validate({
	  	errorElement: "div",
	    rules: {
	        "sub_category_name" : {
            required : true,
            maxlength : 100
	        },
	        "category_id" : {
	        	required : true
	        }  
	    },
	    submitHandler: function(form) {
				var formData = $("#AddSubCategory").serialize(); 
				
			 $.ajax({		
		        url: "<?=base_url()?>MasterAdmin/AddSubCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddSubCategory").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=4";
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

		$("#AddService").validate({
	  	errorElement: "div",
	    rules: {
	        "service_name" : {
            required : true,
            maxlength : 100
	        },
	        "service_est_time" :{
	        	required : true,
	        	maxlength : 50
	        },
	        "service_price_inr" : {
	        	required : true
	        },
	        "service_gst_percentage" : {
	        	required : true
	        },
	        "service_sub_category_id" :{
	        	required : true
	        }
	    },
	    submitHandler: function(form){
			 var formData = $("#AddService").serialize(); 
			 $.ajax({		
		        url: "<?=base_url()?>MasterAdmin/AddService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		      success: function(data) {
			  if(data.success == 'true'){ 
              	$("#ModalAddService").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=1";
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

	$("#AddOTCCategory").validate({
      errorElement: "div",
      rules: {
          "otc_item_name" : {
            required : true,
            maxlength : 100
          },
          "otc_brand" :{
            required : true,
            maxlength : 100
          },
          "otc_unit" : {
            required : true
          },
          "otc_sub_category_id" : {
            required : true
          },
          "otc_price_inr" : {
            required : true
          },
          "otc_gst_percentage" : {
            required : true,
            digits : true
          },
        
      },
      submitHandler: function(form) {
        var formData = $("#AddOTCCategory").serialize(); 
        $.ajax({
            url: "<?=base_url()?>MasterAdmin/AddOTCService",
            data: formData,
            type: "POST",
            // crossDomain: true,
            cache: false,
            // dataType : "json",
            success: function(data) {
              if(data.success == 'true'){ 
                $("#ModalAddOTC").modal('hide');
				 $("#SuccessModalMessage").html("").html(data.message);
                $('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
                 
                }).on('hidden.bs.modal', function (e) {
                    //window.location.reload();
					 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=2";
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


		$("#AddRawMaterial").validate({
	  	errorElement: "div",
	    rules: {
	        "raw_material_item_name" : {
            required : true,
            maxlength : 100
	        },
	        "raw_material_brand" :{
	        	required : true,
	        	maxlength : 100
	        },
	        "raw_material_unit" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddRawMaterial").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/AddRawMaterial",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddRawMaterial").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
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

		$("#EditCategory").validate({
	  	errorElement: "div",
	    rules : {
      	"category_name" : {
      	  required : true,
        	maxlength : 100
      	}
      },
	    submitHandler: function(form) {
				var formData = $("#EditCategory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/EditCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditCategory").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=3";
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

		$("#EditSubCategory").validate({
	  	errorElement: "div",
	    rules: {
      	"sub_category_name" : {
      	  required : true,
        	maxlength : 100
        },
        "category_id" : {
        	required : true
        }
      },
	    submitHandler: function(form) {
				var formData = $("#EditSubCategory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/EditSubCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditSubCategory").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=4";
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

		$("#EditService").validate({
	  	errorElement: "div",
	    rules : {
      	"service_name" : {
          required : true,
          maxlength : 100
        },
        "service_est_time" :{
        	required : true,
        	maxlength : 50
        },
        "service_price_inr" : {
        	required : true
        },
        "service_gst_percentage" : {
        	required : true
        }
       /* "service_sub_category_id" :{
        	required : true
        }*/
      },
	    submitHandler: function(form) {
				var formData = $("#EditService").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/EditService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditService").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=1";
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

		$("#EditOTCCategory").validate({
	  	errorElement: "div",
	    rules: {
	        "otc_item_name" : {
            required : true,
            maxlength : 100
	        },
	        "otc_brand" :{
	        	required : true,
	        	maxlength : 100
	        },
	        "otc_unit" : {
	        	required : true
	        },
	        "otc_price_inr" : {
	        	required : true
	        },
	        "otc_gst_percentage" : {
	        	required : true,
	        	digits : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#EditOTCCategory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/EditOTCService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditOTC").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
								}).on('hidden.bs.modal', function (e) {
										//window.location.reload();
										 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=2";
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

		$("#EditRawMaterial").validate({
	  	errorElement: "div",
	    rules: {
	        "raw_material_item_name" : {
            required : true,
            maxlength : 100
	        },
	        "raw_material_brand" :{
	        	required : true,
	        	maxlength : 100
	        },
	        "raw_material_unit" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#EditRawMaterial").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>MasterAdmin/EditRawMaterial",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditRawMaterial").modal('hide');
								$("#SuccessModalMessage").html("").html(data.message);
								$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
									
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

	$(document).on('click','.category-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        category_id : $(this).attr('category_id')
      };
      $.getJSON("<?=base_url()?>MasterAdmin/GetCategory/", parameters)
      .done(function(data, textStatus, jqXHR) { 
	 
	   $('[id=category_type_id] option').filter(function() { 
			return ($(this).text() == data.category_type); //To select dropdown record
		}).prop('selected', true);
	
        $("#EditCategory input[name=category_name]").attr('value',data.category_name);
        $("#EditCategory textarea[name=category_description]").val(data.category_description);
        $("#EditCategory input[name=category_id]").attr('value',data.category_id);

        $("#ModalEditCategory").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.sub-category-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        sub_category_id : $(this).attr('sub_category_id')
      };
      $.getJSON("<?=base_url()?>MasterAdmin/GetSubCategory/", parameters)
      .done(function(data, textStatus, jqXHR) { 
	  
	    $('[id=sub_category_type_id] option').filter(function() { 
			return ($(this).text() == data.category_type); //To select dropdown record
		}).prop('selected', true);
		
		// trigger function getCategory
		GetCategory(data.category_type,'EditSubCategory',data.sub_category_category_id);
		
        $("#EditSubCategory input[name=sub_category_name]").attr('value',data.sub_category_name);
        $("#EditSubCategory textarea[name=sub_category_description]").val(data.sub_category_description);
        $("#EditSubCategory select[name=category_id]").val(data.sub_category_category_id);
        $("#EditSubCategory input[name=sub_category_id]").val(data.sub_category_id);
        $("#ModalEditSubCategory").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.service-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
	  var service_type = $(this).attr('service_type');
	   if(service_type=='service'){
		   var objId = 'EditService';
	   }else{
		   var objId = 'EditOTCCategory';
	   }
      var parameters = {
        service_id : $(this).attr('service_id'),
		service_type : service_type
      };

      $.getJSON("<?=base_url()?>MasterAdmin/GetService", parameters)
      .done(function(data, textStatus, jqXHR) { 
	  
		  if(service_type=='service'){
			
			    $('[id=service_category_type_id] option').filter(function() { 
					return ($(this).text() == data.category_type); //To select dropdown record
				}).prop('selected', true);
				// trigger function getCategory
				GetCategory(data.category_type,'EditService',data.category_id);
				GetService(data.category_id,'EditService',data.sub_category_id,'service_sub_category_id');
					
				$("#EditService input[name=service_name]").attr('value',data.service_name);
				$("#EditService input[name=service_price_inr]").attr('value',data.service_price_inr);
				$("#EditService input[name=service_gst_percentage]").attr('value',data.service_gst_percentage);
				$("#EditService textarea[name=service_description]").val(data.service_description);
				$("#EditService select[name=category_id]").val(data.category_id);
				$("#EditService input[name=service_est_time]").attr('value',data.service_est_time);
				$("#EditService select[name=service_sub_category_id]").val(data.service_sub_category_id);
				$("#EditService input[name=service_id]").attr('value',data.service_id);
				
				$("#ModalEditService").modal('show');
		   }else{
			   
			    $('[id=otc_category_type_id] option').filter(function() { 
					return ($(this).text() == data.category_type); //To select dropdown record
				}).prop('selected', true);
				
			   // trigger function getCategory
				GetCategory(data.category_type,'EditOTCCategory',data.category_id);
				GetService(data.category_id,'EditOTCCategory',data.sub_category_id,'otc_sub_category_id');
					
				$("#EditOTCCategory input[name=otc_item_name]").attr('value',data.service_name);
				
				 $('[id=otc_unit_edit] option').filter(function() { 
					return ($(this).text() == data.service_unit); //To select dropdown record
				}).prop('selected', true);
				
				
				$("#EditOTCCategory input[name=otc_brand]").val(data.service_brand);
				$("#EditOTCCategory input[name=otc_price_inr]").attr('value',data.service_price_inr);
				$("#EditOTCCategory input[name=otc_gst_percentage]").attr('value',data.service_gst_percentage);
				
				$("#EditOTCCategory input[name=sku_size]").attr('value',data.qty_per_item);
				$("#EditOTCCategory input[name=otc_service_id]").attr('value',data.service_id);
			   
			   
			   $("#ModalEditOTC").modal('show');
		   }
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.otc-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_id : $(this).attr('otc_service_id')
      };

      $.getJSON("<?=base_url()?>MasterAdmin/GetService/", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditOTCCategory input[name=otc_item_name]").attr('value',data.service_name);
        $("#EditOTCCategory input[name=otc_brand]").attr('value',data.service_brand);
        $("#EditOTCCategory select[name=otc_unit]").attr('value',data.service_unit);
        $("#EditOTCCategory input[name=otc_service_id]").attr('value',data.service_id);
        $("#EditOTCCategory input[name=otc_price_inr]").attr('value',data.service_price_inr);
        $("#EditOTCCategory input[name=otc_gst_percentage]").attr('value',data.service_gst_percentage);
		$("#EditOTCCategory input[name=sku_size]").attr('value',data.qty_per_item);
       
        $("#ModalEditOTC").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.raw-material-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        raw_material_category_id : $(this).attr('raw_material_category_id')
      };

      $.getJSON("<?=base_url()?>MasterAdmin/GetRawMaterial/", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditRawMaterial input[name=raw_material_name]").attr('value',data.raw_material_name);
        $("#EditRawMaterial input[name=raw_material_brand]").attr('value',data.raw_material_brand);
        $("#EditRawMaterial input[name=raw_material_unit]").attr('value',data.raw_material_unit);
        $("#EditRawMaterial input[name=raw_material_category_id]").attr('value',data.raw_material_category_id);
       
        $("#ModalEditRawMaterial").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.service-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "service_id" : $(this).attr('service_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>MasterAdmin/DeactivateService",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								//window.location.reload();
								 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=1";
						});
          }
          else if (data.success == 'false'){          
						$("#ErrorModalMessage").html("").html(data.message);
						$('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        }
			});
    });

    $(document).on('click','.category-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "category_id" : $(this).attr('category_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
			// alert($(this).attr('category_id'));
      $.ajax({
        url: "<?=base_url()?>MasterAdmin/DeactivateCategory",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
			           $("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								//window.location.reload();
								 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=3";
						});
          }
          else if (data.success == 'false'){    
			$("#ErrorModalMessage").html("").html(data.message);		  
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        }
			});
    });

    $(document).on('click','.sub-category-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "sub_category_id" : $(this).attr('sub_category_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>MasterAdmin/DeactivateSubCategory",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								//window.location.reload();
								 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=4";
						});
          }
          else if (data.success == 'false'){    
            $("#ErrorModalMessage").html("").html(data.message);		  
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        }
			});
    });

    $(document).on('click','.otc-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "service_id" : $(this).attr('otc_service_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>MasterAdmin/DeactivateService",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
			            $("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								//window.location.reload();
								 window.location.href = basePath+'MasterAdmin/MenuManagementNew' + "?tab=2";
						});
          }
          else if (data.success == 'false'){  
			$("#ErrorModalMessage").html("").html(data.message);		  
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        }
			});
    });

    $(document).on('click','.raw-material-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "raw_material_category_id" : $(this).attr('raw_material_category_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>MasterAdmin/DeactivateRawMaterial",
        data: parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
			            $("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){    
			$("#ErrorModalMessage").html("").html(data.message);			
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        }
			});
    });


	 

    $(".Service-Category-Id").on('change',function(e){
		var objId = $(this).closest("form").attr('id');
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected>--Select--</option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#"+objId+' select[name=service_sub_category_id]').html("").html(options);
       		$("#"+objId+' select[name=otc_sub_category_id]').html("").html(options);
			
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#OTC-Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#OTC-Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

  	
  	$(document).on('click',".DownloadCategories",function(event){
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one outlet.");
        return false;
      }
      var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#sub_category"+count).is(":checked"))
        {
          business_admin_id : $("#sub_category"+count).attr("business_admin_id");
          business_outlet_id : $("#sub_category"+count).attr("business_outlet_id");
          business_details.push($("#sub_category"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
      // alert(business_details);
      var parameters = { "outlet_id" : business_details};
  		event.preventDefault();
  		$(this).blur();
  		$.ajax({
        url: "<?=base_url()?>MasterAdmin/GetCategoriesPublic",
        data : parameters,
        type: "POST",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
            
						JSONToCSVConvertor(data.message, "Categories", true);
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
  	});

  	$(document).on('click',".DownloadSubCategories",function(event){
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one outlet.");
        return false;
      }
		var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#service"+count).is(":checked"))
        {
          business_admin_id : $("#service"+count).attr("business_admin_id");
          business_outlet_id : $("#service"+count).attr("business_outlet_id");
          business_details.push($("#service"+count).attr("business_outlet_id"));
        }
        else if($("#product"+count).is(":checked"))
        {
          business_details.push($("#service"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
      var parameters = {"outlet_id" : business_details};
  		event.preventDefault();
  		$(this).blur();
  		$.ajax({
        url: "<?=base_url()?>MasterAdmin/GetSubCategoriesPublic",
        type: "POST",
        data : parameters,
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						JSONToCSVConvertor(data.message, "SubCategories", true);
            window.location.reload(setTimeout=1000);
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
  	});
    
  	$("#UploadServices").submit(function(e){
      // alert(document.getElementById("service"+2).attr("business_admin_id"));
      var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#service"+count).is(":checked"))
        {
          business_admin_id : $("#service"+count).attr("business_admin_id");
          business_outlet_id : $("#service"+count).attr("business_outlet_id");
          business_details.push($("#service"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
	  // alert(business_details);
      $("#file_contents").val(business_details);
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>MasterAdmin/BulkUploadServices",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        contentType: "application/octet-stream",
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        // dataType : "json", 
        success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){       
			$("#ErrorModalMessage").html("").html(data.message);            
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadOTC").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>MasterAdmin/BulkUploadOTC",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        contentType: "application/octet-stream",
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        // dataType : "json",
        success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){   
			$("#ErrorModalMessage").html("").html(data.message);			
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadCategory").submit(function(e){
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one outlet.");
        return false;
      }
      var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#category"+count).is(":checked"))
        {
          business_admin_id : $("#category"+count).attr("business_admin_id");
          business_outlet_id : $("#category"+count).attr("business_outlet_id");
          business_details.push($("#category"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
	  // alert(business_details);
      $("#category_contents").val(business_details);
    	event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>MasterAdmin/BulkUploadCategory",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        contentType: "application/octet-stream",
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        // dataType : "json",
        success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){      
			$("#ErrorModalMessage").html("").html(data.message);			
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadSubCategory").submit(function(e){
      var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#sub_category"+count).is(":checked"))
        {
          business_admin_id : $("#sub_category"+count).attr("business_admin_id");
          business_outlet_id : $("#sub_category"+count).attr("business_outlet_id");
          business_details.push($("#sub_category"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
	  // alert(business_details);
      $("#subcategory_contents").val(business_details);
    	event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>MasterAdmin/BulkUploadSubCategory",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        contentType: "application/octet-stream",
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        // dataType : "json",
        success: function(data) {
          if(data.success == 'true'){
						$("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){  
			$("#ErrorModalMessage").html("").html(data.message);			
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadRawMaterial").submit(function(e){
      checked = $("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("You must check at least one outlet.");
        return false;
      }
      var business_details=[];
      for(var count = 1;count< <?=count($business_admin_details);?>;count++)
      {
        if($("#raw_material"+count).is(":checked"))
        {
          business_admin_id : $("#raw_material"+count).attr("business_admin_id");
          business_outlet_id : $("#raw_material"+count).attr("business_outlet_id");
          business_details.push($("#raw_material"+count).attr("business_outlet_id"));
        }
        
        // alert(business_details);    
      }
	  // alert(business_details);
      $("#raw_file_contents").val(business_details);
    	event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>MasterAdmin/BulkUploadRawMaterial",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        contentType: "application/octet-stream",
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        // dataType : "json",
        success: function(data) {
          if(data.success == 'true'){
			            $("#SuccessModalMessage").html("").html(data.message);
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							
						}).on('hidden.bs.modal', function (e) {
								window.location.href = "<?=base_url()?>MasterAdmin/MenuManagement/";
						});
          }
         	else if (data.success == 'false'){  
			$("#ErrorModalMessage").html("").html(data.message);			
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

  });
</script>
<script>
	function exportTableToExcel(tableID, filename = ''){
    
			checked = $("input[type=checkbox]:checked").length;
		  var business_details=[];
      if(tableID == 'servicMenu'){
        for(var count = 1;count< <?=count($business_admin_details);?>;count++)
        {
          if($("#service"+count).is(":checked"))
          {
            business_admin_id : $("#service"+count).attr("business_admin_id");
            business_outlet_id : $("#service"+count).attr("business_outlet_id");
            business_details.push($("#service"+count).attr("business_outlet_id"));
          }
          
          
          // alert(business_details);    
        }
        var parameters = {"outlet_id" : business_details};
        event.preventDefault();
        $(this).blur();
        $.ajax({
          url: "<?=base_url()?>MasterAdmin/GetServicePublic",
          type: "POST",
          data : parameters,
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if(data.success == 'true'){
              JSONToCSVConvertor(data.message, "Service Menu", true);
              window.location.reload(setTimeout=1000);
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
      }
      else if(tableID == 'otcMenu'){
        for(var count = 1;count< <?=count($business_admin_details);?>;count++)
        {
          if($("#product"+count).is(":checked"))
          {
            business_admin_id : $("#product"+count).attr("business_admin_id");
            business_outlet_id : $("#product"+count).attr("business_outlet_id");
            business_details.push($("#product"+count).attr("business_outlet_id"));
          }
          
          
          // alert(business_details);    
        }
        var parameters = {"outlet_id" : business_details};
        event.preventDefault();
        $(this).blur();
        $.ajax({
          url: "<?=base_url()?>MasterAdmin/GetProductPublic",
          type: "POST",
          data : parameters,
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if(data.success == 'true'){
              JSONToCSVConvertor(data.message, "Product Menu", true);
              window.location.reload(setTimeout=1000);
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
      }
      else{
        for(var count = 1;count< <?=count($business_admin_details);?>;count++)
        {
          if($("#raw_material"+count).is(":checked"))
          {
            business_admin_id : $("#raw_material"+count).attr("business_admin_id");
            business_outlet_id : $("#raw_material"+count).attr("business_outlet_id");
            business_details.push($("#raw_material"+count).attr("business_outlet_id"));
          }
          
          
          // alert(business_details);    
        }
        var parameters = {"outlet_id" : business_details};
        event.preventDefault();
        $(this).blur();
        $.ajax({
          url: "<?=base_url()?>MasterAdmin/GetRawMaterialPublic",
          type: "POST",
          data : parameters,
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if(data.success == 'true'){
              JSONToCSVConvertor(data.message, "RawMaterial Menu", true);
              window.location.reload(setTimeout=1000);
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
      }
      
      
	}
</script>
<script>
  function GetCategory(category_type,selectedId,selectedValue="",onChangeType='')
  {
	
	var parameters = {category_type : category_type };
  
    $.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCategoryType",parameters)
    .done(function(data,textStatus,jqXHR){
      $("#"+selectedId+' '+'select[name=category_id]').html("");
	 if(onChangeType=='dropdownOnChange'){  // for reset sub category dropdown
	  $('#Service-Sub-Category-Id-Edit').html("<option value=''>--Select--</option>");
	  $('#Service-Sub-Category-Id').html("<option value=''>--Select--</option>");
	  $('#OTC-Sub-Category-Id').html("<option value=''>--Select--</option>");
	  $('#OTC-Sub-Category-Id-Edit').html("<option value=''>--Select--</option>");
	 
	 }
	  $("#"+selectedId+' '+'select[name=category_id]').append("<option value=''>--Select--</option>");
      var selectedParam="";
	  for (var i=0;i<data.length;i++)
      {
		if(data[i]['category_id']==selectedValue){
			selectedParam = 'selected="selected"';
		}else{
			selectedParam = "";
		}
		$("#"+selectedId+' '+'select[name=category_id]').append("<option value="+data[i]['category_id']+" "+selectedParam+" >"+data[i]['category_name']+"</option>");
      }
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
	
  }
  function GetService(category_id,selectedId,selectedValue="",selectDropdownName="")
  {
	var parameters = {category_id : category_id};

    $.getJSON("<?=base_url()?>MasterAdmin/GetSubCategoriesByCatId",parameters)
    .done(function(data,textStatus,jqXHR){
      $("#"+selectedId+' '+'select[name='+selectDropdownName+']').html("");
      $("#"+selectedId+' '+'select[name='+selectDropdownName+']').append("<option value=''>--Select--</option>");
      var selectedParam="";
	  for(var i=0;i<data.length;i++)
      {
		if(data[i]['sub_category_id']==selectedValue){
			selectedParam = 'selected="selected"';
		}else{
			selectedParam = "";
		}
        $("#"+selectedId+' '+'select[name='+selectDropdownName+']').append("<option value="+data[i]['sub_category_id']+" "+selectedParam+" >"+data[i]['sub_category_name']+"</option>");
      }
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function GetOtcCategory()
  {
    var parameters = {category_type : $('#Otc-Category-Type').val()};

    $.getJSON("<?=base_url()?>MasterAdmin/GetCategoryByCategoryType",parameters)
    .done(function(data,textStatus,jqXHR){
      $('#AddOTCCategory select[name=category_id]').html("");
      $('#AddOTCCategory select[name=otc_sub_category_id]').html("");
      $('#AddOTCCategory select[name=category_id]').append("<option value=''>--Select--</option>");
      for(var i=0;i<data.length;i++)
      {
        $('#AddOTCCategory select[name=category_id]').append("<option value="+data[i]['category_id']+">"+data[i]['category_name']+"</option>");
      }
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
</script>
<script type="text/javascript">
	function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
	}

	//thanks: http://javascript.nwbox.com/cursor_position/
	function getSelectionStart(o) {
		if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
	}
</script>
