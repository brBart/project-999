{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		{include file='header_data_log_html.tpl'}
		<fieldset>
			<table id="list" class="content_medium">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Autorizaci&oacute;n</th>
						<th>Fecha Auto.</th>
						<th>Serie</th>
						<th>Del</th>
						<th>Al</th>
						<th>Fecha Ingreso</th>
						<th>Tipo Docto</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].resolution_number|escape}</td>
						<td>{$list[i].resolution_date}</td>
						<td>{$list[i].serial_number}</td>
						<td>{$list[i].initial_number}</td>
						<td>{$list[i].final_number}</td>
						<td>{$list[i].created_date}</td>
						<td>{$list[i].document_type|escape}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		{include file='controls_log_html.tpl'}
	</div>
</div>