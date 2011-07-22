{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/document_page.js"></script>
{if $status eq 0}
<script type="text/javascript" src="../scripts/set_organization.js"></script>
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
	var oSetOrganization = new SetOrganizationCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, 'set_supplier_purchase_return');
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oDeleteProductObj = new DeleteProductObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oDetails, 'delete_product_withdraw_document');
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
		{include file='header_data_html.tpl' document_name='Devoluci&oacute;n'}
		<fieldset id="main_data">
			<p>
		  		<label for="supplier_id">Proveedor:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<select name="form_widget" id="supplier_id"
		  			onchange="oSetOrganization.execute(this.value, this.id);">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].id}">
	    					{$supplier_list[i].name|escape}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="supplier_id-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$supplier|escape}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="contact">Contacto:</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="contact" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_contact_object', this.value, this.id);" />
		  		<span id="contact-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$contact|escape}&nbsp;</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="reason">Motivo:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="reason" type="text" maxlength="100"
		  			onblur="oSetProperty.execute('set_reason_object', this.value, this.id);" />
		  		<span id="reason-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$reason|escape}</span>
		  		{/if}
		  	</p>
		  	{if $status eq 0}
		  		{include file='withdraw_toolbar_html.tpl' details_obj='oDetails' add_cmd='add_product_withdraw_document' event_delegator_obj='oEventDelegator'}
		  	{else}
		  		{* Because Firefox css rule margin-top on table rule bug. *}
		  		<p>&nbsp;</p>
		  	{/if}
		  	<div id="details" class="items"></div>
		</fieldset>
		{include file='controls_doc_html.tpl' print_cmd='print_purchase_return' cancel_cmd='cancel_purchase_return'}
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('supplier_id');
oSetOrganization.init('contact');
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails', 'add_product', 'save', 'oDeleteProductObj');
{else}
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails');
{/if}
oDetails.getLastPage();
</script>