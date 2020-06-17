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
				<h1 class="h3 mb-3">Inventory Health</h1>
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
								<!-- <h5 class="card-title">XXXXXXXX</h5> -->
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
			$.getJSON("<?=base_url()?>index.php/BusinessAdmin/InventoryStatus/", parameters)
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