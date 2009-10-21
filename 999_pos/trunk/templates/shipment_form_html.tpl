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
	var oSetOrganization = new SetOrganizationCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
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
		{include file='header_data_html.tpl' document_name='Recibo'}
		<fieldset id="main_data">
			<p>
		  		<label for="organization_id">Proveedor:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<select name="form_widget" id="organization_id"
		  			onblur="oSetOrganization.execute('set_supplier_document', this.value, this.id);">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].id}">
	    					{$supplier_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="organization_id-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$supplier}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="shipment_number">Env&iacute;o No:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="shipment_number" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_shipment_number_receipt', this.value, this.id);" />
		  		<span id="shipment_number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$shipment_number}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="shipment_total">Total env&iacute;o:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="shipment_total" type="text" maxlength="13"
		  			onblur="oSetProperty.execute('set_shipment_total_receipt', this.value, this.id);" />
		  		<span id="shipment_total-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$shipment_total}</span>
		  		{/if}
		  	</p>
		  	{if $status eq 0}
		  		{include file='entry_toolbar_html.tpl' details_obj='oDetails' add_cmd='add_product_entry_document' event_delegator_obj='oEventDelegator'}
		  	{else}
		  		{* Because Firefox css rule margin-top on table rule bug. *}
		  		<p>&nbsp;</p>
		  	{/if}
		  	<div id="details"></div>
		</fieldset>
		{include file='controls_doc_html.tpl' print_cmd='print_receipt' cancel_cmd='cancel_receipt'}
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('organization_id');
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails', 'add_product', 'save', 'oDeleteProductObj');
{else}
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails');
{/if}
oDetails.getLastPage();
</script>