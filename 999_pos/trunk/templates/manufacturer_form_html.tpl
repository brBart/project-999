{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_small_menu">
		{include file='status_bar_html.tpl'}
		<fieldset id="sub_menu">
			<p {if $status eq 0}class="invisible"{/if}>
				<a name="form_widget" href="#" onclick="oProductList.showForm();">Productos</a>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
		  	<p><label for="name">Nombre:*</label><input name="form_widget" id="name" type="text"
		  			value="{$name}" maxlength="100"
		  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  	<span id="name-failed" class="hidden">*</span></p>
		</fieldset>
		{if $status eq 1}
			{assign var='edit_cmd' value=$edit_cmd}
			{assign var='focus_on_edit' value='name'}
			{assign var='delete_cmd' value=$delete_cmd}
		{/if}
		{include file='controls_html.tpl'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{else}
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/modal_form.js"></script>
<script type="text/javascript" src="../scripts/modal_list.js"></script>
<div id="products_container" class="hidden">
	<div class="list_form">
		<a class="close_window" href="#" onclick="oProductList.hideForm();">Cerrar[X]</a>
		<div id="products_console" class="console_display"></div>
		<div id="products" class="items"></div>
	 </div>
</div>
<script type="text/javascript">
var oEventDelegator = new EventDelegator();
var oProductsFrm = new ModalForm('products_container');
var oProductsConsole = new Console('products_console');
var oManufacturerProducts = new ObjectDetails(oSession, oProductsConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_manufacturer_products');
oManufacturerProducts.init('../xsl/product_list.xsl', 'products', 'oManufacturerProducts');
var oProductList = new ModalList(oManufacturerProducts, oProductsFrm);
</script>
{/if}