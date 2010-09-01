{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<params>
		<total>{$total|nf:2}</total>
		<total_items>{$total_items}</total_items>
	</params>
	<grid>
		{section name=i loop=$details}
		<row>
			<detail_id>{$details[i].id}</detail_id>
			<receipt_id>{$details[i].receipt_id}</receipt_id>
			<received>{$details[i].received}</received>
			<deposited>{$details[i].deposited}</deposited>
		</row>
		{/section}
	</grid>
</response>