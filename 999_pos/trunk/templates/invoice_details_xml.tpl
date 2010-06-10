{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<sub_total>{$sub_total|nf:2}</sub_total>
		<discount_percentage>{$discount_percentage|nf:2}</discount_percentage>
		<discount>{$discount|nf:2}</discount>
		<total>{$total|nf:2}</total>
		<total_items>{$total_items}</total_items>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			{if $details[i].is_bonus eq 1}
			<row_pos>0</row_pos>
			<is_bonus>1</is_bonus>
			{assign var=format_percent value=$details[i].percentage|nf:2}
			<percentage>{'(-'|cat:$format_percent|cat:'%)'}</percentage>
			{else}
			<row_pos>{counter}</row_pos>
			<is_bonus>0</is_bonus>
			<percentage>0</percentage>
			{/if}
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