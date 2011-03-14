{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Productos Sin Movimiento</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Dias:</label><span>Mas de {$days}</span>
			</p>
		</fieldset>
		<fieldset>
			<p>&nbsp;</p>
			<table id="list" class="content_medium">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Presentaci&oacute;n</th>
						<th>En Stock</th>
						<th>Ultima Venta</th>
						<th>Salieron</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].bar_code|escape|wordwrap:20:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:15:"<br />":true}</td>
						<td>{$list[i].name|escape|wordwrap:30:"<br />":true}</td>
						<td>{$list[i].quantity}</td>
						<td>{$list[i].last_sale}</td>
						<td>{$list[i].sale_quantity}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_inactive_product_list&days={$days}&date={$date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>