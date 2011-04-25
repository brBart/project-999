{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<page>{$page}</page>
		<page_items>{$page_items}</page_items>
	</params>
	<grid>
		{section name=i loop=$reserves}
		<row>
			<reserve_id>{$reserves[i].id}</reserve_id>
			<created_date>{$reserves[i].created_date}</created_date>
			<username>{$reserves[i].username}</username>
			<lot_id>{$reserves[i].lot_id}</lot_id>
			<quantity>{$reserves[i].quantity}</quantity>
		</row>
		{/section}
	</grid>
</response>