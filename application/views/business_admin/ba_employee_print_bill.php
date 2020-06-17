<?php

	// $this->load->view('universal/header_view');
	tcpdf1();
	

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	$pdf->AddPage();
	ob_start();
			// we can have any view part here like HTML, PHP etc
			
	?>
	<html>
	<body>
	<!-- <main  style="width: 100%;" > -->
			<div style="text-align:center">
				<label style="font-size:larger;font-weight:bolder"><?=$business_outlet_details['business_outlet_bill_header_msg']?></label><br>
				<label><?=$business_outlet_details['business_outlet_address']?></label>
			</div>
			<div>
				<table class="table table-striped">
					<tr>
						<th><label>Employee Name : <?= $salary[0]['employee_name'] ?></label></th>
						<th><label style="text-align:right;margin:50px">Month : <?php $date=date_create("01-".$salary[0]['month']); echo date_format($date,'M-Y')?></label></th>
					</tr>
					<tr>
						<th><label>Employee Name : <?= $salary[0]['expert_id'] ?></label></th>
						<th><label style="text-align:right;margin:50px">Working Days : <?=$salary[0]['working_days']?></label></th>
					</tr>
					<tr>
						<th><label>DOJ : <?php $doj=date_create($employee['employee_date_of_joining']); echo date_format($doj,'d-M-Y') ?></label></th>
						<th><label style="text-align:right;margin:50px">LWP : <?=$salary[0]['leaves']/($salary[0]['salary']/30)?></label></th>
					</tr>
					
				</table>
			</div>
		
			<table> 
				<tr>
					<th>	
						<table style="border:solid 1px;padding:1px">
							<tr style="color:blue">
								<th style="border:solid 1px;text-align:center">S.No</th><th style="border:solid 1px;text-align:center">&nbsp;Description</th><th style="border:solid 1px;text-align:center">Amount(Rs)</th>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">A</td><td style="border:solid 1px">&nbsp;Basic</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['basic_salary']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">B</td><td style="border:solid 1px">&nbsp;PF</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['pf']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">C</td><td style="border:solid 1px">&nbsp;Gratuity</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['gratuity']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">D</td><td style="border:solid 1px">&nbsp;Others</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['others']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">E</td><td style="border:solid 1px">&nbsp;Commission</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['commission']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">F</td><td style="border:solid 1px">&nbsp;OverTime</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['overtime']?></td>
							</tr>
							<tr>
								 <th style="border:solid 1px;color:blue;" colspan="2">&nbsp;<label>Total Earning(Rs) </label></th>
								 <th style="border:solid 1px;text-align:center">&nbsp;<label><?=$salary[0]['basic_salary']+$salary[0]['pf']+$salary[0]['gratuity']+$salary[0]['others']+$salary[0]['commission']+$salary[0]['overtime']?></label></th>
							</tr>
						</table>
						
					</th>
					<th style="width:150px"></th>
					<th>
						<table style="padding:1px">
							<tr style="border:solid 1px;text-align:center;color:blue">
								<th style="border:solid 1px">S.No</th><th style="border:solid 1px;width:fit-content">Description</th><th style="border:solid 1px">Amount(Rs)</th>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">A</td><td style="border:solid 1px">&nbsp;Income Tax</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['income_tax']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">B</td><td style="border:solid 1px">&nbsp;Professional Tax</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['professional_tax']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">C</td><td style="border:solid 1px">&nbsp;Advance</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['advance']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">D</td><td style="border:solid 1px">&nbsp;HalfDay</td><td style="border:solid 1px;text-align:center"> <?=$salary[0]['halfday']?></td>
							</tr>
							<tr>
								<td style="border:solid 1px;text-align:center">E</td>
								<td style="border:solid 1px">&nbsp;Leave Without Pay</td>
								<td style="border:solid 1px;text-align:center"> <?=$salary[0]['leaves']?></td>
							</tr>
							<?php $ded=0; ?>
							<tr>
								<td style="border:solid 1px;color:blue;width:fit-content">&nbsp;<label>Total Deductions(Rs)</label></td>
								<td></td>
								<td style="border:solid 1px;text-align:center"><label><?=$salary[0]['professional_tax']+$salary[0]['income_tax']+$salary[0]['advance']+$salary[0]['halfday']+$salary[0]['leaves']?></label></td>
							</tr>
						</table>
					</th>	
				</tr>	
			</table>
			<br>
			<br>
			<table>
				<tr>
					<th>
						<table style="border:solid 1px;text-align:center">
							<tr>
								<th style="border:solid 1px;color:blue"><label>Net Pay</label></th><td><label>Rs <?=$salary[0]['netpayout']?></label></td>
								
							</tr>
							
						</table>
					</th>
					<th></th>
					<th></th>
				</tr>		
			</table>	
		</div>
			
			<label>Payroll Team,</label><br>
			<label><?=$business_outlet_details['business_outlet_bill_header_msg']?></label>
		
	<!-- </main> -->
</body>
	</html>
<?php
		$content = ob_get_contents();
		ob_end_clean();
		$pdf->writeHTML($content, true, false, true, false, '');
		$pdf->Output('salary.pdf', 'I');
?>
