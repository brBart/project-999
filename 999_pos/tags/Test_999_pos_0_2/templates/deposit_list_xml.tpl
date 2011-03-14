{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<grid>
		{section name=i loop=$list}
		<row>
			<id>{$list[i].id}</id>
			<bank_id>{$list[i].bank_id}</bank_id>
			<number><![CDATA[{$list[i].number}]]></number>
			<status>{$list[i].status}</status>
		</row>
		{/section}
	</grid>
</response>