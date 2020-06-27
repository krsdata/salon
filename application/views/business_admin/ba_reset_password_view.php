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
				<h1 class="h3 mb-3">Change Password</h1>
				<div class="row">
					<div class="col-12">
						<div class="card">
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

								<form id="ChangePassword" action="#" method="POST">
									<div class="form-group">
										<label>New password</label>
										<input type="password" class="form-control" name="new_password">
									</div>
									<div class="form-group">
										<label>Verify password</label>
										<input type="password" class="form-control" name="confirm_new_password">
									</div>
									<button type="submit" class="btn btn-primary">Save changes</button>
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
				</div>
			</div>
		</main>
<?php
	$this->load->view('business_admin/ba_footer_view');
?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#ChangePassword").validate({
	  	errorElement: "div",
	    rules: {
	        "new_password" : {
            required : true
	        },
	        "confirm_new_password" : {
            required : true
	        }
	    },
	    submitHandler: function(form) {
				var formData = $("#ChangePassword").serialize(); 
				$.ajax({
		        url: "<?=base_url()?>BusinessAdmin/ResetBusinessAdminPassword",
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
	});
</script>