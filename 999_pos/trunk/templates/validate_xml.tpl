{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>{$success}</success>
	<elementid>{$elementid}</elementid>
	<message><![CDATA[ {$message} ]]></message>
</response>