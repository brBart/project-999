{* Smarty *}
<div id="list_results">
	{if $count eq 0}
		<p>No hay resultados.</p>
	{else}
		<p>
			<span>
				{(($page - 1) * $items_per_page) + 1} -
				{if $page eq $total_pages $total_items else $page * $items_per_page} de {$total_items}
			</span>
			<span></span>
		</p>
	{/if}
</div>