{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<sub_total>{$sub_total|nf:2}</sub_total>
		<discount>{$discount|nf:2}</discount>
		<total>{$total|nf:2}</total>
		<total_items>{$total_items}</total_items>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<product><![CDATA[{$details[i].product}]]></product>
			<packaging><![CDATA[{$details[i].packaging}]]></packaging>
			<quantity>{$details[i].quantity}</quantity>
			<price>{$details[i].price|nf:2}</price>
			<total>{$details[i].total|nf:2}</total>
		</row>
		{/section}
	</grid>
</response>