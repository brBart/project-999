{* Smarty *}
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="header_data">
			<p>
				<label>Reporte:</label><span>Estad&iacute;sticas de Ventas y Compras</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date}</span>
			</p>
			<p>
				<label>Ultimos:</label><span>{$months} meses</span>
			</p>
			<p>
				<label>Ordenado por:</label><span>{if $order eq 'product'}Producto{else}Casa{/if}</span>
			</p>
		</fieldset>
		<fieldset>
			<p>&nbsp;</p>
			<table id="list" class="content_large large_report">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Presentaci&oacute;n</th>
						{section name=i loop=$months_names}
							<th>{$months_names[i]}</th>
						{/section}
						<th>Promedio</th>
					</tr>
					<tr>
						<th colspan="4"></th>
						{section name=i loop=$months_names}
							<th>V | C</th>
						{/section}
						<th>V | C</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].bar_code|escape}</td>
						<td>{$list[i].manufacturer|escape}</td>
						<td>{$list[i].product|escape}</td>
						<td>{$list[i].packaging|escape}</td>
						{section name=x loop="$months"}
							<td>{$list[i].sales[x].sales} | {$list[i].purchases[x].purchases}</td>
						{/section}
						<td>{$list[i].sales_average|nf} | {$list[i].purchases_average|nf}</td>
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