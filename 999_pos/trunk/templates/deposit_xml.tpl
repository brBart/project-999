{* Smarty *}
{php}
header('Content-Type: text/xml');
{/php}
<?xml version="1.0" encoding="UTF-8"?>
<response>
	<success>1</success>
	<key>{$key}</key>
	<date_time>{$date_time}</date_time>
	<username>{$username}</username>
	<grid>
		{section name=i loop=$bank_account_list}
		<row>
			<bank_account_id><![CDATA[{$bank_account_list[i].id}]]></bank_account_id>
			<holder_name><![CDATA[{$bank_account_list[i].name}]]></holder_name>
		</row>
		{/section}
	</grid>
</response>