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
		<quantity>{$quantity}</quantity>
		<available>{$available}</available>
	</params>
	<grid>
		{section name=i loop=$lots}
		<row>
			<lot_id>{$lots[i].id}</lot_id>
			<entry_date>{$lots[i].entry_date}</entry_date>
			<expiration_date>
				{if $lots[i].expiration_date neq ''}{$lots[i].expiration_date}{else}N/A{/if}
			</expiration_date>
			<price>{$lots[i].price|nf:2}</price>
			<quantity>{$lots[i].quantity}</quantity>
			<available>{$lots[i].available}</available>
		</row>
		{/section}
	</grid>
</response>