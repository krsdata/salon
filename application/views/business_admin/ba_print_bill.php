<?php
	tcpdf();

	//$variable = $logo;
	define('SALON_LOGO_IMG',base_url('public/images/').$logo);
	// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	    
	//Page header
	public function Header() {		
		
        //echo SALON_LOGO_IMG;die;
		$image_file = SALON_LOGO_IMG;//K_PATH_IMAGES.'logo_example.jpg';
		$ext = pathinfo(SALON_LOGO_IMG, PATHINFO_EXTENSION);
        $this->Image($image_file, 24, 10, 25, 10, strtoupper($ext), '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $headerdata['line_color']));
			$this->SetY((32.835 / $this->k) + max($imgy, $this->y));
			if ($this->rtl) {
				$this->SetX($this->original_rMargin);
			} else {
				$this->SetX($this->original_lMargin);
			}
			$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
	}
}
	

	$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetTitle($shop_details['business_outlet_name']);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO,10, $shop_details['business_outlet_bill_header_msg'], PDF_HEADER_STRING);
	$pdf->SetHeaderData(PDF_HEADER_LOGO,18, '', '');

	$pdf->SetTitle($shop_details['business_outlet_name']);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont('helvetica');
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// $pdf->SetMargins(PDF_MARGIN_LEFT-10, PDF_MARGIN_TOP-20, PDF_MARGIN_RIGHT-10);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetMargins(PDF_MARGIN_LEFT-4, PDF_MARGIN_TOP-4, PDF_MARGIN_RIGHT);
	if(count($cart) <= 4)
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-40);
	else
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetFont('helvetica', '', 6.5);
	$pdf->setFontSubsetting(false);
	$pdf->AddPage('P','A7');

	ob_start();
			// we can have any view part here like HTML, PHP etc
			
	?>
	<main class="content" style="width: 100%;" >
		<div class="container-fluid">
			<div class="col-md-12">
				<?php

					$pdf->SetTextColor(0, 63, 127);
					$shop=$shop_details['business_outlet_address'];
					$bill_header=$shop_details['business_outlet_bill_header_msg'];
					$pdf->MultiCell(0,	0,$bill_header, 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T');
					$pdf->Ln(3);
				// set color for text
					$pdf->SetTextColor(0, 63, 127);
					$shop=$shop_details['business_outlet_address'];
					$cust_name=$cart[0]['customer_name'].' '.$cart[0]['date'];
					$pdf->MultiCell(45,	0,$shop, 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T');
					$pdf->Ln(6);
					$pdf->MultiCell(140,0,$cust_name, 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T');
					// $bill_no=180;
					$html ='<br>';
					$pdf->writeHTML($html, true, false, true, false, '');
					// create columns content
					$first_column = '<b>Bill No: </b>'.$cart[0]['txn_unique_serial_id'];
					$second_column = '<b>GST: </b>'.$shop_details['business_outlet_gst_in'];
					// get current vertical position
					$y = $pdf->getY();
					
					// write the first column
					$pdf->writeHTMLCell(40, '', '', $y+2, $first_column, 0, 0, 0, true, 'J', false);
					$pdf->writeHTMLCell(40, '', '', $y+5, $second_column, 0, 0, 0, true, 'J', true);
					
				?>
			</div>
			
			<div class="col-md-12">
				<?php
					if(!isset($cart) || empty($cart)){
				?>					
				<div class="alert alert-secondary alert-outline alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<div class="alert-icon">
						<i class="far fa-fw fa-bell"></i>
					</div>
					<div class="alert-message">
						<strong>Hello there!</strong> Cart is empty!
					</div>
				</div>					
				<?php
					}
					else{						
				?>
				<table class="table bill_print">
					<thead>
						<hr/>
						<tr style="margin-top:5px;">
							<th style="width:40%;"><strong>Service</strong></th>
							<th  style="width:20%;text-align:center;"><strong>MRP</strong></th>
							<th  style="width:15%;text-align:center;"><strong>Qty</strong></th>
							<!-- <th style="width:10%;"><strong>GST</strong></th> -->
							<!-- <th style="width:15%;"><strong>Discount</strong></th>							 -->
							<th  style="width:25%;text-align:center;"><strong>Total</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$total_service_value=0;
							$total_base_price=0;
							$total_gst=0;
							$total_discount=0;
							$new_gst=0;
							$discount=0;	
							$total_value=0;
							$final_mrp=0;
							$gross_price=0;
							foreach ($cart as $item){
								if($item['txn_service_discount_percentage']>0){
									$discount=$item['txn_service_discount_percentage'];
									$price= $item['service_price_inr'];
									$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
									$mrp=$price+$gst;
									$total_value=$mrp*$item['txn_service_quantity'];
									$final_mrp= ($mrp+$item['txn_add_on_amount'])*$item['txn_service_quantity'];
									$gross_price+=$final_mrp;
									// if($item['txn_add_on_amount'] >0){
									// 	$total_value+=$item['txn_add_on_amount'];

									// }
									$discount=$total_value*$discount/100;																
									$total_value-=$discount;
									$total_service_value+=$total_value;
									$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage']/100)));
									
								}else{
									$discount=$item['txn_service_discount_absolute'];
									$price= $item['service_price_inr'];
									$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
									$mrp=$price+$gst;
									$total_value=$mrp*$item['txn_service_quantity'];
									$final_mrp= ($mrp+$item['txn_add_on_amount'])*$item['txn_service_quantity'];
									$gross_price+=$final_mrp;
									if($discount >0){
										$total_value-=$discount;
									}
									if($item['txn_add_on_amount'] >0){
										$total_value+=$item['txn_add_on_amount'];
									}
									$total_service_value+=$total_value;
									$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage'])/100));
								
								}
								$total_discount+=$discount;
						?>
						<tr>
							<td style="width:40%;"><?=$item['service_name']?></td>
							<td  style="width:20%;text-align:center;"><?=round($final_mrp)?></td>
							<td  style="width:15%;text-align:center;"><?=$item['txn_service_quantity']?></td>
							<!-- <td style="width:10%;text-align:center;"><?=abs(round($new_gst))?></td> -->
							<!-- <td style="width:15%;"><?=round($total_discount)?></td> -->
							<td  style="width:25%;text-align:center;">
							<?=round($total_value)?>							
							</td>	
						</tr>	
						<?php		
							}
							// endforeach;
						?>
					</tbody>
				</table>
				<?php
					}
				?>
			</div>
			<div class="col-md-12">
				<?php
					if(isset($cart) && !empty($cart)):
				?>
				<div class="mb-3">
					<table class="table table-hover bill_print">
						<tbody>
							<tr>
								<!-- <td style="width:70%;">Total Bill</td> -->
								<td style="width:66%;">Total Bill</td>
								<td style="width:30%;"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
								<?=round($gross_price)?>
								</td>
								
							</tr>
							<tr>
								<td>Package Applied</td>
								<td><?php 
								foreach($cart as $package){
									if(isset($package['salon_package_name'])){
										echo $package['salon_package_name'];
									}
								}?></td>
							</tr>
							<tr>
								<td>Discount</td>
								<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
									<?=round($total_discount)?>
								</td>
							</tr>
							<tr>
								<td>GST</td>
								<td><i class="fas fa-fw fa-rupee-sign "></i><?=round($gst)?></td>
							
							</tr>
							<tr>
								<td><strong>Final Payable Amount</strong></td>
								<td><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i>
								<strong><?=round($total_service_value)?></strong>
								</td>
							</tr>
							<tr><td></td><td></td></tr>
							<?php if(!empty($payment['full_payment_info']) || !empty($payment['split_payment_info'])){ 
								if(!empty($payment['full_payment_info'])) { ?>
							<tr>
								<td>Payment Type</td>
								<td><?=$payment['full_payment_info']['payment_type']?></td>
							</tr>
							<tr>
								<td>Amount Received</td>
								<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i><?=$payment['full_payment_info']['total_amount_received']?></td>
							</tr>
							<tr>
								<td>Balance to be paid back</td>
								<td><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['full_payment_info']['balance_to_be_paid_back']?></td>
							</tr>
							<tr>
								<td>Pending Amount</td>
								<td><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i><?=$payment['full_payment_info']['pending_amount']?></td>
							</tr>
							<?php 
								}
								else{
							?>
							<tr>
								<td>Payment Type</td>
								<td>Split Payment</td>
							</tr>
							<tr>
								<td>Amount Received</td>
								<td><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['total_amount_received']?></td>
							</tr>
							<tr>
								<td>Balance to be paid back</td>
								<td><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['balance_to_be_paid_back']?></td>
							</tr>
							<tr>
								<td>Pending Amount</td>
								<td><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['total_pending_amount']?></td>
							</tr>	
							<?php
								}
								}
							?>
						</tbody>
					</table>
				</div>
				<?php
					endif;
				?>
			</div>				
			</div>
		</div>
	</main>
<?php
		$content = ob_get_contents();
		$footer=$shop_details['business_outlet_bill_footer_msg'];
		$header=$shop_details['business_outlet_bill_header_msg'];

		ob_end_clean();
		// $pdf->writeHTML($header, true, false, true, false, 'T');
		$pdf->writeHTML($content, true, false, true, false, '');
		$pdf->writeHTML($footer, true, false, true, false, 'C');
		//Add a custom size  
		$pdf->Output('bill'.time().'.pdf', 'I');
?>
