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
		{section name=i loop=$products}
		<row>
			<product_id>{$products[i].id}</product_id>
			<name><![CDATA[{$products[i].name|wordwrap:46:"\n":true}]]></name>
			<manufacturer><![CDATA[{$products[i].manufacturer|wordwrap:23:"\n":true}]]></manufacturer>
		</row>
		{/section}
	</grid>
</response>