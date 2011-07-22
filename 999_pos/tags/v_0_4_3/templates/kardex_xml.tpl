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
		<balance>{$balance}</balance>
	</params>
	<grid>
		{section name=i loop=$kardex}
		<row>
			<created_date>{$kardex[i].created_date}</created_date>
			<document><![CDATA[{$kardex[i].document}]]></document>
			<number><![CDATA[{$kardex[i].number}]]></number>
			<lot_id>{$kardex[i].lot_id}</lot_id>
			<entry>{$kardex[i].entry}</entry>
			<withdraw>{$kardex[i].withdraw}</withdraw>
			<balance>{$kardex[i].balance}</balance>
		</row>
		{/section}
	</grid>
</response>