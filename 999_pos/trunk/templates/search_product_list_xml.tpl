{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	{section name=i loop=$list}
	<result>
		<bar_code>{$list[i].bar_code}</bar_code>
		<name>{$list[i].name}</name>
		<packaging>{$list[i].packaging}</packaging>
	</result>
	{/section}
</response>