<?php
	$this->load->view('cashier/cashier_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('cashier/cashier_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('cashier/cashier_top_nav_view');
		?>	
			
			<main class="content">
				<div class="container-fluid p-0">
					<h1 class="h3">Attendance Marker</h1>
					<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-6">
									<div class="card-title"><?php echo "Employees" ?></div>
									</div>
									<div class="col-md-6">
									
									</div>
								</div>
								
								
							</div>
							<div class="card-body">
															<!--start row div  -->
														<form action="#" method="POST" id="MarkAttendance">
															<div class="form-row">
																		<div class="form-group col-md-2">
																			<label>Select Employee</label>
																		</div>
																		<div class="form-group col-md-3">
																			<select name="expert_id" id="expert_id" class="form-control">
																				<option value=""  selected="true" disabled="disabled">Select Employee</option>
																				<?php
																						foreach($experts as $expert)
																						{
																				?>		
																				
																					<option value="<?=$expert['employee_id']?>"><?=$expert['employee_first_name'],'  ',$expert['employee_last_name'];?></option>
																					<?php	
																						}
																					?>
																				</select>
																		</div> 
																
																<div class="form-group col-md-3">
																	<button type="submit" class="btn btn-success addAttendanceInTime"><i class="fa fa-plus"></i>In-time</button>&nbsp;

																	<button class="btn btn-success addAttendanceOutTime"><i class="fa fa-plus"></i>Out-Time</button>
																</div>
															</div>
														</form>
															<!-- end row div -->
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-6">
									<div class="card-title"><?php echo "Today : ".date("d-m-Y"); ?></div>
									</div>
									<div class="col-md-6">
									
									</div>
								</div>
								
								
							</div>
							<div class="card-body">
							<table id="" class="table table-striped datatables-buttons" style="width:100%;text-align:center;">
													<thead>
														<tr>
															<th>Employee Name</th>
															<th>Employee Mobile</th>
															<th>Employee Address</th>
															<th>IN-TIME</th>
															<th>OUT-TIME</th>
															<th>Working-Hours</th>
															<!-- <th>Over-Time</th> -->
																										
														</tr>
													</thead>
													<tbody>			
														<?php 
														foreach($attendance_today as $attend)
														{
															if($attend['working_hours'] == 0)
															{
																$work=' ';
															}
															else{
																$work=round($attend['working_hours']/60,2).' hrs';
															}
														?>
														<tr>
															<td><?=$attend['employee_first_name'].' '.$attend['employee_last_name']?></td>
															<td><?=$attend['employee_mobile']?></td>
															<td><?=$attend['employee_address']?></td>
																									
																
															<!-- <button class="btn btn-success addAttendanceOutTime" employee_id="<?=$attend['employee_id']?>"><i class="fa fa-plus"></i>Out-Time</button> -->
															<!-- </td> -->
															<td><?=$attend['in_time']?></td>
															<td><?=$attend['out_time']?></td>
															<td ><?=$work?> </td>
															<!-- <td>
																<?=$attend['over_time']?>
															</td> -->
															</tr>
															<?php
														}
														?>
													</tbody>
												</table>
															
							</div>
						</div>
					</div>

			</div>
			<!-- open model	 -->
			<!-- end modal -->
			</div>			
		</main>
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
<script>
	 $(document).ready(function(){
				

				//Amrk Attendance
		$(document).on('click','.addAttendanceInTime',function(event) {
			event.preventDefault();
			
			this.blur(); // Manually remove focus from clicked link.
			var emp_id=document.getElementById("expert_id").value;
			// alert(emp_id);
			// var emp_id=$(this).attr('employee_id');
			// var emp_id=3;
			// var emp_id=$("#MarksAttendance select[name=expert_id]").value;
			// alert($("#MarksAttendance select[name=expert_id]").val());
				// document.getElementByClass('.addAttendanceInTime').disabled = true;
				var formData = $("#AddInTime").serialize();	
				$.ajax({
					url: "<?=base_url()?>index.php/Cashier/AddInTime",
					data: {emp_id:emp_id},
					type: "POST",
					crossDomain: true,
					cache: false,
					dataType : "json",
					success: function(data) {
						if(data.success == 'true'){
							
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
			$("#ModalAddInTime").modal('show');	
		});
		
		$("#AddInTime").validate({
			errorElement: "div",
			rules: {	
				"attendance_date" : {
					required : true
				},
				"in_time":{
					required : true
				}
			},
			submitHandler: function(form) {		
				var formData = $("#AddInTime").serialize();			
				$.ajax({
					url: "<?=base_url()?>index.php/Cashier/AddIntime/",
					data: formData,
					type: "POST",
					crossDomain: true,
					cache: false,
					dataType : "json",
					success: function(data) {
						if(data.error == 'true'){
							// $("#ModalAddInTime").modal('hide'); 
							toastr["success"](data.message+"","", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
							// alert(data.message);
						}
						else if (data.error='false'){   
							// alert(data.message);

	//was						
							if($('.feedback').hasClass('alert-success')){
								$('.feedback').removeClass('alert-success').addClass('alert-danger');
							}
							else{
								$('.feedback').addClass('alert-danger');
							}
							$('.alert-message').html("").html(data.message); 
//was
						}
					},
					error: function(data){
						$('.feedback').addClass('alert-danger');
						$('.alert-message').html("").html(data.message); 
					}
				});
			}
		});



		$(document).on('click','.addAttendanceOutTime',function(event) {
			event.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			// var emp_id=$(this).attr('employee_id');
			// alert(emp_id);
			var emp_id=document.getElementById("expert_id").value;
		
				
					
				$.ajax({
					url: "<?=base_url()?>index.php/Cashier/AddOutTime/",
					data: {emp_id:emp_id},
					type: "POST",
					crossDomain: true,
					cache: false,
					dataType : "json",
					success: function(data) {
						if(data.success == 'true'){
							
							toastr["success"](data.message,"", {
								positionClass: "toast-top-right",
								progressBar: "toastr-progress-bar",
								newestOnTop: "toastr-newest-on-top",
								rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
								timeOut: 2000
							});
							setTimeout(function () { location.reload(1); }, 2000);
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
			
		});

  });
</script>

<script type="text/javascript">
			function disableButton(btn){
				document.getElementById(btn.id).disabled = true;
				alert("Button has been disabled.");
			}
		</script>