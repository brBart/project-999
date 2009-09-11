{* Smarty *}
<div id="content">
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
				<th>Nombre</th>
				<th>Presentaci&oacute;n</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td><a href="{$item_link|cat:$list[i].product_id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page}"
						onclick="oSession.setIsLink(true);">{$list[i].name}</a></td>
				<td>{$list[i].packaging}</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>