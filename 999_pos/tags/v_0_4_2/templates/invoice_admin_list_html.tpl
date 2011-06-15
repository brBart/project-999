{* Smarty *}
<div id="content">
	<p class="date_title">Jornada {$start_date} al {$end_date}</p>
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Jornada</th>
				<th>Factura</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td>{$list[i].working_day}</td>
				<td>
					<a href="{'index.php?cmd=get_invoice_by_working_day&working_day='|cat:$list[i].working_day|cat:'&serial_number='|cat:$list[i].serial_number|cat:'&number='|cat:$list[i].number|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page|cat:'&start_date='|cat:$start_date|cat:'&end_date='|cat:$end_date}"
						onclick="oSession.setIsLink(true);">{$list[i].serial_number|cat:'-'|cat:$list[i].number}</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>