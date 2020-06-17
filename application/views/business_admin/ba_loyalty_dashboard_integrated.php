<?php
	$this->load->view('business_admin/ba_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('business_admin/ba_nav_view');
	?>
	<div class="main" onLoad=" LoadOnce()">
		<?php
			$this->load->view('business_admin/ba_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">Loyalty Management</h1>
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
          <!-- Loylaty Offer Define -->
          <?php
          
          if($CheckOutletRule[0]['rule_type'] == '')
          {
            ?>
            <div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">Define Offer</h5>
								</div>
								<div class="card-body">
									<h5>Please define rule for the outlet first.</h5>
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
								<h5 class="card-title">Offers</h5>
							</div>
							<div class="card-body">
                				<!-- Loyalty Offer Define -->
								<?php
                if( $CheckOutletRule[0]['rule_type'] == 'Cashback Single Rule' || $CheckOutletRule[0]['rule_type'] == 'Cashback Multiple Rule' || $CheckOutletRule[0]['rule_type'] == 'Cashback ')
                
								{
									?>
									<div class="row">
                  <div class="col-md-12">
                    <div class="card flex-fill w-100">
                      <div class="card-header">
                        <h5>CashBack Rule has been defined for this outlet.</h5>
                      </div>
                    </div>
                  </div>
                </div>
								<?php 
                }
                else if($CheckOutletRule[0]['rule_type'] != 'Cashback Visits')
                {
                ?>
                  <div class="row">
										<div class="col-md-12">
											<div class="card flex-fill w-100">
                        <div class="card-header">
                          <!-- <div class="form-row col-md-12"> -->
                            <form action="#" method="POST" id="OffersTable">
                              <table class="table table-hover" id="OfferTable" style="width: 100%;">
																<th>Points</th>
																<th></th>
																<th>Offers</th>
																<th>Actions</th>
																<?php
																	if(!isset($loyalty_offer) || !empty($loyalty_offer))
																	{
																		foreach($loyalty_offer as $loyalty)
																		{
																			if($loyalty['offers_status'] == 1)
																			{
																?>
																<tr>
																	<td>
																		<input type="text" value="<?=$loyalty['offer_id']?>" name="offer_id[]" hidden="hidden">
																		<input type="number" placeholder="Enter points" name="points[]" class="form-control" value="<?=$loyalty['points']?>" readonly>
																	</td>
																	<td><label><b>=</b></label></td>
																	<td>
																		<input type="text" placeholder="Enter Offers" name="offers[]" class="form-control" value="<?=$loyalty['offers']?>" readonly >
																	</td>
																	<td>
																		<!-- <button type="button" class="btn btn-primary offers_edit_btn" offer_id="<?=$loyalty['offer_id']?>">
																			<i class="align-middle" data-feather="edit-2"></i>
																		</button> -->
																		<button type="button" class="btn btn-danger offers_delete_btn" offer_id="<?=$loyalty['offer_id']?>"><i class="align-middle" data-feather="trash-2"></i></button>
																	</td>
																</tr>
																<?php
																			}
																		}
																	}
                
																	?>
                              </table>
                            </form>
                            <form action="#" method="POST" id="NewOffersTable">
															<table class="table table-hover" id="NewOfferTable" style="width: 100%;">
																<th>Points</th>
																<th></th>
																<th>Category</th>
																<th>Sub Category</th>
																<th>Service</th>
                                <tr>
                                  <td>
                                    <!-- <input type="text" name="offer_id[]" hidden="hidden"> -->
                                    <input type="text" name="rule_type[]" hidden="hidden" value="<?=$CheckOutletRule[0]['rule_type']?>"> 
                                    <input type="number" placeholder="Enter points" name="points[]" class="form-control">
                                  </td>
                                  <td><label><b>=</b></label></td>
                                  <td>
																		<select class="form-control" name="category_id[]" id="Category-Id" temp="Category_id">
																			<option value="" selected>-- Select Category --</option>
																				<?php
																					foreach ($categories as $category) {
																						echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																					}
																				?>
																		</select>
                                  </td>
                                  <td>
																		<select class="form-control" name="sub_category_id[]" id="Sub-Category-Id" temp="Sub_Category">
																		</select>
																	</td>
																	<td>
																		<select class="form-control" name="service_id[]" id="Service-Id" temp="Service">
																		</select>
																	</td>
                                </tr>
                              </table>
                              <button type="button" class="btn btn-success" id="AddMultipleRule" onclick="AddOffers()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
                              <button type="button" class="btn btn-danger" onclick="DeleteOffers()" id="DeleteMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
                              <button type="submit" class="btn btn-primary">Submit</button>
                          <!-- </div> -->
                          <div class="form-row col-md-6">
                            <div class="form-group col-md-12">
                            <input type="hidden" name="business_outlet_id" value="<?=$selected_outlet['business_outlet_id']?>" readonly="true">
                            <input type="hidden" name="business_outlet_business_admin" value="<?= $business_admin_details['business_admin_id'] ?>">
                            <input type="hidden" class="form-control" name="business_outlet_creation_date" id="business_outlet_creation_date">
                            </div>
                          </div>
                          </form>
                        </div>
											</div>
										</div>
									</div>
                <?php
                }
								else
								{
								?>
									<div class="row">
										<div class="col-md-12">
											<div class="card flex-fill w-100">
											<div class="card-header">
												<form action="#" method="POST" id="ExistingVisitOffer">
													<table class="table table-hover" id="OfferTable" style="width: 100%;">
														<th>Visit</th>
														<th></th>
														<th>Offers</th>
														<th>Actions</th>
														<?php
															if($loyalty_offer != 0)
																{
																	foreach($loyalty_offer as $loyalty)
																	{
																		if($loyalty['offers_status'] == 1)
																		{
															?>
															<tr>
																<td>
																	<input type="text" value="<?=$loyalty['offer_id']?>" name="offer_i[]" hidden="hidden">
																	<input type="number" placeholder="Enter Visit Count" name="visit[]" class="form-control" value="<?=$loyalty['visits']?>" readonly>
																</td>
																<td><label><b>=</b></label></td>
																<td>
																	<input type="text" placeholder="Enter Offers" name="offers[]" class="form-control" value="<?=$loyalty['offers']?>" readonly >
																</td>
																<td>
																	<!-- <button type="button" class="btn btn-primary visit_edit_btn" offer_id="<?=$loyalty['offer_id']?>">
																		<i class="align-middle" data-feather="edit-2"></i>
																	</button> -->
																	<button type="button" class="btn btn-danger offers_delete_btn" offer_id="<?=$loyalty['offer_id']?>"><i class="align-middle" data-feather="trash-2"></i></button>
																</td>
															</tr>
															<?php
																		}
																	}
																}
																?>
                          </table>
												</form>
												<form action="#" method="POST" id="NewVisitOffersTable">
													<div class="form-row">
														<table class="table table-hover" id="VisitsTable" style="width: 100%;">
															<th>Visit</th>
															<th></th>
															<th>Category</th>
															<th>Sub Category</th>
															<th>Service</th>
                              <tr>
                                <td>
                                  <!-- <input type="text" name="offer_id[]" hidden="hidden"> -->
                                  <input type="text" name="rule_type[]" hidden="hidden" value="<?=$CheckOutletRule[0]['rule_type']?>">
                                  <input type="number" placeholder="Enter Visit Count" name="visit[]" class="form-control">
                                </td>
                                <td><label><b>=</b></label></td>
                                <td>
																	<select class="form-control" name="category_id[]" id="visit_category-id" temp="Visit_Category_id">
																		<option value="" selected>-- Select Category --</option>
																			<?php
																				foreach ($categories as $category) {
																					echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																					}
																			?>
																	</select>
                                </td>
                                <td>
																	<select class="form-control" name="sub_category_id[]" id="visit_sub-category-id" temp="Visit_Sub_Category">
																	</select>
																</td>
																<td>
																	<select class="form-control" name="service_id[]" id="visit_service-id" temp="Visit_Service">
																	</select>
																</td>
                              </tr>
														</table>
														<button type="button" class="btn btn-success" id="AddMultipleRule" onclick="AddVisitOffers()">Add <i class="fa fa-plus" aria-hidden="true"></i></button>&ensp;
														<button type="button" class="btn btn-danger" onclick="DeleteVisitOffers()" id="DeleteMultipleRule">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
														<button type="submit" class="btn btn-primary ">Submit</button>
													</div>
													<div class="form-row col-md-6">
														<div class="form-group col-md-12">
														<input type="hidden" name="business_outlet_id" value="<?=$selected_outlet['business_outlet_id']?>" readonly="true">
														<input type="hidden" name="business_outlet_business_admin" value="<?= $business_admin_details['business_admin_id']?>">
														<input type="hidden" class="form-control" name="business_outlet_creation_date" id="business_outlet_creation_date"> 
														</div>
													</div>
												</form>
											</div>
											</div>
										</div>
									</div>
								<?php
								}
								?>
							 </div>
						</div>
          </div>
          <?php
              }
          
          ?>
				  	<!-- End of Loyalty Offers Define -->
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-2">
										<h5 class="card-title">Loyalty Transcation Data</h5>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<div class="input-group">
												<input type="text" placeholder="Search Customer by name/contact no." class="form-control" id="SearchCustomer" >
												<span class="input-group-append">
													<button class="btn btn-success" type="button" id="SearchCustomerButton" Customer-Id="Nothing" style="padding:0px 0px;">Search</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<button class="btn btn-primary float-right" onclick="exportTableToExcel('cust_data','Cashback')"><i class="fa fa-download"></i> Download Data</button>
									</div>
									
								</div>
							</div>
							<div class="card-body">
							<table class="table table-hover datatables-basic" style="width: 100%;" id="cust_data">
									<thead>
										<tr>
											<!--<th>S.No.</th>-->
											<th>Mobile</th>
											<th>Name</th>
											<th>Total Amount spent</th>
											<th>Rewards Balance</th>
											<!-- <th>Cashback Redeemed</th>
											<th>Balance Cashback</th> -->
											<th>Last Update date</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($cust_data as $data){
										?>
										<tr>
											<!--<td></td>-->
											<td><?=$data['customer_mobile']?></td>
											<td><?=$data['customer_name']?></td>		
											<td><?=$data['total_spent']?></td>										
											<?php
											if(!empty($rules))
											{
												if($rules['rule_type'] =='Offers Single Rule' ||$rules['rule_type'] =='Offers Multiple Rule' || $rules['rule_type'] =='Offers LTV Rule')
												{
												?>
													<td><?=$data['customer_rewards']?></td>
													<?php
												}
												else if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
												{
													?>
													<td><?=$data['customer_cashback']?></td>
													<?php
												}
											}
											?>												
											<td><?=$data['last_txn_date']?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- modal -->
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
							</div>
						</div>
					</div>
					<div class="modal fade" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
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
          <?php
            if($CheckOutletRule[0]['rule_type'] == 'Cashback Visits')
            {
          ?>
					<div class="modal fade" id="ModalEditVisitOffer" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-md" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-white">Edit Loyalty Offer</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<form id="EditVisitOffer" method="POST" action="#">
												<div class="form-row">
													<table id="EditOfferTable" class="table table-hover table-bordered">
														<tbody>
															<tr>
																<td>
																	<div class="form-group">
																		<label>Visit Count</label>
																		<input type="number" class="form-control" name="visit">
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<label>Category</label>
																		<select class="form-control" name="category_id" id="category_id">
																			<option value="" selected></option>
																				<?php
																					foreach ($categories as $category) {
																						echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																					}
																				?>
																			</select>
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																			<label>Sub-Category</label>
																			<select class="form-control" name="sub_category_id" id="sub_category_id">
																			</select>
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																			<label>Service</label>
																				<select class="form-control" name="service_id" id="service_id">
																				</select>
																		</div>
																	</td>
																</tr>
															</tbody>
													</table>
													<input type="hidden" class="form-group" name="offer_id"/>
                          </div>
												<input type="hidden" class="form-control" name="loyalty_id" id="" />
												
											</form>
											<div class="alert alert-dismissible feedback1" style="margin:0px;" role="alert">
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
          <!-- end -->
          <!-- modal -->
          <!-- <div class="modal fade" id="defaultModalSuccess" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Success</h5>
                  <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body m-3">
                  <p class="mb-0" id="SuccessModalMesage"></p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="defaultModalDanger" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Error</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="Close">
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
          </div> -->
          <?php
            }
            else{
          ?>
          <div class="modal fade" id="ModalEditOffer" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
						<div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-white">Edit Loyalty Offer</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
									</button>
									</div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12">
                        <form id="EditOffers" method="POST" action="#">
                          <div class="form-row">
														<table id="EditOfferTable" class="table table-hover table-bordered">
															<tbody>
																<tr>
																	<td>
																		<div class="form-group">
																			<label>Points</label>
																			<input type="number" class="form-control" name="points">
																		</div>
																	</td>
																	<td>
																		<div class="form-group">
																			<label>Category</label>
																			<select class="form-control" name="category_id" id="edit_category_id">
																				<option value="" selected></option>
																					<?php
																						foreach ($categories as $category) {
																							echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
																						}
																					?>
																				</select>
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<label>Sub-Category</label>
																					<select class="form-control" name="sub_category_id" id="edit_sub_category_id">
																					</select>
																			</div>
																		</td>
																		<td>
																			<div class="form-group">
																				<label>Service</label>
																					<select class="form-control" name="service_id" id="edit_service_id">
																					</select>
																			</div>
																		</td>
																	</tr>
																</tbody>
														</table>
														<input type="hidden" class="form-group" name="offer_id"/>
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <div class="alert alert-dismissible feedback1" style="margin:0px;" role="alert">
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
          <!-- end -->
					<?php
            }
          }
					?>
				</div>
      </div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script language="JavaScript" >
	function LoadOnce() 
	{ 
	window.location.reload(); 
	} 

</script>
<script>
		$(document).ajaxStart(function() {
      $("#load_screen").show();		
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });
    //add offers Tab
    function AddOffers()
    {
      event.preventDefault();
      this.blur();
      var rowno = $('#NewOfferTable tr').length;

      rowno = rowno+1;

      $('#NewOfferTable tr:last').after('<tr><td><input type="text" name="offer_id[]" hidden="hidden"><input type="text" name="rule_type[]" hidden="hidden" value="<?=$CheckOutletRule[0]['rule_type']?>"><input type="number" placeholder="Enter points" name="points[]" class="form-control"></td><td><label><b>=</b></label></td><td><select class="form-control" name="category_id[]" id="Category-Id" temp="Category_id"><option value="" selected>-- Select Category --</option><?php foreach ($categories as $category) {echo "<option value=".$category["category_id"].">".$category["category_name"]."</option>";}?></select></td><td><select class="form-control" name="sub_category_id[]" id="Sub-Category-Id" temp="Sub_Category"></select></td><td><select class="form-control" name="service_id[]" id="Service-Id" temp="Service"></select></td></tr>');
    }

    //Delete Offer Tabs
    function DeleteOffers()
    {
     event.preventDefault();
     this.blur();
     var rowno = $('#NewOfferTable tr').length;

     if(rowno > 1)
     {
      $('#NewOfferTable tr:last').remove();
     }
    }
     
    function AddVisitOffers()
    {
      event.preventDefault();
      this.blur();
      var rowno = $('#VisitsTable tr').length;
      rowno = rowno+1;

      $('#NewVisitOffersTable tr:last').after(' <tr><td><input type="text" name="offer_id[]" hidden="hidden"><input type="text" name="rule_type[]" hidden="hidden" value="<?=$CheckOutletRule[0]['rule_type']?>"><input type="number" placeholder="Enter points" name="visit[]" class="form-control"></td><td><label><b>=</b></label></td><td><select class="form-control" name="category_id[]" id="visit_category-id" temp="Visit_Category_id"><option value="" selected>-- Select Category --</option><?php foreach ($categories as $category) {echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";}?></select></td><td><select class="form-control" name="sub_category_id[]" id="visit_sub-category-Id" temp="Visit_Sub_Category"></select></td><td><select class="form-control" name="service_id[]" id="visit_service-id" temp="Visit_Service"></select></td></tr>');
    }
    function DeleteVisitOffers()
    {
      event.preventDefault();
       var rowno = $('#NewVisitOffersTable tr').length;

       if(rowno >2)
       {
         $('#NewVisitOffersTable tr:last').remove();
       }
    }
    //VisitOffer Submit
    $("#NewVisitOffersTable").validate({
	  	errorElement: "div",
	    rules: {
	        "visits[]" : {
            number:true,
            required : true
	        },
	        "category_id[]" :{
	        	required : true
	        },
          "sub_category_id[]" :{
            required : true
          },
          "service_id[]" : {
            required :true
          }
	    },
	    submitHandler: function(form) {
				var formData = $("#NewVisitOffersTable").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>index.php/BusinessAdmin/InsertIntegratedVisitOffer/",
		        data: formData,
		        type: "POST",
		        crossDomain: true,
						cache: false,
		        dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	// $("#ModalEditRawMaterial").modal('hide');
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
			},
		});
    //Offer Submit
    $("#NewOffersTable").validate({
	  	errorElement: "div",
	    rules: {
	        "points[]" : {
            number:true,
            required : true
	        },
	        "category_id[]" :{
	        	required : true
	        },
					"sub_category_id[]" :{
						required : true
					},
					"service_id[]" : {
						required : true
					}
	    },
	    submitHandler: function(form) {
				var formData = $("#NewOffersTable").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>index.php/BusinessAdmin/InsertOffer/",
		        data: formData,
		        type: "POST",
		        crossDomain: true,
						cache: false,
		        dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	// $("#ModalEditRawMaterial").modal('hide');
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
                  toastr["danger"](data.message,"", {
									positionClass: "toast-top-right",
									progressBar: "toastr-progress-bar",
									newestOnTop: "toastr-newest-on-top",
									rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
									timeOut: 1000
								});
								setTimeout(function () { location.reload(1); }, 1000);
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
		//Offers Delete Button
		$(document).on('click','.offers_delete_btn',function(event){
			event.preventDefault();
			this.blur();
			var parameters = {
				offer_id : $(this).attr('offer_id')
			};

			$.getJSON("<?=base_url()?>index.php/BusinessAdmin/LoyaltyDeleteOfferIntegrated/",parameters)
			.done(function(data,textStatus,jqXHR){
				if(data.success == 'true')
        {
					toastr["success"](data.message,"", {
					positionClass: "toast-top-right",
					progressBar: "toastr-progress-bar",
					newestOnTop: "toastr-newest-on-top",
					rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
					timeOut: 1000
					});
					setTimeout(function () { location.reload(1); }, 1000);
        }
        else if(data.success == 'false')
        {
					toastr["error"](data.message,"", {
					positionClass: "toast-top-right",
					progressBar: "toastr-progress-bar",
					newestOnTop: "toastr-newest-on-top",
					rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
					timeOut: 1000
					});
        }
      })
			.fail(function(jqXHR,textStatus,errorThrown){
				console.log(errorThrown.toString());
			});
		});
    //Offers Edit Button
    $(document).on('click','.offers_edit_btn',function(event){
      event.preventDefault();
      this.blur();//Manually remove focus from clicked link
      var parameters = {
        offer_id : $(this).attr('offer_id')
      };

      $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetOffers/",parameters)
      .done(function(data,textStatus,jqXHR){
        $("#EditOffers input[name=points]").attr('value',data.points);
        $("EditOffers input[name=offers]").attr('value',data.offers);
        $("#EditOffers input[name=offer_id]").attr('value',data.offer_id);
        $("#ModalEditOffer").modal('show');
      })
      .fail(function(jqXHR,textStatus,errorThrown){
        console.log(errorThrown.toString());
      });
    });
      $("#EditOffer").validate({
        errorElement: "div",
        rules:{
          "points":{
            number:true,
            required : true
          },
          "category_id":{
            required : true
          },
					"sub_category_id" : {
						required : true
					},
					"service_id" :{
						required :true
					}
        },
        submitHandler : function(form){
          var formData = $("#EditOffer").serialize();
          $.ajax({
            url   : "<?=base_url()?>index.php/BusinessAdmin/UpdateOffer/",
            data  : formData,
            type  : "POST",
            cache : false,
            crossDomain : true,
            dataType    : "json",
            success :function(data)
            {
              if(data.success == 'true')
              {

              }
              else if(data.success == 'false')
              {

              }
              else
              {

              }
            },
            error : function(data)
            {

            }
          });
        }
      });
      //Visit Offer edit Button
      $(document).on('click','.visit_edit_btn',function(event){
      event.preventDefault();
      this.blur();//Manually remove focus from clicked link
      var parameters = {
        offer_id : $(this).attr('offer_id')
      };

      $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetOffers/",parameters)
      .done(function(data,textStatus,jqXHR){
        $("#EditOffers input[name=points]").attr('value',data.visits);
        $("EditOffers input[name=offers]").attr('value',data.offers);
        $("#EditOffers input[name=offer_id]").attr('value',data.offer_id);
        $("#ModalEditOffer").modal('show');
      })
      .fail(function(jqXHR,textStatus,errorThrown){
        console.log(errorThrown.toString());
      });
    });
      $("#EditVisit").validate({
        errorElement: "div",
        rules:{
          "visits":{
            number:true,
            required : true
          },
          "category_id":{
            required : true
          },
					"sub_category_id" : {
						required : true
					},
					"service_id" :{
						required :true
					},
          "offer_id" : {
            required :true
          }
        },
        submitHandler : function(form){
          var formData = $("#EditVisit").serialize();
          $.ajax({
            url   : "<?=base_url()?>index.php/BusinessAdmin/UpdateVisitOfferIntegrated/",
            data  : formData,
            type  : "POST",
            cache : false,
            crossDomain : true,
            dataType    : "json",
            success :function(data)
            {
              if(data.success == 'true')
              {

              }
              else if(data.success == 'false')
              {

              }
              else
              {

              }
            },
            error : function(data)
            {

            }
          });
        }
      });
    //DataTable
		$(".datatables-basic").DataTable({
			responsive: true,
			searching : false
		});
		//AddConversion Ratio
		$("#conversion").validate({
	  	errorElement: "div",
	    rules: {
	        "rupee" : {
            required : true
	        },
	        "rate" :{
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#conversion").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>index.php/BusinessAdmin/AddConversionRatio/",
		        data: formData,
		        type: "POST",
		        crossDomain: true,
						cache: false,
		        dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	// $("#ModalEditRawMaterial").modal('hide');
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
			},
		});
		//edit Conersion ratio
		$(document).on('click','.loyalty_conversion_btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        loyalty_id : $(this).attr('loyalty_id')
      };

      $.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetConversionRatio/", parameters)
      .done(function(data, textStatus, jqXHR) { 
        $("#EditConversionRatio input[name=conversion_ratio]").attr('value',data.loyalty_conversion);
        $("#EditConversionRatio input[name=conversion_rate]").attr('value',data.loyalty_rate);
				$("#EditConversionRatio input[name=validity]").attr('value',data.validity);
				$("#EditConversionRatio input[name=loyalty_id]").attr('value',data.loyalty_id);
        $("#ModalEditConversionRatio").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		$("#EditConversionRatio").validate({
	  	errorElement: "div",
	    rules: {
	        "conversion_ratio" : {
            required : true
	        },
	        "conversion_rate" :{
	        	required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#EditConversionRatio").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>index.php/BusinessAdmin/UpdateConversionRatio/",
		        data: formData,
		        type: "POST",
		        crossDomain: true,
						cache: false,
		        dataType : "json",
		    		success: function(data) {
              if(data.success == 'true'){ 
              	$("#ModalEditConversionRatio").modal('hide');
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
          	    if($('.feedback1').hasClass('alert-success')){
                  $('.feedback1').removeClass('alert-success').addClass('alert-danger');
                }
                else{
                  $('.feedback1').addClass('alert-danger');
                }
                $('.alert-message').html("").html(data.message); 
              }
            },
            error: function(data){
    					$('.feedback1').addClass('alert-danger');
    					$('.alert-message').html("").html(data.message); 
            }
				});
			},
		});
		//
		//functionality for getting the dynamic input data
    $("#SearchCustomer").typeahead({
      	autoselect: true,
				highlight: true,
				minLength: 1
			},
			{
				source: SearchCustomer,
				templates: {
					empty: "No Customer Found!",
					suggestion: _.template("<p><%- customer_name %>, <%- customer_mobile %></p>")
				}
    });
       
    var to_fill = "";

    $("#SearchCustomer").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchCustomer";
      to_fill = suggestion.customer_name+","+suggestion.customer_mobile;
      setVals(loc,to_fill,suggestion.customer_id);
    });

    $("#SearchCustomer").blur(function(){
      $("#SearchCustomer").val(to_fill);
      to_fill = "";
    });

    function SearchCustomer(query, cb){
      var parameters = {
        query : query
      };
			
      $.ajax({
        url: "<?=base_url()?>index.php/BusinessAdmin/GetCustomerData/",
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

    function setVals(element,fill,customer_id){
      $(element).attr('value',fill);
      $(element).val(fill);
      $("#SearchCustomerButton").attr('Customer-Id',customer_id);
    }

    $(document).on('click',"#SearchCustomerButton",function(event){
    	event.preventDefault();
      this.blur();
			var customer_id = $(this).attr('Customer-Id');
			
      if(customer_id == "Nothing"){
      	$('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
					$("#ErrorModalMessage").html("").html("Please select customer!");
				});
      }
      else{
	      var parameters = {
	        customer_id : $(this).attr('Customer-Id')
	      };
	      
				$("#SearchCustomerButton").attr('Customer-Id',"Nothing");
					$.getJSON("<?=base_url()?>index.php/BusinessAdmin/AddCustomerDataInTable/", parameters)
					.done(function(data, textStatus, jqXHR) { 
						var str_2 = "";
						
						str_2 += "<tr>";
						str_2 += "<td>1</td>";
						str_2 += "<td>"+data[0].customer_mobile+"</td>";
						str_2 += "<td>"+data[0].customer_name+"</td>";
						str_2 += "<td>"+data[0].total_spent+"</td>";
							<?php
							if(!empty($rules))
							{
								if($rules['rule_type'] =='Offers Single Rule' ||$rules['rule_type'] =='Offers Multiple Rule' || $rules['rule_type'] =='Offers LTV Rule')
								{
								?>
									str_2 += "<td>"+data[0].customer_rewards+"</td>";
									<?php
								}
								else if($rules['rule_type'] == 'Cashback Single Rule' || $rules['rule_type'] == 'Cashback Multiple Rule' || $rules['rule_type'] == 'Cashback LTV Rule')
								{
									?>
									str_2 += "<td>"+data[0].customer_cashback+"</td>";
									<?php
								}
							}
							?>								
						str_2 += "<td>"+data[0].last_txn_date+"</td>";
						str_2 += "</tr>";
						$("#cust_data tbody tr").remove();
						$("#cust_data tbody").append(str_2);
					})
					.fail(function(jqXHR, textStatus, errorThrown) {
						console.log(errorThrown.toString());
				});
	    }
  	});
	
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
	var input = document.getElementById("SearchCustomer");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
		event.preventDefault();
		document.getElementById("SearchCustomerButton").click();
		}
	});
	
</script>
<!-- Add Dynamic Offer Category/Sub-Category/Service -->
<script>
	$(document).on('change','#NewOfferTable tr:last select[temp=Category_id]',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetSubCategoriesByCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#NewOfferTable tr:last select[temp=Sub_Category]").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('change','#NewOfferTable tr:last select[temp=Sub_Category]',function(e){
    	var parameters = {
    		'sub_category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetServicesBySubCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
       		}
       		$("#NewOfferTable tr:last select[temp=Service]").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
<!-- End of Dynamic Offer Category/Sub-Category/Service  -->
<!-- Add Dynamic Offer Category/Sub-Category/Service -->
<script>
	$(document).on('change','#NewVisitOffersTable tr:last select[temp=Visit_Category_id]',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetSubCategoriesByCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#NewVisitOffersTable tr:last select[temp=Visit_Sub_Category]").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $(document).on('change','#NewVisitOffersTable tr:last select[temp=Visit_Sub_Category]',function(e){
    	var parameters = {
    		'sub_category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetServicesBySubCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
       		}
       		$("#NewVisitOffersTable tr:last select[temp=Visit_Service]").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
<!-- End of Dynamic Offer Category/Sub-Category/Service  -->
<script>
	$("#category_id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetSubCategoriesByCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value=' ' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#sub_category_id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#sub_category_id").on('change',function(e){
    	var parameters = {
    		'sub_category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetServicesBySubCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value=' ' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
       		}
       		$("#service_id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
