{* Smarty *}
<div id="content">
	<p class="date_title">Fecha {$start_date} al {$end_date}</p>
	<table id="list" class="content_small">
		<caption>
			<span>{$first_item} - {$last_item} de {$total_items}</span>
			<span>P&aacute;gina {$page} de {$total_pages}</span>
			{if $previous_link neq ''}
				<a href="{$previous_link}" onclick="oSession.setIsLink(true);">Anterior</a>
			{else}
				Anterior
			{/if} |
			{if $next_link neq ''}
				<a href="{$next_link}" onclick="oSession.setIsLink(true);">Siguiente</a>
			{else}
				Siguiente
			{/if}
		</caption>
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Recibo No.</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td>{$list[i].created_date}</td>
				<td>
					<a href="{$item_link|cat:$list[i].receipt_id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page|cat:'&start_date='|cat:$start_date|cat:'&end_date='|cat:$end_date}"
						onclick="oSession.setIsLink(true);">{$list[i].receipt_id}</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>