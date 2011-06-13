{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		{include file='header_data_log_html.tpl'}
		<fieldset>
			<table id="list" class="content_medium">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Serie</th>
						<th>N&uacute;mero</th>
						<th>Fecha</th>
						<th>Monto</th>
						<th>Estado</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].serial_number}</td>
						<td>{$list[i].number}</td>
						<td>{$list[i].date}</td>
						<td>{$list[i].total|nf:2}</td>
						<td>{$list[i].state|escape}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		{include file='controls_log_html.tpl'}
	</div>
</div>