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
		<sub_total>{$sub_total|nf:2}</sub_total>
		<discount_percentage>{$discount_percentage|nf:2}</discount_percentage>
		<discount>{$discount|nf:2}</discount>
		<total>{$total|nf:2}</total>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			{if $details[i].is_bonus eq 1}
			<is_bonus>1</is_bonus>
			{assign var=format_percent value=$details[i].percentage|nf:2}
			<percentage>{'(-'|cat:$format_percent|cat:'%)'}</percentage>
			{else}
			<is_bonus>0</is_bonus>
			<percentage>0</percentage>
			<bar_code><![CDATA[{$details[i].bar_code|wordwrap:16:"\n":true}]]></bar_code>
			<manufacturer><![CDATA[{$details[i].manufacturer|wordwrap:11:"\n":true}]]></manufacturer>
			<um><![CDATA[{$details[i].um|wordwrap:8:"\n":true}]]></um>
			<expiration_date>
			{if $details[i].expiration_date neq ''}{$details[i].expiration_date}{else}N/A{/if}
			</expiration_date>
			{/if}
			<detail_id>{$details[i].id}</detail_id>
			<product><![CDATA[{$details[i].product|wordwrap:22:"\n":true}]]></product>
			<quantity>{$details[i].quantity}</quantity>
			<price>{$details[i].price|nf:2}</price>
			<total>{$details[i].total|nf:2}</total>
		</row>
		{/section}
	</grid>
</response>