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
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<bar_code><![CDATA[{$details[i].bar_code}]]></bar_code>
			<manufacturer><![CDATA[{$details[i].manufacturer}]]></manufacturer>
			<product><![CDATA[{$details[i].product}]]></product>
			<packaging><![CDATA[{$details[i].packaging}]]></packaging>
			<um><![CDATA[{$details[i].um}]]></um>
			<physical>{$details[i].physical}</physical>
			<system>{$details[i].system}</system>
			<diference>{$details[i].diference}</diference>
		</row>
		{/section}
	</grid>
</response>