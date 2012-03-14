{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<nit>{$nit}</nit>
	<name><![CDATA[{$name|escape}]]></name>
</response>