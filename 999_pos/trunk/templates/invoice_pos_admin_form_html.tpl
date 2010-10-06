{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/invoice_page.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new InvoicePage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='cash_register_status_bar_html.tpl'}
		{include file='status_bar_invoice_html.tpl'}
		{include file='header_data_invoice_html.tpl'}
		<fieldset id="main_data">
		  	<p>
		  		<label>Nit:</label>
		  		<span>{$nit}</span>
		  	</p>
		  	<p>
		  		<label>Nombre:</label>
		  		<span>{$customer|htmlchars}&nbsp;</span>
		  	</p>
		  	<br />
		  	<p>
		  		<label>Efectivo:</label>
		  		<span>{$cash_amount|nf:2}</span>
		  	</p>
			<p>
				<label>Cambio:</label>
				<span>{$change_amount|nf:2}</span>
			</p>
			<p>
				<label>Tarjetas:</label>
				<span><a href="#" onclick="oVouchersFrm.show();">Ver...</a></span>
			</p>
	  		{* Because Firefox css rule margin-top on table rule bug. *}
	  		<p>&nbsp;</p>
		  	<div id="details" class="items"></div>
		</fieldset>
		<fieldset id="controls">
		  	<input name="form_widget" id="cancel" type="button" value="Anular"
		  			{if $status eq 1 and $cash_register_status eq 1}onclick="oCancel.showForm();"{else}disabled="disabled"{/if} />
		</fieldset>
		{if $status eq 1 and $cash_register_status eq 1}
		{include file='authentication_form_html.tpl' cancel_cmd='cancel_invoice'}
		{/if}
	</div>
</div>
<script type="text/javascript">
oDetails.init('../xsl/invoice_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
</script>
<div id="vouchers_container" class="hidden">
	<div class="list_form">
		<a class="close_window" href="#" onclick="oVouchersFrm.hide();">Cerrar[X]</a>
		<br />
		{include file='vouchers_html.tpl'}
	 </div>
</div>
<script type="text/javascript" src="../scripts/modal_form.js"></script>
<script type="text/javascript">
var oVouchersFrm = new ModalForm('vouchers_container');
</script>