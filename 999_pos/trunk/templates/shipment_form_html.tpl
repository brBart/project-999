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
	var oSetOrganization = new SetOrganizationCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, 'set_branch_shipment');
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
		{include file='header_data_html.tpl' document_name='Envio'}
		<fieldset id="main_data">
			<p>
		  		<label for="branch_id">Sucursal:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<select name="form_widget" id="branch_id"
		  			onchange="oSetOrganization.execute(this.value, this.id);">
	    			{section name=i loop=$branch_list}
	    				<option value="{$branch_list[i].id}">
	    					{$branch_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="branch_id-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$branch|htmlchars}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="contact">Contacto:</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="contact" type="text" maxlength="100"
		  			onblur="oSetProperty.execute('set_contact_object', this.value, this.id);" />
		  		<span id="contact-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$contact|htmlchars}&nbsp;</span>
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
		{include file='controls_doc_html.tpl' print_cmd='print_shipment' cancel_cmd='cancel_shipment'}
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('branch_id');
oSetOrganization.init('contact');
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails', 'add_product', 'save', 'oDeleteProductObj');
{else}
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails');
{/if}
oDetails.getLastPage();
</script>