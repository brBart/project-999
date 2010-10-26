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
						<td>{$list[i].bar_code|htmlchars}</td>
						<td>{$list[i].manufacturer|htmlchars}</td>
						<td>{$list[i].name|htmlchars}</td>
						<td>{$list[i].packaging|htmlchars}</td>
						<td>{$list[i].quantity}</td>
						<td>{$list[i].last_sale}</td>
						<td>{$list[i].sale_quantity}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" />
		</fieldset>
	</div>
</div>