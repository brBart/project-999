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
			<shift_id>{$list[i].id}</shift_id>
			<name><![CDATA[{$list[i].name|cat:", "|cat:$list[i].time_table}]]></name>
		</row>
		{/section}
	</grid>
</response>