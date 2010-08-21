{* Smarty *}
<div id="content">
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Serie No.</th>
				<th>Inicial</th>
				<th>Final</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			{assign var=id value=$list[i].id|htmlchars}
			<tr>
				<td>
					<a href="{$item_link|cat:$id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page}"
						onclick="oSession.setIsLink(true);">
						{$list[i].serial_number}{if $list[i].is_default eq 1} (Predeterminado){/if}
					</a>
				</td>
				<td>{$list[i].initial_number}</td>
				<td>{$list[i].final_number}</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>