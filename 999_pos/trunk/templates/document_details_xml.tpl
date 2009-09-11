{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<page>{$page}</page>
		<total_pages>{$total_pages}</total_pages>
		<total_items>{$total_items}</total_items>
		<first_item>{$first_item}</first_item>
		<last_item>{$last_item}</last_item>
		<previous_link>{$previous_link}</previous_link>
		<next_link>{$next_link}</next_link>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<bar_code>{$details[i].bar_code}</bar_code>
			<manufacturer>{$details[i].manufacturer}</manufacturer>
			<product>$details[i].product</product>
			<packaging>$details[i].packaging</packaging>
			<um>$details[i].um</um>
			<quantity>$details[i].quantity</quantity>
			<price>$details[i].price</price>
			<total>$details[i].total</total>
			<expiration_date>$details[i].expiration_date</expiration_date>
		</row>
		{/section}
	</grid>
</response>