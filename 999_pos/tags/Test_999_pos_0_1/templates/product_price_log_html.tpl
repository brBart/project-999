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
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Presentaci&oacute;n</th>
						<th>Anterior</th>
						<th>Nuevo</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].logged_date}</td>
						<td>{$list[i].username}</td>
						<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
						<td>{$list[i].name|escape|wordwrap:11:"<br />":true}</td>
						<td>{$list[i].packaging|escape|wordwrap:11:"<br />":true}</td>
						<td>{$list[i].last_price|nf:2}</td>
						<td>{$list[i].new_price|nf:2}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		{include file='controls_log_html.tpl'}
	</div>
</div>