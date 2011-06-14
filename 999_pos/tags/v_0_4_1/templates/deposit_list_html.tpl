{* Smarty *}
<div id="content">
	<p class="date_title">Jornada {$start_date} al {$end_date}</p>
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Jornada</th>
				<th>Deposito No.</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td>{$list[i].working_day}</td>
				<td>
					<a href="{'index.php?cmd=get_deposit_by_working_day&deposit_working_day='|cat:$list[i].working_day|cat:'&id='|cat:$list[i].id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page|cat:'&start_date='|cat:$start_date|cat:'&end_date='|cat:$end_date}"
						onclick="oSession.setIsLink(true);">{$list[i].id}</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>