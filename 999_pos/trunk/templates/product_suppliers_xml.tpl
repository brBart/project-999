{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<total_items>{$total_items}</total_items>
	</params>
	<grid>
		{section name=i loop=$suppliers}
		<row>
			<product_supplier_id><![CDATA[{$suppliers[i].product_supplier_id}]]></product_supplier_id>
			<supplier><![CDATA[{$suppliers[i].supplier}]]></supplier>
			<product_sku><![CDATA[{$suppliers[i].product_sku}]]></product_sku>
		</row>
		{/section}
	</grid>
</response>