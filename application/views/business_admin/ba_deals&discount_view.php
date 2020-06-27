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
				<h1 class="h3 mb-3">Deals & Discount</h1>
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
									<div class="col-md-9">
										<h5 class="card-title">Configure Scheme</h5>
									</div>									
								</div>								
							</div>
							<div class="card-body">
								<form method="POST" action="" id="addDeals">
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Applicable to</label>
											<select class="form-control" name="tag_name">
											    <option value="All">All Customer</option>
                                                <option value="New">New Customer</option>
                                                <option value="Repeat">Repeat Customer</option>
                                                <option value="Regular">Regular Customer</option>
                                                <option value="Risk">Risk Customer</option>
                                                <option value="Lost">Lost Customer</option>
											<?php foreach($tag as $tag){?>
                                              <option value="<?=$tag['tag_id']?>"><?=$tag['rule_name']?></option>
                                            <?php }?>										
    											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Deals Name</label>
											<input type="text" name="deal_name" class="form-control" required placeholder="Deals Name" />
										</div>
										<div class="form-group col-md-3">
											<label>Deals Code</label>
											<input type="text" name="deal_code" class="form-control" maxlength="8" minlength="4" placeholder="Deals Code" required />
										</div>
										<div class="form-group col-md-3">
											<label>Price</label>
											<input type="number" class="form-control" name="deal_price" placeholder="Deals Price" required />	
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Validity</label>
											<input type="text" class="form-control" name="daterange" required />
										</div>
										<div class="form-group col-md-3">
											<label>Deals Time From</label>
											<input type="time" name="start_time" class="form-control" required />
										</div>
										<div class="form-group col-md-3">
											<label>To</label>
											<input type="time" name="end_time" class="form-control" required />
										</div>
										<div class="form-group col-md-3">
											<label>Applicable on Weekends</label>
											<select name="weekend" class="form-control">
												<option value="1">Yes</option>
												<option value="0">No</option>
											</select>	
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Applicable on national holidays</label>
											<select name="national_holiday" class="form-control">
												<option value="1">Yes</option>
												<option value="0">No</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Applicable on Bday/Anni.</label>
											<select name="bday_anni" class="form-control">
												<option value="1">Yes</option>
												<option value="0">No</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Select weekdays</label>
											<select name="weekday" class="form-control">
												<option value="all">Select All</option>
												<option value="monday">Monday</option>
												<option value="tuesday">Tuesday</option>
												<option value="monday">Wednesday</option>
												<option value="tuesday">Thirsday</option>
												<option value="monday">Friday</option>
												<option value="tuesday">Saturday</option>
												<option value="monday">Sunday</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Benifit Type</label>
											<select name="benifit_type" class="form-control">
												<option value="discount">Discount</option>
												<!-- <option value="cashback">Cashback</option> -->
												<!-- <option value="loyalty_point">Loyalty Points</option> -->
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="form-group col-md-3">
											<label>Invoice Min Amount</label>
											<input type="number" class="form-control" name="minimum_amt" placeholder="Invoice Min Amount" required />
										</div>
										<div class="form-group col-md-3">
											<label>Invoice Max Amount</label>
											<input type="number" class="form-control" name="maximum_amt" placeholder="Invoice Max Amount" required />
										</div>
										<div class="form-group col-md-3">
											<label>Total Redemptions</label>
											<input type="number" class="form-control" name="total_service" placeholder="Total Number of Redemptions" required>
										</div>
										<div class="form-group col-md-3">
											<label>Discount</label>
											<input type="number" class="form-control" name="discount" placeholder="Discount % " required />
										</div>
									</div>
									<div class="form-row">
										<table id="specialMembershipTable1" class="table">
											<tbody>
												<tr>
													<td style="width:30%;">
														<div class="form-group">
															<!-- <label>Cat. Type</label> -->
															<select class="form-control" name="category_type1[]">
																<option value="" selected>Category type</option>
																<option value="Service">Service</option>
																<option value="Products">Product</option>
															</select>
														</div>
													</td>
													<td style="width:30%;">
														<div class="form-group">
															<!-- <label>Min Price</label> -->
															<input type="text" class="form-control" name="min_price1[]" temp="min_price1" placeholder="Min Price">
														</div>
													</td>
													<td style="width:30%;">
														<div class="form-group">
															<!-- <label>Max Price</label> -->
															<input type="text" class="form-control" min="0" name="max_price1[]"  placeholder="Max Price" >
														</div>
													</td>		
													<td style="width:10%;">
													<button type="button" class="btn btn-success mb-1 ml-1" id="AddRowSpecialMembership1"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
											<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership1"><i class="fa fa-trash" aria-hidden="true"></i></button>
													</td>											
												</tr>																				
											</tbody>
										</table>																		
													
										<table id="specialMembershipTable2" class="table">
											<tbody>	
												<tr>
													<td style="width:22%;">
														<div class="form-group">
															<!-- <label>Cat. Type</label> -->
															<select class="form-control" name="category_type2">
																<option value="" selected>Category type</option>
																<option value="Service">Service</option>
																<option value="Products">Product</option>
															</select>
														</div>
													</td>
													<td style="width:22%;">
														<div class="form-group">
															<!-- <label>Category</label> -->
															<select class="form-control" name="special_category_id2[]" temp="special_category_id2">
																
															</select>
														</div>
													</td>
													<td style="width:22%;">
														<div class="form-group">
															<!-- <label>Min Price</label> -->
															<input type="text" class="form-control" name="min_price2[]" temp="min_price" placeholder="Min Price">
														</div>
													</td>
													<td style="width:24%;">
														<div class="form-group">
															<!-- <label>Max Price</label> -->
															<input type="text" class="form-control" min="0" name="max_price2[]"  placeholder="Max Price" >
														</div>
													</td>			
													<td style="width:10%;">
													<button type="button" class="btn btn-success mb-1 ml-1" id="AddRowSpecialMembership2"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
													<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership2"><i class="fa fa-trash" aria-hidden="true"></i></button>
													</td>	
												</tr>																			
											</tbody>
										</table>																		
										
										<table id="specialMembershipTable3" class="table">
											<tbody>
												<tr>
													<td style="width:18%;"> 
														<div class="form-group">
															<!-- <label>Cat. Type</label> -->
															<select class="form-control" name="category_type3">
																<option value="" selected>Category type</option>
																<option value="Service">Service</option>
																<option value="Products">Product</option>
															</select>
														</div>
													</td>
													<td style="width:18%;">
														<div class="form-group">
															<!-- <label>Category</label> -->
															<select class="form-control" name="special_category_id3">
																
															</select>
														</div>
													</td>
													<td style="width:18%;">
														<div class="form-group">
															<!-- <label>Sub-Cat.</label> -->
															<select class="form-control" name="special_sub_category_id3[]" temp="special_sub_category_id3">
															</select>
														</div>
													</td>
													<td style="width:18%;">
														<div class="form-group">
															<!-- <label>Min Price</label> -->
															<input type="text" class="form-control" name="min_price3[]" temp="min_price" placeholder="Min Price">
														</div>
													</td>
													<td style="width:18%;">
														<div class="form-group">
															<!-- <label>Max Price</label> -->
															<input type="text" class="form-control" min="0" name="max_price3[]"  placeholder="Max Price" >
														</div>
													</td>		
													<td style="width:10%;">
													<button type="button" class="btn btn-success mb-1 ml-1" id="AddRowSpecialMembership3"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
											<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership3"><i class="fa fa-trash" aria-hidden="true"></i></button>
													</td>											
												</tr>																			
											</tbody>
										</table>																		
										
										<table id="specialMembershipTable4" class="table">
											<tbody>
												<tr>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Cat. Type</label> -->
															<select class="form-control" name="category_type4">
																<option value="" selected>Category type</option>
																<option value="Service">Service</option>
																<option value="Products">Product</option>
															</select>
														</div>
													</td>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Category</label> -->
															<select class="form-control" name="special_category_id4">
																
															</select>
														</div>
													</td>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Sub-Cat.</label> -->
															<select class="form-control" name="special_sub_category_id4">
															</select>
														</div>
													</td>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Service</label> -->
															<select class="form-control" name="special_service_id4[]" temp="Service">
															</select>
														</div>
													</td>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Min Price</label> -->
															<input type="number" class="form-control" name="min_price4[]" temp="min_price" placeholder="Min Price">
														</div>
													</td>
													<td style="width:15%;">
														<div class="form-group">
															<!-- <label>Max Price</label> -->
															<input type="number" class="form-control" min="0" name="max_price4[]"  placeholder="Max Price" >
														</div>
													</td>
													<td style="width:10%;">
													<button type="button" class="btn btn-success mb-1 ml-1" id="AddRowSpecialMembership4"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
											<button type="button" class="btn btn-danger mb-1" id="DeleteRowSpecialMembership4"><i class="fa fa-trash" aria-hidden="true"></i></button>
													</td>
												</tr>																				
											</tbody>
										</table>																		
										
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
										<h5 class="card-title">Active Deals</h5>
									</div>
									<div class="col-md-3">
											<button class="btn btn-primary mb-2" onclick="exportTableToExcel('dealTable','Deals')"><i class="fa fa-file-export"></i>Export</button>
									</div>
								</div>								
							</div>
							<div class="card-body">
								<table class="table table-striped datatables-basic" style="width:100%" id="dealTable">
									<thead>
										<tr>
											<th>Sr No.</th>
											<th>Deal Name</th>
											<th>Deal Code</th>
											<th>Valid From</th>
											<th>To</th>
											<th>App. For</th>
											<th>Benifit Type</th>
											<th>Benifit</th>
											<th>Number Of Redemptions</th>
										</tr>
									</thead>
										<tbody>
										<?php
													$index = 1;
													foreach ($deals as $deals):
												?>
												<tr>
													<td><?=$index;?></td>
													<td><?=$deals['deal_name']?></td>
													<td><?=$deals['deal_code']?></td>
													<td><?=$deals['start_date']?></td>
													<td><?=$deals['end_date']?></td>
													<td><?=$deals['tag_id']?></td>
													<td><?=$deals['benifit_type']?></td>
													<td><?=$deals['discount']?> %</td>
													<td>10</td>
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
					<!-- end -->
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
	  
		$("input[name=\"daterange\"]").daterangepicker({
		opens: "right",
		locale: {
            format: 'YYYY-MM-DD'
				}
		}); 

  	$("#addDeals").validate({
	  	errorElement: "div",
	    rules: {
	        "tag_name" : {
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
				var formData = $("#addDeals").serialize();
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddDeals",
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


		// table
		// table1
			$("#AddRowSpecialMembership1").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable1 tr").length;
				
				rowno = rowno+1;
				
				$("#specialMembershipTable1 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type1[]\"><option value=\"service\" selected>Service</option><option value=\"otc\">Products</option></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price1[]\" placeholder=\"Min price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price1[]\" placeholder=\"Max price\"></div></td></td></tr>");
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
				
				$("#specialMembershipTable2 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type2\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id2[]\" temp=\"special_category_id2\"> </select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price2[]\" placeholder=\"Min price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price2[]\" placeholder=\"Max price\"></div></td></td></tr>");
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
				
				$("#specialMembershipTable3 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type3\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id3\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_sub_category_id3[]\" temp=\"special_sub_category_id3\"></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price3[]\" placeholder=\"Min price\"></div></td></td></tr>");
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
				
				$("#specialMembershipTable4 tr:last").after("<tr><td><div class=\"form-group\"><select class=\"form-control\" name=\"category_type4\"><option value=\"Service\" selected>Service</option><option value=\"Products\">Products</option></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_category_id4\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_sub_category_id4\"></select></div></td><td><div class=\"form-group\"><select class=\"form-control\" name=\"special_service_id4[]\" temp=\"Service\"></select></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"min_price4[]\" placeholder=\"Mini price\"></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" min=\"0\" name=\"max_price4[]\" placeholder=\"Max price\"></div></td></tr>");
			});

			$("#DeleteRowSpecialMembership4").click(function(event){
				event.preventDefault();
				this.blur();
				var rowno = $("#specialMembershipTable4 tr").length;
				if(rowno > 1){
					$('#specialMembershipTable4 tr:last').remove();
				}
			});
		// end table
		
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
