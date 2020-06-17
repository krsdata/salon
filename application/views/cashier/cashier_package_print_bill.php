<?php

	// $this->load->view('universal/header_view');
	tcpdf2();
	
	// Extend the TCPDF class to create custom Header and Footer
// class MYPDF extends TCPDF {

// 	//Page header
// 	public function Header() {
// 			// Set font
// 			$this->SetFont('helvetica', 'B', 14);
// 			// Title
// 			$this->Cell(0, 15,'Invoice', 0, false, 'C', 0, '', 0, false, 'M', 'M');
// 	}

// 	// Page footer
// 	public function Footer() {
// 			// Position at 15 mm from bottom
// 			$this->SetY(-15);
// 			// Set font
// 			$this->SetFont('helvetica', 'I', 8);
// 			// Page number
// 			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
// 	}
// }
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetTitle($shop_details['business_outlet_name']);
	$pdf->SetHeaderData(PDF_HEADER_LOGO,10, $shop_details['business_outlet_bill_header_msg'], PDF_HEADER_STRING);
	$pdf->SetTitle($shop_details['business_outlet_name']);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont('helvetica');
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	// $pdf->SetMargins(PDF_MARGIN_LEFT-10, PDF_MARGIN_TOP-20, PDF_MARGIN_RIGHT-10);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetFont('helvetica', '', 9);
	$pdf->setFontSubsetting(false);
	$pdf->AddPage();
	ob_start();
			// we can have any view part here like HTML, PHP etc
			
	?>
	<main class="content" style="width: 100%;" >
		<div class="container-fluid">
			<div class="col-md-12">
				<?php
				// set color for text
					$pdf->SetTextColor(0, 63, 127);
					$shop=$shop_details['business_outlet_address'];
					$cust=$individual_customer['customer_name'].' '.date('d-M-Y h:i A');
					$pdf->MultiCell(25,	0,$cust, 0, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T');
					// $pdf->Cell(30, 25,'Date : '.date('d-M-Y h:i A') , 0, 0, 'L', 0, '', 0, false, 'T', 'C');
					$pdf->MultiCell(40,0,$shop, 0, 'R', 0, 0, '', '', true, 0, false, true, 0, 'T');
					// $bill_no=180;
					$html ='<br>';
					$pdf->writeHTML($html, true, false, true, false, '');
					// create columns content
					// $first_column = '<b>Bill No: </b>'.$bill_no;
					$second_column = '<b>GST: </b>'.$shop_details['business_outlet_gst_in'];
					// get current vertical position
					$y = $pdf->getY();
					
					// write the first column
					// $pdf->writeHTMLCell(70, '', '', $y+10, $first_column, 0, 0, 0, true, 'J', false);
					$pdf->writeHTMLCell(70, '', '', $y+14, $second_column, 0, 0, 0, true, 'J', true);
					
				?>
			</div>
			
			<div class="col-md-12">
				<?php
					if(!isset($package_cart) || empty($package_cart)){
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
						<tr style="margin-top:10px;">
							<th style="width:40%;"><strong>Package</strong></th>
							<th  style="width:35%;text-align:center;"><strong>MRP</strong></th>
							<!-- <th  style="width:15%;text-align:center;"><strong>GST</strong></th> -->
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
								
									$discount=$package_cart['package_discount_absolute'];
									$price= $package_cart['package_price_inr'];
									$gst=$package_cart['package_gst'];
									$mrp=$package_cart['package_final_value'];
									$total_value=$mrp;
									if($discount > 0){
										$total_value-=$discount;
									}
									
									$total_service_value+=$total_value;
									
									$package_name=$package_cart['package_name'];
									$total_discount=$discount;
								
						?>
						<tr>
							<td style="width:40%;"><?=$package_name?></td>
							<td  style="width:35%;text-align:center;"><?=round($price+$gst)?></td>
							<!-- <td  style="width:15%;text-align:center;"><?=$gst?></td> -->
							<!-- <td style="width:10%;text-align:center;"><?=abs(round($new_gst))?></td> -->
							<!-- <td style="width:15%;"><?=round($total_discount)?></td> -->
							<td  style="width:25%;text-align:center;">
							<?=round($total_value)?>							
							</td>	
						</tr>	
						
					</tbody>
				</table>
				<?php
					}
				?>
			</div>
			<div class="col-md-12">
				<?php
					if(isset($package_cart) && !empty($package_cart)):
				?>
				<div class="mb-3">
					<table class="table table-hover bill_print">
						<tbody>
							<tr>
								<td style="width:70%;">Total Bill</td>
								<td style="width:30%;"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i>
								<?=round($total_service_value)?>
								</td>
								
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
		$pdf->Output('bill.pdf', 'I');
?>
