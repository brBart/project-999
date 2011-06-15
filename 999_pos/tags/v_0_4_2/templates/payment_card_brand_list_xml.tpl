{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<grid>
		{section name=i loop=$list}
		<row>
			<payment_card_brand_id>{$list[i].id}</payment_card_brand_id>
			<name><![CDATA[{$list[i].name}]]></name>
		</row>
		{/section}
	</grid>
</response>