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
				<h2>Users & Permissions</h2>
				<div class="row">				
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								Users & Management
							</div>
							<div class="card-body">
								<table class="table">
									<thead class="text-center">
										<th>Business Admin</th>
										<th colspan="3">Category</th>
										<th colspan="3">Sub-Category</th>
										<th colspan="3">Service</th>
										<th colspan="3">Products</th>
										<th colspan="3">Raw Material</th>
									</thead>
									<thead style="color:blue;">
									<td>Business Admin</td>
										<td>Create</td>
										<td>Edit</td>
										<td>Delete</td>
										<td>Create</td>
										<td>Edit</td>
										<td>Delete</td>
										<td>Create</td>
										<td>Edit</td>
										<td>Delete</td>
										<td>Create</td>
										<td>Edit</td>
										<td>Delete</td>
										<td>Create</td>
										<td>Edit</td>
										<td>Delete</td>
										
									</thead>
									<tbody>
										<?php 
										foreach($business_admin as $admin){
										?>
										<tr>
										<td><?=$admin['business_admin_first_name']?></td>
										<td>
											<?php if($admin['category_create']==1){?>
												<input type="checkbox" name="create_category" id="create_category" business_admin_id="<?=$admin['business_admin_id']?>" class="createCategory" create_category="0" checked>
											<?php }else{?>
												<input type="checkbox" name="create_category" class="createCategory" business_admin_id="<?=$admin['business_admin_id']?>" id="create_category" create_category="1">
											<?php }?>
										</td>
										<td>
											<?php if($admin['category_edit']==1){?>
												<input type="checkbox" name="edit_category" class="editCategory" id="edit_category" business_admin_id="<?=$admin['business_admin_id']?>" edit_category="0" checked>
											<?php }else{?>
											<input type="checkbox" name="edit_category"  class="editCategory" id="edit_category" edit_category="1" business_admin_id="<?=$admin['business_admin_id']?>">
											<?php }?>
										</td>
										<td>
											<?php if($admin['category_delete']==1){?>
											<input type="checkbox" name="delete_category" id="delete_category" class="deleteCategory" business_admin_id="<?=$admin['business_admin_id']?>" delete_category="0" checked>
											<?php }else{?>
												<input type="checkbox" class="deleteCategory" name="delete_category" id="delete_category" business_admin_id="<?=$admin['business_admin_id']?>" delete_category="1">
											<?php }?>											
										</td>
										<td>
										<?php if($admin['sub_category_create']==1){?>
												<input type="checkbox" name="create_category" id="create_category" business_admin_id="<?=$admin['business_admin_id']?>" class="createSubCategory" sub_category_create="0" checked>
											<?php }else{?>
												<input type="checkbox" name="create_category" class="createSubCategory" business_admin_id="<?=$admin['business_admin_id']?>" id="create_category" sub_category_create="1">
											<?php }?>
										</td>
										<td>
											<?php if($admin['sub_category_edit']==1){?>
												<input type="checkbox" name="edit_category" class="editSubCategory" id="edit_category" business_admin_id="<?=$admin['business_admin_id']?>" edit_sub_category="0" checked>
											<?php }else{?>
												<input type="checkbox" name="edit_category"  class="editSubCategory" id="edit_category" edit_sub_category="1" business_admin_id="<?=$admin['business_admin_id']?>">
											<?php }?>
										</td>
										<td>
											<?php if($admin['sub_category_delete']==1){?>
												<input type="checkbox" name="delete_category" id="delete_category" class="deleteSubCategory" business_admin_id="<?=$admin['business_admin_id']?>" delete_sub_category="0" checked>
											<?php }else{?>
												<input type="checkbox" class="deleteSubCategory" name="delete_category" id="delete_category" business_admin_id="<?=$admin['business_admin_id']?>" delete_sub_category="1">
											<?php }?>	
										</td>
										<td>
											<?php if($admin['service_create']==1){?>
												<input type="checkbox"  business_admin_id="<?=$admin['business_admin_id']?>" class="createService" service_create="0" checked>
											<?php }else{?>
												<input type="checkbox"  class="createService" business_admin_id="<?=$admin['business_admin_id']?>" service_create="1">
											<?php }?>
										</td>
										<td>
											<?php if($admin['service_edit']==1){?>
												<input type="checkbox" name="" class="editService" id="" business_admin_id="<?=$admin['business_admin_id']?>" service_edit="0" checked>
											<?php }else{?>
												<input type="checkbox" name=""  class="editService" id="" service_edit="1" business_admin_id="<?=$admin['business_admin_id']?>">
											<?php }?>
										</td>
										<td>
											<?php if($admin['service_delete']==1){?>
												<input type="checkbox" name="" id="" class="deleteService" business_admin_id="<?=$admin['business_admin_id']?>" service_delete="0" checked>
											<?php }else{?>
												<input type="checkbox" class="deleteService" name="" id="" business_admin_id="<?=$admin['business_admin_id']?>" service_delete="1">
											<?php }?>	
										</td>
										<td>
											<?php if($admin['product_create']==1){?>
												<input type="checkbox"  business_admin_id="<?=$admin['business_admin_id']?>" class="createProduct" product_create="0" checked>
											<?php }else{?>
												<input type="checkbox"  class="createProduct" business_admin_id="<?=$admin['business_admin_id']?>" product_create="1">
											<?php }?>
										</td>
										<td>
											<?php if($admin['product_edit']==1){?>
												<input type="checkbox" name="" class="editProduct" id="" business_admin_id="<?=$admin['business_admin_id']?>" product_edit="0" checked>
											<?php }else{?>
												<input type="checkbox" name=""  class="editProduct" id="" product_edit="1" business_admin_id="<?=$admin['business_admin_id']?>">
											<?php }?>
										</td>
										<td>
											<?php if($admin['product_delete']==1){?>
												<input type="checkbox" name="" id="" class="deleteProduct" business_admin_id="<?=$admin['business_admin_id']?>" product_delete="0" checked>
											<?php }else{?>
												<input type="checkbox" class="deleteProduct" name="" id="" business_admin_id="<?=$admin['business_admin_id']?>" product_delete="1">
											<?php }?>	
										</td>
										<td>
											<?php if($admin['raw_material_create']==1){?>
												<input type="checkbox"  business_admin_id="<?=$admin['business_admin_id']?>" class="createRawMaterial" raw_material_create="0" checked>
											<?php }else{?>
												<input type="checkbox"  class="createRawMaterial" business_admin_id="<?=$admin['business_admin_id']?>" raw_material_create="1">
											<?php }?>
										</td>
										<td>
											<?php if($admin['raw_material_edit']==1){?>
												<input type="checkbox" name="" class="editRawMaterial" id="" business_admin_id="<?=$admin['business_admin_id']?>" raw_material_edit="0" checked>
											<?php }else{?>
												<input type="checkbox" name=""  class="editRawMaterial" id="" raw_material_edit="1" business_admin_id="<?=$admin['business_admin_id']?>">
											<?php }?>
										</td>
										<td>
											<?php if($admin['raw_material_delete']==1){?>
												<input type="checkbox" name="" id="" class="deleteRawMaterial" business_admin_id="<?=$admin['business_admin_id']?>" raw_material_delete="0" checked>
											<?php }else{?>
												<input type="checkbox" class="deleteRawMaterial" name="" id="" business_admin_id="<?=$admin['business_admin_id']?>" raw_material_delete="1">
											<?php }?>	
										</td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
<?php
	$this->load->view('master_admin/ma_footer_view');
?>
<script type="text/javascript">
	$(document).ready(function(){
	
		$(document).ajaxStart(function() {
      $("#load_screen").show();
    });

    $(document).ajaxStop(function() {
      $("#load_screen").hide();
    });

    $(".datatables-basic").DataTable({
			responsive: true
		});		

		//Craete Category
		$(document).on('click','.createCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        category_create : $(this).attr('create_category'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/CreateCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Edit Category
		$(document).on('click','.editCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        category_edit : $(this).attr('edit_category'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/EditCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Delete Category
		$(document).on('click','.deleteCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        category_delete : $(this).attr('delete_category'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/DeleteCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Craete Sub_Category
		$(document).on('click','.createSubCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        sub_category_create : $(this).attr('sub_category_create'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/CreateSUbCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Edit Sub_Category
		$(document).on('click','.editSubCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        sub_category_edit : $(this).attr('edit_sub_category'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/EditSubCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Delete Sub_Category
		$(document).on('click','.deleteSubCategory',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        sub_category_delete : $(this).attr('delete_sub_category'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/DeleteSubCategoryPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });
		
		//Craete Service
		$(document).on('click','.createService',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_create : $(this).attr('service_create'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/CreateServicePermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Edit Service
		$(document).on('click','.editService',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_edit : $(this).attr('service_edit'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/EditServicePermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Delete Service
		$(document).on('click','.deleteService',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        service_delete : $(this).attr('service_delete'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/DeleteServicePermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Craete Product
		$(document).on('click','.createProduct',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        product_create : $(this).attr('product_create'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/CreateProductPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Edit Product
		$(document).on('click','.editProduct',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        product_edit : $(this).attr('product_edit'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/EditProductPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Delete Product
		$(document).on('click','.deleteProduct',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        product_delete : $(this).attr('product_delete'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/DeleteProductPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Craete Raw MAterial
		$(document).on('click','.createRawMaterial',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        raw_material_create : $(this).attr('raw_material_create'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/CreateRawMaterialPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Edit Raw MAterial
		$(document).on('click','.editRawMaterial',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        raw_material_edit : $(this).attr('raw_material_edit'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/EditRawMaterialPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//Delete Raw MAterial
		$(document).on('click','.deleteRawMaterial',function(event) {
      event.preventDefault();
      this.blur(); // Manually remove focus from clicked link.
      var parameters = {
        raw_material_delete : $(this).attr('raw_material_delete'),
				business_admin_id: $(this).attr('business_admin_id')
      };
      $.getJSON("<?=base_url()?>index.php/MasterAdmin/DeleteRawMaterialPermission/", parameters)
      .done(function(data, textStatus, jqXHR) { 
				if(data.success=='true'){
					alert(data.message);
					window.location.reload();
				}
    	})
    	.fail(function(jqXHR, textStatus, errorThrown) {
        console.log(errorThrown.toString());
   		});
    });

		//end master admin
  });
</script>
