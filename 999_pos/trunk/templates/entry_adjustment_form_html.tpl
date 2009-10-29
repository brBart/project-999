{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/document_page.js"></script>
{if $status eq 0}
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/delete_detail.js"></script>
<script type="text/javascript" src="../scripts/delete_product_object.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new DocumentPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{if $status eq 0}
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oDeleteProductObj = new DeleteProductObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oDetails, 'delete_product_entry_document');
	// For the delete key pressed.
	oDetails.mDeleteObj = oDeleteProductObj;
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='status_bar_doc_html.tpl'}
		{include file='header_data_html.tpl' document_name='Vale de Entrada'}
		<fieldset id="main_data">
		  	<p>
		  		<label for="reason">Motivo:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="reason" type="text" maxlength="150"
		  			onblur="oSetProperty.execute('set_reason_object', this.value, this.id);" />
		  		<span id="reason-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$reason}</span>
		  		{/if}
		  	</p>
		  	{if $status eq 0}
		  		{include file='entry_toolbar_html.tpl' details_obj='oDetails' add_cmd='add_product_entry_document' event_delegator_obj='oEventDelegator'}
		  	{else}
		  		{* Because Firefox css rule margin-top on table rule bug. *}
		  		<p>&nbsp;</p>
		  	{/if}
		  	<div id="details" class="items"></div>
		</fieldset>
		{include file='controls_doc_html.tpl' print_cmd='print_entry_adjustment' cancel_cmd='cancel_entry_adjustment'}
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('reason');
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails', 'add_product', 'save', 'oDeleteProductObj');
{else}
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails');
{/if}
oDetails.getLastPage();
</script>