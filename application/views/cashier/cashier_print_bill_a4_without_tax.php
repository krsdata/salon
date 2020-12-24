<?php

	tcpdf();

	//$variable = $logo;
	define('SALON_LOGO_IMG',base_url('public/images/').$logo);
	// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	    
	//Page header
	public function Header() {		
		/*
        //echo SALON_LOGO_IMG;die;
		$image_file = SALON_LOGO_IMG;//K_PATH_IMAGES.'logo_example.jpg';
		$ext = pathinfo(SALON_LOGO_IMG, PATHINFO_EXTENSION);
        $this->Image($image_file, 24, 10, 25, 10, strtoupper($ext), '', 'T', false, 300, '', false, false, 0, false, false, false);
        
		//$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $headerdata['line_color']));
		//$this->SetY((32.835 / $this->k) + max($imgy, $this->y));
			if ($this->rtl) {
				//$this->SetX($this->original_rMargin);
			} else {
				//$this->SetX($this->original_lMargin);
			}
			//$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
	    */
	}
}
	
//echo PDF_UNIT = mm;
//echo PDF_PAGE_FORMAT = array;
//echo PDF_CREATOR = TCPDF;
//echo PDF_FONT_NAME_MAIN = helvetica;
//echo PDF_HEADER_LOGO = '';
//echo PDF_FONT_NAME_DATA=helvetica;
//echo PDF_MARGIN_HEADER = 5;
//echo PDF_MARGIN_LEFT = 15;echo '<br/>';
//echo PDF_MARGIN_TOP = 2;echo '<br/>';
//echo PDF_MARGIN_RIGHT = 2;echo '<br/>';
//echo PDF_FONT_SIZE_MAIN= 10;
	$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetTitle($shop_details['business_outlet_name']);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO,10, $shop_details['business_outlet_bill_header_msg'], PDF_HEADER_STRING);
	//$pdf->SetHeaderData(PDF_HEADER_LOGO,18, '', '');

	$pdf->SetTitle($shop_details['business_outlet_name']);
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont('helvetica');
	//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetMargins(PDF_MARGIN_LEFT-10, PDF_MARGIN_TOP-18, PDF_MARGIN_RIGHT-10);
	//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	
	$pdf->SetAutoPageBreak(TRUE,PDF_MARGIN_BOTTOM);
	
	$pdf->SetFont('helvetica', '', 6.5);
	$pdf->setFontSubsetting(false);
	$pdf->AddPage('P','A4');
	//
	ob_start();
			// we can have any view part here like HTML, PHP etc
			
	?>
<!DOCTYPE html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <title>Invoice</title>
 <style>
	
body{
    font-size: 9px;
	line-height:12px;
	
}
table{
    border-collapse: collapse;
}

table.bordered , table.bordered td, table.bordered th {
    border: 0.5px solid black;
}

.total tr td {border: 0.5px solid black;}
.total tr th {border: 0.5px solid black;}
.ex1 { 
  width:100%;
  padding-right: 15px;
  padding-top: 15px;
  padding-bottom: 15px;
  
}
.tr-border td{border:0.5px solid black;}
</style>
</head>
<body onload="window.print();">
<div class="ex1" >
	<center><img src="<?php echo SALON_LOGO_IMG; ?>" style="width:50px;height:50px;text-align:center;"></center>
	<div style="font-size:10px">
	  <table  border="0"  width="50%" class="invoice" ><tr><td>
		<b><?php echo $shop_details['business_outlet_name']; ?> <br>
		<?php echo $shop_details['business_outlet_address']; ?> <?php echo $shop_details['business_outlet_mobile']; ?></b>
		</td></tr>
	  </table>
	</div>

	<div style="width:100px;font-size:10px;">
	  <table  border="0"  width="50%" class="invoice" ><tr>
	     <td>
			<b>Billed To</b><br>
			<b><?php echo $individual_customer['customer_title']." ".$individual_customer['customer_name']." ".$individual_customer['customer_mobile']." ";?></b>
		 </td>
		</tr>
		</table>
	</div>

<div style="width:100%;font-size:10px;">
<table  border="0"  width="100%" class="invoice"  >
	<tr>
	 <td>
	   <b>Invoice No   : <?php echo $bill_no; ?></b><br>
	   <b>Invoice Date : <?php echo date("d-m-Y"); ?></b><br>
	   <b>Invoice Type : Retail</b>
	   
	 </td>
	</tr>
</table>
</div>
<?php
if(!isset($cart) || empty($cart)){
?>					
<div class="alert alert-secondary alert-outline alert-dismissible" role="alert">
	<!--<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>-->
	<div class="alert-icon">
		<i class="far fa-fw fa-bell"></i>
	</div>
	<div class="alert-message">
		<strong>Hello there!</strong> Cart is empty!
	</div>
</div>					
<?php
}else{						
?>
<div>
<table border=1 width="100%" class="border-table">
	
<tr class="tr-border">
<th style="font-size:10px;width:50%" bgcolor="#e0e0d2">Description</th>
<th style="font-size:10px;width:10%" bgcolor="#e0e0d2">MRP</th>
<th style="font-size:10px;width:10%" bgcolor="#e0e0d2">Qty</th>
<th style="font-size:10px;width:10%" bgcolor="#e0e0d2">Amt</th>
<th style="font-size:10px;width:20%" bgcolor="#e0e0d2">Net Amt</th>
</tr>

<?php

	$total_service_value=0;
	$total_base_price=0;
	$total_gst=0;
	$total_discount=0;
	$new_gst=0;
	$discount=0;	
	$total_value=0;
	$gross_price=0;
	$final_mrp=0;
	$grossAmountBTax = 0;
	$totalGst = 0;
	$final_mrp_b_tax = 0;
	$memberDiscountTotal = 0;
    $memberDiscountAmt = 0;
	$final_amt_at_mrp = 0;
	$gstArray = array();
	foreach ($cart as $item){
		$balance 	    = isset($membershipDetails[$item['service_id']]['balance']) ? $membershipDetails[$item['service_id']]['balance'] : 0;
		$memberDiscount = isset($membershipDetails[$item['service_id']]['discount_percentage']) ? $membershipDetails[$item['service_id']]['discount_percentage'] : 0;
		
		if($item['service_discount_percentage']>0){
			
			$discount=$item['service_discount_percentage'];
			$price= $item['service_price_inr'];
			
			//$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
			
			$mrp=$price;
			$total_value=($mrp+$item['service_add_on_price'])*$item['service_quantity'];
			$final_mrp  = ($mrp+$item['service_add_on_price'])*$item['service_quantity'];
			
			$final_mrp_b_tax+=($price+$item['service_add_on_price'])*$item['service_quantity'];
			
			$final_amt_at_mrp+=$final_mrp;
			if($balance>0){ 
			  $memberDiscountAmt = round($total_value)*$memberDiscount/100;
			  $memberDiscountTotal += $total_value*$memberDiscount/100;
			  
			  $total_value = ($total_value-$memberDiscountTotal);	
			}
			$gross_price+=$total_value;
			
			// if($item['service_add_on_price'] >0){
			// 	$total_value+=$item['service_add_on_price'];
			// }
			
			$discount=$total_value*$discount/100;
			$discount= round($discount);
				
			$total_value-=$discount;
			
			$total_service_value+=$total_value;
			
			//$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage']/100)));
			//$totalGst+=$gst;
			/*if(!empty($item['service_gst_percentage']) && $item['service_gst_percentage']>0){
				$gstArray[] = array('gst'=>$item['service_gst_percentage'],'gst_amt'=>$gst);		
			} */
		}else{
			$discount=$item['service_discount_absolute'];
			$price= $item['service_price_inr'];
			
			//$gst=$item['service_price_inr']*$item['service_gst_percentage']/100;
			$mrp=$price;
			$total_value=($mrp+$item['service_add_on_price'])*$item['service_quantity'];
			if($balance>0){
			  
			  $memberDiscountAmt = round($total_value)*$memberDiscount/100;	
			  		  
			  $memberDiscountTotal += $total_value*$memberDiscount/100;	
			  		  
			  $total_value = ($total_value-$memberDiscountTotal);	
			}
			$final_mrp = ($mrp+$item['service_add_on_price'])*$item['service_quantity'];
			
			$final_mrp_b_tax+=($price+$item['service_add_on_price'])*$item['service_quantity'];
				
			$final_amt_at_mrp+=$final_mrp;
			$gross_price+= $total_value;
			// if($item['service_add_on_price'] >0){
			// 	$total_value+=$item['service_add_on_price'];										
			// }
			if($discount >0){
				$total_value-=$discount;
			}
			
			$total_service_value+=$total_value;
			//$gst=($total_value*($item['service_gst_percentage']/100)/(1+($item['service_gst_percentage'])/100));
			//$totalGst+=$gst;
			/*if(!empty($item['service_gst_percentage']) && $item['service_gst_percentage']>0){
				$gstArray[] = array('gst'=>$item['service_gst_percentage'],'gst_amt'=>$gst);		
			}*/
		}
		$total_discount+=$discount;
?>	
						<tr>
						<td style="font-size:10px;" bgcolor="#ccddff">
						<?=$item['service_name']?>
						</td>
						<td style="font-size:10px;" bgcolor="#ccddff"><?=round($mrp)?></td>
						<td style="font-size:10px;" bgcolor="#ccddff">
						<?=$item['service_quantity']?>
						</td>
						<td style="font-size:10px;" bgcolor="#ccddff"><?=round($mrp*$item['service_quantity'])?></td>
						<td style="font-size:10px;" bgcolor="#ccddff"></td>
						</tr>
                   <?php if($item['service_discount_percentage']>0){ ?>
						<tr>
							<td style="font-size:10px;" bgcolor="#ffe6e6">
							<b>Discount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $item['service_discount_percentage']; ?>%</b> 
							</td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6">
							 <?php echo round($discount); ?>
							</td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
						</tr>
						
				   <?php }else{ ?>
					    <tr>
							<td style="font-size:10px;" bgcolor="#ffe6e6">
							<b>Discount</b> 
							</td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6">
							 <?php echo round($discount); ?>
							</td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
						</tr>
					   
				   <?php } ?>
				       
						<?php if($balance>0){ 
						?>
						<tr>
							<td style="font-size:10px;" bgcolor="#ffe6e6">
							<b>Membership Discount [<?php echo $membershipDetails[$item['service_id']]['package_name']; ?>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $memberDiscount; ?>%</b>
							</td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"></td>
							<td style="font-size:10px;" bgcolor="#ffe6e6"><?php echo $memberDiscountAmt; ?></td>
						    <td style="font-size:10px;" bgcolor="#ffe6e6"></td>
						</tr>
						<?php 
						
						} ?>
						
						<tr>
						<td  style="font-size:10px" bgcolor="#cceeff">
						<b>Net Amt</b>
						</td>
						<td style="font-size:10px;" bgcolor="#cceeff"></td>
						<td style="font-size:10px;" bgcolor="#cceeff"></td>
						<td  style="font-size:10px" bgcolor="#cceeff"></td>
						<td  style="font-size:10px" bgcolor="#cceeff"><b><?=round($total_value)?></b></td>
						</tr>
						
						<tr >
							<td  style="font-size:10px" bgcolor="#ffffff"></td>
							<td style="font-size:10px;" bgcolor="#ffffff"></td>
							<td style="font-size:10px;" bgcolor="#ffffff"></td>
							<td  style="font-size:10px" bgcolor="#ffffff"></td>
							<td  style="font-size:10px" bgcolor="#ffffff"></td>
						</tr>

						<?php
							}
							// endforeach;
						?>




</table>

<br><br>
<table border=1 width="100%" class="total">
<tr>
<td width="70%" style="font-size:10px"><center>Final Amount at MRP</center></td>
<td width="30%" style="font-size:10px"><center><?=round($final_amt_at_mrp)?></center></td>
</tr>
<?php if($total_discount>0 || $memberDiscountAmt>0){ ?>
<tr> 
<td style="font-size:10px"><center>Total Discount</center></td>
<td  style="font-size:10px"><center><?php if($total_discount>=$memberDiscountAmt){
	   echo $total_discount+$memberDiscountAmt;
   }else{
	   echo $memberDiscountAmt+$total_discount;
   } ?>
   </center></td>
</tr>
<?php } ?>

<tr>
<td  style="font-size:10px"><center>Invoice Amount</center></td>
<td  style="font-size:10px"><center><?=round($total_service_value)?></center></td>
</tr>


<?php if(!empty($payment['full_payment_info']) || !empty($payment['split_payment_info'])){ 
  if(!empty($payment['full_payment_info'])) { ?>
	<tr>
		<td  style="font-size:10px">Tender Mode</td>
		<td  style="font-size:10px"><?=$payment['full_payment_info']['payment_type']?></td>
	</tr>
	<tr>
		<td  style="font-size:10px">Amount Received</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i><?=$payment['full_payment_info']['total_amount_received']?></td>
	</tr>
	<tr>
		<td  style="font-size:10px">Balance to be paid back</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['full_payment_info']['balance_to_be_paid_back']?></td>
	</tr>
	<tr>
		<td  style="font-size:10px">Pending Amount</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign" aria-hidden="true"></i><?=$payment['full_payment_info']['pending_amount']?></td>
	</tr>
	<?php 
		}else{
	?>
	<tr>
		<td  style="font-size:10px">Amount Received</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['total_amount_received']?></td>
	</tr>
	<tr>
		<td  style="font-size:10px">Balance to be paid back</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['balance_to_be_paid_back']?></td>
	</tr>
	<tr>
		<td  style="font-size:10px">Pending Amount</td>
		<td  style="font-size:10px"><i class="fas fa-fw fa-rupee-sign " aria-hidden="true"></i><?=$payment['split_payment_info']['total_pending_amount']?></td>
	</tr>	
	<tr>
		<td>Tender Mode</td>
		<td>
			<small>
		<?php 
			foreach($payment['split_payment_info']['multiple_payments'] as $key=>$val){
				echo $val['payment_type']." : ".$val['amount_received']." ";
			}
			?></small>
		</td>
	</tr>
	<?php
		}
	}
?>

</table>
<br>
</div>
<?php } ?>
<div>
<h3 style="text-align:right">For <?php echo $shop_details['business_outlet_name']; ?></h3>
</div>
<div style="border: 2px solid black; height:90px;">
<b>&nbsp;&nbsp;Notes</b>
<br>
<br>
<br>
<br>
</div>

</div>
</body>
</html>
<?php
		$content = ob_get_contents();
		$footer=$shop_details['business_outlet_bill_footer_msg'];
		$header=$shop_details['business_outlet_bill_header_msg'];

		ob_end_clean();
		// $pdf->writeHTML($header, true, false, true, false, 'T');
		$pdf->writeHTML($content, true, false, true, false, '');
		$pdf->writeHTML($footer, true, false, true, false, 'C');
		$pdf->Output('bill_new.pdf', 'I');
?>
