{* Smarty *}
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