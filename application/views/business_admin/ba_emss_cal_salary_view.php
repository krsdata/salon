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
                <h1 class="h3 mb-3">Salary Management</h1>
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
					<!-- start -->
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="margin-left:10px;">
								<ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab-1">Salary</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-2">Attendance</a>
									</li>
								</ul>
							</div>
							<div class="card-body">
								<div class="tab-content">
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
									<div class="col-md-12">
								<div class="card">
										<div class="card-header">
												<h5 class="card-title">Check Salary</h5>
										</div>
										<div class="card-body">
												<form class="" id="form1" name="form1" method="GET" action="#">
														<div class="form-row">
																<div class="form-group col-md-2" style="text-align:center">
																		<label style="padding:6px">Select Month</label>
																</div>
																<div class="from-group col-md-2">
																		<select name="year" id="year" class="form-control"
																				style="width:150px;float:right" onchange="MyYear()" required>
																				<option selected disabled>Year</option>
																				<?php
																					for($i=2020;$i<=date('Y');$i++)
																					{
																				?>
																				<option value="<?=$i?>"><?=$i?></option>
																				<?php		
																					}
																				?>
																		</select>
																</div>
																<div class="from-group col-md-2">
																		<!-- <input type="month" class="form-control" id="month" name="month" > -->
																		<select name="month" id="month" class="form-control" style="width:150px;"
																				disabled>
																				<option selected disabled>Month</option>

																		</select>
																</div>
																<div class="form-group col-md-2" style="text-align:center">
																		<!-- <label class="float-center" style="padding:6px">Select Employee</label> -->
																</div>
																<div class="form-group col-md-1" style="text-align:left">
																		<select name="expert_id" id="expert_id" class="form-control" hidden>
																				<option value="" selected="true" disabled="disabled">Select Employee
																				</option>
																				<option value="All" selected>All</option>
																				<?php
																					foreach ($emp as $expert){
																				?>
																				<option value="<?=$expert['employee_id']?>">
																						<?=$expert['employee_first_name'],'  ',$expert['employee_last_name'];?>
																				</option>
																				<?php	
																					}
																				?>
																		</select>
																</div>
																<div class="form-group col-md-2" style="text-align:right">
																		<button type="submit" class="btn btn-primary"
																				onclick="enableButton2()">Calculate</button>
																</div>
																<div class="form-group col-md-1" style="text-align:right">
																		<button type="submit" class="btn btn-primary GetDataAttendance" id="button2"
																				onclick="exportTableToExcel('FillTxnDetails','Salary')"
																				disabled>Download</button>
																</div>
														</div>
												</form>
										</div>
								</div>
								<div class="card">
								<div class="card-body">
													<div class="row">
															<div class="col-md-12">
																	<table class="table table-striped table-hover" id="FillTxnDetails"
																			style="text-align:center;font-weight:900;width: 100%;">
																	</table>
															</div>
													</div>
											</div>
									</div>
									<br><br>
									<div class="card">
											<div class="card-header">
													<h5 class="card-title"><?php echo date("M-Y"); ?></h5>
											</div>
											<div class="card-body">
													<div class="row">
															<div class="col-md-12">
																	<table class="table table-striped table-hover" id="tabledata"
																			style="text-align:center;width: 100%;font-weight:bold">
								<tbody id="">
																				<tr style="font-weight: bold;background:#c5d0ff">
																						<td>Employee ID</td>
																						<td>Name</td>
																						<td>Gross Salary</td>
																						<td>Professional Tax</td>
																						<td>Income Tax</td>
																						<td>Advance</td>
																						<!-- <td>Commission</td> -->
																						<td>Half Days</td>
																						<!-- <td>Leaves</td> -->
																						<td>OverTime</td>
																						<td>Net Payout</td>
																						<!-- <td>Finalize Month Salary</td> -->
																				</tr>
																					<?php
																						foreach ($salary as $item) {
																					?>
																					<tr>
																						<td><?php echo $item['employee_id'];?></td>
																						<td><?=$item['employee_first_name'];?>&ensp;<?=$item['employee_last_name'];?></td>
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['Salary'],1);?></td>
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['employee_pt'],1);?></td>
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['employee_income_tax'],1);?></td>
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['amt']);?></td>
																						<!-- <td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['comm']);?></td> -->
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['HalfDay']);?></td>
																						<!-- <td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['Leaves']);?></td> -->
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format($item['OverTime']);?></td>
																						<td><i class="fas fa-rupee-sign"></i>&nbsp;<?=number_format((round($item['Salary'],1)+round($item['OverTime']))-($item['amt']+round($item['HalfDay']))-($item['employee_pt']+$item['employee_income_tax']));?></td>
																						<?php
																					}
																					?>
																				</tr>
																		</tbody>
																</table>
														</div>
												</div>
										</div>
								</div>
						</div>
										
									</div>
									<div class="tab-pane" id="tab-2" role="tabpanel">
										<div class="col-xl-12">
											<div class="card">
												<div class="card-header">
													<h4 style="font-weight:bold">Attendance</h4>
												</div>
												<form action="#" method="GET" id="GetResults">
													<div class="card-body">
														<div class="form-row">

															<!-- <div class="form-group col-md-2">
																
																<label text-align="center" value="attendance" name="groupName" style="font-weight:bold" id="groupName" class="form-control">Attendance</label>
															</div> -->
															<!-- <div class="form-group col-md-2">
																<label text-align="center" value="attendance" name="groupName" style="font-weight:bold;border:hidden" id="groupName" class="form-control"><h3><?=date('M');?>&nbsp;MTD</h3></label>
															</div> -->
															<div class="form-group col-md-2">
																<input type="text" value="<?=date('M');?> MTD" name="groupName" style="font-weight:bold;border:hidden" id="groupName" class="form-control"> 
															</div>
															<div class="form-group col-md-2" align="center">
																<label align="center" style="padding-top:8px;font-weight:bold">Select Month</label>
															</div>
															
															<div class="from-group col-md-2">
																<select name="year" id="year" class="form-control" style="width:150px;float:right;font-weight:bold">
																	<?php
																	for ($i = 2020; $i <= date('Y'); $i++) {
																	?>
																		<option value="<?= $i ?>"><?= $i ?></option>
																	<?php
																	}
																	?>
																</select>
															</div>
															<div class="from-group col-md-2">
																
																<select name="month" id="month" class="form-control" style="width:150px;font-weight:bold">
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

															<div class="from-group col-md-3" style="text-align:right">
																<button type="button" style="float:right" class="btn btn-success GetDataAttendance" onclick="enableButton2()">Submit</button>
															</div>
															<div class="form-group col-md-1" style="text-align: right">
																<input type="submit" class="btn btn-primary" id="button2" onclick="exportTableToExcel('FillTxnDetails','Attendance')" value="Download" disabled>
															</div>
															

														</div>
												</form>

												<div class="card-body" id="AttendanceDetail">
													<div class="row">
														<div class="col-md-12">
															<table class="table table-striped table-hover" style="text-align:center;width: 100%;font-weight:bold" id="FillTxnDetails">

																<tbody>
																	<tr>
																		<td>Employee_id</td>
																		<td>Name</td>
																		<td>Calendar Days</td>
																		<td>Working Days</td>
																		<td>Present</td>
																		<td>Leave</td>
																		<td>OverTime (hrs)</td>
																		<td>Week-Off</td>
																		<td>Holidays</td>
																		<td>Half-days</td>
																		<td>Net Days Present</td>

																	</tr>
																	<?php
																	foreach ($attendance_details as $attend) {
																	?>
																		<tr>
																			<td><?= $attend['employee_id'] ?></td>
																			<td><?= $attend['employee_first_name'],' ',$attend['employee_last_name'] ?></td>
																			<td><?= $attend['Calender_Days'] ?></td>
																			<td><?= $attend['Working-Days'] ?></td>
																			<td><?= $attend['Present'] ?></td>
																			<td><?= $attend['Absent'] ?></td>
																			<td><?= $attend['AttenOverTime'] ?></td>
																			<td><?= $attend['weekoff'] ?></td>
																			<td><?= (int) $attend['Holidays'] ?></td>
																			<td><?= (int) $attend['halfday'] ?></td>
																			<td><?= ($attend['Present'] - ($attend['halfday'] * 0.5)) ?></td>
																		</tr>
																	<?php
																	}
																	?>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
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
        $('#form1').validate({
            submitHandler: function(form) {
                // alert("hii");
                var formData = $('#form1').serialize();
                event.preventDefault();
                $(this).blur();
                $.getJSON("<?=base_url()?>BusinessAdmin/GetSalaryEmployee", formData)
                    .done(function(data, textStatus, jqXHR) {
                        // alert(data.result.length);
                        
                            var temp_str =
                                "<tr><th>Sr.No</th><th>Employee Id</th><th>Name</th><th>Salary</th><th>Professional Tax</th><th>Income Tax</th><th>Advance</th><th>Commission</th><th>Half Day</th><th>Leaves</th><th>OverTime</th><th>Net Payout</th><th>Download</th></tr>";
                            var formatter = new Intl.NumberFormat('en-IN', {
                                // style: 'currency',
                                // currency: 'INR',
                            });
                            for (var i = 0; i < data.result.length; i++) {
                                temp_str += "<tr>";
                                temp_str += "<td>" + (i + 1) + "</td>";
                                temp_str += "<td>" + data.result[i].expert_id + "</td>";
                                temp_str += "<td>" + data.result[i].employee_name + "</td>";;
                                temp_str += "<td>" + formatter.format(data.result[i].salary) +
                                    "</td>";
                                temp_str += "<td>- " + formatter.format(data.result[i].professional_tax) + "</td>";
                                temp_str += "<td>- " + formatter.format(data.result[i].income_tax) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].advance) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].commission) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].halfday) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].leaves) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].overtime) + "</td>";
                                temp_str += "<td>" + formatter.format(data.result[i].netpayout) + "</td>";
                                temp_str += 	"<td><a href='<?=base_url()?>BusinessAdmin/PrintSalary/"+data.result[i].expert_id+"/"+data.result[i].month+"' class='btn btn-success' id='Print' target='_blank'>Print</a></td>";
                                temp_str += "</tr>";
                            }
                            
                        
                        $("#FillTxnDetails").html("").html(temp_str);
                        $("#ModalCustomerDetails").modal('show');
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown.toString());
                    });
            },
        });
        </script>
        <script>
        $(document).ready(function() {
            $(document).on('click', '.showSalary', function(event) {
                event.preventDefault();
                this.blur(); // Manually remove focus from clicked link.
                var emp_id = document.getElementById("expertid").value;
                var month = document.getElementById("month_name").value;
                // alert("hii");
                var parameters = {
                    emp_id,
                    month
                };
                $.getJSON("<?=base_url()?>BusinessAdmin/ShowSalary", parameters)
                    .done(function(data, textStatus, jqXHR) {
                        var formatter = new Intl.NumberFormat('en-IN', {
                                // style: 'currency',
                                // currency: 'INR',
                            });
                        var temp_str =
                            "<tr><th>Employee ID</th><th>Name</th><th>Salary</th><th>Advance</th><th>commission</th><th>Payout</th></tr>";
                        temp_str += "<tr>";
                        temp_str += "<td>" + data.res_arr[0].employee_id + "</td>";
                        temp_str += "<td>" + data.res_arr[0].employee_first_name + " " + data
                            .res_arr[0].employee_last_name + "</td>";
                        temp_str += "<td>" + data.res_arr[0].salary + " Rs</td>"
                        temp_str += "<td>" + data.res_arr[0].advance + " Rs</td>"
                        temp_str += "<td>" + data.res_arr[0].commission + " Rs</td>";
                        temp_str += "<td>" + formatter.format(data.res_arr[0].payout) + " Rs</td>";
                        temp_str += "</tr>";
                        $("#FillTxnDetails").html("").html(temp_str);
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown.toString());
                    });
            });
        });
        // insert data into salary table
        $(document).on('click', '.employee-insert', function(event) {
            event.preventDefault();
            this.blur(); // Manually remove focus from clicked link.
            var parameters = {
                "employee_id": $(this).attr('employee_id'),
                "salary": $(this).attr('salary'),
                "advance": $(this).attr('advance'),
                "commission": $(this).attr('commission'),
                "payout": $(this).attr('payout'),
            };
            $.ajax({
                url: "<?=base_url()?>BusinessAdmin/InsertSalary",
                data: parameters,
                type: "POST",
                // crossDomain: true,
                cache: false,
                // dataType: "json",
                success: function(data) {
                    if (data.success == 'true') {
                        toastr["success"](data.message, "", {
                            positionClass: "toast-top-right",
                            progressBar: "toastr-progress-bar",
                            newestOnTop: "toastr-newest-on-top",
                            rtl: $("body").attr("dir") === "rtl" || $("html").attr(
                                "dir") === "rtl",
                            timeOut: 1000
                        });
                        setTimeout(function() {
                            location.reload(1);
                        }, 1000);
                    }
                }
            });
        });
        </script>
        <script>
        function exportTableToExcel(tableID, filename = '') {
            document.getElementById("button2").disabled = true;
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'excel_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
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
        function MyYear() {
            // alert("Hii");
            var year = parseInt(document.getElementById("year").value);
            document.getElementById("month").disabled = false;
            document.getElementById("month").innerHTML = "";
            var dt = new Date();
            var y = dt.getFullYear();
            var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ];
            if (year == y) {

                var sel = document.getElementById('month');
                var opt = document.createElement('option');
                var DateObj = new Date();
                var months = DateObj.getMonth();
                // alert(months('1'));
                for (var i = 1; i <= months; i++) {
                    var opt = document.createElement('option');
                    opt.appendChild(document.createTextNode(month[i - 1]));
                    opt.value = i;
                    sel.appendChild(opt);
                }
            } else {
                var sel = document.getElementById('month');
                for (var i = 1; i <= 12; i++) {
                    var opt = document.createElement('option');
                    opt.appendChild(document.createTextNode(month[i - 1]));
                    opt.value = i;
                    sel.appendChild(opt);
                }
            }
        }
        </script>
        <script>
        function enableButton2() {
            document.getElementById("button2").disabled = false;
        }
        </script>
	<script>
		$(document).ready(function() {

			$("#GetResults").validate({
				errorElement: "div",
				rules: {
					"month": {
						required: true
					},
					"year": {
						required: true
					}
				},
				submitHandler: function(form) {
					var formData = $("#GetResults").serialize();
					$.ajax({
						url: "<?= base_url() ?>BusinessAdmin/Attendance",
						data: formData,
						type: "GET",
						// crossDomain: true,
						cache: false,
						// dataType: "json",
						success: function(data) {
							if (data.success == 'true') {
								JSONToCSVConvertor(data.result, "Reports", true);
							} else if (data.success == 'false') {
								$("#ErrorModalMessage").html("").html(data.message);
								$("#defaultModalDanger").modal('show');
							}
						},
						error: function(data) {
							$("#ErrorModalMessage").html("").html(data.message);
							$("#defaultModalDanger").modal('show');
						}
					});
				},
			});
		});
	</script>
	
	<!-- show data -->
	<script>
		$(document).ready(function() {
			$(document).on('click', '.GetDataAttendance', function(event) {
				event.preventDefault();
				this.blur(); // Manually remove focus from clicked link.
				// var emp_id=$(this).attr('employee_id');
				// alert(emp_id);
				// var group_name=document.getElementById("groupName").value;
				var group_name = 'attendance';
				var month = document.getElementById("month").value;
				var year = document.getElementById("year").value;
				
				var month = year.concat('-', month);
                var m= new Date();
                var y=m.getFullYear();
                var n=m.getMonth();
                 n = n + 1;
                 mon='0';
                 if(n < 10){
                     mon = y + '-' + mon + n;
                 }else{
                     mon= y + '-' + n;
                 }
				// alert(month);
				// var to_date=document.getElementById("to_date").value;
				var parameters = {
					group_name,
					month
				};
				if (month == mon) {
					$.getJSON("<?= base_url() ?>BusinessAdmin/GetDataAttendance", parameters)
						.done(function(data, textStatus, jqXHR) {
							var temp_str = "<tr><th>Sr.No</th><th>Employee Id</th><th>Month</th><th>Employee Name</th><th>Calender Days</th><th>Working Days</th><th>Present</th><th>Leave</th><th>OverTime (hrs)</th><th>Week-Off</th><th>Holidays</th><th>Half Days</th><th>Net Days Present</th></tr>";
							for (var i = 0; i < data.result.length; i++) {
								temp_str += "<tr>";
								temp_str += "<td>" + (i + 1) + "</td>";
								temp_str += "<td>" + data.result[i].employee_id + "</td>";
								temp_str += "<td>"+data.result[i].month+"</td>";
								temp_str += "<td>" + data.result[i].employee_first_name+" "+data.result[i].employee_last_name + "</td>";
								temp_str += "<td>" + data.result[i].Calender_Days + "</td>";
								temp_str += "<td>" + data.result[i].Working_Days + "</td>";
								temp_str += "<td>" + data.result[i].Present + "</td>";
								temp_str += "<td>" + data.result[i].Absent + "</td>";
								temp_str += "<td>" + data.result[i].AttenOverTime + "</td>";
								temp_str += "<td>" + data.result[i].weekoff + "</td>";
								temp_str += "<td>" + data.result[i].Holidays + "</td>";
								temp_str += "<td>" + data.result[i].halfday + "</td>";
								temp_str += "<td>" + data.result[i].Net_Days_Present + "</td>";
								temp_str += "</tr>";

							}
							$("#FillTxnDetails").html("").html(temp_str);


							$("#ModalCustomerDetails").modal('show');
						})

						.fail(function(jqXHR, textStatus, errorThrown) {
							console.log(errorThrown.toString());
						});
				}   else if (month != mon) {
					$.getJSON("<?= base_url() ?>BusinessAdmin/GetDataAttendance", parameters)
						.done(function(data, textStatus, jqXHR) {
							var temp_str = "<tr><th>Sr.No</th><th>Employee Id</th><th>Month</th><th>Employee Name</th><th>Calender Days</th><th>Working Days</th><th>Present</th><th>Leave</th><th>OverTime (hrs)</th><th>Week-Off</th><th>Holidays</th><th>Half Days</th><th>Net Days Present</th></tr>";

							for (var i = 0; i < data.result.length; i++) {
								temp_str += "<tr>";
								temp_str += "<td>" + (i + 1) + "</td>";
								temp_str += "<td>" + data.result[i].employee_id + "</td>";
								temp_str += "<td>" + data.result[i].month + "</td>";
								temp_str += "<td>" + data.result[i].employee_first_name+" "+data.result[i].employee_last_name + "</td>";
								temp_str += "<td>" + data.result[i].Calender_Days + "</td>";
								temp_str += "<td>" + data.result[i].Working_Days + "</td>";
								temp_str += "<td>" + data.result[i].Present + "</td>";
								temp_str += "<td>" + data.result[i].Leave + "</td>";
								temp_str += "<td>" + data.result[i].AttenOverTime + "</td>";
								temp_str += "<td>" + data.result[i].weekoff + "</td>";
								temp_str += "<td>" + data.result[i].Holidays + "</td>";
								temp_str += "<td>" + data.result[i].Halfday + "</td>";
								temp_str += "<td>" + data.result[i].Net_Days_Present + "</td>";
								temp_str += "</tr>";

							}
							$("#FillTxnDetails").html("").html(temp_str);
							$("#ModalCustomerDetails").modal('show');
						})
						.fail(function(jqXHR, textStatus, errorThrown) {
							console.log(errorThrown.toString());
						});
				}
			});
		});
	</script>
	 <script>
        function enableButton2() {
			document.getElementById("groupName").value = ' ';
            document.getElementById("button2").disabled = false;
        }
    </script>
