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
				<h1 class="h3 mb-3"></h1>
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
                <div class="card-header" style="margin-left:10px;">
                <h3>Integrate Facebook Appointment</h3>
                </div>
                <div class="card-body">
                 <form method="POST" action="#" id="AddCalendar">
                  <div class="form-row">
                    <!--<div class="form-group col-md-6">-->
                    <!--  <label>Enter Api Key : </label>-->
                    <!--  <input type="text" class="form-control" name="api_key" placeholder="Enter API KEY"  id="api_key" required />-->
                      
                    <!--</div>-->
                    <div class="form-group col-md-6">
                      <label>Enter Google Calendar Id: </label>
                    <input type="text" class="form-control" name="calendarId" placeholder="Enter Calendar ID"  id="calendar_id" required />
                    <span class="input-group-append">
                      </span>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary mt-1">Submit</button>						
                </form>
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
    $("#AddCalendar").validate({
		errorElement: "div",
		submitHandler: function(form) {
			var formData = $("#AddCalendar").serialize(); 
			
			$.ajax({
				url: "<?=base_url()?>index.php/BusinessAdmin/IntegrateGoogle/",
				data: formData,
				type: "POST",
				crossDomain: true,
				cache: false,
				dataType : "json",
				success: function(data) {
					if(data.success == 'true'){
						$("#ModalAddCalendar").modal('hide'); 
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
						toastr["alert"](data.message,"", {
							positionClass: "toast-top-right",
							progressBar: "toastr-progress-bar",
							newestOnTop: "toastr-newest-on-top",
							rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
							timeOut: 1000
						}); 
					}
				},
				error: function(data){
					$('.feedback').addClass('alert-danger');
					$('.alert-message').html("").html(data.message); 
				}
			});
		}
	});
</script>