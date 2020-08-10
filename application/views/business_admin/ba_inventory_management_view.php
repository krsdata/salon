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
				<h1 class="h3 mb-3">Composition Management</h1>
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
								<h5 class="card-title">Add Composition</h5>
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
								<form id="AddComposition" method="POST" action="#">
									<div class="form-row">
										<div class="form-group col-md-4">
											<label>Category</label>
											<select class="form-control" name="category_id" id="Category-Id">
												<option value="" selected></option>
												<?php
													foreach ($categories as $category) {
														echo "<option value=".$category['category_id'].">".$category['category_name']."</option>";
													}
												?>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label>Sub-Category</label>
											<select class="form-control" name="sub_category_id" id="Sub-Category-Id">
											</select>
										</div>
										<div class="form-group col-md-4">
											<label>Service</label>
											<select class="form-control" name="service_id" id="Service-Id">
											</select>
										</div>
									</div>
						      <table class="table table-hover table-bordered table-responsive" id="Inventory-Composition-Table">
										<tbody>
						       		<tr>
						        		<td>1</td>
						        		<td>
						        			<div class="form-group">
							        			<select class="form-control Raw-Material-ID" name="raw_material_id[]" required>
							        				<option value="" selected disabled>Raw Category</option>
							        				<?php
							 									foreach ($raw_materials as $rm) {
							 										echo "<option value=".$rm['service_id'].">".$rm['service_name']."</option>";
							 									}
							        				?>
							        			</select>
						        		</div>
						        		</td>
						        		<td>
						        			<div class="form-group">
						        				<input type="text" class="form-control Raw-Material-Name" name="raw_material_name[]" readonly required />
						        			</div>
						        		</td>
						        		<td>
						        			<div class="form-group">
						        				<input type="text" class="form-control Raw-Material-Unit" name="raw_material_unit[]" readonly required/>
						        			</div>
						        		</td>
						        		<td>
						        			<div class="form-group">
						        				<input type="text" class="form-control Raw-Material-Brand" name="raw_material_brand[]" readonly required />
						        			</div>
						        		</td>
						        		<td>
						        			<div class="form-group">
						        				<input type="number" class="form-control Consumption-Quantity" name="consumption_quantity[]" required/>
						        			</div>
						        		</td>
						       		</tr>
						       	</tbody>
						      </table>
  								<button type="button" class="btn btn-success" id="AddRow">Add <i class="fa fa-plus" aria-hidden="true"></i></button>
    							<button type="button" class="btn btn-danger" id="DeleteRow">Delete <i class="fa fa-trash" aria-hidden="true"></i></button>
    							<button type="submit"  class="btn btn-primary">Submit</button>
      					</form>
      					<div class="alert alert-dismissible feedback mt-2" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
			            </button>
									<div class="alert-message">
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Added Compositions</h5>
							</div>
							<div class="card-body">
								<!--MODAL AREA-------------------------------------->
								<div class="modal fade" id="ModalViewComposition" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header" style="background-color:#47bac1;">
												<h5 class="modal-title text-white">View Composition</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                      <span aria-hidden="true">&times;</span>
		                    </button>
											</div>
											<div class="modal-body m-3">
												<div class="row">
													<div class="col-md-12">
														<table class="table table-hover table-responsive mt-3">
															<thead>
																<tr>
																	<th>Sno.</th>
																	<th>Service</th>
																	<th>Raw Material</th>
																	<th>Brand</th>
																	<th>Unit</th>
																	<th>Consumption</th>
																</tr>
															</thead>
															<tbody id="ViewParticularComposition">
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--MODAL AREA END---------------------------------->
								<table class="table table-hover datatables-basic mt-3" style="width: 100%;">
									<thead>
										<tr>
											<th>Composition</th>
											<th>Service</th>
											<th>Sub-Category</th>
											<th>Category</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$counter = 1;
											foreach ($compositions as $composition):
										?>
										<tr>
											<td><?=$counter?></td>
											<td><?=$composition['service_name']?></td>
											<td><?=$composition['sub_category_name']?></td>
											<td><?=$composition['category_name']?></td>
											<td class="table-action">
												<button type="button" class="btn btn-primary composition-view-btn" service_id="<?=$composition['service_id']?>">
									        <i class="align-middle fa fa-th-list" aria-hidden="true"></i>
									      </button>
									      <button type="button" class="btn btn-danger composition-deactivate-btn" service_id="<?=$composition['service_id']?>">
									        <i class="fa fa-trash" aria-hidden="true"></i>
									      </button>
											</td>
										</tr>	
										<?php	
											$counter = $counter + 1;	
											endforeach;
										?>
									</tbody>
								</table>
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
	
		$(document).ajaxStart(function() {
			$("#load_screen").show();
		});

		$(document).ajaxStop(function() {
			$("#load_screen").hide();
		});
		
		$(".datatables-basic").DataTable({
			responsive: true
		});    

    $("#AddRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Inventory-Composition-Table tr").length;
      rowno = rowno+1;
      
      $("#Inventory-Composition-Table tr:last").after("<tr><td>"+rowno+"</td><td><div class=\"form-group\"><select class=\"form-control Raw-Material-ID\" name=\"raw_material_id[]\"><option value=\"\" selected required></option><?php foreach ($raw_materials as $rm) { echo "<option value=".$rm['service_id'].">".$rm['service_name']."</option>"; } ?></select></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control Raw-Material-Name\" name=\"raw_material_name[]\" readonly required /></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control Raw-Material-Unit\" name=\"raw_material_unit[]\" readonly required /></div></td><td><div class=\"form-group\"><input type=\"text\" class=\"form-control Raw-Material-Brand\" name=\"raw_material_brand[]\" readonly required /></div></td><td><div class=\"form-group\"><input type=\"number\" class=\"form-control Consumption-Quantity\" name=\"consumption_quantity[]\" required /></div></td></tr>");
    });

    $("#DeleteRow").click(function(event){
    	event.preventDefault();
      this.blur();
      var rowno = $("#Inventory-Composition-Table tr").length;
      if(rowno > 1){
      	$('#Inventory-Composition-Table tr:last').remove();
    	}
    });

    

   

    $(document).on('click',".composition-deactivate-btn",function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_id : $(this).attr('service_id')
      };
      $.ajax({
        url: "<?=base_url()?>BusinessAdmin/DeleteComposition",
        data: parameters,
        type: "GET",
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
          else if (data.success == 'false'){                   
						$('#defaultModalDanger').modal('show').on('shown.bs.modal', function (e) {
							$("#ErrorModalMessage").html("").html(data.message);
						});
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
		$("#Category-Id").on('change',function(e){
			// alert($(this).val());
			// alert("hii");
			var parameters = {
				'category_id' :  $(this).val()
			};
			$.getJSON("<?=base_url()?>BusinessAdmin/GetSubCategoriesByCatId", parameters)
			.done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#Sub-Category-Id").html("").html(options);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
			});
		});

    $("#Sub-Category-Id").on('change',function(e){
		// alert($(this).val());
    	var parameters = {
    		'sub_category_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetServicesBySubCatId", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].service_id+">"+data[i].service_name+"</option>";
       		}
       		$("#Service-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

	$(document).on('change',".Raw-Material-ID",function(e){
    	var parameters = {
    		'service_id' :  $(this).val()
    	};
    	$.getJSON("<?=base_url()?>BusinessAdmin/GetService", parameters)
      .done(function(data, textStatus, jqXHR) {
      		$("#Inventory-Composition-Table tr:last").find('td .form-group .Raw-Material-Name').attr('value',data.service_name);
      		$("#Inventory-Composition-Table tr:last").find('td .form-group .Raw-Material-Brand').attr('value',data.service_brand);
      		$("#Inventory-Composition-Table tr:last").find('td .form-group .Raw-Material-Unit').attr('value',data.service_unit);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

	$("#AddComposition").validate({
	  	errorElement: "div",
	    rules: {
	        "service_id" : {
            required : true
	        },
	        "raw_material_name" : {
            required : true,
            maxlength : 100
	        },
	        "raw_material_brand" : {
	          required : true,
	          maxlength : 100
	        },
	        "raw_material_unit" : {
	        	required : true,
	        	maxlength : 50
	        },
	        "raw_material_id":{
	        	required : true
	        },
	        "consumption_quantity":{
	        	required : true
	        } 
	    },
	    submitHandler: function(form) {
				var formData = $("#AddComposition").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/AddComposition",
		        data: formData,
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

	$(document).on('click','.composition-view-btn',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_id : $(this).attr('service_id')
      };

      $.getJSON("<?=base_url()?>BusinessAdmin/GetParticularComposition", parameters)
      .done(function(data, textStatus, jqXHR) { 
       	var temp = "";
       	for(i=0;i<data.length;i++){
       		temp += "<tr>\
       							<td>"+(i+1)+"</td>\
       							<td>"+data[i].service_name+"</td>\
       							<td>"+data[i].service_name+"</td>\
       							<td>"+data[i].service_brand+"</td>\
       							<td>"+data[i].service_unit+"</td>\
       							<td>"+data[i].consumption_quantity+"</td>\
       						</tr>";
       	}
       	$("#ViewParticularComposition").html("").html(temp);
        $("#ModalViewComposition").modal('show');
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
