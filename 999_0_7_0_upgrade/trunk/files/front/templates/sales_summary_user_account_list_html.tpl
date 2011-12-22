{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Resumen de Ventas por Usuario</span>
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
						<th>Usuario</th>
						<th>Nombre</th>
						<th>Vendio</th>
						<th>Descuentos Realizo</th>
						<th>Recolecto</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].rank}</td>
						<td>{$list[i].username}</td>
						<td>{$list[i].name}</td>
						<td>{$list[i].subtotal|nf:2}</td>
						<td>{$list[i].discount_total|nf:2}</td>
						<td class="total_col">{$list[i].total|nf:2}</td>
					</tr>
				{/section}
				</tbody>
				<tfoot>
		       		<tr>
		       			<td colspan="4"></td>
		       			<td class="total_col">Total:</td>
		       			<td class="total_col">{$total|nf:2}</td>
		       		</tr>
		       	</tfoot>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_sales_summary_user_account_list&start_date={$start_date|escape:'url'}&end_date={$end_date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>