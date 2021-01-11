<nav class="sidebar <?php if(isset($sidebar_collapsed)){ if($sidebar_collapsed){ echo 'sidebar-collapsed toggled'; }} ?>">
	<div class="sidebar-content">
		<a class="sidebar-brand" href="<?=base_url()?>MasterAdmin/Dashboard">
 		 <img src="<?=base_url()?>public/images/salonfirst.jpeg" width="200px" height="70px" class="img-responsive">
	   <!-- <span class="align-middle">MarkS ReTech</span> -->
    </a>

		<ul class="sidebar-nav">
			<!-- <?php
				if(array_search('POS System', $business_admin_packages) !== false):
			?> -->
			<li class="sidebar-item">
				<a href="#dashboards" data-toggle="" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="grid" style="color:#0070c0;"></i> <span class="align-middle">Dashboard</span>
		    </a>
				<ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Dashboard"><i data-feather="grid" style="color:#0070c0;"></i>Dashboard</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Permissions"><i data-feather="user-x" style="color:#0070c0;"></i>Users & Permissions</a></li>
					<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/MenuManagement"><i data-feather="list" style="color:#0070c0;"></i>Menu Management</a></li>-->
					<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Inventory"><i data-feather="book" style="color:#0070c0;"></i>Inventory & Stock</a></li>-->
					<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Employee"><i data-feather="user" style="color:#0070c0;"></i>Employee Management</a></li>-->
					<!--<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Discount"><i data-feather="package" style="color:#0070c0;"></i>Deals & Discounts</a></li>-->

					 <li class="sidebar-item"> 
						 <a href="#configurations" data-toggle="collapse" class="sidebar-link collapsed">
		          <span class="align-middle"><i data-feather="settings" style="color:#d50f25;"></i>Configurations</span>
		        </a>
				    <ul id="configurations" class="sidebar-dropdown list-unstyled collapse">
			    		<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/MenuManagement"><i data-feather="menu" style="color:#d50f25;"></i>Menu Management</a></li>	
				    </ul>
				</ul>
			</li>
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/ReportsManagement"><i data-feather="book" style="color:#009925;"></i>Reports</a></li>
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/Inventory"><i data-feather="book" style="color:#009925;"></i>Inventory</a></li>
			<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>MasterAdmin/BillSettings"><i data-feather="grid" style="color:#3369e8;"></i>Bill Settings</a></li>
			<!--  -->
			<!-- <?php
				endif;

				if(array_search('Marks360', $business_admin_packages) !== false):
			?> -->
			<!-- <li class="sidebar-item">
				<a href="#autoengage" data-toggle="collapse" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="settings" style="color:#0070c0;"></i> <span class="align-middle">Configurations</span>
		    </a>
				<ul id="autoengage" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/AutoEngage"><i data-feather="menu" style="color:#d50f25;"></i>Menu Management</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Loyalty"><i class="fa fa-user-friends" style="color:#eeb211;"></i>Employee</a></li>
				</ul>
			</li> -->
			<!-- <?php
				endif;
				if(array_search('Marks360', $business_admin_packages) !== false):
			?> -->
			<li class="sidebar-item">
				<!-- <a href="#emss" data-toggle="" class="sidebar-link collapsed">
		      <i class="align-middle" data-feather="user" style="color:green;"></i> <span class="align-middle">User Permissions</span>
		    </a> -->
				<!-- <ul id="emss" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Employee_details"><i data-feather="book" style="color:#0070c0;"></i>Details</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Attendance"><i data-feather="calendar" style="color:blue;"></i>Attendance</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Salary"><i class="fa fa-rupee-sign" style="color:red;"></i>Salary</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Commission"><i class="fa fa-rupee-sign" style="color:#009925;"></i>Commission</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Holidays"><i data-feather="sun" style="color:orange;"></i>Holidays</a></li>
				</ul> -->
			</li>
			
			<!-- <?php
				endif;
			?> -->
			<!--  -->
			<li class="sidebar-header">
				Other Options
			</li>
			<li class="sidebar-item">
				<a href="#ui" data-toggle="collapse" class="sidebar-link collapsed">
		          <i class="align-middle" data-feather="settings" style="color:#0070c0;"></i> <span class="align-middle">Settings</span>
		        </a>
				<ul id="ui" class="sidebar-dropdown list-unstyled collapse ">
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/Logout"><i data-feather="log-out" style="color:#eeb211;"></i>Logout</a></li>
					<li class="sidebar-item"><a class="sidebar-link" href="<?=base_url()?>BusinessAdmin/ResetBusinessAdminPassword"><i data-feather="grid" style="color:#3369e8;"></i>Reset Password</a></li>
				</ul>
			</li>
		</ul>
		<div class="sidebar-bottom d-none d-lg-block">
			<div class="media">
				<img class="rounded-circle mr-3" src="<?=base_url()?>public/images/default.png" alt="Business Admin" width="40" height="40">
				<div class="media-body">
					<h5 class="mb-1">Pranshu</h5>
					<div>
						<i class="fas fa-circle text-success"></i> Online
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
