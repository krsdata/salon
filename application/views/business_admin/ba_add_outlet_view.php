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
				<h1 class="h3 mb-3">Business Outlets</h1>
				<div class="row">
					<div class="col-md-12">
						<div class="modal fade" id="ModalAddOutlet" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-white">Add Outlet</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="AddOutlet" method="POST" action="#">
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Outlet Name</label>
													<input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
												</div>
												<div class="form-group col-md-4">
													<label>Firm Name</label>
													<input type="text" class="form-control" placeholder="Firm Name" value="" name="business_outlet_firm_name">
												</div>
											
												<div class="form-group col-md-4">
													<label>GSTIN</label>
													<input type="text" class="form-control" placeholder="GST IN" name="business_outlet_gst_in" minlength="15" maxlength="15">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Address</label>
													<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
												</div>
											
												<div class="form-group col-md-4">
													<label>Email</label>
													<input type="email" class="form-control" placeholder="Email ID" name="business_outlet_email">
												</div>
												<div class="form-group col-md-4">
													<label>Mobile</label>
													<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="business_outlet_mobile">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Landline</label>
													<input type="text" class="form-control" placeholder="Landline Number"  name="business_outlet_landline" maxlength="15">
												</div>
											
												<div class="form-group col-md-4">
												
													<label>State</label>
													<select name="business_outlet_state" class="form-control">
														<option value="" selected>Select State</option>
														<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
														<option value="Andhra Pradesh">Andhra Pradesh</option>
														<option value="Arunachal Pradesh">Arunachal Pradesh</option>
														<option value="Assam">Assam</option>
														<option value="Bihar">Bihar</option>
														<option value="Chandigarh">Chandigarh</option>
														<option value="Chhattisgarh">Chhattisgarh</option>
														<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
														<option value="Daman and Diu">Daman and Diu</option>
														<option value="Delhi">Delhi</option>
														<option value="Goa">Goa</option>
														<option value="Gujarat">Gujarat</option>
														<option value="Haryana">Haryana</option>
														<option value="Himachal Pradesh">Himachal Pradesh</option>
														<option value="Jammu and Kashmir">Jammu and Kashmir</option>
														<option value="Jharkhand">Jharkhand</option>
														<option value="Karnataka">Karnataka</option>
														<option value="Kerala">Kerala</option>
														<option value="Lakshadweep">Lakshadweep</option>
														<option value="Madhya Pradesh">Madhya Pradesh</option>
														<option value="Maharashtra">Maharashtra</option>
														<option value="Manipur">Manipur</option>
														<option value="Meghalaya">Meghalaya</option>
														<option value="Mizoram">Mizoram</option>
														<option value="Nagaland">Nagaland</option>
														<option value="Orissa">Orissa</option>
														<option value="Pondicherry">Pondicherry</option>
														<option value="Punjab">Punjab</option>
														<option value="Rajasthan">Rajasthan</option>
														<option value="Sikkim">Sikkim</option>
														<option value="Tamil Nadu">Tamil Nadu</option>
														<option value="Tripura">Tripura</option>
														<option value="Uttaranchal">Uttaranchal</option>
														<option value="Uttar Pradesh">Uttar Pradesh</option>
														<option value="West Bengal">West Bengal</option>
													</select>
												</div>		
												<div class="form-group col-md-4">								
													<label>City</label>
													<input type="text" class="form-control" name="business_outlet_city">
												</div>
											</div>
											<div class="form-row">	
												<div class="form-group col-md-4">
													<label>ZipCode</label>
													<input type="number" pattern="[0-9]+" maxlength="6" min="000000" max="999999" class="form-control" name="business_outlet_pincode">
												</div>
												<div class="form-group col-md-4">
												<label class="form-label">Facebook URL</label>
													<input type="text" class="form-control" name="business_outlet_facebook_url" placeholder="URL">
												</div>
												<div class="form-group col-md-4">
												<label class="form-label">Instagram URL</label>
													<input type="text" class="form-control" name="business_outlet_instagram_url" placeholder="URL">
												</div>
											</div>
											<div class="form-row ">	
												<div class="form-group col-md-6">
												<label>Bill Header Message</label>
													<textarea class="form-control" rows="2" placeholder="Bill Header Message" name="business_outlet_bill_header_msg"></textarea>
												</div>
												<div class="form-group col-md-6">
												<label>Bill Footer Message</label>
													<textarea class="form-control" rows="2" placeholder="Bill footer Message" name="business_outlet_bill_footer_msg"></textarea>
												</div>
											</div>
											<div class="form-row ">	
												<div class="form-group col-md-6">
													<label class="form-label">Latitude</label>
													<input type="text" class="form-control" name="business_outlet_latitude" placeholder="Outlet Latitude">
												</div>
												<div class="form-group col-md-6">
													<label class="form-label">Longitude</label>
													<input type="text" class="form-control" name="business_outlet_longitude" placeholder="Outlet Longitude">
												</div>
											</div>
											<div class="form-row">
												<div class="form-group col-md-12">
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
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-6">
									<h5 class="card-title">Outlet List</h5>
									</div>
									<div class="col-md-6">
									<!--<button class="btn btn-success  float-right" data-toggle="modal" data-target="#ModalAddOutlet"><i class="fas fa-fw fa-plus"></i>Add Outlet</button>-->
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
												<p class="mb-0" id="SuccessModalMessage"><p>
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
											<div class="modal-header" style="background-color:#47bac1;">
												<h5 class="modal-title text-white">Edit Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body m-3">
												<div class="row">
													<div class="col-md-12">
														<form id="EditOutlet" method="POST" action="#" enctype="multipart/form-data">
															<div class="form-row">
																<div class="form-group col-md-4">
																	<label><b>Outlet Name</b></label>
																	<input type="text" class="form-control" placeholder="Outlet Name" name="business_outlet_name">
																</div>
																<div class="form-group col-md-4">
																	<label><b>Firm Name</b></label>
																	<input type="text" class="form-control" placeholder="Firm Name" value="<?=$business_admin_details['business_admin_firm_name']?>" name="business_outlet_firm_name">
																</div>
															
																<div class="form-group col-md-4">
																	<label><b>GSTIN</b></label>
																	<input type="text" class="form-control" placeholder="GSTIN" name="business_outlet_gst_in" minlength="15" maxlength="15">
																</div>
																</div>
															<div class="form-row">
																<div class="form-group col-md-4">
																	<label><b>Address</b></label>
																	<input type="text" class="form-control" placeholder="Apartment, studio, or floor" name="business_outlet_address">
																</div>
															
																<div class="form-group col-md-4">
																	<label><b>Email</b></label>
																	<input type="email" class="form-control" placeholder="Email ID" name="business_outlet_email">
																</div>
																<div class="form-group col-md-4">
																	<label><b>Mobile</b></label>
																	<input type="text" class="form-control" placeholder="Mobile Number" data-mask="0000000000" name="business_outlet_mobile">
																</div>
															</div>
															<div class="form-row">
															<div class="form-group col-md-4">
																<label><b>Landline</b></label>
																<input type="text" class="form-control" placeholder="Landline Number" maxlength="15" name="business_outlet_landline">
															</div>
				
																<div class="form-group col-md-4">
																	<label><b>State</b></label>
														      <select name="business_outlet_state" class="form-control">
																		<option value="" selected>Select State</option>
																		<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
																		<option value="Andhra Pradesh">Andhra Pradesh</option>
																		<option value="Arunachal Pradesh">Arunachal Pradesh</option>
																		<option value="Assam">Assam</option>
																		<option value="Bihar">Bihar</option>
																		<option value="Chandigarh">Chandigarh</option>
																		<option value="Chhattisgarh">Chhattisgarh</option>
																		<option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
																		<option value="Daman and Diu">Daman and Diu</option>
																		<option value="Delhi">Delhi</option>
																		<option value="Goa">Goa</option>
																		<option value="Gujarat">Gujarat</option>
																		<option value="Haryana">Haryana</option>
																		<option value="Himachal Pradesh">Himachal Pradesh</option>
																		<option value="Jammu and Kashmir">Jammu and Kashmir</option>
																		<option value="Jharkhand">Jharkhand</option>
																		<option value="Karnataka">Karnataka</option>
																		<option value="Kerala">Kerala</option>
																		<option value="Lakshadweep">Lakshadweep</option>
																		<option value="Madhya Pradesh">Madhya Pradesh</option>
																		<option value="Maharashtra">Maharashtra</option>
																		<option value="Manipur">Manipur</option>
																		<option value="Meghalaya">Meghalaya</option>
																		<option value="Mizoram">Mizoram</option>
																		<option value="Nagaland">Nagaland</option>
																		<option value="Orissa">Orissa</option>
																		<option value="Pondicherry">Pondicherry</option>
																		<option value="Punjab">Punjab</option>
																		<option value="Rajasthan">Rajasthan</option>
																		<option value="Sikkim">Sikkim</option>
																		<option value="Tamil Nadu">Tamil Nadu</option>
																		<option value="Tripura">Tripura</option>
																		<option value="Uttaranchal">Uttaranchal</option>
																		<option value="Uttar Pradesh">Uttar Pradesh</option>
																		<option value="West Bengal">West Bengal</option>
																	</select>
																</div>
																
																<div class="form-group col-md-4">
																	<label><b>City</b></label>
																	<input type="text" class="form-control" name="business_outlet_city">
																</div>
															</div>
															<div class="form-row">
																<div class="form-group col-md-4">
																	<label><b>ZipCode</b></label>
																	<input type="number" class="form-control" name="business_outlet_pincode">
																</div>
																<div class="form-group col-md-4">
																	<label class="form-label"><b>Facebook URL</b></label>
																	<input type="text" class="form-control" name="business_outlet_facebook_url" placeholder="URL">
																</div>
																<div class="form-group col-md-4">
																	<label class="form-label"><b>Instagram URL</b></label>
																	<input type="text" class="form-control" name="business_outlet_instagram_url" placeholder="URL">
																</div>
															</div>
															<div class="form-row">
																<div class="form-group col-md-6">
																	<label><b>Bill Header Message</b></label>
																	<textarea class="form-control" rows="2" placeholder="Bill Header Message" name="business_outlet_bill_header_msg"></textarea>
																</div>
																<div class="form-group col-md-6">
																	<label><b>Bill Footer Message</b></label>
																	<textarea class="form-control" rows="2" placeholder="Bill footer Message" name="business_outlet_bill_footer_msg"></textarea>
																</div>
															</div>
															<div class="form-row">
															<div class="form-group col-md-4">
																<label class="form-label"><b>Latitude</b></label>
																<input type="text" class="form-control" name="business_outlet_latitude" placeholder="Outlet Latitude">
															</div>
															<div class="form-group col-md-4">
																<label class="form-label"><b>Longitude</b></label>
																<input type="text" class="form-control" name="business_outlet_longitude" placeholder="Outlet Longitude">
															</div>
															<div class="form-group col-md-2">
																<label class="form-label"><b>Upload Logo</b>(100x50)</label>
																<input type="file" name="business_outlet_logo" >
															</div>
															<div class="col-md-2" id="outlet_logo">
																<img class="img-responsive" src="../public/images/<?=$logo?>" width="100px" height="50px">
															</div>
														</div>
															<div class="form-group">
					                      <input class="form-control" type="hidden" name="business_outlet_id" readonly="true">
					                    </div>
															<button type="submit" class="btn btn-primary">Submit</button>
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
								<table class="table table-hover table-striped" style="width: 100%;">
									<thead>
										<tr>
											<th>Name</th>
											<th>GST-IN</th>
											<th>Address</th>
											<th>Mobile</th>
											<th>Landline</th>
											<th>Action</th>
											<th>SMS Status</th>
											<th>WhatsApp Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach ($business_outlet_details as $outlet):
										?>
										<tr>
											<td><?=$outlet['business_outlet_name']?></td>
											<td><?=$outlet['business_outlet_gst_in']?></td>
											<td><?=$outlet['business_outlet_address']?></td>
											<td><?=$outlet['business_outlet_mobile']?></td>
											<td><?=$outlet['business_outlet_landline']?></td>
											<td class="table-action">
												<button type="button" class="btn btn-primary outlet-edit-btn" business_outlet_id="<?=$outlet['business_outlet_id']?>">
									        <i class="align-middle" data-feather="edit-2"></i>
												</button>
											</td>
											<td class="table-action">
												<?php if($outlet['business_outlet_sms_status']==1){?>
												<button type="button" class="btn btn-success sms_status" business_outlet_id="<?=$outlet['business_outlet_id']?>" sms_type="sms" sms_status="0">
									        ON
												</button>
											<?php }else{?>
												<button type="button" class="btn btn-danger sms_status" business_outlet_id="<?=$outlet['business_outlet_id']?>" sms_type="sms" sms_status="1">
									        OFF
												</button>
											<?php }?>
											</td>
											<td class="table-action">
												<?php if($outlet['whats_app_sms_status']==1){?>
												<button type="button" class="btn btn-success sms_status" business_outlet_id="<?=$outlet['business_outlet_id']?>" sms_type="wapp" sms_status="0">
									        ON
												</button>
											<?php }else{?>
												<button type="button" class="btn btn-danger sms_status" business_outlet_id="<?=$outlet['business_outlet_id']?>" sms_type="wapp" sms_status="1">
									        OFF
												</button>
											<?php }?>
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
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script type="text/javascript">
	$(document).ready(function(){

		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
	  	
  	$("#AddOutlet").validate({
	  	errorElement: "div",
	    rules: {
	        "business_outlet_name" : {
            required : true,
            maxlength : 100
	        },
	        "business_outlet_address" : {
	          required : true
	        },
	        "business_outlet_pincode" : {
	        	required : true,
	        	maxlength : 10,
	        },
	        "business_outlet_state" : {
	        	required : true,
	        	maxlength : 100,
	        },
	        "business_outlet_city" : {
	        	required : true,
	        	maxlength : 100,
	        },
	        "business_outlet_country" : {
	        	required : true,
	        	maxlength : 100,
	        },
	        "business_outlet_email" : {
	        	email : true
	        },
	        "business_outlet_facebook_url":{
	        	url : true
	        },
	        "business_outlet_instagram_url":{
	        	url : true
	        },
	        "business_outlet_mobile":{
	        	maxlength : 10,
	        	minlength : 10
	        },
	        "business_outlet_landline":{
	        	maxlength: 10
	        },
	        "business_outlet_gst_in" : {
	        	maxlength : 15,
	        	minlength : 15
	        }    
	    },
	    submitHandler: function(form) {
				var formData = $("#AddOutlet").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddOutlet",
		        data: formData,
		        type: "POST",
		        // crossDomain: true,
						cache: false,
		        // dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalAddOutlet").modal('hide');
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
		// Edit outlet with Logo
		$("form#EditOutlet").submit(function(form) {
   			 form.preventDefault();    
			
				var formData = new FormData(this);
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/EditOutlet",
		        data: formData,
						contentType: "application/octet-stream",
						enctype: 'multipart/form-data',
						contentType: false,
						processData: false,
		        type: "POST",
						cache: false,
		    		success: function(data) {
              if(data.success == 'true'){
              	$("#ModalEditEmployee").modal('hide');
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
								$("#ModalEditEmployee").modal('hide');                
								toastr["error"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
              }
            },
            error: function(data){
				toastr["error"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								}); 
            }
				});
		});
		//
		// $("#EditOutlet").validate({
	  // 	errorElement: "div",
	  //   rules: {
	  //       "business_outlet_name" : {
    //         required : true,
    //         maxlength : 100
	  //       },
	  //       "business_outlet_address" : {
	  //         required : true
	  //       },
	  //       "business_outlet_pincode" : {
	  //       	required : true,
	  //       	maxlength : 10,
	  //       },
	  //       "business_outlet_state" : {
	  //       	required : true,
	  //       	maxlength : 100,
	  //       },
	  //       "business_outlet_city" : {
	  //       	required : true,
	  //       	maxlength : 100,
	  //       },
	  //       "business_outlet_country" : {
	  //       	required : true,
	  //       	maxlength : 100,
	  //       },
	  //       "business_outlet_email" : {
	  //       	email : true
	  //       },
	  //       "business_outlet_facebook_url":{
	  //       	url : true
	  //       },
	  //       "business_outlet_instagram_url":{
	  //       	url : true
	  //       },
	  //       "business_outlet_mobile":{
	  //       	maxlength : 10,
	  //       	maxlength : 10
	  //       },
	  //       "business_outlet_landline":{
	  //       	maxlength: 10
	  //       },
	  //       "business_outlet_gst_in" : {
	  //       	maxlength : 15,
	  //       	minlength : 15
	  //       }    
	  //   },
	  //   submitHandler: function(form) {
		// 		// var formData = $("#EditOutlet").serialize(); 
		// 		var formData = new FormData(this);
		// 		$.ajax({
		//         url: "<?=base_url()?>BusinessAdmin/EditOutlet",
		//         data: formData,
		// 				contentType: "application/octet-stream",
		// 				enctype: 'multipart/form-data',
		//         type: "POST",
		// 				cache: false,
		//     		success: function(data) {
    //           if(data.success == 'true'){
    //           	$("#centeredModalDanger").modal('hide');
		// 						$('#defaultModalSuccess').modal('show').on('shown.bs.modal', function (e) {
		// 							$("#SuccessModalMessage").html("").html(data.message);
		// 						}).on('hidden.bs.modal', function (e) {
		// 								window.location.reload();
		// 						});
    //           }
    //           else if (data.success == 'false'){                   
    //       	    if($('.feedback').hasClass('alert-success')){
    //               $('.feedback').removeClass('alert-success').addClass('alert-danger');
    //             }
    //             else{
    //               $('.feedback').addClass('alert-danger');
    //             }
    //             $('.alert-message').html("").html(data.message); 
    //           }
    //         },
    //         error: function(data){
    // 					$('.feedback').addClass('alert-danger');
    // 					$('.alert-message').html("").html(data.message); 
    //         }
		// 		});
		// 	},
		// });

		$(document).on('click','.outlet-edit-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
            business_outlet_id : $(this).attr('business_outlet_id')
      };
      $.getJSON("<?=base_url()?>BusinessAdmin/GetBusinessOutlet", parameters)
        .done(function(data, textStatus, jqXHR) {
            
            $("#EditOutlet input[name=business_outlet_name]").attr('value',data.business_outlet_name);
            $("#EditOutlet input[name=business_outlet_gst_in]").attr('value',data.business_outlet_gst_in);
            $("#EditOutlet input[name=business_outlet_address]").attr('value',data.business_outlet_address);
            $("#EditOutlet input[name=business_outlet_email]").attr('value',data.business_outlet_email);
            $("#EditOutlet input[name=business_outlet_mobile]").attr('value',data.business_outlet_mobile);
            $("#EditOutlet input[name=business_outlet_landline]").attr('value',data.business_outlet_landline);
            $("#EditOutlet input[name=business_outlet_city]").attr('value',data.business_outlet_city);
            $("#EditOutlet select[name=business_outlet_state]").val(data.business_outlet_state);
            $("#EditOutlet input[name=business_outlet_pincode]").attr('value',data.business_outlet_pincode);
            $("#EditOutlet textarea[name=business_outlet_bill_header_msg]").val(data.business_outlet_bill_header_msg);
            $("#EditOutlet textarea[name=business_outlet_bill_footer_msg]").val(data.business_outlet_bill_footer_msg);
            $("#EditOutlet input[name=business_outlet_facebook_url]").attr('value',data.business_outlet_facebook_url);
            $("#EditOutlet input[name=business_outlet_instagram_url]").attr('value',data.business_outlet_instagram_url);
            $("#EditOutlet input[name=business_outlet_latitude]").attr('value',data.business_outlet_latitude);
            $("#EditOutlet input[name=business_outlet_longitude]").attr('value',data.business_outlet_longitude);
            $("#EditOutlet input[name=business_outlet_id]").attr('value',data.business_outlet_id);

            $("#centeredModalDanger").modal('show');

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown.toString());
        });
    });

		$(document).on('click','.sms_status',function(event) {
				event.preventDefault();
				this.blur(); // Manually remove focus from clicked link.
				var parameters = {
					"business_outlet_id" : $(this).attr('business_outlet_id'),
					"sms_status" : $(this).attr('sms_status'),
					'sms_type'	: $(this).attr('sms_type')
				};
			
				$.ajax({
					url: "<?=base_url()?>BusinessAdmin/ChangeSmsStatus",
					data: parameters,
					type: "POST",
					cache: false,
					success: function(data) {
								if(data.success == 'true'){
									alert(data.message);
									window.location.reload();
								}else {
									alert(data.message);
									window.location.reload();
								}
							}
				});
			});

	});
</script>
