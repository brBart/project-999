{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/comparison_filter_page.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine('1');
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new ComparisonFilterPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='status_bar_doc_html.tpl' status='1'}
		<fieldset id="header_data">
			<p>
				<label>Filtro:</label><span>{$filter_name}</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$filter_date_time}</span>
			</p>
			<p>
				<label>Incluye precios:</label>
				<span>{if $include_prices eq '1'}Si{else}No{/if}</span>
			</p>
		</fieldset>
		<fieldset id="main_data">
		  	<p>
		  		<label>Comparaci&oacute;n No:</label>
		  		<span>{$id}</span>
		  	</p>
		  	<p>
		  		<label>Fecha:</label>
		  		<span>{$date_time}</span>
		  	</p>
			<p>
		  		<label>Motivo:</label>
		  		<span>{$reason|escape}</span>
		  	</p>
		  	<p>
		  		<label>General:</label>
		  		<input name="form_widget" id="general" type="checkbox"
		  				checked="checked" disabled="disabled" />
		  	</p>
	  		<p>&nbsp;</p>
	  		<div id="details" class="items"></div>
		</fieldset>
		<fieldset id="controls">
		  	<input name="form_widget" id="print" type="button" value="Imprimir"
  				onclick="window.open('index.php?cmd=print_comparison_filter&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>
<script type="text/javascript">
oDetails.init('../xsl/comparison_filter_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
</script>