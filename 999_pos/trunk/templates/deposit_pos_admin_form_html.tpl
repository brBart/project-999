{* Smarty * }
{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/deposit_page.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new DepositPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='cash_register_status_bar_html.tpl'}
		{include file='status_bar_doc_html.tpl'}
		{include file='header_data_html.tpl' document_name='Deposito'}
		<fieldset id="main_data">
			<p>
		  		<label>Boleta No:</label>
		  		<span>{$slip_number|htmlchars}</span>
		  	</p>
		  	<p>
		  		<label>Cuenta bancaria:</label>
		  		<span>{$bank_account|htmlchars}</span>
		  	</p>
		  	<p>
		  		<label>Banco:</label>
		  		<span>{$bank|htmlchars}</span>
		  	</p>
		  	<p>&nbsp;</p>
		  	<div id="details" class="items"></div>
	  	</fieldset>
	  	{include file='controls_doc_html.tpl' print_cmd='print_deposit' cancel_cmd='cancel_deposit'}
	</div>
</div>
<script type="text/javascript">
oDetails.init('../xsl/deposit_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
</script>