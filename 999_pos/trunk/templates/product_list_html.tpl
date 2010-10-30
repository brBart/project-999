{* Smarty *}
<div id="content">
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Presentaci&oacute;n</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td><a href="{$item_link|cat:$list[i].id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page}"
						onclick="oSession.setIsLink(true);">{$list[i].name|escape}</a></td>
				<td>{$list[i].packaging|escape}</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>