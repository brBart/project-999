{* Smarty * }
{config_load file="site.conf"}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{#site_title#} - {$module_title}</title>
<link href="../styles/layout.css" rel="stylesheet" type="text/css" />
<link href="../styles/typography.css" rel="stylesheet" type="text/css" />
<link href="../styles/decoration.css" rel="stylesheet" type="text/css" />
{literal}
<style type="text/css">
body {
	background-color: #EEEEEE;
}
</style>
{/literal}
<script type="text/javascript">
	var isSessionActive = true;
</script>
</head>
<body>
	{literal}
	<script type="text/javascript">
		if(screen.width <= 1000 && screen.height <= 700){
			document.body.style.fontSize = '8px';
		}
		else{
			document.body.style.fontSize = '10px';
		}
	</script>
	{/literal}
	<div>
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p id="{$type}" class="{$type}">{$message}</p>
		{/if}
		</div>
		<div id="frm" class="cash_receipt">
			<fieldset id="main_data">
				{include file='vouchers_html.tpl'}
			</fieldset>
		</div>
	</div>
</body>
</html>