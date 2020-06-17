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
				<h1 class="h3 mb-3" style="color: #0070c0"><img src="<?=base_url()?>public/app_stack/img/google_reviews.png" style="height: 150px;width:450px"></h1>
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
					<div class="review"></div>
						<!-- <div class="col-md-7" style="margin-top: -30px">
							<div class="card">
								<!-- <div class="card-header">
									<h5 class="card-title">View Google Rewiews</h5>
								</div> -->
									<div class="card-body">
										<!-- <div>
										<b>Customer Name:</b><b style="margin-left:150px">Star Rating:</b>
										</div>
										<div style="margin-top:20px">
										<b>Comments:</b>
									</div>
									
									</div>
							</div>	
						<!-- </div>
					<div class="col-md-7" style="margin-top: 30px">
						<div class="card">
							<!-- <div class="card-header">
								<h5 class="card-title">View Google Rewiews</h5>
							</div>
								<div class="card-body">
									<div>
									<b>Customer Name:</b><b style="margin-left:150px">Star Rating:</b>
									</div>
									<div style="margin-top:20px">
									<b>Comments:</b>
								</div>
								</div>
						</div>	
					<!-- </div>
					<div class="col-md-7" style="margin-top: 30px">
						<div class="card">
							 <div class="card-header">
								<h5 class="card-title">View Google Rewiews</h5>
							</div> 
								<div class="card-body">
									<div>
									<b>Customer Name:</b><b style="margin-left:150px">Star Rating:</b>
									</div>
									<div style="margin-top:20px">
									<b>Comments:</b>
								</div>
								</div>
						</div>	
					</div> -->
					<?php
						}
					?>
				</div>
				
			</div>
		</main>
	</div>	
</div>		
<?php
	$this->load->view('business_admin/ba_footer_view');
?>
<script src="https://cdn.jsdelivr.net/gh/stevenmonson/googleReviews@6e8f0d794393ec657dab69eb1421f3a60add23ef/google-places.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyB4d6i9SYK1RvyGQrTngcGP9pina_xuhcc&signed_in=true&libraries=places"></script>
<script>
	var review;
jQuery(document).ready(function( $ ) {
   $(".review").googlePlaces({
        placeId: 'ChIJXdesym4dYzkR0y50pK3cRTU'//Find placeID @: https://developers.google.com/places/place-id
      , render: ['reviews']
   });
   console.log(review);

//    jQuery(document).ready(function ( $ ){
//        $("#google_reviews").googlePlaces({
//            placeId: 'ChIJiW4th53I5zsRR6TNDeaQYS0',
//            rener:['reviews'],
//            min_rating:4,
//            max_row:4
//
//        });
//    })
});
</script>