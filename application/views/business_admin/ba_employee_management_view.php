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
				<h1 class="h3 mb-3">Employee Management</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Success</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<p class="mb-0" id="SuccessModalMessage"></p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="ModalAddEmployee" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1">
										<h5 class="modal-title  text-white font-weight-bold">Add Employee</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
											<form id="AddEmployee" method="POST" action="#">
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>First Name</label>
													<input type="text" class="form-control" placeholder="First Name" name="employee_first_name">
												</div>
												<div class="form-group col-md-4">
													<label>Last Name</label>
													<input type="text" class="form-control" placeholder="Last Name" name="employee_last_name">
												</div>
												<div class="form-group col-md-4">
													<label>Nick Name</label>
													<input type="text" class="form-control" placeholder="Nick Name" name="nick_name">
												</div>
												
												</div>									
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Email</label>
													<input type="email" class="form-control" placeholder="Email ID" name="employee_email">
												</div>
												<div class="form-group col-md-4">
													<label>Mobile</label>
													<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="employee_mobile">
												</div>
												
												
												<div class="form-group col-md-4">
												<label>Outlet</label>
												<select name="employee_business_outlet" class="form-control">
													<option value="" selected>Select Outlet</option>
													<?php
														foreach ($business_outlet_details as $outlet):
													?>
													<option value="<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></option>
													<?php		
														endforeach;
													?>
												</select>
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Expertise</label>
													<input type="text" class="form-control" name="employee_expertise" placeholder="Expertise">
												</div>
												<div class="form-group col-md-4">
													<label>Role</label>
											<select name="employee_role" class="form-control">
														<option value="" selected>Select Role</option>
														<option value="Cashier">Cashier</option>
														<option value="Expert">Expert</option>	
													</select>
												</div>
											
												<div class="form-group col-md-4">
													<label>Set Password (Optional for expert)</label>
													<input type="text" class="form-control" name="employee_password" placeholder="Password">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Date of Joining</label>
													<input type="text" class="form-control" name="employee_date_of_joining" placeholder="Date Of Joining">
												</div>		
											</div>	
											<div class="form-row">
												<div class="form-group col-md-12">
													<label>Address</label>
													<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="employee_address">
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
						<div class="modal fade" id="centeredModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header" style="background-color:#47bac1;">
										<h5 class="modal-title text-white">Edit Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body m-3">
										<div class="row">
											<div class="col-md-12">
												<form id="EditEmployee" method="POST" action="#">
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>First Name</label>
															<input type="text" class="form-control" placeholder="First Name" name="employee_first_name">
														</div>
														<div class="form-group col-md-4">
															<label>Last Name</label>
															<input type="text" class="form-control" placeholder="Last Name" name="employee_last_name">
														</div>
														<div class="form-group col-md-4">
															<label>Nick Name</label>
															<input type="text" class="form-control" placeholder="Nick Name" name="nick_name">
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>Mobile</label>
															<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="employee_mobile">
														</div>
														<div class="form-group col-md-4">
															<label>Expertise</label>
															<input type="text" class="form-control" name="employee_expertise" placeholder="Expertise">
														</div>
														<div class="form-group col-md-4">
															<label>Date of Joining</label>
															<input type="text" class="form-control" name="employee_date_of_joining" placeholder="Date Of Joining">
														</div>
													</div>
													<div class="form-row">
														<div class="form-group col-md-4">
															<label>Email</label>
															<input type="email" class="form-control" placeholder="Email ID" name="employee_email">
														</div>
														<div class="form-group col-md-4">
															<label>Outlet</label>
																	<select name="employee_business_outlet" class="form-control">
																<option value="" selected>Select Outlet</option>
																<?php
																	foreach ($business_outlet_details as $outlet):
																?>
																<option value="<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></option>
																<?php		
																	endforeach;
																?>
															</select>
														</div>
														<div class="form-group col-md-4">
															<label>Address</label>
															<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="employee_address">
														</div>
													</div>
													<div class="form-group">
														<input class="form-control" type="hidden" name="employee_id" readonly="true">
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
						<div class="row">					
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
									<div class="row">
											<div class="col-md-6">
											<h5 class="card-title">Employee Details</h5>
											</div>								
											<div class="col-md-6">
											<button class="btn btn-success float-right" data-toggle="modal" data-target="#ModalAddEmployee"><i class="fas fa-fw fa-plus"></i>Add Employee</button>
											</div>
										</div>
										
									</div>
									<div class="card-body">
										<div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Success</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<p class="mb-0" id="SuccessModalMessage"></p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										<div class="modal fade" id="centeredModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Edit Details</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body m-3">
														<div class="row">
															<div class="col-md-12">
																<form id="EditEmployee" method="POST" action="#">
																	<div class="form-row">
																		<div class="form-group col-md-6">
																			<label>First Name</label>
																			<input type="text" class="form-control" placeholder="First Name" name="employee_first_name">
																		</div>
																		<div class="form-group col-md-6">
																			<label>Last Name</label>
																			<input type="text" class="form-control" placeholder="Last Name" name="employee_last_name">
																		</div>
																	</div>
																	<div class="form-group">
																		<label>Address</label>
																		<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="employee_address">
																	</div>
																	<div class="form-group">
																		<label>Email</label>
																		<input type="email" class="form-control" placeholder="Email ID" name="employee_email">
																	</div>
																	<div class="form-group">
																				<label>Mobile</label>
																		<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="employee_mobile">
																	</div>
																	<div class="form-group">
																		<label>Expertise</label>
																		<input type="text" class="form-control" name="employee_expertise" placeholder="Expertise">
																	</div>
																	<div class="form-group">
																		<label>Date of Joining</label>
																		<input type="text" class="form-control" name="employee_date_of_joining" placeholder="Date Of Joining">
																	</div>
																	<div class="form-group">
																		<label>Outlet</label>
																		<select name="employee_business_outlet" class="form-control">
																			<option value="" selected>Select Outlet</option>
																			<?php
																				foreach ($business_outlet_details as $outlet):
																			?>
																			<option value="<?=$outlet['business_outlet_id']?>"><?=$outlet['business_outlet_name']?></option>
																			<?php		
																				endforeach;
																			?>
																		</select>
																	</div>
																	<div class="form-group">
																		<input class="form-control" type="hidden" name="employee_id" readonly="true">
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
										<table class="table table-hover table-striped datatables-basic" style="width: 100%;">
											<thead>
												<tr>
													<th>Emp Name</th>
													<th>Mobile</th>
													<th>Role</th>
													<th>Address</th>													
													<th>Joining Date</th>
													<th>Status</th>
													<th style="width: 15%;">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
													foreach ($business_admin_employees as $employee):
												?>
												<tr>
													<td><?=$employee['employee_first_name']?> <?=$employee['employee_last_name']?></td>
													<td><?=$employee['employee_mobile']?></td>
													<td><?=$employee['employee_role']?></td>
													<td><?=$employee['employee_address']?></td>													
													<td><?=$employee['employee_date_of_joining']?></td>
													<td><?php if($employee['employee_is_active']==1){echo "Active";}else{echo "Inactive";}?></td>
													<td class="table-action">
														<button type="button" class="btn btn-primary employee-edit-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="edit-2"></i>
														</button>
													<?php
														if($employee['employee_is_active'] == 1){
													?>
														<button type="button" class="btn btn-success employee-deactivate-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="user-x"></i>
														</button>
													<?php
														}
														else{
													?>
														<button type="button" class="btn btn-danger employee-activate-btn" employee_id="<?=$employee['employee_id']?>">
															<i class="align-middle" data-feather="user-plus"></i>
														</button>
													<?php
														}
													?>
													</td>
												</tr>	
												<?php		
													endforeach;
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>	
						</div>
					</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script type="text/javascript">
	$("input[name=\"employee_date_of_joining\"]").daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			locale: {
	      		format: 'YYYY-MM-DD'
			}
		});
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
			responsive: true,
			"order": [[ 5, "asc" ]]
		});
  	$("#AddEmployee").validate({
	  	errorElement: "div",
	    rules: {
	        "employee_first_name" : {
            required : true,
            maxlength : 50
	        },
	        "employee_last_name" : {
            required : true,
            maxlength : 50
	        },
	        "employee_address" : {
	          required : true
	        },
	        "employee_email" : {
	        	email : true,
	        	required : true,
	        	maxlength : 100
	        },
	        "employee_mobile":{
	        	maxlength : 15,
	        	required : true
	        },
	        "employee_role" : {
	        	maxlength : 50,
	        	required : true
	        },
	        "employee_date_of_joining" : {
	        	required : true
	        },
	        "employee_business_outlet" : {
	        	required : true
	        }  
	    },
	    submitHandler: function(form) {
				var formData = $("#AddEmployee").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddEmployee",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddEmployee").modal('hide');
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

		$("#EditEmployee").validate({
	  	errorElement: "div",
	    rules: {
	        "employee_first_name" : {
            required : true,
            maxlength : 50
	        },
	        "employee_last_name" : {
            required : true,
            maxlength : 50
	        },
	        "employee_address" : {
	          required : true
	        },
	        "employee_email" : {
	        	email : true,
	        	required : true,
	        	maxlength : 100
	        },
	        "employee_mobile":{
	        	maxlength : 15,
	        	required : true
	        },
	        "employee_date_of_joining" : {
	        	required : true
	        },
	        "employee_business_outlet" : {
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#EditEmployee").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditEmployee",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#centeredModalDanger").modal('hide');
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

		$(document).on('click','.employee-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
            employee_id : $(this).attr('employee_id')
      };
      $.getJSON("<?=base_url()?>BusinessAdmin/GetEmployee", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditEmployee input[name=employee_first_name]").attr('value',data.employee_first_name);
        $("#EditEmployee input[name=employee_last_name]").attr('value',data.employee_last_name);
				$("#EditEmployee input[name=nick_name]").attr('value',data.employee_nick_name);
        $("#EditEmployee input[name=employee_address]").attr('value',data.employee_address);
        $("#EditEmployee input[name=employee_email]").attr('value',data.employee_email);
        $("#EditEmployee input[name=employee_mobile]").attr('value',data.employee_mobile);
    
        $("#EditEmployee input[name=employee_expertise]").attr('value',data.employee_expertise);
        $("#EditEmployee input[name=employee_date_of_joining]").attr('value',data.employee_date_of_joining);
				$("#EditEmployee select[name=employee_business_outlet]").val(data.employee_business_outlet);
        $("#EditEmployee input[name=employee_id]").attr('value',data.employee_id);

        $("#centeredModalDanger").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('click','.employee-deactivate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        "employee_id" : $(this).attr('employee_id'),
        "activate" : 'false',
        "deactivate" : 'true'
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/ChangeEmployeeStatus",
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
        }
			});
    });

    $(document).on('click','.employee-activate-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      
      var parameters = {
        "employee_id" : $(this).attr('employee_id'),
        "activate" : 'true',
        "deactivate" : 'false'
      };
      
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/ChangeEmployeeStatus",
        data: parameters,
        type: "POST",
        crossDomain: true,
				cache: false,
        dataType : "json",
    		success: function(data) {
          if(data.success == 'true'){
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
