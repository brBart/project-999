{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<total>{$total|nf:2}</total>
		<page_items>{$page_items}</page_items>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<transaction_number><![CDATA[{$details[i].transaction_number}]]></transaction_number>
			<number>{$details[i].number}</number>
			<type><![CDATA[{$details[i].type}]]></type>
			<brand><![CDATA[{$details[i].brand}]]></brand>
			<name><![CDATA[{$details[i].name}]]></name>
			<expiration_date>{$details[i].expiration_date}</expiration_date>
			<amount>{$details[i].amount|nf:2}</amount>
		</row>
		{/section}
	</grid>
</response>