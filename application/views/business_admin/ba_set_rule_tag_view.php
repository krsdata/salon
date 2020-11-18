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
				<!-- <h1 class="h3 mb-3">XXXXXXX Management</h1> -->
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
                                <div class="input-group">
                                       <a href="<?= base_url() ?>BusinessAdmin/Tags"><h4><i class="fas fa-arrow-left">&emsp;Back</i></h4></a>
                                       
                                </div> 
							</div>
							<div class="card-body">
                                <form action="#" id="SetRuleTags">
                                    <div class="row" style="margin-top:2%;margin-bottom:2%">
                                        <div class="col-md-3">
                                        <label>Tag Name</label>
                                                <input type="text" name="rule_name" class="form-control" placeholder="Enter Tag Name"  required>
                                        </div>
                                        <div class="col-md-5">
                                              
                                        </div>
                                        <div class="col-md-1">
                                                <label>Select Type :</label>
                                        </div>
                                        <div class="col-md-1">
                                                <input type="radio" name="option" value="OR">OR
                                        </div>
                                        <div class="col-md-1">        
                                                <input type="radio" name="option" value="AND">AND
                                        </div>
                                        <div class="col-md-7">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <table class="table table-hover" id="InventoryAddProducts">
                                                    <tr>  
                                                        <td>
                                                            <div class="form-group" >
                                                                <select name="kpi[]" style="width:200px;" id="kpi" style="margin:0px" class="form-control">
                                                                    <option value="" selected disabled>Select KPI</option>
                                                                    <option value="total_amt_spent">Total Amount Spent</option>
                                                                    <option value="visits">Visit</option>
                                                                    <option value="last_visit_date">Last Visit Date</option>
                                                                    <option value="last_visit_range">Last Visit Range</option>
                                                                    <option value="points">Points</option>
                                                                    <option value="gender">Gender</option>
                                                                    <option value="age">Age</option>
                                                                    <option value="package_subscriber">Package Subscribers</option>
                                                                    <option value="avg_order">Average Order</option>
                                                                    <option value="billing_date">Billing Date</option>
                                                                    <option value="birthday">BirthDay</option>
                                                                    <option value="anniversary">Anniversary</option>
																	<option value="package_expiry">Package Expiry Days Remaining</option>
																	<option value="repeat_service_reminder">Repeat Service Reminder</option>
                                                                </select> 
                                                            </div>
                                                        </td>
                                                        <td>		
                                                            <div class="form-group" id="srange" style="width:200px;">
                                                                    <input type="number"  class="form-control" name="start_range[]"  placeholder="start Range">
                                                            </div>                           
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="erange" style="width:200px;">
                                                                    <input type="number" class="form-control" name="end_range[]" placeholder="End Range">
                                                            </div>
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="select_date" style="width:200px;" hidden>
                                                                    <input type="text" class="form-control date" name="date[]" placeholder="Select Date">
                                                            </div>                            
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="date_srange" style="width:200px;"  hidden>
                                                                    <input type="text" class="form-control date" name="start_range_date[]"  placeholder="start Date">
                                                            </div>
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="date_erange" style="width:200px;"  hidden>
                                                                    <input type="text"  class="form-control date" name="end_range_date[]" placeholder="End Date">
                                                            </div>
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="value" style="width:200px;"  hidden>
                                                                    <input type="number"  class="form-control" min="0" name="value[]" placeholder="Enter Value">
                                                            </div>
                                                        </td>
                                                        <td>	
                                                            <div class="form-group" id="select_opt" style="width:200px;"  hidden>
                                                                    <select name="select_option[]" class="form-control">
                                                                        <option value="Mr.">Male</option>
                                                                        <option value="Ms.">Female</option>
                                                                    </select>
                                                            </div>
                                                        </td>
													</tr>
													<tr id="service_reminder" hidden>
														<td style="width:25%;">
															<div class="form-group col-md-12">
																<select name="category_type" class="form-control">
																	<option value="" selected disabled>Select Category Type</option>
																	<option value="Service">Services</option>
																	<option value="Products">Products</option>																
																</select>
															</div> 
														</td>
														<td style="width:25%;">
															<div class="form-group col-md-12">
																<select name="category_name" class="form-control">
																
																</select>
															</div> 
														</td>
														<td style="width:25%;">
														<div class="form-group col-md-12">
															<select name="sub_category_name" class="form-control">
																
															</select>
                                                        </div> 
														</td>
														<td style="width:25%;">
														<div class="form-group col-md-12">
															<select name="service_name" class="form-control">
																
															</select>
                                                        </div> 
														</td>
													</tr>	
                                                </table>
                                            </div>						
                                            <div class="row" id="action_row">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success" id="AddRow">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    <button type="button" class="btn btn-danger" id="DeleteRow">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>			    	
                                                </div>
                                            </div>
                                        </div>	
                                    
                                    
                                    </div>
                                    <div class="form-row" style="margin-top:30px;margin-bottom:20px" hidden>
                                        <div class="col-md-3">
                                            <label>Total Base Count</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="total" id="total" class="form-control"> 
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" id="calculate" class="btn btn-primary">Calculate</button>
                                        </div>
                                    </div>
                                    <div class="form-row" style="margin-top:30px;margin-bottom:20px">
                                        
                                        <div class="col-md-6">
                                        <button type="submit" style="float:right" id="submit" class="btn btn-primary">Submit</button> 
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" id="cancel" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
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
    $("#AddRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#InventoryAddProducts tr").length;
    	//   alert("dfhbgkj");
      rowno = rowno+1;
    
		if(rowno < 20){
		$("#InventoryAddProducts tr:last").after("<tr><td><div class=\"form-group\"><select name=\"kpi[]\" style=\"width:200px\" id=\"kpi"+rowno+"\" onchange=\"myFunction("+rowno+")\" class=\"form-control\"><option value=\"total_amt_spent\">Total Amount Spent</option><option value=\"visits\">Visit</option><option value=\"last_visit_date\">Last Visit Date</option><option value=\"last_visit_range\">Last Visit Range</option><option value=\"points\">Points</option><option value=\"gender\">Gender</option><option value=\"age\">Age</option><option value=\"package_subscriber\">Package Subscribers</option><option value=\"avg_order\">Average Order</option><option value=\"billing_date\">Billing Date</option><option value=\"birthday\">BirthDay</option><option value=\"anniversary\">Anniversary</option><option value=\"package_expiry\">Package Expiry Days Remaining</option></select></div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"srange"+rowno+"\"><input type=\"int\" class=\"form-control\" name=\"start_range[]\"  placeholder=\"start Range\"></div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"erange"+rowno+"\"><input type=\"int\" class=\"form-control\" name=\"end_range[]\" placeholder=\"End Range\"></div> </td><td><div style=\"width:200px;\" class=\"form-group\" id=\"select_date"+rowno+"\" hidden><input type=\"date\" class=\"form-control\" name=\"date[]\" placeholder=\"Select Date\"></div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"date_srange"+rowno+"\" hidden><input type=\"date\" class=\"form-control\" name=\"start_range_date[]\"  placeholder=\"start Date\"> </div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"date_erange"+rowno+"\" hidden><input type=\"date\" class=\"form-control\" name=\"end_range_date[]\" placeholder=\"End Range\"></div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"value"+rowno+"\" hidden><input type=\"int\" class=\"form-control\" min=\"0\" name=\"value[]\" placeholder=\"Enter Value\"></div></td><td><div style=\"width:200px;\" class=\"form-group\" id=\"select_opt"+rowno+"\" hidden><select class=\"form-control\" name=\"select_option[]\" id=\"select"+rowno+"\"><option value=\"Mr.\">Male</option><option value=\"Ms.\">Female</option></select></div></td></tr>");	
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
	$("#kpi").on('change', function(e) {
		
			// alert("gii");
            var kpi = $('#kpi').val();
            if(kpi == 'total_amt_spent' || kpi == 'visits' || kpi == 'points' || kpi == 'age' || kpi == 'last_visit_range'){
                $('#srange').removeAttr('hidden');
                $('#erange').removeAttr('hidden');
                $("#select_date").attr('hidden', true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#value").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
				$("#service_reminder").attr('hidden', true);
                
            }else if(kpi == 'last_visit_date'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").removeAttr('hidden'); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#value").attr('hidden', true);
                $("#select_opt").attr('hidden', true); 
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'gender'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").attr('hidden',true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#value").attr('hidden', true);
                $("#select_opt").removeAttr('hidden');
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'avg_order'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").attr('hidden',true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
                $("#value").removeAttr('hidden');
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'billing_date' || kpi=='birthday' || kpi == 'anniversary'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#date_erange").removeAttr('hidden'); 
                $("#date_srange").removeAttr('hidden'); 
                $("#select_range").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
                $("#value").attr('hidden',true);
				$("#service_reminder").attr('hidden', true);
            }
            else if(kpi == 'package_subscriber'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").attr('hidden', true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
                $("#value").attr('hidden',true);
				$("#service_reminder").attr('hidden', true);

            }else if(kpi == 'package_expiry'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").attr('hidden', true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
                $("#value").removeAttr('hidden',true);
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'repeat_service_reminder'){
                $('#srange').attr('hidden',true);
                $('#erange').attr('hidden',true);
                $("#select_date").attr('hidden', true); 
                $("#date_srange").attr('hidden', true); 
                $("#date_erange").attr('hidden', true); 
                $("#select_opt").attr('hidden', true);
                $("#value").attr('hidden',true);
				$("#action_row").attr('hidden',true);
				$("#service_reminder").removeAttr('hidden');
            }
	});
	
    function myFunction(no){

            var kpi = $('#kpi'+no).val();
            // alert(kpi);
            if(kpi == 'total_amt_spent' || kpi == 'visits' || kpi == 'points' || kpi == 'age' || kpi == 'last_visit_range'){
                $('#srange'+no).removeAttr('hidden');
                $('#erange'+no).removeAttr('hidden');
                $("#select_date"+no).attr('hidden', true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden', true); 
                $("#select_opt"+no).attr('hidden', true);
				$("#service_reminder").attr('hidden', true);

            }else if(kpi == 'last_visit_date'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).removeAttr('hidden'); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden', true); 
                $("#select_opt"+no).attr('hidden', true);
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'gender'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).attr('hidden',true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden', true);
                $("#select_opt"+no).removeAttr('hidden');
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'avg_order'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).attr('hidden',true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).removeAttr('hidden');
                $("#select_opt"+no).attr('hidden', true);
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'package_subscriber'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).attr('hidden',true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden',true);
                $("#select_opt"+no).attr('hidden', true);
				$("#service_reminder").attr('hidden', true);
            }
            else if(kpi == 'billing_date' || kpi=='birthday' || kpi == 'anniversary'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_opt"+no).attr('hidden', true);
                $("#select_date"+no).removeAttr('hidden'); 
                $("#date_srange"+no).removeAttr('hidden'); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden',true);
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'package_expiry'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).attr('hidden',true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).removeAttr('hidden');
                $("#select_opt"+no).attr('hidden', true);
				$("#service_reminder").attr('hidden', true);
            }else if(kpi == 'repeat_service_reminder'){
                $('#srange'+no).attr('hidden',true);
                $('#erange'+no).attr('hidden',true);
                $("#select_date"+no).attr('hidden',true); 
                $("#date_srange"+no).attr('hidden', true); 
                $("#date_erange"+no).attr('hidden', true); 
                $("#value"+no).attr('hidden');
                $("#select_opt"+no).attr('hidden', true);
				$("#action_row").attr('hidden',true);
				$("#service_reminder").removeAttr('hidden');
            }

    }
</script>
<script type="text/javascript">

        $("input[name=\"start_range_date[]\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("input[name=\"end_range_date[]\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
           
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $("input[name=\"date[]\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });
</script>
<script type="text/javascript">
	$(document).on('change',"#InventoryAddProducts tr:last select[name=category_type]", function(e){
		var parameters = {
			'category_type' :  $(this).val()
		};
		$.getJSON("<?=base_url()?>BusinessAdmin/GetCategoriesByCategoryType", parameters)
		.done(function(data, textStatus, jqXHR) {
				var options = "<option value='' selected></option>"; 
				for(var i=0;i<data.length;i++){
					options += "<option value="+data[i].category_id+">"+data[i].category_name+"</option>";
				}
				$("#InventoryAddProducts tr:last select[name=category_name]").html("").html(options);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});

	$(document).on('change',"#InventoryAddProducts tr:last select[name=category_name]", function(e){
		var parameters = {
			'category_id' :  $(this).val()
		};
		$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
		.done(function(data, textStatus, jqXHR) {
				var options = "<option value='' selected></option>"; 
				for(var i=0;i<data.length;i++){
					options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
				}
				$("#InventoryAddProducts tr:last select[name=sub_category_name]").html("").html(options);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});

	$(document).on('change',"#InventoryAddProducts tr:last select[name=sub_category_name]", function(e){
		var parameters = {
			'sub_category_id' :  $(this).val()
		};
		$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
		.done(function(data, textStatus, jqXHR) {
				var options = "<option value='' selected></option>"; 
				for(var i=0;i<data.length;i++){
					options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
				}
				$("#InventoryAddProducts tr:last select[name=service_name]").html("").html(options);
		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});


    $("#SetRuleTags").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>BusinessAdmin/InsertRuleTag",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        // dataType : "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          if(data.success == 'true'){
                             toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
                        });setTimeout(function () { window.location.reload();}, 500);
            }
         	else if (data.success == 'false'){                   
                        toastr["success"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 500
                        });
          }
        },
        error: function(data){
          alert("Something went wrong!");
        }
      }); 
    });
	
</script>
