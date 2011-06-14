{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		{include file='header_data_log_html.tpl'}
		<fieldset>
			<table id="list" class="content_medium">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Usuario</th>
						<th>Factura</th>
						<th>Sub-Total</th>
						<th>Descuento(%)</th>
						<th>Monto</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].created_date}</td>
						<td>{$list[i].username}</td>
						<td>{$list[i].serial_number|cat:'-'|cat:$list[i].number}</td>
						<td>{$list[i].subtotal|nf:2}</td>
						<td>{$list[i].percentage|nf:2}</td>
						<td>{$list[i].amount|nf:2}</td>
						<td>{$list[i].total|nf:2}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		{include file='controls_log_html.tpl'}
	</div>
</div>