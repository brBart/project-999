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
	</params>
	<grid>
		{section name=i loop=$bonus}
		<row>
			<bonus_id>{$bonus[i].id}</bonus_id>
			<quantity>{$bonus[i].quantity}</quantity>
			<percentage>{$bonus[i].percentage}</percentage>
			<created_date>{$bonus[i].created_date}</created_date>
			<expired_date>{$bonus[i].expired_date}</expired_date>
		</row>
		{/section}
	</grid>
</response>