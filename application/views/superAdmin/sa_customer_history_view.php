<?php
	$this->load->view('superAdmin/sa_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('superAdmin/sa_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('superAdmin/sa_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3"></h1>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header" style="margin-left:10px;">
                                <h4 class="card-title">Transaction History</h4><br>
                                <ul class="nav nav-pills card-header-pills pull-right" role="tablist" style="font-weight: bolder">
                                    <li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab-1"> Invoice Wise Sales</a>
									</li>
                                    <li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-2">Item Wise Sales</a>
                                    </li>
                                    <li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-3">Package Wise Sales</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab-4">Customers Details </a>
									</li>
								</ul>
							</div>
							<div class="card-body">
                                <!-- upload -->
                                <div class="tab-content">
									<div class="tab-pane show active" id="tab-1" role="tabpanel">
                                        <div class="row">  
                                            <div class="col-md-6">
                                                <a class="btn btn-primary" href="<?=base_url()?>public\format\TransactionHistoryFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="#" id="UploadTransactionHistory">
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
                                    <div class="tab-pane show" id="tab-2" role="tabpanel">
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <a class="btn btn-primary" href="<?=base_url()?>public\format\ServiceTransactionFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="#" id="UploadTransactionServiceHistory">
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
                                    <div class="tab-pane show" id="tab-3" role="tabpanel">
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <a class="btn btn-primary" href="<?=base_url()?>public\format\PackageTransactionFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="#" id="UploadTransactionPackageHistory">
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
                                    <div class="tab-pane show" id="tab-4" role="tabpanel">
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <a class="btn btn-primary" href="<?=base_url()?>public\format\customerUploadFormat.xlsx" download><i class="fa fa-download"></i>Format</a>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="#" id="UploadCustomerDetails">
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
			    </div>
		</main>
<?php
$this->load->view('superAdmin/sa_footer_view');
?>
<script>
    $("#UploadTransactionHistory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>index.php/SuperAdmin/BulkUploadTransaction/",
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
    $("#UploadTransactionServiceHistory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>index.php/SuperAdmin/BulkUploadServiceTransaction/",
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
    $("#UploadTransactionPackageHistory").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>index.php/SuperAdmin/BulkUploadPackageTransaction/",
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
    $("#UploadCustomerDetails").submit(function(e){
      event.preventDefault();
      var formData = new FormData(this);
       $.ajax({
        url: "<?=base_url();?>index.php/SuperAdmin/UploadCustomerDetails/",
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