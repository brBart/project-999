{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/comparison_page.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new ComparisonPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
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
				<label>Filtro:</label><span id="document_id">{$filter_name}</span>
			</p>
			<p>
				<label>Fecha:</label><span id="date_time">{$filter_date_time}</span>
			</p>
			<p>
				<label>Incluye precios:</label>
				<input name="include_prices" id="include_prices" type="checkbox"
			  				checked="checked" disabled="disabled" />
			</p>
		</fieldset>
		<fieldset id="main_data">
		  	<p>
		  		<label>Comparaci&oacute;n No:</label>
		  		<span>{$comparison_id}</span>
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
		  	{if $status eq 1}
		  		<p>&nbsp;</p>
		  		<div id="details" class="items"></div>
		  	{/if}
		</fieldset>
		<fieldset id="controls">
		  	<input name="form_widget" id="print" type="button" value="Imprimir"
  				onclick="window.open('index.php?cmd=print_comparison&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
	</div>
</div>
<script type="text/javascript">
oDetails.init('../xsl/comparison_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
</script>