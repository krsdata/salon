        
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
				<h1 class="h3 mb-3">Commission Management</h1>
				<div class="row">
					<?php
					if (empty($business_outlet_details)) {
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
					if (!isset($selected_outlet)) {
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
					} else {
					?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h3>Employee Management System</h3>
								</div>
								<div class="card-body">
									<div class="form-row">
										<div class="form-group col-md-6" style="text-align: left;">
											<button class="btn btn-success btn-sm" style="padding:6px">
												New Commission Model
											</button>
										</div>
										<div class="form-group col-md-6" style="text-align: right;">
											<label style="text-align:left;padding:6px;" class="btn btn-primary btn-sm">
												<a href="<?= base_url() ?>index.php/BusinessAdmin/Commission_opening" style="text-decoration-color:white;" class="text-white">Existing Commission Model</a>
											</label>
										</div>
									</div>
									<!-- Form satrted -->
									<form class="" id="insert_commission" method="POST" action="#">
										<div class="form-row">
											<div class="form-group col-md-2">
												<label>Commission Name</label>
											</div>
											<div class="form-group col-md-3">
												<input type="text" class="form-control" name="name" required>
											</div>
										</div>
										<div class="form-row">
											<div class="form-group col-md-2">
												<label>Select Month</label>
											</div>
											<div class="from-group col-sm-1">
												<select name="year" id="year" float="left" class="form-control" style="width:100px;">
													<option disabled selected>Year</option>
													<?php
														for($i=2018;$i<=date('Y')+1;$i++)
														{
													?>
															<option value="<?=$i?>"><?=$i?></option>
													<?php		
														}
													?>
												</select>
											</div>
											<div class="from-group col-sm-1">
												<select name="month_name" id="month" class="form-control" style="width:100px;" >
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
											</div>
										</div>
										<div class="form-row">
											<div class="form-group col-md-2">
												<label>Select Employee</label>
											</div>
											<div class="form-group col-md-3">
												<select name="expert_id" id="expert_id" class="form-control" required>
													<option value="" selected="true" disabled="disabled">Select Employee</option>
													<option value="0">All</option>
													<?php
													foreach ($emp as $expert) {
													?>
														<option value="<?= $expert['employee_id'] ?>"><?= $expert['employee_first_name'], '  ', $expert['employee_last_name']; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>
										<div class="form-row">
											<div class="form-group col-md-2">
												<label>Commission Type</label>
											</div>
											<div class="form-group col-md-3">
												<select type="text" class="form-control" name="commission_type" id="commission_type"  required>
													<option value="" selected="true" disabled="disabled">Select Commission Type</option>
													<option value="all">ALL</option>
													<option value="service">Service</option>
													<option value="product">Product</option>
													<option value="package">Package</option>
													
											
												</select>
											</div>
										</div>
										<div class="form-row" id="base_kpi">
											<div class="form-group col-md-2">
												<label>Select Base Kpi</label>
											</div>
											<div class="form-group col-md-3">
												<select type="text" class="form-control" name="commission_name" id="commission_name" onchange="kpi_name()" required>
													<option value="" selected="true" disabled="disabled">Select Base KPI</option>
													<!-- <option value="Avg_service_sales">Avg of 3M's Service Sales</option> -->
													<option value="none">None</option>
													<option value="gross_salary">Gross Salary</option>
													<!-- <option value="Avg_total_disc_price">Avg of Last 3 Months Sales MRP</option> -->
													<option value="Avg_sales_mrp">Avg of Last 3 Months Total Sales</option>
													
													
											
												</select>
											</div>
										</div>
										<div class="form-row">
											<div class="form-group col-md-2">
												<label>Target</label>
											</div>
											<div class="form-group col-md-2">
												<div class="input-group">
													<input type="int" class="form-control" name="t1" id="t1" onkeyup="myFunction()">&emsp;
													<span class="input-group-append"><i style="margin-top:8px" class="fas fa-times"></i></span>
												</div>	
											</div>
											<div class="form-group col-md-2">
												<div class="input-group">
													<input type="int" class="form-control" name="t2" id="t2" onkeyup="myFunction()">&emsp;
													<span class="input-group-append"><i style="margin-top:8px" class="fas fa-equals"></i></span>
												</div>	
											</div>
											<div class="form-group col-md-2">	
												<input type="int" class="form-control" name="t3" id="t3">
											</div>
										</div>
										
										<div class="form-row">
											<div class="form-group col-md-12">
												<div id="Services">
													<table id="serviceTable" class="table table-hover">
														<tbody>
															<tr style="text-align:center;font-weight:bold">
																<div class="form-group">
																	<td colspan="2"><label>Start Range</label></td>
																</div>
																<div class="form-group">
																	<td colspan="2"><label>End Range </label></td>
																</div>
																<div class="form-group">
																	<td colspan="2"><label>Commission %</label></td>
																</div>
																<div class="form-group">
																	<td colspan="1"><label>KPI</label></td>
																</div>
																<div class="form-group">
																	<td colspan="1"><label>Base Value</label></td>
																</div>
																
															</tr>
															<tr>
																<td>
																	<div class="form-group">
																		<input type="number" step="0.01" name="start_range[]" class="form-control" onKeyPress="if(this.value.length==3) return false;" placeholder="Enter Percent" required>
																	</div>
																</td>
																<td>
																	<label>to</label>
																</td>
																<td>
																	<div class="form-group">
																		<input type="number" step="0.01" class="form-control" name="end_range[]" min="0" id="1" onKeyPress="if(this.value.length==3) return false;" placeholder="Enter Percent">
																	</div>
																</td>
																<td>
																	<label>=</label>
																</td>
																<td>
																	<div class="form-group">
																		<input type="number" step="0.01" min="0" class="form-control" name="comm[]" onKeyPress="if(this.value.length==3) return false;" placeholder="Enter Percent" required>
																	</div>
																</td>
																<td>
																	<label>of</label>
																</td>
																<td>
																	<div class="form-group">
																		<select id="emp_month_wise" name="basevalue[]" class="form-control emp_month" required>
																			<option value="" selected="true" disabled="disabled">Select Calculation Matrics</option>
																			<option value="Total Sales Net Price">Total Sales Net Price</option>
																			<!-- <option value="Avg_sales_mrp">Avg of Last 3 Months Sales MRP</option> -->
																			<option value="Total Sales MRP">Total Sales MRP</option>
																			<!-- <option value="ServiceSales">Service Sales</option> -->
																		</select>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<select id="new_base_value" name="newbasevalue[]" class="form-control new_base_value" required>
																			<option value="" selected="true" disabled="disabled">Select Base Value</option>
																			<option value="Calculation on Base Target">Calculation on Base Target</option>
																			<option value="Calculation on Achievement">Calculation on Achievement</option>
																		</select>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<input type="number" class="form-control" name="count_service[]" temp="Count" value="1" min="1" max="12" hidden>
																	</div>
																</td>
																</tr>
														</tbody>
													</table>
													<button type="button" class="btn btn-success" id="AddRowService"><i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
													<button type="button" class="btn btn-danger" id="DeleteRowService"><i class="fa fa-trash" aria-hidden="true"></i></button>
												</div>

											</div>
										</div>

										<div class="form-row">

											<div class="form-group col-md-3">
												<input type="hidden" name="admin_id" value="<?= $business_outlet_details[0]['business_outlet_id'] ?>">
												<input type="submit" class="btn btn-primary" value="submit" name="submit">
											</div>

										</div>

									</form>

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
	$(".smartwizard-arrows-primary").smartWizard({
		theme: "arrows",
		showStepURLhash: false
	});
	
	$("#AddRowService").click(function(event) {
		event.preventDefault();
		this.blur();
		var rowno = $("#serviceTable tr").length;

		rowno = rowno + 1;
		var emp_month = $('.emp_month').val();
		  var t3=document.getElementById('emp_month_wise').value;
		  var base=document.getElementById('new_base_value').value;
		//   if(t3=='TotalSales')
		//   alert(base);	
		$("#serviceTable select[name=BaseValue").append('<option selected>' + t3 + '</option>');
		$("#serviceTable select[name=BaseValue").attr('readonly',true);
		$("#serviceTable select[name=newbasevalue").append('<option selected>' + t3 + '</option>');
		$("#serviceTable select[name=newbasevalue").attr('readonly',true);
		$("#serviceTable tr:last").after("<tr><td><div class=\"form-group\"><input type=\"text\" name=\"start_range[]\" id=\"" + rowno + "\" class=\"form-control\" placeholder=\"Enter Percent\"></div></td><td><label>to</label></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"end_range[]\" placeholder=\"Enter Percent\"></div></td><td><label>=</label></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control\" name=\"comm[]\" temp=\"Service\" placeholder=\"Enter Percent\"></div></td><td><label>of</label></td><td><div class=\"form-group\"><select name=\"basevalue[]\" class=\"form-control dynamic_cal\"><option value=\"" + emp_month + "\" selected=\"true\" selected>" + emp_month + "</option></select></div> <input type=\"number\" class=\"form-control\" name=\"count_service[]\" temp=\"Count\" value=\"1\" min=\"1\" max=\"12\" hidden></div></td><td><div class=\"form-group\"><select name=\"newbasevalue[]\" class=\"form-control\"><option value\""+base+"\" selected=\"true\">"+base+"</option></select></div></td></tr>");
	});

	$("#DeleteRowService").click(function(event) {
		event.preventDefault();
		this.blur();
		var rowno = $("#serviceTable tr").length;
		if (rowno > 1) {
			$('#serviceTable tr:last').remove();
		}
	});

	// $(document).ready(function(){
	function kpi_name() {
		event.preventDefault();
		$(this).blur();
		var emp=$("#expert_id").val();
		var kpi = $('#commission_name').val();
		// alert(base_kpi);
		if(emp==0 && kpi=='none'){
			$("#insert_commission input[name=t2]").val(null);
			$("#insert_commission input[name=t2]").val(null);
			$("#t1").attr('readonly',true);
			$("#t2").attr('readonly',true);
		}else if(emp!=0 && kpi=='none'){
			// $("#t1").attr('value',null);
			$("#insert_commission input[name=t2]").val(null);
			$("#insert_commission input[name=t2]").val(null);
			$("#t1").attr('readonly',true);
			$("#t2").attr('readonly',true);
		}
		else if(emp==0 && kpi!='none'){
			$("#t2").val(kpi);
			$("#t1").removeAttr('readonly');
			$("#t2").removeAttr('readonly');
			$("#t3").attr('readonly',true);
		}else if(emp!=0 && kpi!='none'){
			$("#t3").attr('readonly',true);
			var month = document.getElementById("month").value;
				var year = document.getElementById("year").value;
				// alert(month);
				var month = year.concat('-', month);
			// var month = document.getElementById('month_name').value;
			var expert_id = document.getElementById('expert_id').value;
			var base_kpi = document.getElementById('commission_name').value;
			var parameters = {
				expert_id: expert_id,
				month: month,
				base_kpi: base_kpi
			};
			$.getJSON("<?= base_url() ?>index.php/BusinessAdmin/Commission/", parameters)
				.done(function(data, textStatus, jqXHR) {
					//   $("#form1 input[name=t2]").val(data.employee_basic_salary);

					if (base_kpi == 'gross_salary') {
						// alert(data.employee_gross_salary);
						// $("#insert_commission input[name=t2]")='';
						$("#insert_commission input[name=t2]").val(data.employee_gross_salary);
					}
					if (base_kpi == 'Avg_sales_mrp') {
						$("#insert_commission input[name=t2]").val(parseInt(data.total));
					}

					if (base_kpi = 'Avg_total_disc_price') {
						$("#insert_commission input[name=t2]").attr('value', parseInt(data.Service));
					} // alert(data.employee_basic_salary);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					//   console.log(errorThrown.toString());
				});
		}
	}

	// $(document).on('change','#commission_name',function(event){


	// });


	// });
	function myFunction() {

		var t2 = parseInt(document.getElementById("t2").value);
		var t1 = parseInt(document.getElementById("t1").value);
		var t3 = t2 * t1;
		//   alert(t2);
		document.getElementById("t3").value = t3;
	}
</script>
<script type='text/javascript'>
	$( document ).ready(function() {
		function change(theStatus) {
		if (theStatus.value == "none") {

			document.getElementById("t1").readonly = true;
			document.getElementById("t2").readonly = true;
			document.getElementById("t1").value = '';
			document.getElementById("t2").value = '';
		} else {
			document.getElementById("t1").readonly = false;
			document.getElementById("t2").readonly = false;
		}
	}
	//
	$("#insert_commission").validate({
		errorElement: "div",
		rules: {
			"name" : {
				required : true,
				maxlength : 50
			},
			"month" : {
				required : true
			},
			"expert_id" : {
				required : true
			},
			"commission_name" :{
				required : true,
				maxlength : 50
			},
			"start_range" : {
				required : true
			}
		},
		submitHandler: function(form) {
			var formData = $("#insert_commission").serialize(); 
			$.ajax({
				url: "<?= base_url() ?>index.php/BusinessAdmin/InsertCommission/",
				data: formData,
				type: "POST",
				crossDomain: true,
				cache: false,
				dataType : "json",
				success: function(data) {
					if(data.success == 'true'){ 
						// $("#ModalAddPackage").modal('hide');
						var message2 = data.message;
						var title2 = "";
						var type = "success";
						toastr[type](message2, title2, {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
						});
						setTimeout(function () { location.reload(1); }, 500);
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

    