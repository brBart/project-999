{* Smarty *}
<div id="content">
	<p class="date_title">Proveedor: {$supplier}, Env&iacute;o No: {$shipment_number}</p>
	<table id="list" class="content_small">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Env&iacute;o No.</th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td>{$list[i].created_date}</td>
				<td>
					<a href="{$item_link|cat:$list[i].id|cat:'&last_cmd='|cat:$actual_cmd|cat:'&page='|cat:$page|cat:'&supplier_id='|cat:$supplier_id|cat:'&shipment_number='|cat:$shipment_number}"
						onclick="oSession.setIsLink(true);">{$list[i].id}</a>
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>