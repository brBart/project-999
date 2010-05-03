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
<script type="text/javascript" src="../scripts/session.js"></script>
{literal}
<script type="text/javascript">
	var isSessionActive = true;
</script>
{/literal}
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
	<div id="wrapper">
		<div id="header">
			<h1>Sistema 999 - {$module_title}</h1>
			<p>
				Usuario: {get_username}
			</p>
			<p>
			{foreach from=$back_trace item=trace name=back_trace_loop}
				<strong>{$trace}</strong>
				{if not $smarty.foreach.back_trace_loop.last} <img src="../images/trace.png"> {/if}
			{/foreach}
			</p>
		</div>
		<div id="deco_div"></div>
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p class="{$type}">{$message}</p>
		{/if}
		</div>
		{if $content neq 'none'}
			{include file=$content}
		{/if}
		<div id="footer"></div>
	</div>
</body>
</html>