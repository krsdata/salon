<nav class="sidebar <?php if(isset($sidebar_collapsed)){ if($sidebar_collapsed){ echo 'sidebar-collapsed toggled'; }} ?>">
	<div class="sidebar-content">
		<a class="sidebar-brand" href="<?=base_url()?>SuperAdmin/Dashboard">
 		 <img src="<?=base_url()?>public/images/salonfirst.jpeg" width="200px" height="70px" class="img-responsive">
	   <!-- <span class="align-middle">MarkS ReTech</span> -->
    </a>

		<ul class="sidebar-nav">
			<li class="sidebar-item">
				<a href="#dashboards" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="sliders" style="color:#0070c0;"></i> <span class="align-middle">Super Admin Panel</span>
		    </a>
				<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>SuperAdmin/Dashboard"><i data-feather="grid" style="color:#0070c0;"></i>Dashboard</a></li>
					<li class="sidebar-item">
						<a href="#configurations" data-toggle="collapse" class="sidebar-link collapsed">
		         			 <span class="align-middle"><i data-feather="settings"></i>Settings</span>
		        		</a>
						<ul id="configurations" class="sidebar-dropdown list-unstyled collapse">
							<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>SuperAdmin/ResetAdminPassword"><i data-feather="key" style="color:#eeb211;"></i>Reset Admin Password</a></li>
						</ul>
					</li>
					<li class="sidebar-item">
				<a href="#customers" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="users" style="color:#0070c0;"></i> <span class="align-middle">Customers</span>
		    </a>
				<ul id="customers" class="sidebar-dropdown list-unstyled collapse">
					 <li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>SuperAdmin/CustomerHistory"><i data-feather="clock" style="color:#0070c0;"></i>Transaction History</a></li> 
					
				</ul>
			</li>
				</ul>
			</li>
			
		</ul>


		<div class="sidebar-bottom d-none d-lg-block">
			<div class="media">
				<img class="rounded-circle mr-3" src="<?=base_url()?>public/images/default.png" alt="Business Admin" width="40" height="40">
				<div class="media-body">
					<h5 class="mb-1">Ashok</h5>
					<div>
						<i class="fas fa-circle text-success"></i> Online
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
