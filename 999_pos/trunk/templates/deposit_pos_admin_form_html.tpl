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
		<fieldset id="controls">
		  	<input name="form_widget" id="cancel" type="button" value="Anular"
		  			{if $status eq 1 and $cash_register_status eq 1}onclick="oCancel.showForm();"{else}disabled="disabled"{/if} />
		  	<input name="form_widget" id="print" type="button" value="Imprimir"
		  			onclick="window.open('index.php?cmd=print_deposit&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		</fieldset>
		{if $status eq 1 and $cash_register_status eq 1}
		{include file='authentication_form_html.tpl' cancel_cmd='cancel_deposit'}
		{/if}
	</div>
</div>
<script type="text/javascript">
oDetails.init('../xsl/deposit_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
</script>