{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Ranking de Ventas por Producto</span>
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
						<th>Presentaci&oacute;n</th>
						<th>Vendidos</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].rank}</td>
						<td>{$list[i].bar_code|escape|wordwrap:24:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:19:"<br />":true}</td>
						<td>{$list[i].name|escape|wordwrap:19:"<br />":true}</td>
						<td>{$list[i].packaging|escape|wordwrap:19:"<br />":true}</td>
						<td>{$list[i].quantity}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_sales_ranking_list&start_date={$start_date|escape:'url'}&end_date={$end_date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>