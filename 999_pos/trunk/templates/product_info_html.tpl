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
{literal}
<style type="text/css">
body {
	background-color: #EEEEEE;
}
</style>
{/literal}
<script type="text/javascript">
	var isSessionActive = true;
</script>
</head>
<body>
	{literal}
	<script type="text/javascript">
		if(screen.width <= 1000 && screen.height <= 700){
			document.body.style.fontSize = '8px';
		}
		else{
			document.body.style.fontSize = '10px';
		}
	</script>
	{/literal}
	<div>
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p id="{$type}" class="{$type}">{$message}</p>
		{/if}
		</div>
		<div id="frm" class="cash_receipt">
			<fieldset id="main_data">
				<div id="product_details">
					<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
				  	<p>
				  		<label>Nombre:</label>
				  		<span>{$name|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>C&oacute;digo barra:</label>
				  		<span>{$bar_code|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>Presentaci&oacute;n:</label>
				  		<span>{$packaging|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>Descripci&oacute;n:</label>
				  		<span>{$description|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>Casa:</label>
				  		<span>{$manufacturer|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>Unidad de Medida:</label>
				  		<span>{$um|escape}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label>Precio:</label>
				  		<span>{$price}&nbsp;</span>
				  	</p>
				  	<p>
				  		<label for="deactivated">Desactivado:</label>
				  		<input name="form_widget" id="deactivated" type="checkbox"
				  				{if $deactivated eq 1}checked="checked"{/if}
				  				disabled="disabled" />
				  	</p>
				  	<p><label>Cantidad:</label><span id="quantity">{$quantity}&nbsp;</span></p>
				  	<p><label>Disponible:</label><span id="available">{$available}&nbsp;</span></p>
				</div>
				<div id="product_suppliers">
				  	<div id="details" class="items">
				  		<table class="read_only">
				  			<caption>Proveedores</caption>
					      	<thead>
					      		<tr>
					      			<th>Proveedor</th>
					         		<th>Codigo</th>
				         		</tr>
					       	</thead>
					       	<tbody>
				       			{section name=i loop=$suppliers}
								<tr>
									<td>{$suppliers[i].supplier}</td>
									<td>{$suppliers[i].product_sku}</td>
								</tr>
								{/section}
					       	</tbody>
						</table>
				  	</div>
				</div>
			</fieldset>
			<fieldset>
				<div id="lots" class="items">
					<table class="read_only">
						<caption>Lotes</caption>
				      	<thead>
				      		<tr>
				      			<th>Lote</th> 
				         		<th>Ingreso</th>
				         		<th>Vence</th>
				         		<th>Precio</th>
				         		<th>Cantidad</th>
				         		<th>Disponible</th>
				      		</tr>
				       	</thead>
				       	<tbody>
			       			{section name=i loop=$lots}
							<tr>
								<td>{$lots[i].id}</td>
								<td>{$lots[i].entry_date}</td>
								<td>
									{if $lots[i].expiration_date neq ''}{$lots[i].expiration_date}{else}N/A{/if}
								</td>
								<td>{$lots[i].price|nf:2}</td>
								<td>{$lots[i].quantity}</td>
								<td>{$lots[i].available}</td>
							</tr>
							{/section}
				       	</tbody>
					</table>
				</div>
				<p>&nbsp;</p>
				<div id="reserves" class="items">
					<table class="read_only">
						<caption>Reservados</caption>
				      	<thead>
				      		<tr>
				      			<th>Reservado No.</th> 
				         		<th>Fecha</th>
				         		<th>Usuario</th>
				         		<th>Lote</th>
				         		<th>Cantidad</th>
				      		</tr>
				       	</thead>
				       	<tbody>
			       			{section name=i loop=$reserves}
							<tr>
								<td>{$reserves[i].id}</td>
								<td>{$reserves[i].created_date}</td>
								<td>{$reserves[i].username}</td>
								<td>{$reserves[i].lot_id}</td>
								<td>{$reserves[i].quantity}</td>
							</tr>
							{/section}
				       	</tbody>
					</table>
				</div>
			</fieldset>
		</div>
	</div>
</body>
</html>