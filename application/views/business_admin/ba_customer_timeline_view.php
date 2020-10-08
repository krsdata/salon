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
              <div class="card-header" style="background:lightgray">

                <div class="input-group">
                  <a href="<?= base_url() ?>BusinessAdmin/Engagement">
                    <h4><i class="fas fa-arrow-left">&emsp;Customer Timeline Setup</i></h4>
                  </a>

                </div>
              </div>
              <form action="" id="timelinedata">
                <?php if($timeline['success'] != 'false'){?>
                <div class="row">
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$new?></h3>
                          <h6 class="card-text" style="text-align:center">New Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <h6 class="card-text" style="text-align:center">First Visit</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$repeat?></h3>
                          <h6 class="card-text" style="text-align:center">Repeating Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <!-- <h6 class="card-text" style="text-align:center">First Visit</h6> -->
                          <div classs="row" style="text-align:center">
                            From&emsp;<input type="text" name="r1" value="<?=$timeline['res_arr']['r1']?>"
                              style="width:2rem">&emsp;to&emsp;<input type="text" name="r2"
                              value="<?=$timeline['res_arr']['r2']?>" style="width:2rem">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$regular?></h3>
                          <h6 class="card-text" style="text-align:center">Regular Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Above&emsp;<input type="text" name="reg_cust"
                              value="<?=$timeline['res_arr']['regular_cust']?>" style="width:4rem">
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=($allcust - ($new+$regular+$repeat))?></h3>
                          <h6 class="card-text" style="text-align:center">Never Visited</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                          0
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:40px">
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$no_risk?></h3>
                          <h6 class="card-text" style="text-align:center">No Risk</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Last Visit <= <input type="text" name="no_risk"
                              value="<?=$timeline['res_arr']['no_risk']?>" style="width:2rem"> days back
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$dormant?></h3>
                          <h6 class="card-text" style="text-align:center">Dormant</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            <!-- Last Visited <input type="text" name="dormant" value="<?=$timeline['res_arr']['dormant']?>" style="width:2rem"> days back -->
															From&emsp;<input type="text" name="dormant_r1" value="31" style="width:2rem">&emsp;to&emsp;<input
                              type="text" value="60" name="dormant_r2" style="width:2rem">
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;border:none">
                        <div class="card-body">
                        	  <h3 class="card-title" style="text-align:center"><?=$risk?></h3>
                          <h6 class="card-text" style="text-align:center">At Risk</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            <!-- Last Visited <input type="text" name="risk_cust" value="<?=$timeline['res_arr']['at_risk_cust']?>" style="width:2rem"> days back -->
														From&emsp;<input type="text" name="at_risk_r1" value="61" style="width:2rem">&emsp;to&emsp;<input
                              type="text" value="90" name="at_risk_r2" style="width:2rem">
													</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 13rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$lost?></h3>
                          <h6 class="card-text" style="text-align:center">Lost/Churn</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Last Visited <input type="text" name="lost_cust"
                              value="<?=$timeline['res_arr']['lost_customer']?>" style="width:2rem"> days back
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:40px;margin-bottom:40px;text-align:center">
                  <div class="col-md-12">
                    <input type="submit" style="width:200px" class="btn btn-success btn-lg" value="submit">
                    <input type="input" value="<?=$timeline['res_arr']['id']?>" name="id" hidden>
                  </div>
                </div>
                <?php }else{?>
                <div class="row">
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$new?></h3>
                          <h6 class="card-text" style="text-align:center">New Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <h6 class="card-text" style="text-align:center">First Visit</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$repeat?></h3>
                          <h6 class="card-text" style="text-align:center">Repeating Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <!-- <h6 class="card-text" style="text-align:center">First Visit</h6> -->
                          <div classs="row" style="text-align:center">
                            From&emsp;<input type="text" name="r1" value="2" style="width:4rem">&emsp;to&emsp;<input
                              type="text" value="5" name="r2" style="width:4rem">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$regular?></h3>
                          <h6 class="card-text" style="text-align:center">Regular Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Above&emsp;<input type="text" name="reg_cust" value="5" style="width:4rem">
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$regular?></h3>
                          <h6 class="card-text" style="text-align:center">Regular Customer</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            0
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top:40px">
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$risk?></h3>
                          <h6 class="card-text" style="text-align:center">At Risk</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Last Visited <input type="text" name="risk_cust" value="30" style="width:4rem"> days back
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$risk?></h3>
                          <h6 class="card-text" style="text-align:center">At Risk</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
														<!-- Last Visited <input type="text" name="no_risk" value="30" style="width:4rem"> days back -->
														From&emsp;<input type="text" name="no_risk_r1" value="31" style="width:2rem">&emsp;to&emsp;<input
                              type="text" value="60" name="no_risk_r2" style="width:2rem">
                          </div>
                        </div>
                      </div>
                    </div>
									</div>
									<div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$risk?></h3>
                          <h6 class="card-text" style="text-align:center">At Risk</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
														<!-- Last Visited <input type="text" name="dormant" value="31" style="width:4rem"> days back -->
														From&emsp;<input type="text" name="dormant_r1" value="61" style="width:2rem">&emsp;to&emsp;<input
                              type="text" value="90" name="dormant_r2" style="width:2rem">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card-body">
                      <div class="card" style="width: 18rem;border:none">
                        <div class="card-body">
                          <h3 class="card-title" style="text-align:center"><?=$lost?></h3>
                          <h6 class="card-text" style="text-align:center">Lost Customers</h6>
                          <hr style="height:2px;border-width:0;color:gray;background-color:red">
                          <div classs="row" style="text-align:center">
                            Last Visited <input type="text" name="lost_cust" value="90" style="width:4rem"> days back
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="row" style="margin-top:40px;margin-bottom:40px;text-align:center">
                  <div class="col-md-12">
                    <input type="submit" style="width:200px" class="btn btn-success btn-lg" value="submit">
                    <input type="input" value="0" name="id" hidden>
                  </div>

                </div>
                <?php }?>
              </form>
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
    $("#timelinedata").validate({
      errorElement: "div",

      submitHandler: function(form) {
        var formData = $("#timelinedata").serialize();
        $.ajax({
          url: "<?= base_url() ?>BusinessAdmin/InsertTimeline",
          data: formData,
          type: "POST",
          // crossDomain: true,
          cache: false,
          // dataType : "json",
          success: function(data) {
            if (data.success == 'true') {
              // $("#ModalAddPackage").modal('hide');
              var message2 = data.message;
              var title2 = "";
              var type = "success";
              toastr[type](message2, title2, {
                positionClass: "toast-top-right",
                progressBar: "toastr-progress-bar",
                newestOnTop: "toastr-newest-on-top",
                rtl: $("body").attr("dir") === "rtl" || $("html").attr("dir") === "rtl",
                timeOut: 500
              });
              setTimeout(function() {
                location.reload(1);
              }, 500);
            } else if (data.success == 'false') {
              if ($('.feedback').hasClass('alert-success')) {
                $('.feedback').removeClass('alert-success').addClass('alert-danger');
              } else {
                $('.feedback').addClass('alert-danger');
              }
              $('.alert-message').html("").html(data.message);
            }
          },
          error: function(data) {
            $('.feedback').addClass('alert-danger');
            $('.alert-message').html("").html(data.message);
          }
        });
      },
    });
    </script>
