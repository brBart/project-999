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
				<label>Ordenado por:</label><span>{if $order eq 'product'}Nombre{else}Casa{/if}</span>
			</p>
		</fieldset>
		<fieldset>
			<p>&nbsp;</p>
			<table id="list_large" class="content_large large_report">
				{include file='list_caption_html.tpl'}
				<thead>
					<tr>
						<th>Barra</th>
						<th>Casa</th>
						<th>Nombre</th>
						<th>Presentaci&oacute;n</th>
						{section name=i loop=$months_names}
							<th class="data_title" colspan="3">{$months_names[i]}</th>
						{/section}
						<th  class="data_title" colspan="3">Promedio</th>
					</tr>
					<tr>
						<th colspan="4"></th>
						{section name=i loop=$months_names}
							<th class="data_col data_left">V</th>
							<th class="data_col data_separator">|</th>
							<th class="data_col data_right">C</th>
						{/section}
						<th class="data_col data_left">V</th>
						<th class="data_col data_separator">|</th>
						<th class="data_col data_right">C</th>
					</tr>
				</thead>
				<tbody>
				{section name=i loop=$list}
					<tr>
						<td>{$list[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
						<td>{$list[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
						<td>{$list[i].product|escape|wordwrap:22:"<br />":true}</td>
						{section name=x loop="$months"}
							<td class="data_col data_left">{$list[i].sales[x].sales}</td>
							<td class="data_col data_separator">|</td>
							<td class="data_col data_right">{$list[i].purchases[x].purchases}</td>
						{/section}
						<td class="data_col data_left">{$list[i].sales_average|nf}</td>
						<td class="data_col data_separator">|</td>
						<td class="data_col data_right">{$list[i].purchases_average|nf}</td>
					</tr>
				{/section}
				</tbody>
			</table>
		</fieldset>
		<fieldset>
			<input type="button" value="Imprimir" onclick="window.open('index.php?cmd=print_sales_and_purchases_stadistics_list&date={$date}&months={$months}&order={$order}&first={$first}&last={$last}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>