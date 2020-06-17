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
				<h1 class="h3 mb-3">Inventory Management</h1>
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
                                <div class="col-md-12">
                                    <div class="tab">
                                        <ul class="nav nav-pills" role="tablist" style="font-weight: bolder">
                                            <li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Products</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab" role="tab" aria-selected="false" style="font-size:14px;">Bulk Upload Inventory</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab-1" role="tabpanel">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <button class="btn btn-primary btn-lg" id="AddProduct" data-toggle="modal" data-target="#ModalAddOTCStock"><i class="fas fa-fw fa-plus"></i> Add Product</button>
                                                        <button class="btn btn-primary btn-lg" id="download" style="float:right" ><i class="fa fa-file-export"></i> Download</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <!--Modal Area starts-->
                                                        <div class="modal" id="ModalAddOTCStock" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h5 class="modal-title text-white">Add Stock</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true" class="text-white">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body m-3">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                    <form id="AddOTCInventory" method="POST" action="#">
                                                                        <div  class="smartwizard-arrows-primary wizard wizard-primary">
                                                                            <ul>
                                                                                <li><a href="#arrows-primary-step-1">Inventory<br /></a></li>
                                                                                <li><a href="#arrows-primary-step-2">Invoice<br /></a></li>
                                                                            </ul>
                                                                            <div>
															                    <div id="arrows-primary-step-1" class="">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                        <label>Inventory Type</label>
                                                                                        <select class="form-control" id="otc_inventory_type" name="otc_inventory_type" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Retail Product">Retail Product</option>
                                                                                                <option value="Raw Material">Raw Material</option>
                                                                                        </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="input-group col-md-6">
                                                                                            <!-- <label>Search Item</label> -->
                                                                                            <input type="text" class="form-control" placeholder="Search Item Name" id="SearchServiceByName" name="search">
                                                                                            <span class="input-group-append">
                                                                                                    <button class="btn btn-success" type="button" id="SearchServiceButton">Go</button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row" style="margin-top:8px">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Category</label>
                                                                                            <select class="form-control" name="category_id" id="OTC-Category-Id">
                                                                                                
                                                                                            </select>
                                                                                        </div>   
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Sub-Category</label>
                                                                                            <select class="form-control" name="otc_sub_category_id" id="OTC-Sub-Category-Id">
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Item Name</label>
                                                                                            <select class="form-control" name="service_id" style="width: 100%" id="ServiceOTCId">
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>&ensp;</label>
                                                                                            <span class="input-group-append">
                                                                                                <button class="btn btn-success" type="button" id="SearchItemButton" >Go</button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class='row'>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Category</label>
                                                                                            <input class="form-control" placeholder="Category" id="category" name="otc_category" readonly>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Sub-Category</label>
                                                                                            <input class="form-control" placeholder="Sub-Category" id="sub_category" name="otc_sub_category" readonly>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Item</label>
                                                                                            <input class="form-control" placeholder="Item" id="otc_item" name="otc_item" readonly>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>SKU Size</label>
                                                                                            <select class="form-control" name="sku_size" style="width: 100%" id="Service_SKU_Size">
                                                                                                <option value>Select...</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class='row'>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>SKU Count</label>
                                                                                            <input type="number" name="sku_count" id="sku_count" class="form-control" placeholder="Number of SKU">
                                                                                        </div>
                                                                                        <div class="form-group col-md-1" style="padding:0px 0px 0px 0px;margin:0px 0px 0px 12px">
                                                                                            <!-- <input type="text" name="expiry_date" id="expiry" class="form-control date"> -->
                                                                                            <label>Expiry Date</label>
                                                                                            <select name="year" id="year" class="form-control" style="width:60px;padding:0px 0px;margin:0px 0px 0px">
                                                                                                <option disabled selected>Year</option>
                                                                                                <?php
                                                                                                    for($i=date('Y');$i<=date('Y')+3;$i++)
                                                                                                    {
                                                                                                ?>
                                                                                                        <option value="<?=$i?>"><?=$i?></option>
                                                                                                <?php		
                                                                                                    }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-1" style="float:left;padding:0px 0px 0px 0px;margin:0px 0px 0px 0px">	
                                                                                            <label>&ensp;</label>
                                                                                            <select name="month" id="month" class="form-control" style="width:80px;padding:0px 0px 0px 0px;margin:0px 0px 0px 0px;" >
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
                                                                                        <div class="form-group col-md-3">
                                                                                            <input type="text" name="barcode" class="form-control" id="barcode" hidden>
                                                                                            <input type="text" name="unit" class="form-control" id="unit" hidden>
                                                                                            <input type="text" name="barcode_id" class="form-control" hidden>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="arrows-primary-step-2" class="">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Purchase Date</label>
                                                                                            <input type="text" name="ipurchase_date" value="<?=date('Y-m-d')?>" class="form-control date" placeholder="Purchase Date">
                                                                                        </div>
                                                                                
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Select Vendor</label>
                                                                                            <select name="ivendors" class="form-control" >
                                                                                                   
                                                                                                    <option value="" disabled selected> Select Vendor</option>
                                                                                                    <?php
                                                                                                        foreach($vendors as $vendor){
                                                                                                    ?>
                                                                                                            <option value="<?=$vendor['vendor_id']?>"><?=$vendor['vendor_name']?></option>
                                                                                                    <?php
                                                                                                        }
                                                                                                    ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Invoice no.</label>
                                                                                            <input type="text" name="iinvoice_no" class="form-control" placeholder="Invoice No.">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>HSN Code</label>
                                                                                            <input type="text" name="ihsn_no" class="form-control" placeholder="HSN Code">
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Product Name</label>
                                                                                            <input type="text" name="iproduct_name" id="iproduct_name" class="form-control" placeholder="Product Name">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Base Cost</label>
                                                                                            <input type="text" name="ibase_cost" id="ibase_cost" class="form-control" placeholder="Base Cost Amount"> 
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                             <label>GST</label>
                                                                                            <input type="text" name="igst" id="igst" class="form-control" placeholder="%GST">
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Total Cost</label>
                                                                                            <input type="text" name="itotal_cost" id="itotal_cost" class="form-control" placeholder="Total Cost Amount">
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>MRP</label>
                                                                                            <input type="text" name="imrp" id="imrp" class="form-control" placeholder="MRP/Unit">
                                                                                            <input type="text" id="imrptemp" class="form-control" hidden>
                                                                                            <input type="text" id="igsttemp" class="form-control" hidden>
                                                                                            <input type="text" id="ipricetemp" class="form-control" hidden>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Type Of Invoice</label>
                                                                                            <!-- <input type="text" name="itype_of_invoice" class="form-control" placeholder="Type of Invoice"> -->
                                                                                            <select name="itype_of_invoice" class="form-control">
                                                                                                <option value="Tax">Tax</option>
                                                                                                <option value="Lumpsum">Lumpsum</option>
                                                                                                <option value="Others">Others</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group col-md-3">
                                                                                            <label>Freight Charges</label>
                                                                                            <input type="text" name="ifreight_charges" class="form-control" placeholder="Freight Charges">
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                                </div>
                                                                            </div>    
                                                                                
                                                                        </div>        
                                                                    </form>
                                                                    <div class="alert alert-dismissible feedback" role="alert">
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
                                                        <!--Modal Area Ends-->
                                                        <table class="table table-striped datatables-basic" style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>SubCategory</th>
                                                                    <th>Category</th>
                                                                    <th>Inventory Type</th>
                                                                    <th>SKU Size</th>
                                                                    <th>Unit</th>										
                                                                    <th>Total Stock</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if(isset($otc_stock)){
                                                                    foreach ($otc_stock as $stock):
                                                                ?>
                                                                <tr>
                                                                    <td><?=$stock['service_name']?></td>
                                                                    <td><?=$stock['sub_category_name']?></td>
                                                                    <td><?=$stock['category_name']?></td>
                                                                    <td><?=$stock['inventory_type']?></td>
                                                                    <td><?=$stock['qty_per_item']?></td>
                                                                    <td><?=$stock['service_unit']?></td>											
                                                                    <td><?=$stock['sku_count']?></td>											
                                                                </tr>	
                                                                <?php		
                                                                    endforeach;
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>			
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-2" role="tabpanel">
                                                <div class="card">
                                                    <div class="card-header">
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">    
                                                            <div class="col-md-6">
                                                                <a class="btn btn-primary" href="<?=base_url()?>public\format\InventoryFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <form action="#" id="UploadInventory">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6" style="overflow:hidden;">
                                                                            <input type="file" name="file" class="btn" />
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <button type= "submit" class="btn btn-primary" ><i class="fa fa-upload"></i>Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>    
                                                       								
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Modal Area-->
                                        <?php
                                            $this->load->view('cashier/cashier_success_modal_view');
                                            $this->load->view('cashier/cashier_error_modal_view');
                                        ?>
                                        
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
	$(".date").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});

	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
</script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$(".datatables-basic").DataTable({
			responsive: true
		});

		// Initialize Select2 select box
		$("#RawMaterialCategoryId").select2({
			allowClear: true,
			placeholder: "Select...",
		}).change(function() {
			$(this).valid();
		});

	

		$("#RawMaterialCategoryId").on('change',function(e){
  		var parameters = {
  			'raw_material_category_id' :  $(this).val()
  		};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetRawMaterial/", parameters)
      .done(function(data, textStatus, jqXHR) {
      	$("#AddRawMaterialInventory input[name=rm_brand]").attr('value',data.raw_material_brand);
        $("#AddRawMaterialInventory input[name=rm_category_id]").attr('value',data.raw_material_category_id);
        $("#AddRawMaterialInventory select[name=rm_unit]").val(data.raw_material_unit);	
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });



    $("#AddRawMaterialInventory").validate({
	  	errorElement: "div",
	    rules: {
	        "rmc_id" : {
            required : true
	        },
	        "rm_sku" : {
	        	required : true,
	        	digits : true
	        },
	        "rm_quantity":{
	        	required : true,
	        	digits : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#AddRawMaterialInventory").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>index.php/BusinessAdmin/AddRawMaterialInventory/",
	        data: formData,
	        type: "POST",
	        crossDomain: true,
					cache: false,
	        dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddRawMaterial").modal('hide'); 
							$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
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

	  $("#AddOTCInventory").validate({
	  	errorElement: "div",
	    rules: {
	       
	        "sku_count" : {
	        	required : true,
	        	digits : true
	        }

	    },
	    submitHandler: function(form) {
			// alert(document.getElementById('expiry').value);
				var formData = $("#AddOTCInventory").serialize(); 
				$.ajax({
	        url: "<?=base_url()?>index.php/BusinessAdmin/AddOTCInventory/",
	        data: formData,
	        type: "POST",
	        crossDomain: true,
					cache: false,
	        dataType : "json",
	    		success: function(data) {
            if(data.success == 'true'){
            	$("#ModalAddOTCStock").modal('hide'); 
							$('#centeredModalSuccess').modal('show').on('shown.bs.modal', function (e){
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

	});

	 //functionality for getting the dynamic input data
	 $("#SearchServiceByName").typeahead({
      autoselect: true,
      highlight: true,
      minLength: 1
     },
     {
      source: SearchServiceByName,
      templates: {
        empty: "No Services Found!",
        suggestion: _.template("<p class='service_search'><%- service_name %>(<%- qty_per_item %><%- service_unit %>),(<%- sub_category_name %>), (<%- category_name %>),Rs <%- service_price_inr %> </p>")
      }
    });
       
    var to_fill = "";

    $("#SearchServiceByName").on("typeahead:selected", function(eventObject, suggestion, name) {
      var loc = "#SearchServiceByName";
      to_fill = suggestion.service_name;
      setVals(loc,to_fill,suggestion);
    });

    $("#SearchServiceByName").blur(function(){
      $("#SearchServiceByName").val(to_fill);
      to_fill = "";
    });

    function SearchServiceByName(query, cb){
		var inventory = document.getElementById("otc_inventory_type").value;
      var parameters = {
        query : query,
		inventory_type : inventory
      };
      
      $.ajax({
        url: "<?=base_url()?>index.php/BusinessAdmin/GetProductData/",
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

    function setVals(element,fill,suggestion){
      $(element).attr('value',fill);
      $(element).val(fill);
      
      $("#SearchServiceButton").attr('service-id',suggestion.service_id);
      $("#SearchServiceButton").attr('service-name',suggestion.service_name);
      $("#SearchServiceButton").attr('service-price-inr',suggestion.service_price_inr);
      $("#SearchServiceButton").attr('service-gst-percentage',suggestion.service_gst_percentage);
      $("#SearchServiceButton").attr('service-est-time',suggestion.service_est_time);
    }


	$(document).on('click',"#SearchServiceButton",function(event){
    	event.preventDefault();
      	this.blur();
		//   var product = $(this).attr('service-id');
		//   alert(product);
		var parameters = {
        product : $(this).attr('service-name'),
		inventory_type : $(this).attr('otc_inventory_type')
		};	
		//   alert(parameters);
		$.ajax({
	        url: "<?=base_url()?>index.php/BusinessAdmin/GetProductDetails/",
	        data: parameters,
	        type: "GET",
	        crossDomain: true,
					cache: false,
	        dataType : "json",
	    	success: function(data) {
            if(data.success == 'true'){
							document.getElementById("category").value = data.result[0]['category_name'];
							document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
							document.getElementById("otc_item").value = data.result[0]['service_name'];
                            document.getElementById("iproduct_name").value = data.result[0]['service_name'];
							document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
							document.getElementById("barcode").value = data.result[0]['barcode'];
							document.getElementById("unit").value = data.result[0]['service_unit'];
							// document.getElementById("barcode_id").value = data.result[0]['barcode_id']; 
							for(var i=0; i < data.result.length;i++ ){
								$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
							}
            }
            else if (data.success == 'false'){                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }	
			}
		}); 
    });
	$(document).on('click',"#SearchItemButton",function(event){
    	event.preventDefault();
      	this.blur();
		  var product = document.getElementById("ServiceOTCId").value;
		  var inventory = document.getElementById("otc_inventory_type").value;
		//   alert(product);
		var parameters = {
        product : product,
		inventory_type : inventory
		};	
		//   alert(parameters);
		$.ajax({
	        url: "<?=base_url()?>index.php/BusinessAdmin/GetProductDetail/",
	        data: parameters,
	        type: "GET",
	        crossDomain: true,
			cache: false,
	        dataType : "json",
	    	success: function(data) {
            if(data.success == 'true'){
							document.getElementById("category").value = data.result[0]['category_name'];
							document.getElementById("sub_category").value = data.result[0]['sub_category_name'];
							document.getElementById("otc_item").value = data.result[0]['service_name'];
                            document.getElementById("iproduct_name").value = data.result[0]['service_name'];
							document.getElementById("OTC-Category-Id").value = data.result[0]['category_id'];
							for(var i=0; i < data.result.length;i++ ){
								$("#AddOTCInventory select[name=sku_size]").append("<option value=" + data.result[i]['service_id']+","+data.result[i]['qty_per_item'] + ">" + data.result[i]['qty_per_item'] + "</option>")
							}
            }
            else if (data.success == 'false'){                   
        	    $('#centeredModalDanger').modal('show').on('shown.bs.modal', function (e) {
								$("#ErrorModalMessage").html("").html(data.message);
							});
            }	
			}
		}); 
    });
	// function GetServiceid(){
	// 	var a = document.getElementById("Service_SKU_Size").value;
	// 	alert(a);
	// }
	$("#otc_inventory_type").on('change',function(e){
    	var parameters = {
    		'inventory_type' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetCategoriesByInventory/", parameters)
      .done(function(data, textStatus, jqXHR) {
		//   alert(data);
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.res_arr.length;i++){
       			options += "<option value="+data.res_arr[i].category_id+">"+data.res_arr[i].category_name+"</option>";
       		}
       		$("#OTC-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
	$("#OTC-Category-Id").on('change',function(e){
    	var parameters = {
    		'category_id' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetSubCategoriesByCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value="+data[i].sub_category_id+">"+data[i].sub_category_name+"</option>";
       		}
       		$("#OTC-Sub-Category-Id").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#OTC-Sub-Category-Id").on('change',function(e){
		// alert("hii");
    	var type = document.getElementById('otc_inventory_type').value;
		var parameters = {
    		'sub_category_id' :  $(this).val(),
			'type'	: type
    	};
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetServicesBySubCatId/", parameters)
      .done(function(data, textStatus, jqXHR) {
      		var options = "<option value='' selected></option>"; 
       		for(var i=0;i<data.length;i++){
       			options += "<option value='"+data[i].service_name+"'>"+data[i].service_name+"</option>";
       		}
       		$("#ServiceOTCId").html("").html(options);
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
</script>
<script type="text/javascript">
	<?php if($modal == 1) { ?>
			$(document).ready(function(){        
				$('#AddProduct').click();
			}); 
	<?php } ?>		
	$("input[name=\"expiry_date\"]").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM'
            }
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM'));
        });	
        $(".smartwizard-arrows-primary").smartWizard({
			theme: "arrows",
			showStepURLhash: false
		});
</script>
<script type="text/javascript">
	$(document).on('click',"#download",function(event){
		
		event.preventDefault();
		this.blur();
			
			$.getJSON("<?=base_url()?>index.php/BusinessAdmin/InventoryDownload/")	
			.done(function(data, textStatus, jqXHR) {
					
				JSONToCSVConvertor(data.result," ", true);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown.toString());
			});
		});
	
</script>
<script>
		function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
			//If JSONData is not an object then JSON.parse will parse the JSON string in an Object
			var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
			
			var CSV = '';    
			//Set Report title in first row or line
			
			CSV += ReportTitle + '\r\n\n';

			//This condition will generate the Label/Header
			if (ShowLabel) {
				var row = "";
				
				//This loop will extract the label from 1st index of on array
				for (var index in arrData[0]) {
					
					//Now convert each value to string and comma-seprated
					row += index + ',';
				}

				row = row.slice(0, -1);
				
				//append Label row with line break
				CSV += row + '\r\n';
			}
			
			//1st loop is to extract each row
			for (var i = 0; i < arrData.length; i++) {
				var row = "";
				
				//2nd loop will extract each column and convert it in string comma-seprated
				for (var index in arrData[i]) {
					row += '"' + arrData[i][index] + '",';
				}

				row.slice(0, row.length - 1);
				
				//add a line break after each row
				CSV += row + '\r\n';
			}

			if (CSV == '') {        
				alert("Invalid data");
				return;
			}   
			
			//Generate a file name
			var fileName = "MSS_";
			//this will remove the blank-spaces from the title and replace it with an underscore
			fileName += ReportTitle.replace(/ /g,"_");   
			
			//Initialize file format you want csv or xls
			var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
			
			// Now the little tricky part.
			// you can use either>> window.open(uri);
			// but this will not work in some browsers
			// or you will not get the correct file extension    
			
			//this trick will generate a temp <a /> tag
			var link = document.createElement("a");    
			link.href = uri;
			
			//set the visibility hidden so it will not effect on your web-layout
			link.style = "visibility:hidden";
			link.download = fileName + ".csv";
			
			//this part will append the anchor tag and remove it after automatic click
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		}
</script>
<script type="text/javascript">
	$(".date").daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		autoUpdateInput : false,
		locale: {
      format: 'YYYY-MM-DD'
		}
	});

	$('.date').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });

    $("#Service_SKU_Size").on('change',function(e){
    	var parameters = {
    		'item_id' :  $(this).val()
    	};
		// alert($(this).val());
    	$.getJSON("<?=base_url()?>index.php/BusinessAdmin/GetProduct/", parameters)
      .done(function(data, textStatus, jqXHR) {
          var mrp = parseInt(data.result[0]['service_price_inr'])+parseInt((data.result[0]['service_price_inr']*data.result[0]['service_gst_percentage'])/100);
        //   alert(mrp);
            document.getElementById("imrptemp").value = mrp;
            document.getElementById("imrp").value = mrp;
            document.getElementById("igsttemp").value = data.result[0]['service_gst_percentage'];
            document.getElementById("igst").value = data.result[0]['service_gst_percentage'];
            document.getElementById("ipricetemp").value = data.result[0]['service_price_inr'];
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

    $("#ibase_cost").on('keyup',function(e){
    	
		// alert($(this).val());
        var sku=parseInt(document.getElementById("sku_count").value);
        // document.getElementById("ibase_cost").value = baseamount;
        var baseamount = sku * $(this).val();
        var totalamount = parseInt( baseamount ) + parseInt((baseamount * document.getElementById("igsttemp").value)/100);
        // alert(totalamount);
        document.getElementById("itotal_cost").value = totalamount;
        
    });



    // bulk upload inventory
    $("#UploadInventory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>index.php/BusinessAdmin/BulkUploadInventory/",
        type: "POST",             // Type of request to be send, called as method
        data: formData,
        dataType : "json",
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
								timeOut: 1000
							});
							setTimeout(function () { location.reload(1); }, 1000);
          }
         	else if (data.success == 'false'){                   
                toastr["success"](data.message,"", {
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
          alert("Something went wrong!");
        }
      }); 
    });
</script>