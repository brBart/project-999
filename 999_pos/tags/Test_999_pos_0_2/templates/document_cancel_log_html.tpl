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
						<th>Documento</th>
						<th>No.</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].cancelled_date}</td>
						<td>{$list[i].username}</td>
						<td>{$list[i].document|escape}</td>
						<td>{$list[i].number}</td>
						<td>{$list[i].total|nf:2}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		{include file='controls_log_html.tpl'}
	</div>
</div>