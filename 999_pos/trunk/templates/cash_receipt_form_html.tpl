{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{#site_title#} - {$module_title}</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/typography.css" rel="stylesheet" type="text/css" />
<link href="../styles/decoration.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var isSessionActive = true;
	var isLarge = true;
</script>
</head>
<body>
	{literal}
	<script type="text/javascript">
		if(screen.width <= 1000 && screen.height <= 700){
			document.body.style.fontSize = '8px';
			isLarge = false;
		}
		else{
			document.body.style.fontSize = '10px';
		}
	</script>
	{/literal}
	<div id="wrapper_cash_receipt">
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p id="{$type}" class="{$type}">{$message}</p>
		{/if}
		</div>
		<div id="frm" class="cash_receipt">
			<fieldset id="main_data">
				<div id="total_amounts">
					<p>
				  		<label id="cash_label">Efectivo:</label>
						<input id="cash_input" type="text" onfocus="timerObj.start(500);" onblur="timerObj.stop();" />
				  		<span id="cash-failed" class="hidden">*</span>
				  	</p>
				  	<p>
				  		<label id="vouchers_total_label">Tarjetas:</label>
				  		<span id="vouchers_total">{$total_vouchers|nf:2}</span>
				  	</p>
				  	<p>
			  			<label id="invoice_total_label">Total:</label>
				  		<span id="invoice_total">{$invoice_total|nf:2}</span>
				  	</p>
				  	<p>
				  		<label id="change_total_label">Vuelto:</label>
				  		<span id="change_total">{$change|nf:2}</span>
				  	</p>
			  	</div>
			  	<div id="details" class="items"></div>
			</fieldset>
		</div>
	</div>
</body>
<script type="text/javascript">
	var oInput = document.getElementById('cash_input');
	oInput.focus();
</script>
</html>