{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<keyword><![CDATA[{$keyword}]]></keyword>
	{section name=i loop=$list}
	<result>
		<bar_code><![CDATA[{$list[i].bar_code}]]></bar_code>
		<name><![CDATA[{$list[i].name}]]></name>
		<manufacturer><![CDATA[{$list[i].manufacturer}]]></manufacturer>
	</result>
	{/section}
</response>