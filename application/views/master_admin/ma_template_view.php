<?php
	$this->load->view('master_admin/ma_header_view');
?>
<div class="wrapper">
	<?php
		$this->load->view('master_admin/ma_nav_view');
	?>
	<div class="main">
		<?php
			$this->load->view('master_admin/ma_top_nav_view');
		?>
		<main class="content">
			<div class="container-fluid p-0">
				<h1 class="h3 mb-3">XXXXXXX Management</h1>
				<div class="row">				
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
				</div>
			</div>
		</main>
<?php
	$this->load->view('master_admin/ma_footer_view');
?>
