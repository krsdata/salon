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
				<h1 class="h3 mb-3">Inventory Management</h1>
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
											<a class="nav-link active" data-toggle="tab" href="#tab-1">Add Stock</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-2">Stock Transfer</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-3">Stock Level</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-4">Incoming Stock</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-5">Outgoing Stock</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#tab-6">Inventory Health</a>
										</li>
									</ul>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="tab-pane show active" id="tab-1" role="tabpanel">
											<div class="row">
												<div class="col-md-12">
													<div class="card">
														<div class="card-header">
															<h4>Stock Entry</h4>
														</div>
														<div class="card-body">
															<form action="#" id="AddProduct" method="POST">
																<div class="form-row">
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="text" class="form-control" name="invoice_number" placeholder="Invoice No.">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="date" class="form-control" value="<?=date('Y-m-d');?>" name="invoice_date"  placeholder="Entry Date">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="text" class="form-control" name="invoice_amount" min="0" placeholder="Invoice Value">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="text" class="form-control" name="invoice_tax" min="0"  placeholder="Extra Freight Charges">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="source_type" class="form-control">
																					<option value="" disabled="disabled" selected>Select Source Type</option>
																					<option value="warehouse">Warehouse</option>
																					<option value="branch">Branch</option>
																					<option value="vendor">Vendor</option>
																					<option value="return">Sales return</option>
																				</select>
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="source_name" class="form-control">
																				
																				</select>
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="invoice_type" class="form-control">
																				<option value="" disabled="disabled" selected>Select Invoice Type</option>
																					<option value="tax">Tax Invoice</option>
																					<option value="lumpsum">Lumpsum</option>
																					<option value="challan">Delivery Challan</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group col-md-3">
																			<input type="text" id="searchProductByName" class="form-control" placeholder="Search Product By Name or Barcode">
																		</div>
																	</div>
																	<div id="productTable">
																		<table id="addProductTable" class="table table-hover table-bordered mb-1">
																			<tbody>
																				<tr>
																					<td>1</td>
																					<td>
																						<div class="form-group">
																							<label>Product Name</label>
																							<input type="text" class="form-control searchProductByName" name="product_name[]" readonly>
																							<input type="hidden" class="product_id" name="product_id[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Type</label>
																							<input type="text" class="form-control product_type" name="product_type[]" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Barcode</label>
																							<input type="text" class="form-control product_barcode" name="product_barcode[]" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>SKU Size</label>
																							<input type="text" class="form-control sku_size" name="sku_size[]" temp="service_price_inr" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Qty</label>
																							<input type="text" class="form-control" name="product_qty[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Cost/Unit(<small>before tax</small>)</label>
																							<input type="text" class="form-control" name="product_price[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>GST %</label>
																							<input type="text" class="form-control" name="product_gst[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>MRP</label>
																							<input type="text" class="form-control mrp" name="product_mrp[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Expiry</label>
																							<input type="date" class="form-control" value="<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>" name="product_exp_date[]" temp="Count">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowProductTable">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowProductTable">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>										
																</div>
																<div class="form-row mt-2">
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-12">
																				<textarea class="form-control" name="note" placeholder="Notes :-"></textarea>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-4">
																				<input type="number" name="amount_paid" class="form-control" min="0" placeholder="Enter Amount">
																			</div>
																			<div class="form-group col-md-4">
																				<select name="payment_mode" class="form-control">
																				<option value="" disabled="disabled" selected>Payment Mode</option>
																					<option value="cash">Cash</option>
																					<option value="credit_card">Credit Card</option>
																					<option value="debit_card">Debit Card</option>
																					<option value="phonepe">Phonepe</option>
																					<option value="google_pay">Google Pay</option>
																					<option value="paytm">Paytm</option>
																					<option value="bank">Bank A/C</option>
																				</select>
																			</div>
																			<div class="form-group col-md-4">
																				<input type="text name" class="form-control" name="payment_status" placeholder="Payment Status" readonly>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group ml-2">
																		<button type="submit" value="" class="btn btn-primary ">Submit</button>
																	</div>
																</div>
															</form>
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
															<h4>Stock Transfer</h4>
														</div>
														<div class="card-body">
															<form action="#" id="TransProduct" method="POST">
																<div class="form-row">
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="text" class="form-control" name="invoice_number" placeholder="Tranf. Order">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="date" class="form-control" value="<?=date('Y-m-d');?>" name="invoice_date"  placeholder="Entry Date">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																			<input type="text" class="form-control" name="invoice_amount" min="0" placeholder="Invoice Value">
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																				<input type="text" class="form-control" name="invoice_tax" min="0"  placeholder="Extra Freight Charges">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="destination_type" class="form-control">
																					<option value="" disabled="disabled" selected>Select Destination Type</option>
																					<option value="warehouse">Warehouse</option>
																					<option value="branch">Branch</option>
																					<option value="vendor">Vendor</option>
																					<option value="return">Sales return</option>
																				</select>
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="destination_name" class="form-control">
																				
																				</select>
																			</div>
																		</div>
																		<div class="row">
																			<div class="form-group col-md-4">
																				<select name="invoice_type" class="form-control">
																				<option value="" disabled="disabled" selected>Select Invoice Type</option>
																					<option value="tax">Tax Invoice</option>
																					<option value="lumpsum">Lumpsum</option>
																					<option value="challan">Delivery Challan</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group col-md-3">
																			<input type="text" id="searchProductTransferByName" class="form-control" placeholder="Search Product By Name or Barcode">
																		</div>
																	</div>
																	<div id="transferTable">
																		<table id="transProductTable" class="table table-hover table-bordered mb-1">
																			<tbody>
																				<tr>
																					<td>1</td>
																					<td>
																						<div class="form-group">
																							<label>Product Name</label>
																							<input type="text" class="form-control searchProductByName" name="product_name[]" readonly>
																							<input type="hidden" class="product_id" name="product_id[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Type</label>
																							<input type="text" class="form-control product_type" name="product_type[]" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Barcode</label>
																							<input type="text" class="form-control product_barcode" name="product_barcode[]" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>SKU Size</label>
																							<input type="text" class="form-control sku_size" name="sku_size[]" temp="service_price_inr" readonly>
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Qty</label>
																							<input type="text" class="form-control" name="product_qty[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Cost/Unit(<small>before tax</small>)</label>
																							<input type="text" class="form-control" name="product_price[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>GST %</label>
																							<input type="text" class="form-control" name="product_gst[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>MRP</label>
																							<input type="text" class="form-control mrp" name="product_mrp[]">
																						</div>
																					</td>
																					<td>
																						<div class="form-group">
																							<label>Expiry</label>
																							<input type="date" class="form-control" value="<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>" name="product_exp_date[]" temp="Count">
																						</div>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																		<button type="button" class="btn btn-success" id="AddRowProductTransTable">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
																		<button type="button" class="btn btn-danger" id="DeleteRowProductTransTable">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
																	</div>										
																</div>
																<div class="form-row mt-2">
																	<div class="col-md-6">
																		<div class="row">
																			<div class="form-group col-md-12">
																				<textarea class="form-control" name="note" placeholder="Notes :-"></textarea>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="row">
																			
																			<div class="form-group col-md-4">
																				<input type="number" name="amount_paid" class="form-control" min="0" placeholder="Enter Amount">
																			</div>
																			
																			<div class="form-group col-md-4">
																				<select name="payment_mode" class="form-control">
																					<option value="" disabled="disabled" selected>Payment Mode</option>
																					<option value="cash">Cash</option>
																					<option value="credit_card">Credit Card</option>
																					<option value="debit_card">Debit Card</option>
																					<option value="phonepe">Phonepe</option>
																					<option value="google_pay">Google Pay</option>
																					<option value="paytm">Paytm</option>
																					<option value="bank">Bank A/C</option>
																				</select>
																			</div>
																			<div class="form-group col-md-4">
																				<input type="text name" class="form-control" name="payment_status" placeholder="Payment Status" readonly>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-row">
																	<div class="form-group ml-2">
																		<button type="submit" value="" class="btn btn-primary ">Submit</button>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-3" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<!-- <form action="#" class="form-inlne" method="POST">
														<div class="form-row">
															<div class="form-group col-md-2">
																<input type="date" class="form-control" name="invoice_number" value="<?=date('Y-m-d');?>">
															</div>										
															<div class="form-group col-md-2">
																<select name="" class="form-control" required>
																<option selected="selected" disabled>Select type</option>
																	<option value="">Warehouse</option>
																	<option value="">Branch</option>
																</select>
															</div>
															<div class="form-group col-md-3">
																<select name="" class="form-control" required>
																	<option value="">Warehouse</option>
																	<option value="">Branch</option>
																</select>
															</div>
															<div class="form-group col-md-3">
																<select name="" class="form-control" required>
																	<option selected="selected" disabled>Stock Category</option>
																	<option value="">All</option>
																	<option value="">Regular</option>
																	<option value="">Slow moving</option>
																	<option value="">Dead</option>
																</select>
															</div>
															<div class="form-group col-md-2">
																<button type="submit" value="" class="btn btn-primary ">Submit</button>
															</div>
														</div>
													</form> -->
													<div class="row">
														<div class="col-md-10">
															<h3>Available Stock</h3>
														</div>
														<div class="col-md-2">
														<button class="btn btn-primary" onclick="exportTableToExcel('availableStock','Product Stock')"><i class="fa fa-file-export"></i>Download</button>
														</div>
													</div>
												</div>
												<div class="card-body">
													<table class="table table-hover datatables-basic" style="width: 100%;" id="availableStock">
														<thead>
															<th>Sr. No.</th>
															<th>Product Name</th>
															<th>Type</th>
															<th>Barcode</th>
															<th>SKU size</th>
															<th>Total Stock</th>
															<th>Stock in Unit</th>
															<th>Last Updated</th>
															<th>Location</th>
															<th>Action</th>
														</thead>
														<tbody>
															<?php $count=1; foreach($stock as $stock){ ?>
																<tr>
																	<td><?=$count?></td>
																	<td><?=$stock['service_name'];?></td>
																	<td><?=$stock['inventory_type'];?></td>
																	<td><?=$stock['barcode'];?></td>
																	<td><?=$stock['qty_per_item'].' '.$stock['service_unit'];?></td>
																	<td><?=$stock['total_stock'];?></td>
																	<td><?=$stock['stock_in_unit'];?></td>
																	<td><?=$stock['updated_on'];?></td>
																	<td><?=$stock['business_outlet_name'];?></td>
																	<td>
																		<button class="btn btn-primary EditInventory" data-toggle="modal" id="" data-target="#ModalEditInventory" service_id="<?=$stock['service_id'];?>"><i class="fa fa-pen"></i></button>
																	</td>
																</tr>
															<?php $count++; }?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-4" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<h4>Incoming Stock</h4>
												</div>
												<div class="card-body">
													<table class="table table-hover datatables-basic" style="width: 100%;">
														<thead>
															<th>Sr. No.</th>
															<th>Txn Id</th>
															<th>Product Name</th>
															<th>Type</th>
															<th>Barcode</th>
															<th>SKU size</th>
															<th>Product Qty</th>
															<th>MRP</th>
															<th>Entry Date</th>
															<th>Source Name</th>
															<th>Destination Name</th>
															<th>Actions</th>
														</thead>
														<tbody>
															<?php $count=1; foreach($stock_incoming as $incoming){ ?>
																<tr>
															<td><?=$count?></td>
															<td><?=$incoming['inventory_transfer_data_id'];?></td>
															<td><?=$incoming['product_name'];?></td>
															<td><?=$incoming['product_type'];?></td>
															<td><?=$incoming['product_barcode'];?></td>
															<td><?=$incoming['sku_size'].' '.$stock['service_unit'];?></td>
															<td><?=$incoming['product_qty'];?></td>
															<td><?=$incoming['product_mrp'];?></td>
															<td><?=$incoming['invoice_date'];?></td>
															<td><?=$incoming['source'];?></td>
															<td><?=$incoming['destination'];?></td>
															<td>
																<?php if($incoming['transfer_status']==0){?>
																<button class='btn btn-success acceptInventory'  trans_data_id='<?=$incoming['inventory_transfer_data_id']?>' total_stock='<?=$incoming['product_qty']?>' stock_service_id='<?=$incoming['service_id']?>' sku_size="<?=$incoming['sku_size']?>" sender_outlet='<?=$incoming['business_outlet_id']?>'><i class='fa fa-check'>Accept</i></button>
																<button class='btn btn-danger rejectInventory'  trans_data_id='<?=$incoming['inventory_transfer_data_id']?>'><i class='fa fa-times'>Reject</i></button>
																<?php }else{?>
																	Accepted
																<?php }?>
															</td>
															</tr>
															<?php $count++; }?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-5" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<h4>Outgoing Stock</h4>
												</div>
												<div class="card-body">
													<table class="table table-hover datatables-basic" style="width: 100%;">
														<thead>
															<th>Sr. No.</th>
															<th>Product Name</th>
															<th>Type</th>
															<th>Barcode</th>
															<th>SKU size</th>
															<th>Product Qty</th>
															<th>MRP</th>
															<th>Status</th>
														</thead>
														<tbody>
															<?php $count=1; foreach($stock_outgoing as $outgoing){ ?>
																<tr>
															<td><?=$count?></td>
															<td><?=$outgoing['product_name'];?></td>
															<td><?=$outgoing['product_type'];?></td>
															<td><?=$outgoing['product_barcode'];?></td>
															<td><?=$outgoing['sku_size'].' '.$stock['service_unit'];?></td>
															<td><?=$outgoing['product_qty'];?></td>
															<td><?=$outgoing['product_mrp'];?></td>
															<td>
																<?php if($outgoing['transfer_status']==0){?>
																<button class='btn btn-warning' disabled>No action</button>
																<?php }else if($outgoing['transfer_status']==1){?>
																<button class='btn btn-success' disabled>Accepted</button>
																<?php }else{?>
																<button class='btn btn-danger' disabled>Rejected</button>
																<?php }?>
															</td>
															</tr>
															<?php $count++; }?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab-6" role="tabpanel">
											<div class="card">
												<div class="card-header">
													<h5 class="card-title">Inventory Health</h5>
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-md-3">
															<h5>Stock Value (MRP)</h5>
															<label class="btn btn-danger" id="labeltotal" style="width:130px">Rs <?=$stockvalue?></label>
														</div>
														<div class="col-md-3">
															<h5>Stock Status</h5>
															<select id="status" name="status" class="form-control">
																<option value="Regular">Regular Stock</option>
																<option value="Dead">Dead Stock</option>
																<option value="slow">Slow Moving</option>
															</select>
														</div>
													</div>
													<div class="row">
															<div class="col-md-12">
																	<table id="details" class="table table-striped" style="text-align:center">
																			<thead>
																					<th>Bucket</th>
																					<th>Item Name</th>
																					<th>Sub Category</th>
																					<th>Category</th>
																					<th>SKU Size</th>
																					<th>Current Stock</th>
																					<th>Stock In Regular-Stock Stage</th>
																					<th>Entry Date</th>
																					<th>No of Days since entry Date</th>
																					<th>Total Revenue Stuck(Rs)</th>
																			</thead>
																				<?php
																					foreach($stockdetails as $key=> $value){
																						?>
																						<tr>
																							<td>Regular Stock</td>
																							<td><?=$value['service_name']?></td>
																							<td><?=$value['sub_category_name']?></td>
																							<td><?=$value['category_name']?></td>
																							<td><?=$value['sku_size']?></td>
																							<td><?=$value['sku_count']?></td>
																							<td><?=$value['deadstock']?></td>
																							<td><?=$value['entrydate']?></td>
																							<td><?=$value['days']?></td>
																							<td><?=$value['Total']?></td>
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
									</div>
								</div>
								<!-- modal -->
								<div class="modal fade" id="ModalEditInventory" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title text-white">Edit Inventory</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
											<div class="modal-body">
												<form id="EditInventory" method="POST" action="#">
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>Invoice Number</label>
															<input type="text" class="form-control" placeholder="Invoice Number" name="invoice_number">
														</div>
														<div class="form-group col-md-4">
															<label>Date</label>
															<input type="date" class="form-control"  value="<?=date('Y-m-d');?>" name="invoice_date">
														</div>													
														<div class="form-group col-md-4">
															<label>Product Name</label>
															<input type="text" class="form-control" placeholder="Product Name" name="product_name" readonly>
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>Product Brand</label>
															<input type="text" class="form-control" placeholder="Product Brand" name="product_brand" readonly>
														</div>
													
														<div class="form-group col-md-4">
															<label>SKU Size</label>
															<input type="number" class="form-control" placeholder="SKU SIZE" name="sku_size" readonly>
														</div>
														<div class="form-group col-md-4">
															<label>Product Qty</label>
															<input type="number" class="form-control" placeholder="Quantity"  name="product_qty">
														</div>
													</div>
													<div class="form-row">	
														<div class="form-group col-md-4">
															<label>Cost/Unit(<small>before tax</small>)</label>
															<input type="number" class="form-control" name="product_price" placeholder="Cost/Unit">
														</div>
														<div class="form-group col-md-4">
														<label class="form-label">Product gst</label>
															<input type="number" class="form-control" name="product_gst" placeholder="GST">
														</div>
														<div class="form-group col-md-4">
														<label class="form-label">Product MRP</label>
															<input type="number" class="form-control" name="product_mrp" placeholder="Product MRP">
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-12">
															<input type="hidden" name="service_id">
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
								<!-- end -->
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
	$(".date").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});
	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
</script>
<script type="text/javascript">
	$(document).ready(function(){		
		$(".datatables-basic").DataTable({
			responsive: true
		});

		// Initialize Select2 select box
		$("#RawMaterialCategoryId").select2({
			allowClear: true,
			placeholder: "Select...",
		}).change(function() {
			$(this).valid();
		});

		$("#AddProduct select[name=source_type]").on('change',function(e){
  		var parameters = {
  			'source_type' :  $(this).val()
			};
			// alert($(this).val());
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetBranchAndVendor", parameters)
      .done(function(data, textStatus, jqXHR) {
				// alert(data.message);
				$('#AddProduct select[name=source_name]').children().remove();
      	for(var i=0; i < data.message.length;i++ ){
					console.log(data.message[i].source_id);
					$("#AddProduct select[name=source_name]").append("<option value=" + data.message[i].source_id+ ">" + data.message[i].source_name+ "</option>")
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
		});
		$("#TransProduct select[name=destination_type]").on('change',function(e){
  		var parameters = {
  			'source_type' :  $(this).val()
			};
			// alert($(this).val());
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetBranchAndVendor", parameters)
      .done(function(data, textStatus, jqXHR) {
				// alert(data.message);
				$('#TransProduct select[name=destination_name]').children().remove();
      	for(var i=0; i < data.message.length;i++ ){
					console.log(data.message[i].source_id);
					$("#TransProduct select[name=destination_name]").append("<option value=" + data.message[i].source_id+ ">" + data.message[i].source_name+ "</option>")
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		$("#AddRowProductTable").click(function(event){
			event.preventDefault();
			this.blur();
			var rowno = $("#addProductTable tr").length;
			
			rowno = rowno+1;
			
			$("#addProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_gst[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\"></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
		});

		$("#DeleteRowProductTable").click(function(event){
			event.preventDefault();
			this.blur();
			var rowno = $("#addProductTable tr").length;
			if(rowno > 1){
				$('#addProductTable tr:last').remove();
			}
		});

			//Search Product By Name

		$("#searchProductByName").typeahead({	
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: searchProductByName,
      templates: {
        empty: "No Product Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
      }
    });
       
    // var to_fill = "";
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", mrp="";

    $("#searchProductByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#addProductTable tr:last .searchProductByName";
			var loc2 = "#addProductTable tr:last .product_type";
			var loc3 = "#addProductTable tr:last .product_barcode";
			var loc4 = "#addProductTable tr:last .sku_size";
			var loc5 = "#addProductTable tr:last .mrp";
			var loc6 = "#addProductTable tr:last .product_id";
      service_name = suggestion.service_name;
			product_id = suggestion.service_id;
			service_type = suggestion.inventory_type;
			service_barcode = suggestion.barcode;
			sku_size = suggestion.qty_per_item+suggestion.service_unit;
			mrp = suggestion.mrp;
      setVals(loc,service_name,suggestion);
			setVals(loc2,service_type,suggestion);
			setVals(loc3,service_barcode,suggestion);
			setVals(loc4,sku_size,suggestion);
			setVals(loc5,mrp,suggestion);
			setVals(loc6,product_id,suggestion);
    });

    $("#searchProductByName").blur(function(){
      $("#addProductTable tr:last .searchProductByName").val(service_name);
			$("#addProductTable tr:last .product_id").val(product_id);
			$("#addProductTable tr:last .product_type").val(service_type);
			$("#addProductTable tr:last .product_barcode").val(service_barcode);
			$("#addProductTable tr:last .sku_size").val(sku_size);
			$("#addProductTable tr:last .mrp").val(mrp);
      service_name = "";
			product_id="";
			service_type = "";
			service_barcode = "";
			sku_size = "";
			mrp="";
    });

    function searchProductByName(query, cb){
		var inventory = 'Retail Product';
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetProductData",
        data: parameters,
        type: "GET",
				cache: false,
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
    }


		$("#AddProduct input[name=amount_paid]").on('input',function(e){
  			var amt_paid=  Number($(this).val());
				var invoice_amount= Number($("#AddProduct input[name=invoice_amount]").val());
				if(amt_paid==invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#AddProduct input[name=payment_status]").attr('value',"partial paid");
				}else if(amt_paid == 0){
					$("#AddProduct input[name=payment_status]").attr('value',"unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#AddProduct input[name=payment_status]").attr('value',"over paid");
				}	
    });

		$("#AddProduct").validate({
	  	errorElement: "div",
	    rules: {	       
	        "invoice_number" : {
	        	required : true
	        },
					"product_name" : {
	        	required : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#AddProduct").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>BusinessAdmin/AddInventory",
	        data: formData,
	        type: "POST",
					cache: false,
	    		success: function(data) {
            if(data.success == 'true'){
            	var message1 = data.message;
							var title = "Inventory Addition";
							var type = "success";
							toastr[type](message1, title, {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    alert("Error in Adding Inventory.");
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});

		//addproductend
		//Transfer Product start
			$("#AddRowProductTransTable").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#transProductTable tr").length;
				
				rowno = rowno+1;
				
				$("#transProductTable tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control searchProductByName\" name=\"product_name[]\" readonly><input type=\"hidden\" class=\"product_id\" name=\"product_id[]\"></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_type\" name=\"product_type[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control product_barcode\" name=\"product_barcode[]\" readonly></div></td><td><div class=\"form-group\" ><input type=\"text\" class=\"form-control sku_size\" name=\"sku_size[]\" readonly></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_qty[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_price[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"product_gst[]\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control mrp\" name=\"product_mrp[]\"></div></td><td><div class=\"form-group\"><input type=\"date\" class=\"form-control\" value=\"<?=date('Y-m-d',strtotime('+ 1 year', strtotime(date('Y-m-d'))));?>\" name=\"product_exp_date[]\" ></div></td></tr>");
			});

			$("#DeleteRowProductTransTable").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#transProductTable tr").length;
				if(rowno > 1){
					$('#transProductTable tr:last').remove();
				}
			});

			//Search Product By Name

		$("#searchProductTransferByName").typeahead({	
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: searchProductTransByName,
      templates: {
        empty: "No Product Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
      }
    });
       
    // var to_fill = "";
		var service_name="",product_id="", service_type="", service_barcode="", sku_size="", mrp="";

    $("#searchProductTransferByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#transProductTable tr:last .searchProductByName";
			var loc2 = "#transProductTable tr:last .product_type";
			var loc3 = "#transProductTable tr:last .product_barcode";
			var loc4 = "#transProductTable tr:last .sku_size";
			var loc5 = "#transProductTable tr:last .mrp";
			var loc6 = "#transProductTable tr:last .product_id";
      service_name = suggestion.service_name;
			service_type = suggestion.inventory_type;
			service_barcode = suggestion.barcode;
			sku_size = suggestion.qty_per_item;
			mrp = suggestion.mrp;
			product_id=suggestion.service_id;

      setVals(loc,service_name,suggestion);
			setVals(loc2,service_type,suggestion);
			setVals(loc3,service_barcode,suggestion);
			setVals(loc4,sku_size,suggestion);
			setVals(loc5,mrp,suggestion);
			setVals(loc6,product_id,suggestion);
    });

    $("#searchProductTransferByName").blur(function(){
      $("#transProductTable tr:last .searchProductByName").val(service_name);
			$("#transProductTable tr:last .product_type").val(service_type);
			$("#transProductTable tr:last .product_barcode").val(service_barcode);
			$("#transProductTable tr:last .sku_size").val(sku_size);
			$("#transProductTable tr:last .mrp").val(mrp);
			$("#transProductTable tr:last .product_id").val(product_id);

      service_name = "";
			service_type = "";
			service_barcode = "";
			sku_size = "";
			mrp="";
			product_id="";
    });

    function searchProductTransByName(query, cb){
		var inventory = 'Retail Product';
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/GetProductData",
        data: parameters,
        type: "GET",
				cache: false,
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
    }


		$("#TransProduct input[name=amount_paid]").on('input',function(e){
  			var amt_paid=  Number($(this).val());
				var invoice_amount= Number($("#TransProduct input[name=invoice_amount]").val());
				if(amt_paid==invoice_amount){
					$("#TransProduct input[name=payment_status]").attr('value',"paid");
				}else if(amt_paid > 0 && amt_paid < invoice_amount){
					$("#TransProduct input[name=payment_status]").attr('value',"partial paid");
				}else if(amt_paid == 0){
					$("#TransProduct input[name=payment_status]").attr('value',"unpaid");
				}else{
					alert("Amount Paid is much than Invoice Amount.");
					$("#TransProduct input[name=payment_status]").attr('value',"over paid");
				}	
    });

		$("#TransProduct").validate({
	  	errorElement: "div",
	    rules: {	       
	        "invoice_number" : {
	        	required : true
	        },
					"product_name" : {
	        	required : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#TransProduct").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>BusinessAdmin/TransferInventory",
	        data: formData,
	        type: "POST",
					cache: false,
	    		success: function(data) {
            if(data.success == 'true'){
            	var message1 = data.message;
							var title = "Inventory Transfer";
							var type = "success";
							toastr[type](message1, title, {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
            }
            else if (data.success == 'false'){                   
        	    alert(data.message);
            }
          },
          error: function(data){
  					$('.feedback').addClass('alert-danger');
  					$('.alert-message').html("").html(data.message); 
          }
				});
			},
		});


		$(document).on('click',".acceptInventory",function(event){
    	event.preventDefault();
			this.blur();
			var parameters = {
					transfer_data_id : $(this).attr('trans_data_id'),
					total_stock : $(this).attr('total_stock'),
					stock_in_unit:$(this).attr('sku_size'),
					service_id	:	$(this).attr('stock_service_id'),
					sender_outlet_id	:	$(this).attr('sender_outlet')
			};	
			$.ajax({
				url: "<?=base_url()?>BusinessAdmin/TransferFinalInventory",
				data: parameters,
				type: "POST",
				cache: false,
	    	success: function(data) {
					if(data.success == 'true'){
						alert("Inventory Updated");
						window.location.reload();
					}
					else if (data.success == 'false'){                   
						alert("Inventory Error");
						window.location.reload();
					}	
				}
			}); 
		});	
		
		$(document).on('click',".rejectInventory",function(event){
    	event.preventDefault();
			this.blur();
			var parameters = {
				transfer_data_id : $(this).attr('trans_data_id')
			};	
			$.ajax({
				url: "<?=base_url()?>BusinessAdmin/RejectTransferInventory",
				data: parameters,
				type: "POST",
				cache: false,
	    	success: function(data) {
					if(data.success == 'true'){
						alert("Inventory Rejected");
						window.location.reload();
					}
					else if (data.success == 'false'){                   
						alert("Inventory Error");
						window.location.reload();
					}	
				}
			}); 
    });
		//trans end
		$("#RawMaterialCategoryId").on('change',function(e){
  		var parameters = {
  			'raw_material_category_id' :  $(this).val()
  		};
    	$.getJSON("<?=base_url()?>Cashier/GetRawMaterial", parameters)
      .done(function(data, textStatus, jqXHR) {
      	$("#AddRawMaterialInventory input[name=rm_brand]").attr('value',data.raw_material_brand);
        $("#AddRawMaterialInventory input[name=rm_category_id]").attr('value',data.raw_material_category_id);
        $("#AddRawMaterialInventory select[name=rm_unit]").val(data.raw_material_unit);	
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

	  $("#AddOTCInventory").validate({
	  	errorElement: "div",
	    rules: {	       
	        "sku_count" : {
	        	required : true,
	        	digits : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#AddOTCInventory").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>Cashier/AddOTCInventory",
	        data: formData,
	        type: "POST",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddOTCStock").modal('hide'); 
							$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
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


		$(document).on('click','.EditInventory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
            service_id : $(this).attr('service_id')
      };
      $.getJSON("<?=base_url()?>BusinessAdmin/EditInventory", parameters)
        .done(function(data, textStatus, jqXHR) {            
					// alert(data['service_name']);
					mrp=(Number(data.message.service_price_inr)+Number(data.message.service_price_inr)*Number(.18));
            $("#ModalEditInventory input[name=product_name]").attr('value',data.message.service_name);
            $("#ModalEditInventory input[name=product_gst]").attr('value',data.message.service_gst_percentage);
            $("#ModalEditInventory input[name=product_brand]").attr('value',data.message.service_brand);
            $("#ModalEditInventory input[name=sku_size]").attr('value',data.message.qty_per_item);
            $("#ModalEditInventory input[name=product_price]").attr('value',data.message.service_price_inr);
            $("#ModalEditInventory input[name=service_id]").attr('value',data.message.service_id);
						$("#ModalEditInventory input[name=product_mrp]").attr('value',mrp);
            $("#ModalEditInventory").modal('show');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    	});


		$("#EditInventory").validate({
	  	errorElement: "div",
	    rules: {
	        "product_name" : {
            required : true
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#EditInventory").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditInventory",
		        data: formData,
		        type: "POST",
						cache: false,
		    		success: function(data) {
              if(data.success == 'true'){
								$("#ModalEditInventory").modal('hide');
              	alert("Inventory Added");
								window.location.reload();
              }
              else if (data.success == 'false'){                   
                $('.alert-message').html("").html(data.message);
								window.location.reload(); 
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
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
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
        url: "<?=base_url()?>Cashier/GetProductData",
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

    function setVals(element,fill,suggestion){
      $(element).attr('value',fill);
      $(element).val(fill);
      
      $("#SearchServiceButton").attr('service-id',suggestion.service_id);
      $("#SearchServiceButton").attr('service-name',suggestion.service_name);
      $("#SearchServiceButton").attr('service-price-inr',suggestion.service_price_inr);
      $("#SearchServiceButton").attr('service-gst-percentage',suggestion.service_gst_percentage);
      $("#SearchServiceButton").attr('service-est-time',suggestion.service_est_time);
    }


	$(document).on('click',"#SearchServiceButton",function(event){
    	event.preventDefault();
      	this.blur();
		//   var product = $(this).attr('service-id');
		//   alert(product);
		var parameters = {
        product : $(this).attr('service-name'),
		inventory_type : $(this).attr('otc_inventory_type')
		};	
		//   alert(parameters);
		$.ajax({
	        url: "<?=base_url()?>Cashier/GetProductDetails",
	        data: parameters,
	        type: "GET",
	        // crossDomain: true,
					cache: false,
	        // dataType : "json",
	    	success: function(data) {
            if(data.success == 'true'){
							document.getElementById("category").value = data.result[0]['category_name'];
							document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
							document.getElementById("otc_item").value = data.result[0]['service_name'];
							document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
							document.getElementById("barcode").value = data.result[0]['barcode'];
							document.getElementById("iproduct_name").value = data.result[0]['service_name'];
							document.getElementById("unit").value = data.result[0]['service_unit'];
							// document.getElementById("barcode_id").value = data.result[0]['barcode_id']; 
							for(var i=0; i < data.result.length;i++ ){
								$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
							}
            }
            else if (data.success == 'false'){                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }	
			}
		}); 
    });
		$(document).on('click',"#SearchItemButton",function(event){
    	event.preventDefault();
      	this.blur();
		  var product = document.getElementById("ServiceOTCId").value;
		  var inventory = document.getElementById("otc_inventory_type").value;
		//   alert(product);
		var parameters = {
        product : product,
		inventory_type : inventory
		};	
		//   alert(parameters);
		$.ajax({
				url: "<?=base_url()?>Cashier/GetProductDetail",
				data: parameters,
				type: "GET",
				crossDomain: true,
				cache: false,
				dataType : "json",
	    	success: function(data) {
					if(data.success == 'true'){
						document.getElementById("category").value = data.result[0]['category_name'];
						document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
						document.getElementById("otc_item").value = data.result[0]['service_name'];
						document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
						for(var i=0; i < data.result.length;i++ ){
							$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
						}
					}
					else if (data.success == 'false'){                   
						$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html(data.message);
						});
					}	
				}
			}); 
    });
</script>
<script type="text/javascript">		
	$("input[name=\"expiry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM'));
        });	
</script>
<script type="text/javascript">
	$(document).on('click',"#download",function(event){		
		event.preventDefault();
		this.blur();			
			$.getJSON("<?=base_url()?>Cashier/InventoryDownload")	
			.done(function(data, textStatus, jqXHR) {					
				JSONToCSVConvertor(data.result," ", true);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown.toString());
			});
		});
	
</script>
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
		$("#status").on('change',function(e){
			// alert($(this).val());
			if($(this).val() == 'Regular'){
				// alert("hoo");
				window.location.reload();
			}
			var parameters = {
				'status' :  $(this).val()
			};
			$.getJSON("<?=base_url()?>BusinessAdmin/InventoryStatus", parameters)
		.done(function(data, textStatus, jqXHR) {
				// alert(data.stock);
				if((data.stock == 'Slow')){
					$('#labeltotal').text("Rs "+data.total);
					var temp_str="<thead><th>Bucket</th><th>Item Name</th><th>Sub Category</th><th>Category</th><th>SKU SIZE</th><th>Current Stock</th><th>Stock In Slow Stock Stage</th><th>Entry Date</th><th>No of Days since entry Date</th><th>Total Revenue Stuck</th></thead> ";
					for(var i = 0;i < data.stockdetails.length;i++){
				
					temp_str += "<tr>";
								temp_str += "<td> Slow Stock  </td>";
								temp_str += "<td>" + data.stockdetails[i].service_name + "</td>";
								temp_str += "<td>" + data.stockdetails[i].sub_category_name + "</td>";
								temp_str += "<td>" + data.stockdetails[i].category_name+"</td>";
								temp_str += "<td>" + data.stockdetails[i].sku_size + "</td>";
								temp_str += "<td>" + data.stockdetails[i].sku_count+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].deadstock+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].entrydate+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].days+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].Total+ "</td>";
								temp_str += "</tr>";
					}
					$("#details").html("").html(temp_str);
				}else if(data.stock == 'Dead'){
					// document.getElementById("labeltotal").text=data.total;
					$('#labeltotal').text("Rs "+data.total);
					var temp_str="<thead><th>Bucket</th><th>Item Name</th><th>Sub Category</th><th>Category</th><th>SKU SIZE</th><th>Current Stock</th><th>Stock In Dead Stock Stage</th><th>Entry Date</th><th>No of Days since entry Date</th><th>Total Revenue Stuck</th></thead> ";
					for(var i = 0;i < data.stockdetails.length;i++){
				
					temp_str += "<tr>";
								temp_str += "<td> Dead Stock  </td>";
								temp_str += "<td>" + data.stockdetails[i].service_name + "</td>";
								temp_str += "<td>" + data.stockdetails[i].sub_category_name + "</td>";
								temp_str += "<td>" + data.stockdetails[i].category_name+"</td>";
								temp_str += "<td>" + data.stockdetails[i].sku_size + "</td>";
								temp_str += "<td>" + data.stockdetails[i].sku_count+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].deadstock+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].entrydate+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].days+ "</td>";
								temp_str += "<td>" + data.stockdetails[i].Total+ "</td>";
								temp_str += "</tr>";
					}
					$("#details").html("").html(temp_str);
				}else{
					window.location.reload();
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
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
