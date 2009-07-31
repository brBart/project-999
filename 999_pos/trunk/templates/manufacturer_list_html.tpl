{* Smarty *}
<div id="list_results">
	{if $total_items eq 0}
		<p>No hay resultados.</p>
	{else}
		<table>
			<caption>
				<span>{$first_item} - {$last_item} de {$total_items}</span>
				<span>
					{if $previous_link not eq ''}
						<a href="{$previous_link}" onclick="oSession.setIsLink(true);">Anterior</a>
					{else}
						Anterior
					{/if} | 
					{if $next_link not eq ''}
						<a href="{$next_link}" onclick="oSession.setIsLink(true);">Siguiente</a>
					{else}
						Siguiente
					{/if}
				</span>
				<span>P&aacute;gina {$page} de {$total_pages}</span>
			</caption>
			<thead>
				<tr>
					<th>Nombre</th>
				</tr>
			</thead>
			<tbody>
			{section name=i loop=$list}
				<tr>
					<td>{$list[i].name}</td>
				</tr>
			{/section}
			</tbody>
		</table>
	{/if}
</div>