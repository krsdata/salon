<nav class="navbar navbar-expand">
	<a class="sidebar-toggle d-flex mr-2">
		<i class="hamburger align-self-center"></i>
	</a>&ensp;&ensp;&ensp;
	<a class="text-white" href="<?=base_url()?>Cashier/Dashboard/" title="Billing"><i data-feather="shopping-cart" ></i></a>&ensp;&ensp;&ensp;
	<a class="text-white" href="<?=base_url()?>Cashier/BuyPackages/" title="Packages"><i data-feather="gift"></i></a>&ensp;&ensp;&ensp;
	
	<?php
				if(array_search('Appointments', $business_admin_packages) !== false):
	?>
	<a class="text-white" href="<?=base_url()?>Cashier/Appointment/AddAppointmentModal"  title="Appointment">	<i data-feather="clock"></i></a>&ensp;&ensp;&ensp;
	<?php
				endif;
	?>
	<a class="text-white" href="<?=base_url()?>Cashier/Inventory/AddInventoryModal" title="Inventory"><i class="fas fa-luggage-cart" style="font-size:17px"></i></a>&ensp;&ensp;&ensp;
	<a class="text-white" href="<?=base_url()?>Cashier/Expenses/AddExpenseModal"  title="Expenses"><i class="far fa-list-alt" style="font-size:17px"></i></a>&ensp;&ensp;&ensp;
	
	<!-- <i class="fas fa-shoe-prints"></i><h5><span class="text-white font-weight-bold">45</span>&ensp;&ensp;&ensp; -->
			
	<div class="navbar-collapse collapse">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item" style="margin-top: 8px;margin-right: 16px;">
				<img src="<?=base_url()?>public/images/visit.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> 
				<?php
					echo "<span class=\"text-white font-weight-bold\">".$nav_details['visit']."</span>";
				?>
			</li>
			<li class="nav-item" style="margin-top: 8px;margin-right: 16px;">
				<img src="<?=base_url()?>public/images/revenue.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> 
				<?php
					echo "<span class=\"text-white font-weight-bold\">".($nav_details['revenue']-($loyalty_payment+$cards_data['payment_wise']['others']))."</span>";
				?>
			</li>
			<li class="nav-item" style="margin-top: 8px;margin-right: 16px;">
				<img src="<?=base_url()?>public/images/appointment.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> 
				<?php
					echo "<span class=\"text-white font-weight-bold\">".$nav_details['appointment']."</span>";
				?>
			</li>
			<li class="nav-item" style="margin-top: 8px;margin-right: 16px;">
				<img src="<?=base_url()?>public/images/shop_icon.jpg" class="avatar img-fluid rounded-circle mr-1" alt="Business Admin" /> 
				<?php
					echo "<span class=\"text-white font-weight-bold\">".$cashier_details['business_outlet_name']."</span>";
				?>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle d-none d-sm-inline-block text-white" href="#" data-toggle="dropdown">
			    <img src="<?=base_url()?>public/images/default.png" class="avatar img-fluid rounded-circle mr-1" alt="Cashier" /> <span class="text-white font-weight-bold"><?=$cashier_details['employee_first_name']?> <?=$cashier_details['employee_last_name']?></span>
			  	</a>
				<div class="dropdown-menu dropdown-menu-right">
				
					<a class="dropdown-item" href="<?=base_url()?>Cashier/Profile/"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
					<a class="dropdown-item" href="<?=base_url()?>Cashier/Logout/">Sign out</a>
				</div>
			</li>
		</ul>
	</div>
</nav>
