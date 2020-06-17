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
		<div class="row" style="margin-right:0px;margin-left: 15px;">		
			<div class="col-md-4 videoCard">	
				<video width="300" controls controlsList="nodownload">
					<source src="<?=base_url()?>public/app_stack/video/add_customer_and_billing.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
			<div class="col-md-4 videoCard">
				<video width="300" controls controlsList="nodownload">
					<source src="<?=base_url()?>public/app_stack/video/existing_customer_billing.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
			<div class="col-md-4 videoCard">
				<video width="300" controls controlsList="nodownload">
					<source src="<?=base_url()?>public/app_stack/video/appointment.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</div>
		<div class="row" style="margin-right:0px;margin-left: 15px;">
			<div class="col-md-4" >
				<h3>Add customer and billing</h3>
			</div>
			<div class="col-md-4">
				<h3>Existing customer billing</h3>
			</div>
			<div class="col-md-4">
				<h3>Add Appointment</h3>
			</div>
		</div>
		<div class="row" style="margin-right:0px;margin-left: 15px;">	
			<div class="col-md-4 videoCard">
				<video width="300" controls controlsList="nodownload">
					<source src="<?=base_url()?>public/app_stack/video/packages_billing.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
			<div class="col-md-4 videoCard">
				<video width="300" controls controlsList="nodownload">
					<source src="<?=base_url()?>public/app_stack/video/package_redemption.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</div>
		<div class="row" style="margin-right:0px;margin-left: 15px;">
			<div class="col-md-4">
				<h3>Package Billing</h3>
			</div>
			<div class="col-md-4">
				<h3>Package Redemption</h3>
			</div>
			<!-- <div class="col-md-4">
				<h3>Add customer and billing</h3>
			</div> -->
		</div>
<?php
	$this->load->view('cashier/cashier_footer_view');
?>
