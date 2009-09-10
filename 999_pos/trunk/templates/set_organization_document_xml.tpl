{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<element_id>{$element_id}</element_id>
	<contact>{$contact}</contact>
</response>