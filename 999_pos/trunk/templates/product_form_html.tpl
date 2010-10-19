{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_details.js"></script>
<script type="text/javascript" src="../scripts/add_supplier_product.js"></script>
<script type="text/javascript" src="../scripts/delete_detail.js"></script>
<script type="text/javascript" src="../scripts/delete_supplier_product.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oProductSuppliers = new ObjectDetails(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_suppliers');
	var oAddSupplierProduct = new AddSupplierProductCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oProductSuppliers);
	var oDeleteSupplierProduct = new DeleteSupplierProductCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oProductSuppliers, 'delete_supplier_product');
	// For the delete key pressed. 
	oProductSuppliers.mDeleteObj = oDeleteSupplierProduct;
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_medium">
		{include file='status_bar_html.tpl'}
		<fieldset id="sub_menu">
			<p {if $status eq 0}class="invisible"{/if}>
				<a name="form_widget" href="#" onclick="oKardexList.showForm();">Kardex</a>
				<a name="form_widget" href="#" onclick="oLotsList.showForm();">Lotes</a>
				<a name="form_widget" href="#" onclick="oReservesList.showForm();">Reservados</a>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<div id="product_details">
				<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
			  	<p>
			  		<label for="name">Nombre:*</label>
			  		<input name="form_widget" id="name" type="text" value="{$name|htmlchars}" maxlength="100"
			  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="name-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="bar_code">C&oacute;digo barra:*</label>
			  		<input name="form_widget" id="bar_code" type="text" value="{$bar_code|htmlchars}" maxlength="100"
			  			onblur="oSetProperty.execute('set_bar_code_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="bar_code-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="packaging">Presentaci&oacute;n:*</label>
			  		<input name="form_widget" id="packaging" type="text" value="{$packaging|htmlchars}" maxlength="150"
			  			onblur="oSetProperty.execute('set_packaging_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="packaging-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="description">Descripci&oacute;n:</label>
			  		<textarea name="form_widget" id="description" rows="5" cols="30"
			  			onblur="oSetProperty.execute('set_description_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if}>{$description|htmlchars}</textarea>
			  		<span id="description-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="manufacturer_id">Casa:*</label>
			  		<select name="form_widget" id="manufacturer_id"
			  			onblur="oSetProperty.execute('set_manufacturer_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if}>
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].id}" 
		    					{if $manufacturer_list[i].id eq $manufacturer_id}selected="selected"{/if}>
		    					{$manufacturer_list[i].name}
		    				</option>
		    			{/section}
		    		</select>
			  		<span id="manufacturer_id-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="um_id">Unidad de Medida:*</label>
			  		<select name="form_widget" id="um_id"
			  			onblur="oSetProperty.execute('set_unit_of_measure_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if}>
		    			{section name=i loop=$um_list}
		    				<option value="{$um_list[i].id}"
		    					{if $um_list[i].id eq $um_id}selected="selected"{/if}>
		    					{$um_list[i].name}
		    				</option>
		    			{/section}
		    		</select>
			  		<span id="um_id-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="price">Precio:*</label>
			  		<input name="form_widget" id="price" type="text" value="{$price}" maxlength="13"
			  			onblur="oSetProperty.execute('set_price_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="price-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="deactivated">Desactivado:</label>
			  		<input name="form_widget" id="deactivated" type="checkbox"
			  			onblur="oSetProperty.execute('deactivate_object', (this.checked ? 1 : 0), this.id);"
			  			{if $status eq 1}
			  				{if $deactivated eq 1}checked="checked"{/if}
			  				disabled="disabled"
			  			{/if} />
			  		<span id="deactivated-failed" class="hidden">*</span>
			  	</p>
			  	<p><label>Cantidad:</label><span id="quantity">{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span id="available">{$available}&nbsp;</span></p>
			</div>
			<div id="product_suppliers">
			  	<p id="prod_supp_tb">
			  		<label for="supplier_id">Proveedor:</label>
			  		<select name="form_widget" id="supplier_id" {if $status eq 1}disabled="disabled"{/if}>
		    			{section name=i loop=$supplier_list}
		    				<option value="{$supplier_list[i].id}">
		    					{$supplier_list[i].name}
		    				</option>
		    			{/section}
		    		</select>
		    		<span id="supplier_id-failed" class="hidden">*</span>
			  		<label for="product_sku">C&oacute;digo:</label>
			  		<input name="form_widget" id="product_sku" type="text" maxlength="50"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="product_sku-failed" class="hidden">*</span>
			  		<input name="form_widget" id="add_supplier" type="button" value="Agregar"
			  			onclick="oAddSupplierProduct.execute();" {if $status eq 1}disabled="disabled"{/if}  />
			  		<span id="product_suppliers-failed" class="hidden">*</span>
			  	</p>
			  	<div id="details" class="items"></div>
			</div>
		</fieldset>
		{include file='controls_html.tpl' edit_cmd='edit_product' focus_on_edit='name' delete_cmd='delete_product'}
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('name');
{/if}
oAddSupplierProduct.init('supplier_id', 'product_sku');
oEventDelegator.init();
oProductSuppliers.init('../xsl/product_suppliers.xsl', 'details', 'oProductSuppliers', 'add_supplier', 'save', 'oDeleteSupplierProduct');
oProductSuppliers.update();
</script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/get_product_balance.js"></script>
<script type="text/javascript">
var oProductBalance = new GetProductBalanceCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
oProductBalance.init('quantity', 'available');
</script>
<script type="text/javascript" src="../scripts/modal_form.js"></script>
<script type="text/javascript" src="../scripts/modal_page.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/kardex_page.js"></script>
<div id="kardex_container" class="hidden">
	<div class="list_form">
		<a class="close_window" href="#" onclick="oKardexList.hideForm();">Cerrar[X]</a>
		<div id="kardex_console" class="console_display"></div>
		<div id="kardex" class="items"></div>
	 </div>
</div>
<script type="text/javascript">
var oKardexFrm = new ModalForm('kardex_container');
var oKardexConsole = new Console('kardex_console');
var oKardex = new KardexPage(oSession, oKardexConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
oKardex.init('../xsl/kardex.xsl', 'kardex', 'oKardex');
var oKardexList = new ModalList(oKardex, oKardexFrm, oProductBalance);
</script>
<script type="text/javascript" src="../scripts/modal_list.js"></script>
<div id="lots_container" class="hidden">
	<div class="list_form">
		<a class="close_window" href="#" onclick="oLotsList.hideForm();">Cerrar[X]</a>
		<div id="lots_console" class="console_display"></div>
		<div id="lots" class="items"></div>
	 </div>
</div>
<script type="text/javascript">
var oLotsFrm = new ModalForm('lots_container');
var oLotsConsole = new Console('lots_console');
var oProductLots = new ObjectDetails(oSession, oLotsConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_lots');
oProductLots.init('../xsl/product_lots.xsl', 'lots', 'oProductLots');
var oLotsList = new ModalList(oProductLots, oLotsFrm, oProductBalance);
</script>
<div id="reserves_container" class="hidden">
	<div class="list_form">
		<a class="close_window" href="#" onclick="oReservesList.hideForm();">Cerrar[X]</a>
		<div id="reserves_console" class="console_display"></div>
		<div id="reserves" class="items"></div>
	 </div>
</div>
<script type="text/javascript">
var oReservesFrm = new ModalForm('reserves_container');
var oReservesConsole = new Console('reserves_console');
var oProductReserves = new ObjectDetails(oSession, oLotsConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_reserves');
oProductReserves.init('../xsl/product_reserves.xsl', 'reserves', 'oProductReserves');
var oReservesList = new ModalList(oProductReserves, oReservesFrm, oProductBalance);
</script>
{/if}