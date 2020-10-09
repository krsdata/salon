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
				<h1 class="h3 mb-3">Menu Management</h1>
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
								<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab-1">Service</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-2">Product</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-3">Category</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-4">Sub-Category</a>
									</li>
									<!-- <li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-5">Raw Material</a>
									</li> -->
										<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-6">Top 5 Services</a>
									</li>
									<!--<li class="nav-item">-->
									<!--	<a class="nav-link" data-toggle="tab" href="#tab-7">Transaction History</a>-->
									<!--</li>-->
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
										<!-- upload service -->
										<?php if($permission['service_create']==1){?>
											<div class="row">
												<div class="col-md-2">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddService"><i class="fas fa-fw fa-plus"></i> Add Service</button>
												</div>
												<div class="col-md-2">
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
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Upload</button>
															</div>
														</div>
													</form>
												</div>
													
											</div>
										<?php }?>
										<!-- end -->
										
										<table class="table table-striped datatables-basic" id="servicMenu" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Service Name</th>
													<th>Category Type</th>
													<th>Category</th>
													<th>SubCategory</th>
													<th>Price</th>
													<th>GST%</th>
													<th>GST(Rs.)</th>
													<th>Total</th>
													<th style="width: 14%;">Actions</th>
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
													<td><?=$service['category_type']?></td>
													<td><?=$service['category_name']?></td>
													<td><?=$service['sub_category_name']?></td>
													<td><?=$service['service_price_inr']?></td>
													<td><?=$service['service_gst_percentage']." %"?></td>
												  <td><?=round(number_format((($service['service_gst_percentage']*$service['service_price_inr'])/100),2))?></td>
													<td>
														<?=round($service['service_price_inr'] + (($service['service_gst_percentage']*$service['service_price_inr']/100)))?>
												</td>
													<td class="table-action">
														<?php if($permission['service_edit']==1){?>
														<button type="button" class="btn service-edit-btn" service_id="<?=$service['service_id']?>">
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
														<?php }else{?>
															<button type="button" class="btn service-edit-btn" service_id="<?=$service['service_id']?>" disabled>
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
															<?php }?>
														<?php if($permission['service_delete']==1){?>
														<button type="button" class="btn service-deactivate-btn" service_id="<?=$service['service_id']?>">
															<i class="align-middle" data-feather="trash-2"></i>
														</button>
														<?php }else{?>
															<button type="button" class="btn service-deactivate-btn" service_id="<?=$service['service_id']?>" disabled>
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
									<div class="tab-pane" id="tab-2" role="tabpanel">
										<!-- upload -->
										<?php if($permission['product_create']==1){?>
											<div class="row">
												<div class="col-md-2">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddOTC"><i class="fas fa-fw fa-plus"></i> Add Product</button>
												</div>
												<div class="col-md-2">
															<button class="btn btn-primary mb-2" onclick="exportTableToExcel('otcMenu','OTCMenu')"><i class="fa fa-file-export"></i>Product</button>
												</div>
												<div class="col-md-4">
													<!-- <button class="btn btn-primary DownloadCategories"><i class="fa fa-download"></i> Category</button> -->
													<button class="btn btn-primary DownloadSubCategories"><i class="fa fa-download"></i>Sub Category</button>
													<a class="btn btn-primary" href="<?=base_url()?>public/format/OTCUploadFormat.xlsx" download><i class="fa fa-download"></i> Format</a>
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
												
											</div>
										<?php }?>
										<!-- end -->
										<table class="table table-striped datatables-basic" id="otcMenu" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Item Name</th>
													<th>Category</th>
													<th>Sub-Category</th>
													<th>Inventory Type</th>
													<th>SKU Size</th>
													<th>BarCode</th>
													<th>Brand</th>
													<th>Gross Price</th>
													<th>GST%</th>
													<th>GST Amount</th>
													<th>MRP</th>
													<th style="text-align:center">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$index = 1;
													foreach ($all_otc as $otc):
												?>
												<tr>
													<td><?=$index;?></td>
													<td><?=$otc['service_name']?></td>
													<td><?=$otc['category_name']?></td>
													<td><?=$otc['sub_category_name']?></td>
													<td><?=$otc['inventory_type']?></td>
													<td><?=$otc['qty_per_item']." ".$otc['service_unit']?></td>
													<td><?=$otc['barcode']?></td>
													<td><?=$otc['service_brand']?></td>
													<td><?=$otc['service_price_inr']?></td>
													<td><?=$otc['service_gst_percentage']." %"?></td>
													<td><?=(($otc['service_gst_percentage']/100)*$otc['service_price_inr'])?></td>
													<td><?=round($otc['service_price_inr'] + (($otc['service_gst_percentage']/100)*$otc['service_price_inr']))?></td>
													<td class="table-action"  style="text-align:center">
														<?php if($permission['product_edit']==1){?>
														<button type="button" class="btn otc-edit-btn mb-1" otc_service_id="<?=$otc['service_id']?>">
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
														<?php }else{?>
															<button type="button" class="btn otc-edit-btn mb-1" otc_service_id="<?=$otc['service_id']?>" disabled>
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
														<?php }?>	
														<?php if($permission['product_delete']==1){?>
															<button type="button" class="btn otc-deactivate-btn" otc_service_id="<?=$otc['service_id']?>">
																<i class="align-middle" data-feather="trash-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn otc-deactivate-btn" otc_service_id="<?=$otc['service_id']?>" disabled>
																<i class="align-middle" data-feather="trash-2"></i>
															</button>
															<?php }?>
													</td>
												</tr>	
												<?php	
														$index = $index+1;	
													endforeach;
												?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="tab-3" role="tabpanel">
										<!-- upload -->
										<?php if($permission['category_create']==1){?>
											<div class="row">
												<div class="col-md-3">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddCategory"><i class="fas fa-fw fa-plus"></i> Add Category</button>
												</div>
												<div class="col-md-4">
													<a class="btn btn-primary" href="<?=base_url()?>public\format\CategoryUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
												</div>	
												<div class="col-md-4">
													<form action="#" id="UploadCategory">
														<div class="form-row">
															<div class="form-group col-md-6" style="overflow:hidden;">
																<input type="file" name="file" class="btn" />
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
															</div>
														</div>
													</form>
												</div>
											
											</div>
										<?php }?>
										<!-- end -->
										<table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Category Name</th>
													<th>Catgeory Type</th>
													<th>Description</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
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
												?>
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="tab-4" role="tabpanel">
										<!-- upload -->
										<?php if($permission['sub_category_create']==1){?>
											<div class="row">
												<div class="col-md-3">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddSubCategory"><i class="fas fa-fw fa-plus"></i> Add Sub-Category</button>
												</div>
													<div class="col-md-4">
													<button class="btn btn-primary DownloadCategories"><i class="fa fa-download"></i> Category</button>
													<a class="btn btn-primary" href="<?=base_url()?>public\format\SubCategoryUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
												</div>
												<div class="col-md-4">
													<form action="#" id="UploadSubCategory">
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
												
											</div>
										<?php }?>
										<!-- end -->
										<table class="table table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Sub-Category Name</th>
													<th>Category Name</th>
													<th>Description</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$index = 1;
													foreach ($sub_categories as $sub_category):
												?>
												<tr>
													<td><?=$index;?></td>
													<td><?=$sub_category['sub_category_name']?></td>
													<td><?=$sub_category['category_name']?></td>
													<td><?=$sub_category['sub_category_description']?></td>
													<td class="table-action">
														<?php if($permission['sub_category_edit']==1){?>
															<button type="button" class="btn sub-category-edit-btn" sub_category_id="<?=$sub_category['sub_category_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn sub-category-edit-btn" sub_category_id="<?=$sub_category['sub_category_id']?>" disabled>
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }?>
														<?php if($permission['sub_category_delete']==1){?>
															<button type="button" class="btn sub-category-deactivate-btn" sub_category_id="<?=$sub_category['sub_category_id']?>">
																<i class="align-middle" data-feather="trash-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn sub-category-deactivate-btn" sub_category_id="<?=$sub_category['sub_category_id']?>" disabled>
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
										<?php if($permission['raw_material_create']==1){?>
											<div class="row">
												<div class="col-md-3">
													<button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddRawMaterial"><i class="fas fa-fw fa-plus"></i> Add Raw Material</button>
												</div>
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
															</div>
															<div class="form-group col-md-6">
																<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
															</div>
														</div>
													</form>
												</div>
													
											</div>
										<?php }?>
										<!-- end -->
										<table class="table table-striped datatables-basic" id="rawMaterial" style="width: 100%;">
											<thead>
												<tr>
													<th>Sno.</th>
													<th>Item Name</th>
													<th>Brand</th>
													<th>Unit</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
													$index = 1;
													foreach ($raw_materials as $raw_material):
												?>
												<tr>
													<td><?=$index;?></td>
													<td><?=$raw_material['raw_material_name']?></td>
													<td><?=$raw_material['raw_material_brand']?></td>
													<td><?=$raw_material['raw_material_unit']?></td>
													<td class="table-action">
														<?php if($permission['raw_material_edit']==1){?>
															<button type="button" class="btn raw-material-edit-btn" raw_material_category_id="<?=$raw_material['raw_material_category_id']?>">
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
														<?php }else{?>
															<button type="button" class="btn raw-material-edit-btn" raw_material_category_id="<?=$raw_material['raw_material_category_id']?>" disabled>
																<i class="align-middle" data-feather="edit-2"></i>
															</button>
															<?php }?>
															<?php if($permission['raw_material_delete']==1){?>
																<button type="button" class="btn raw-material-deactivate-btn" raw_material_category_id="<?=$raw_material['raw_material_category_id']?>">
																	<i class="align-middle" data-feather="trash-2"></i>
																</button>
														<?php }else{?>
															<button type="button" class="btn raw-material-deactivate-btn" raw_material_category_id="<?=$raw_material['raw_material_category_id']?>" disabled>
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
									<div class="tab-pane fade" id="tab-6" role="tabpanel">
											<div class="row">
											<?php
											if($recommend_status['service_recommended_status'] == 0){
											?>  
											<div class="col-md-6">
													<label style="float:right">
															<input type="radio" name="redm_option" value="database" float="right" checked>&ensp;<span style="font-size: 15px;background:#2c7be5;;color:white">Auto fetch-Database</span>
													</label>
											</div>
											<!-- <div class="col-md-6">
													<label>
															<input type="radio" name="redm_option" value="manual" >&ensp;<span style="font-size: 15px;background:#2c7be5;;color:white">Manual Entry</span>
													</label>
											</div> -->
											<?php
											}
											if($recommend_status['service_recommended_status'] == 1){
											?>
															<div class="col-md-6">
																	<label style="float:right">
																			<input type="radio" name="redm_option" value="database" float="right">&ensp;<span style="font-size: 15px;background:#2c7be5;;color:white">Auto fetch-Database</span>
																	</label>
															</div>
															<div class="col-md-6">
																	<label>
																			<input type="radio" name="redm_option" value="manual" checked>&ensp;<span style="font-size: 15px;background:#2c7be5;;color:white">Manual Entry</span>
																	</label>
															</div>
											<?php
											}
											?>      
											</div>
											<div class="row">
													<div class="col-md-12">
															<table class="table table-striped datatables-basic" id="servicMenu" style="width: 100%;">
																			<thead>
																					<tr>
																							<th>Sno.</th>
																							<th>Category</th>
																							<th>SubCategory</th>
																							<th>Service Name</th>
																							<th>Price</th>
																							<!-- <th style="width: 14%;">Actions</th> -->
																					</tr>
																			</thead>
																			<tbody>
																					<?php
																					if($recommend_services_status == 1)
																					{   $i=1;
																							foreach($recommend_services as $recommend_service)
																							{
																									
																							?>
																							<tr>
																									<td><?php echo $i;?></td>
																									<td><?=$recommend_service['Category']?></td>
																									<td><?=$recommend_service['Sub-Category']?></td>
																									<td><?=$recommend_service['service_name']?></td>
																									<td><?=$recommend_service['service_price_inr']?></td>
																									<!-- <td class="table-action">
																											<button type="button" class="" service_id="<?=$recommend_service['service_id']?>">
																													<i class="align-middle" data-feather="edit-2"></i>
																											</button>
																											<button type="button" class="" service_id="<?=$recommend_service['service_id']?>">
																													<i class="align-middle" data-feather="trash-2"></i>
																											</button>
																									</td> -->
																							</tr>
																							<?php
																									$i=$i+1;
																							}
																					}
																					?>
																			</tbody>
															</table>
													</div>
											</div>                                        
									</div>
									<div class="tab-pane fade" id="tab-7" role="tabpanel">
										<!-- upload -->
										<div class="row">
											
											<div class="col-md-3">
												<a class="btn btn-primary" href="<?=base_url()?>public\format\TransactionHistoryFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
											</div>
											<div class="col-md-4">
												<form action="#" id="UploadTransactionHistory">
													<div class="form-row">
														<div class="form-group col-md-6" style="overflow:hidden;">
															<input type="file" name="file" class="btn" />
														</div>
														<div class="form-group col-md-6">
															<button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
														</div>
													</div>
												</form>
											</div>
												
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
															<input type="text" class="form-control" placeholder="Service Name" name="service_name" autofocus>
														</div>
													
														<div class="form-group col-md-6">
															<label>Service Gross Price</label>
															<input type="text" min="0" class="form-control" placeholder="Service Gross Price" name="service_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label>Service GST Percentage</label>
															<input type="number" step="0.5" min="0" class="form-control" placeholder="% Value Only" name="service_gst_percentage">
														</div>
														<div class="form-group col-md-6">
															<label>Service Estimate Time <small>(mins)</small></label>
															<input type="number" class="form-control" placeholder="Enter time in Minutes" name="service_est_time">
														</div>
                          </div>
                          
													<div class="row">
                            <div class="form-group col-md-6">
															<label>Category Type</label>
															<select class="form-control" name="service_category_category_type" id="Service-Category-Type" onchange="GetService()">
                                <option value=""></option>
                                <option value="Service">Service</option>
                                <!--<option value="Products">Product</option>-->
															</select>
														</div>
														<div class="form-group col-md-6">
															<label>Category</label>
															<select class="form-control" name="service_category_id" id="Service-Category-Id">
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
															<textarea class="form-control" rows="2" placeholder="Service Description" name="service_description"></textarea>
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
														<input type="text" class="form-control" placeholder="Sub Category Name" name="sub_category_name">
                          </div>
                          <div class="form-group">
                            <label>Category Type</label>
                            <select name="sub_category_category_type" id="sub_category_category_type" class="form-control" onchange="GetCategory()">
                              <option value="">--Choose Category Type--</option>
                              <option value="Service">Service</option>
                              <option value="Products">Products</option>
                            </select>
                          </div>
													<div class="form-group">
														<label>Category</label>
														<select name="sub_category_category_id" class="form-control">
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
														<textarea class="form-control" rows="2" placeholder="Sub Category Description" name="sub_category_description"></textarea>
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
                            <select name="category_type" class="form-control" required>
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
                            <select name="category_type" class="form-control" required>
                            <option value="">--Select Category Type--</option>
                            <option value="Service">Service</option>
                            <option value="Products">Products</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Category</label>
                            <select name="sub_category_category_id" class="form-control">
                              <option value="" selected>Choose Category</option>
                              <?php
                                foreach ($categories as $category) {
                                  echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
                                }
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
                                          <option value="Pcs">Pcs</option>
                                          <!--<option value="Ltr">ltr</option>-->
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
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
								<div class="form-group col-md-3">
								<label>Inventory Type</label>
								<select class="form-control" name="otc_inventory_type" id="otc_inventory_type" required>
										<option value="" selected disabled>Select</option>
										<option value="Retail Product">Retail Product</option>
										<option value="Raw Material">Raw Material</option>
								</select>
								</div>
														
							</div>
							<div class="row">
									<div class="form-group col-md-3">
										<label>Product Name</label>
										<input type="text" class="form-control" placeholder="Item Name" name="otc_item_name" id="SearchServiceByName">
										
									</div>
									<div class="form-group col-md-3">
										<label>Category Type</label>
										<select class="form-control" name="category_type" id='Otc-Category-Type' onchange="GetOtcCategory()">
											<option value="" selected></option>
											<!--<option value="Service">Service</option>-->
											<option value="Products">Product</option>
										</select>
									</div>
									<div class="form-group col-md-3">
										<label>Category</label>
										<select class="form-control" name="category_id" id="OTC-Category-Id">
											<option value="" selected></option>
											<?php
											foreach ($categories as $category) {
												echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
											}
											?>
										</select>
									</div>   
									<div class="form-group col-md-3">
										<label>Sub-Category</label>
										<select class="form-control" name="otc_sub_category_id" id="OTC-Sub-Category-Id">
										</select>
									</div>                      
								</div>
							<div class="row">
								<div class="form-group col-md-3">
									<label>Item Name</label>
									<input type="text" class="form-control" placeholder="Item Name" name="item_name" id="ServiceName">		
								</div>
								<div class="form-group col-md-3">
									<label>Brand</label>
									<input class="form-control" placeholder="Brand Name" name="otc_brand" id="otc_brand">
								</div> 
								<div class="form-group col-md-3">
									<label>BarCode</label>
									<input class="form-control" placeholder="Barcode" name="otc_barcode" id="otc_barcode">
								</div>                       
							
							</div>
							<div class="form-row">
								<div class="form-group col-md-12">
									<table class="table table-hover" id="InventoryAddProducts">
										<tr>  
											<td>
												<div class="form-group">
													<label>SKU Size 1</label>
													<input type="number" class="form-control" id="q1" placeholder="Quantity Per Item" name="qty_per_item[]">
												</div>
											</td>
											<td>		
												<div class="form-group">
													<label>Unit</label>
													<select class="form-control" id="u1" name="otc_unit[]">
														<option value="mL">mL</option>
														<option value="gms">gms</option>
														<option value="Pcs">Pcs</option>
														<!--<option value="Ltr">ltr</option>-->
													</select>
												</div>                           
											</td>
											<td>	
												<div class="form-group">
													<label>Product Gross Price</label>
													<input type="text" class="form-control" id="price" placeholder="OTC Gross Price" name="otc_price_inr[]" onkeypress="return validateFloatKeyPress(this,event);">
												</div>
											</td>
											<td>	
												<div class="form-group">
													<label>GST</label>
													<input type="number" class="form-control" id="gst" placeholder="Percentage" name="otc_gst_percentage[]" onkeyup="toTotalstatic()">
												</div>                            
											</td>
											<td>	
												<div class="form-group">
													<label>Total</label>
													<input type="number" class="form-control" id="total" placeholder="Total" name="otc_total[]">
												</div>
											</td>
										</tr>	
									</table>
								</div>						
								<div class="row">
									<div class="col-md-12">
										<button type="button" class="btn btn-success" id="AddRow">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
										<button type="button" class="btn btn-danger" id="DeleteRow">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>				
										<button type="submit" class="btn btn-primary">Submit</button>
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
						<div class="modal" id="ModalEditOTC" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
														<div class="form-group col-md-3">
															<label>Item Name</label>
															<input type="text" class="form-control" placeholder="Item Name" name="otc_item_name">
														</div>
														<div class="form-group col-md-3">
															<label>BarCode</label>
															<input type="text" class="form-control" placeholder="Barcode" name="otc_barcode">
														</div>
														<div class="form-group col-md-3">
															<label>Inventory Type</label>
															<select class="form-control" name="otc_inventory_type" id="otc_inventory_type">
															<!-- <option value="" selected disabled>Select</option> -->
															<option value="Retail Product">Retail Product</option>
															<option value="Raw Material">Raw Material</option>
															</select>
														</div>
														<div class="form-group col-md-3">
															<label>Unit</label>
															<select class="form-control" name="otc_unit">
																<option value="mL">mL</option>
																<option value="gms">gms</option>
																<option value="Pcs">Pcs</option>
																<!--<option value="Ltr">ltr</option>-->
															</select>
														</div>
													</div>
													<div class="row">
														<!-- <div class="form-group col-md-3">
															<label>Category Type</label>
															<select class="form-control" name="category_type" id='Otc-Category-Type' onchange="GetOtcCategory()">
																<option value="" selected></option>
																<option value="Service">Service</option>
																<option value="Products">Product</option>
															</select>
														</div> -->
														<div class="form-group col-md-3">
															<label>Category</label>
															<select class="form-control" name="category_id" id="Category-Id" disabled>
																<option value="" selected></option>
																<?php
																foreach ($otccategories as $category_otc) {
																	echo "<option value=".$category_otc['category_id'].">".$category_otc['category_name']."</option>";
																}
																?>
															</select>
														</div>   
														<div class="form-group col-md-3">
															<label>Sub-Category</label>
															<select class="form-control" name="otc_sub_category_id" id="Sub-Category-Id">
																<?php
																	foreach ($otc_sub_categories as $sub_category_otc) {
																		echo "<option value=".$sub_category_otc['sub_category_id'].">".$sub_category_otc['sub_category_name']."</option>";
																	}
																?>
															</select>
														</div> 
														
														
													</div>
													<div class="row">
													<div class="form-group col-md-3">
															<label>Brand</label>
															<input class="form-control" placeholder="Brand Name" name="otc_brand">
														</div>
													<div class="form-group col-md-3">
															<label>OTC Gross Price</label>
															<input type="text" class="form-control" placeholder="OTC Gross Price" name="otc_price_inr" onkeypress="return validateFloatKeyPress(this,event);">
														</div>
													<div class="form-group col-md-3">
														<label>OTC GST Percentage</label>
														<input class="form-control" placeholder="% Value Only" name="otc_gst_percentage">
													</div>
													<div class="form-group col-md-3">
														<label>SKU Size</label>
														<input class="form-control" placeholder="SKU Size" name="sku_size">
													</div>
													<div class="form-group">
													<input class="form-control" type="hidden" name="otc_service_id" readonly="true">
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
															<option value="Pcs">Pcs</option>
															<!--<option value="Ltr">ltr</option>-->
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
															<option value="Pcs">Pcs</option>
															<!--<option value="Ltr">ltr</option>-->
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
						<!-----END------>
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
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddCategory").modal('hide');
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

		$("#AddSubCategory").validate({
	  	errorElement: "div",
	    rules: {
	        "sub_category_name" : {
            required : true,
            maxlength : 100
	        },
	        "sub_category_category_id" : {
	        	required : true
	        }  
	    },
	    submitHandler: function(form) {
				var formData = $("#AddSubCategory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddSubCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddSubCategory").modal('hide');
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
	    submitHandler: function(form) {
				var formData = $("#AddService").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddService").modal('hide');
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

		$("#AddOTCCategory").validate({
      errorElement: "div",
      rules: {
          "item_name" : {
            required : true,
            maxlength : 100
          },
          "otc_brand" :{
            required : true,
            maxlength : 100
          },
          "otc_unit[]" : {
            required : true
          },
          "otc_sub_category_id" : {
            required : true
          },
          "otc_price_inr[]" : {
            required : true
          },
          "otc_gst_percentage[]" : {
            required : true,
            digits : true
          },
        
      },
      submitHandler: function(form) {
        var formData = $("#AddOTCCategory").serialize(); 
        $.ajax({
            url: "<?=base_url()?>BusinessAdmin/AddOTCService",
            data: formData,
            type: "POST",
            // crossDomain: true,
            cache: false,
            // dataType : "json",
            success: function(data) {
              if(data.success == 'true'){ 
                $("#ModalAddOTC").modal('hide');
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
		        url: "<?=base_url()?>BusinessAdmin/AddRawMaterial",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddRawMaterial").modal('hide');
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
		        url: "<?=base_url()?>BusinessAdmin/EditCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditCategory").modal('hide');
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

	$("#EditSubCategory").validate({
	  	errorElement: "div",
	    rules: {
      	"sub_category_name" : {
      	  required : true,
        	maxlength : 100
        },
        "sub_category_category_id" : {
        	required : true
        }
      },
	    submitHandler: function(form) {
				var formData = $("#EditSubCategory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditSubCategory",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditSubCategory").modal('hide');
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
		        url: "<?=base_url()?>BusinessAdmin/EditService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditService").modal('hide');
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
		        url: "<?=base_url()?>BusinessAdmin/EditOTCService",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditOTC").modal('hide');
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
		        url: "<?=base_url()?>BusinessAdmin/EditRawMaterial",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditRawMaterial").modal('hide');
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

	$(document).on('click','.category-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        category_id : $(this).attr('category_id')
      };
      $.getJSON("<?=base_url()?>BusinessAdmin/GetCategory", parameters)
      .done(function(data, textStatus, jqXHR) { 
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
      $.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategory", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditSubCategory input[name=sub_category_name]").attr('value',data.sub_category_name);
        $("#EditSubCategory textarea[name=sub_category_description]").val(data.sub_category_description);
        $("#EditSubCategory select[name=sub_category_category_id]").val(data.sub_category_category_id);
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
      var parameters = {
        service_id : $(this).attr('service_id')
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/GetService", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditService input[name=service_name]").attr('value',data.service_name);
        $("#EditService input[name=service_price_inr]").attr('value',data.service_price_inr);
        $("#EditService input[name=service_gst_percentage]").attr('value',data.service_gst_percentage);
        $("#EditService textarea[name=service_description]").val(data.service_description);
        $("#EditService input[name=service_est_time]").attr('value',data.service_est_time);
        $("#EditService select[name=service_sub_category_id]").val(data.service_sub_category_id);
        $("#EditService input[name=service_id]").attr('value',data.service_id);
       
        $("#ModalEditService").modal('show');
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

      $.getJSON("<?=base_url()?>BusinessAdmin/GetServiceOtc", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditOTCCategory input[name=otc_item_name]").attr('value',data.service_name);
        $("#EditOTCCategory input[name=otc_brand]").attr('value',data.service_brand);
        $("#EditOTCCategory select[name=otc_unit]").attr('value',data.service_unit);
        $("#EditOTCCategory input[name=otc_service_id]").attr('value',data.service_id);
        $("#EditOTCCategory input[name=otc_price_inr]").attr('value',data.service_price_inr);
        $("#EditOTCCategory input[name=otc_gst_percentage]").attr('value',data.service_gst_percentage);
				$("#EditOTCCategory input[name=sku_size]").attr('value',data.qty_per_item);
				$("#EditOTCCategory input[name=otc_barcode]").attr('value',data.barcode);
				$("#EditOTCCategory input[select=otc_inventory_type]").val(data.inventory_type);//select
	    	$("#EditOTCCategory select[name=category_id]").val(data.category_id);
        // 		$("#EditOTCCategory select[name=otc_sub_category_id]").val(data.service_sub_category_id);
        $("#EditOTCCategory select[name=otc_sub_category_id]").append('<option value='+data.sub_category_id+' selected>'+data.sub_category_name+'</option>');
       
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

      $.getJSON("<?=base_url()?>BusinessAdmin/GetRawMaterial", parameters)
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
        url: "<?=base_url()?>BusinessAdmin/DeactivateService",
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
          else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
        url: "<?=base_url()?>BusinessAdmin/DeactivateCategory",
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
          else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
        url: "<?=base_url()?>BusinessAdmin/DeactivateSubCategory",
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
          else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
        url: "<?=base_url()?>BusinessAdmin/DeactivateService",
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
          else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
        url: "<?=base_url()?>BusinessAdmin/DeactivateRawMaterial",
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
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        }
			});
    });


    $("#Service-Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#Service-Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#OTC-Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
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

	$("#Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
  	
  	$(document).on('click',".DownloadCategories",function(event){
  		event.preventDefault();
  		$(this).blur();
  		$.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetCategoriesPublic",
        type: "GET",
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
  		event.preventDefault();
  		$(this).blur();
  		$.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetSubCategoriesPublic",
        type: "GET",
        // crossDomain: true,
				cache: false,
        // dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
						JSONToCSVConvertor(data.message, "SubCategories", true);
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
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/BulkUploadServices",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
        url: "<?=base_url();?>BusinessAdmin/BulkUploadOTC",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadCategory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/BulkUploadCategory",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadSubCategory").submit(function(e){
    	event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/BulkUploadSubCategory",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

    $("#UploadRawMaterial").submit(function(e){
    	event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/BulkUploadRawMaterial",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.href = "<?=base_url()?>BusinessAdmin/MenuManagement";
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
						})
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });

	$("#UploadTransactionHistory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/BulkUploadTransaction",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
							$("#SuccessModalMessage").html("").html(data.message);
						}).on('hidden.bs.modal', function (e) {
								window.location.reload();
						});
          }
         	else if (data.success == 'false'){                   
      	    $('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e){
							$("#ErrorModalMessage").html("").html(data.message);
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
<script>
  function GetCategory()
  {
    var parameters = {category_type : $('#sub_category_category_type').val()};
  
    $.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCategoryType",parameters)
    .done(function(data,textStatus,jqXHR){
      $('#AddSubCategory select[name=sub_category_category_id]').html("");
      for (var i=0;i<data.length;i++)
      {
        $('#AddSubCategory select[name=sub_category_category_id]').append("<option value="+data[i]['category_id']+">"+data[i]['category_name']+"</option>");
      }
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function GetService()
  {
    var parameters = {category_type : $('#Service-Category-Type').val()};

    $.getJSON("<?=base_url()?>BusinessAdmin/GetCategoryByCategoryType",parameters)
    .done(function(data,textStatus,jqXHR){
      $('#AddService select[name=service_category_id]').html("");
      $('#AddService select[name=service_sub_category_id]').html("");
      $('#AddService select[name=service_category_id]').append("<option value=''></option>");
      for(var i=0;i<data.length;i++)
      {
        $('#AddService select[name=service_category_id]').append("<option value="+data[i]['category_id']+">"+data[i]['category_name']+"</option>");
      }
    })
    .fail(function(jqXHR,textStatus,errorThrown){
      console.log(errorThrown.toString());
    })
  }
  function GetOtcCategory()
  {
    var parameters = {category_type : $('#Otc-Category-Type').val()};

    $.getJSON("<?=base_url()?>BusinessAdmin/GetCategoryByCategoryType",parameters)
    .done(function(data,textStatus,jqXHR){
      $('#AddOTCCategory select[name=category_id]').html("");
      $('#AddOTCCategory select[name=otc_sub_category_id]').html("");
      $('#AddOTCCategory select[name=category_id]').append("<option value=''></option>");
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


	$("#AddRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#InventoryAddProducts tr").length;
    //   alert("dfhbgkj");
      rowno = rowno+1;
    
		if(rowno < 9){
		$("#InventoryAddProducts tr:last").after("<tr><td><div class=\"form-group\"><label>SKU Size " +rowno+ "</label><input type=\"number\" class=\"form-control\" name=\"qty_per_item[]\" required /></div></td><td><div class=\"form-group\"><label>Unit</label><select class=\"form-control\"  name=\"otc_unit[]\"><option value=\"mL\">mL</option><option value=\"gms\">gms</option><option value=\"Pcs\">Pcs</option><!--<option value=\"Ltr\">Ltr</option>--></select></div></td><td><div class=\"form-group\"><label>Product Gross Price</label><input type=\"text\" class=\"form-control\" id=\"price"+rowno+"\" name=\"otc_price_inr[]\"/></div></td><td><div class=\"form-group\"><label>GST</label><input type=\"number\" id=\"gst"+rowno+"\" onkeyup=\"toTotal("+rowno+")\" class=\"form-control\" name=\"otc_gst_percentage[]\" required /></div></td><td><div class=\"form-group\"><label>Total</label><input type=\"number\" id=\"total"+rowno+"\" class=\"form-control\" name=\"otc_total[]\" required /></div></tr>");	
		}
		else{
			alert("Maximum Number of sizes Defined");
		}
    });

    $("#DeleteRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#InventoryAddProducts tr").length;
      if(rowno > 1){
      	$('#InventoryAddProducts tr:last').remove();
    	}
    });
</script>
<script type="text/javascript">
function toTotal(no){
	var t2 = parseFloat(document.getElementById("price"+no).value);
	var t1 = parseFloat(document.getElementById("gst"+no).value);
	var t3 = t2+(t2 * (t1/100));
	//   alert(t2);
document.getElementById("total"+no).value = t3;
}
function toTotalstatic(){
	var t2 = parseFloat(document.getElementById("price").value);
	var t1 = parseFloat(document.getElementById("gst").value);
	var t3 = t2+(t2 * (t1/100));
	//   alert(t2);
document.getElementById("total").value = t3;
}

 //functionality for getting the dynamic input data
 $("#SearchServiceByName").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: SearchServiceByName,
      templates: {
        empty: "No Services Found!",
        suggestion: _.template("<p class='service_search'><%- brandname %>, (<%- product_type %>), <%- mrp %>,<%- gst %>,<%- sku_size %> </p>")
      }
    });
       
    var to_fill = "";

    $("#SearchServiceByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchServiceByName";
      to_fill = suggestion.service_name;
      setVals(loc,to_fill,suggestion);
    });

    $("#SearchServiceByName").blur(function(){
      $("#SearchServiceByName").val(to_fill);
      to_fill = "";
    });

    function SearchServiceByName(query, cb){
		var inventory = document.getElementById("otc_inventory_type").value;
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetProductDataDetails",
        data: parameters,
        type: "GET",
        crossDomain: true,
				cache: false,
        dataType : "json",
        global : false,
    		success: function(data) {
         	cb(data.message);
        },
        error: function(data){
					console.log("Some error occured!");
        }
			});
    }  

    function setVals(element,fill,suggestion){
      $(element).attr('value',fill);
      $(element).val(fill);	
	  document.getElementById('ServiceName').value = suggestion.item_name;
      document.getElementById('otc_barcode').value = suggestion.barcode;
	  document.getElementById('otc_brand').value = suggestion.brandname;
	  document.getElementById('q1').value = suggestion.sku_size;
	  document.getElementById('u1').value = suggestion.unit;
	  document.getElementById('price').value = suggestion.mrp;
	  document.getElementById('gst').value = suggestion.gst;
	  document.getElementById('total').value = parseInt(suggestion.mrp)+(suggestion.mrp*(suggestion.gst/100));
		for(var i=0;i<=suggestion.length;i++){
			var rowno = $("#InventoryAddProducts tr").length;
				//   alert("dfhbgkj");
			rowno = rowno+1;
		
			if(rowno < 9){
			$("#InventoryAddProducts tr:last").after("<tr><td><div class=\"form-group\"><label>SKU Size " +rowno+ "</label><input type=\"number\" class=\"form-control\" name=\"qty_per_item[]\" required /></div></td><td><div class=\"form-group\"><label>Unit</label><select class=\"form-control\"  name=\"otc_unit[]\"><option value=\"mL\">mL</option><option value=\"gms\">gms</option><option value=\"Pcs\">Pcs</option><!--<option value=\"Ltr\">Ltr</option>--></select></div></td><td><div class=\"form-group\"><label>Product Gross Price</label><input type=\"text\" class=\"form-control\" id=\"price"+rowno+"\" name=\"otc_price_inr[]\"/></div></td><td><div class=\"form-group\"><label>GST</label><input type=\"number\" id=\"gst"+rowno+"\" onkeyup=\"toTotal("+rowno+")\" class=\"form-control\" name=\"otc_gst_percentage[]\" required /></div></td><td><div class=\"form-group\"><label>Total</label><input type=\"number\" id=\"total"+rowno+"\" class=\"form-control\" name=\"otc_total[]\" required /></div></tr>");	
			}
			else{
				alert("Maximum Number of sizes Defined");
			}
		}
   
    }

	
</script>
