{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>En Stock</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
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
						<th>Disponible</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
						<td>{$list[i].name|escape|wordwrap:22:"<br />":true}</td>
						<td>{$list[i].available}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_in_stock_list', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>