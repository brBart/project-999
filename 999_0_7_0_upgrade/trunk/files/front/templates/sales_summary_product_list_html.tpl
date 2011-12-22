{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Resumen de Ventas por Producto</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Fecha Inicial:</label><span>{$start_date}</span>
			</p>
			<p>
				<label>Fecha Final:</label><span>{$end_date}</span>
			</p>
		</fieldset>
		<fieldset>
			<p>&nbsp;</p>
			<table id="list" class="content_medium">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>No.</th>
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Precio Actual</th>
						<th>Precio Promedio</th>
						<th>Vendidos</th>
						<th>Recolectado</th>
						<th>Ofertas / Bonificacion</th>
						<th>Sub Total</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].rank}</td>
						<td>{$list[i].bar_code|escape|wordwrap:24:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:19:"<br />":true}</td>
						<td>{$list[i].name|escape|wordwrap:38:"<br />":true}</td>
						<td>{$list[i].actual_price|nf:2}</td>
						<td>{$list[i].avg_price|nf:2}</td>
						<td>{$list[i].quantity}</td>
						<td>{$list[i].subtotal|nf:2}</td>
						<td>{$list[i].bonus_total|nf:2}</td>
						<td class="total_col">{$list[i].total|nf:2}</td>
					</tr>
				{/section}
				</tbody>
				<tfoot>
					<tr>
		       			<td colspan="8"></td>
		       			<td class="total_col">Sub Total:</td>
		       			<td class="total_col">{$subtotal|nf:2}</td>
		       		</tr>
					<tr>
		       			<td colspan="8"></td>
		       			<td class="total_col">Descuentos:</td>
		       			<td class="total_col">{$discount_total|nf:2}</td>
		       		</tr>
		       		<tr>
		       			<td colspan="8"></td>
		       			<td class="total_col">Total:</td>
		       			<td class="total_col">{$total|nf:2}</td>
		       		</tr>
		       	</tfoot>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_sales_summary_product_list&start_date={$start_date|escape:'url'}&end_date={$end_date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>