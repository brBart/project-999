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
		<total>{$total|nf:2}</total>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<receipt_id>{$details[i].receipt_id}</receipt_id>
			<invoice>{$details[i].serial_number|cat:"-"|cat:$details[i].number}</invoice>
			<received>{$details[i].received|nf:2}</received>
			<deposited>{$details[i].deposited|nf:2}</deposited>
		</row>
		{/section}
	</grid>
</response>