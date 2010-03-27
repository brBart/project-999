{* Smarty *}
<div id="content">
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Titular</th>
				<th>N&uacute;mero</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			{assign var=id value=$list[i].id|htmlchars}
			<tr>
				<td>{$list[i].name|htmlchars}</td>
				<td>
					<a href="{$item_link|cat:$id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page}"
						onclick="oSession.setIsLink(true);">{$id}</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>