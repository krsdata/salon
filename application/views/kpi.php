<p>Dear <?php echo $admin_name?>,</p>
<p>Attached is the Daily Business Update for <?php echo date('Y-m-d');?>, updated till 10PM for <?php echo $business_outlet_name?>,
</p><p>#Location.</p>
<table style="border-collapse:collapse;width:183pt" width="244" cellspacing="0" cellpadding="0" border="1">
   <colgroup>
      <col style="width:119pt" width="158">
      <col style="width:64pt" width="86">
   </colgroup>
   <tbody>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt;width:119pt;color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)" width="158" height="18">Sales
            (Rs)<br>
         </td>
         <td style="width:64pt;color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)" width="86">Today<br></td>
      </tr>
      <tr style="height:13.8pt" height="18">
         <td style="height:13.8pt;color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)" height="18">Total Sales<br></td>
         <td style="color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)"><?php echo $total_sp_Amt;?><br></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18">Services/Products<br></td>
         <td><?php echo $service_Amt;?></td>
      </tr>
      <!-- <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18">Products<br></td>
         <td><br></td>
      </tr> -->
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18">Packages/Memberships<br></td>
         <td><?php echo $package_Amt;?></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18"><br></td>
         <td><br></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt;color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)" height="18">Collections (Rs)<br></td>
         <td style="color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)">Today<br></td>
      </tr>
      <?php
		$total_o = 0;
		$total_t = 0;
		$total_p = 0;
		$total_e = 0;
		foreach ($collection['p_mode'] as $key => $p) {
			?>
			
				<?php $collection['opening_balance_data'][$p];
				if($p == "virtual_wallet"){
					$total_o = $total_o-$collection['opening_balance_data'][$p];
				}else{
					$total_o = $total_o+$collection['opening_balance_data'][$p];
				}												
				?>
				<?php $collection['transaction_data'][$p];
				if($p == "virtual_wallet"){
					$total_t = $total_t-$collection['transaction_data'][$p];
				}else{
					$total_t = $total_t+$collection['transaction_data'][$p];
				}												
				?>	
				<?php $collection['pending_amount_data'][$p];
				if($p == "virtual_wallet"){
					$total_p = $total_p-$collection['pending_amount_data'][$p];
				}else{
					$total_p = $total_p+$collection['pending_amount_data'][$p];
				}												
				?>
				<?php $collection['expenses_data'][$p];
				if($p == "virtual_wallet"){
					$total_e = $total_e-$collection['expenses_data'][$p];
				}else{
					$total_e = $total_e+$collection['expenses_data'][$p];
				}												
				?>
				<?php ($collection['opening_balance_data'][$p]+$collection['transaction_data'][$p]+$collection['pending_amount_data'][$p]-$collection['expenses_data'][$p]);?>
			
			<?php
		}
		?>

      <tr style="height:13.8pt" height="18">
         <td style="height:13.8pt;color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)" height="18">Total Collection<br></td>
         <td style="color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)"><?php echo ($total_t+$total_p+$total_o-$total_e);?><br></td>
      </tr>      

      <?php
		$total_o = 0;
		$total_t = 0;
		$total_p = 0;
		$total_e = 0;
		foreach ($collection['p_mode'] as $key => $p) {
			?>
			<tr style="height:13.2pt" height="18">
				<td style="height:13.2pt" height="18"><?php echo ucfirst(str_replace("_", " ", $p));?><br></td>
				<?php $collection['opening_balance_data'][$p];
				if($p == "virtual_wallet"){
					$total_o = $total_o-$collection['opening_balance_data'][$p];
				}else{
					$total_o = $total_o+$collection['opening_balance_data'][$p];
				}												
				?>
				<?php $collection['transaction_data'][$p];
				if($p == "virtual_wallet"){
					$total_t = $total_t-$collection['transaction_data'][$p];
				}else{
					$total_t = $total_t+$collection['transaction_data'][$p];
				}												
				?>	
				<?php $collection['pending_amount_data'][$p];
				if($p == "virtual_wallet"){
					$total_p = $total_p-$collection['pending_amount_data'][$p];
				}else{
					$total_p = $total_p+$collection['pending_amount_data'][$p];
				}												
				?>
				<?php $collection['expenses_data'][$p];
				if($p == "virtual_wallet"){
					$total_e = $total_e-$collection['expenses_data'][$p];
				}else{
					$total_e = $total_e+$collection['expenses_data'][$p];
				}												
				?>
				<td style="height:13.2pt" height="18"><?php echo ($collection['opening_balance_data'][$p]+$collection['transaction_data'][$p]+$collection['pending_amount_data'][$p]-$collection['expenses_data'][$p]);?></td>
			</tr>
			<?php
		}
		?>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18"><br></td>
         <td><br></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt;color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)" height="18">Customer Visits<br></td>
         <td style="color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)">Today<br></td>
      </tr>
      <tr style="height:13.8pt" height="18">
         <td style="height:13.8pt;color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)" height="18">Total Visits/Bills<br></td>
         <td style="color:white;font-size:11pt;font-weight:700;font-family:Cambria,serif;background:rgb(0,176,80)"><?php echo $visit;?><br></td>
      </tr>
      <!-- <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18">New Customers<br></td>
         <td><br></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18">Repeat Customers<br></td>
         <td><br></td>
      </tr> -->
      <!-- <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt" height="18"><br></td>
         <td><br></td>
      </tr>	 -->
      <!-- <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt;color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)" height="18">Due Amount<br></td>
         <td style="color:white;font-weight:700;font-family:Cambria,serif;background:rgb(0,112,192)">Today<br></td>
      </tr>
      <tr style="height:13.8pt" height="18">
         <td style="height:13.8pt;color:windowtext;font-size:11pt;font-family:Cambria,serif" height="18">Received<br></td>
         <td style="color:windowtext;font-size:11pt;font-family:Cambria,serif"><br></td>
      </tr>
      <tr style="height:13.2pt" height="18">
         <td style="height:13.2pt;color:windowtext;font-family:Cambria,serif" height="18">Credit Given<br></td>
         <td style="color:windowtext;font-family:Cambria,serif"><br></td>
      </tr> -->
   </tbody>
</table>


<p>Please refer to the detailed reports from the business panel, for comprehensive data.</p>
<p>In case of query, please feel free to reach out to us connect@salonfirst.in</p>

<p><b>Regards</b></p>
<p><b>Team Salonfirst</b></p