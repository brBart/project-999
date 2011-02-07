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
		<total>{$total|nf:2}</total>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<transaction_number><![CDATA[{$details[i].transaction_number|wordwrap:16:"\n":true}]]></transaction_number>
			<number>{$details[i].number}</number>
			<type><![CDATA[{$details[i].type|wordwrap:11:"\n":true}]]></type>
			<brand><![CDATA[{$details[i].brand|wordwrap:11:"\n":true}]]></brand>
			<name><![CDATA[{$details[i].name|wordwrap:11:"\n":true}]]></name>
			<expiration_date>{$details[i].expiration_date}</expiration_date>
			<amount>{$details[i].amount|nf:2}</amount>
		</row>
		{/section}
	</grid>
</response>