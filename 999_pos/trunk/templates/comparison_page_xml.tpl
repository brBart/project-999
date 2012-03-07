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
		<total_pages>{$total_pages}</total_pages>
		<total_items>{$total_items}</total_items>
		<first_item>{$first_item}</first_item>
		<last_item>{$last_item}</last_item>
		<previous_page>{$previous_page}</previous_page>
		<next_page>{$next_page}</next_page>
		<physical_total>{$physical_total}</physical_total>
		<system_total>{$system_total}</system_total>
		<total_diference>{$total_diference}</total_diference>
		{if $comparison_filter eq '1'}
		<price_total>{$price_total}</price_total>
		{/if}
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<bar_code><![CDATA[{$details[i].bar_code|wordwrap:22:"\n":true}]]></bar_code>
			<manufacturer><![CDATA[{$details[i].manufacturer|wordwrap:17:"\n":true}]]></manufacturer>
			<product><![CDATA[{$details[i].product|wordwrap:34:"\n":true}]]></product>
			<um><![CDATA[{$details[i].um|wordwrap:10:"\n":true}]]></um>
			<physical>{$details[i].physical}</physical>
			<system>{$details[i].system}</system>
			<diference>{$details[i].diference}</diference>
			{if $comparison_filter eq '1' && $include_prices eq '1'}
			<price>{$details[i].price|nf:2}</price>
			<total>{$details[i].total|nf:2}</total>
			{/if}
		</row>
		{/section}
	</grid>
</response>