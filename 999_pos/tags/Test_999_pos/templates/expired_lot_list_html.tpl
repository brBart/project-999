{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Lotes Vencidos</span>
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
						<th>Lote</th>
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Presentaci&oacute;n</th>
						<th>Vencio</th>
						<th>Cantidad</th>
						<th>Disponible</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].lot_id}</td>
						<td>{$list[i].bar_code|escape}</td>
						<td>{$list[i].manufacturer|escape}</td>
						<td>{$list[i].name|escape}</td>
						<td>{$list[i].packaging|escape}</td>
						<td>{$list[i].expiration_date}</td>
						<td>{$list[i].quantity}</td>
						<td>{$list[i].available}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_expired_lot_list&date={$date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>