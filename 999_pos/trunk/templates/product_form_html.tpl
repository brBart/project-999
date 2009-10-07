{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/product_suppliers.js"></script>
<script type="text/javascript" src="../scripts/add_supplier_product.js"></script>
<script type="text/javascript" src="../scripts/delete_detail.js"></script>
<script type="text/javascript" src="../scripts/delete_supplier_product.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oProductSuppliers = new ProductSuppliers(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	var oAddSupplierProduct = new AddSupplierProductCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oProductSuppliers);
	var oDeleteSupplierProduct = new DeleteSupplierProductCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oProductSuppliers);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	// For the delete key pressed. 
	oProductSuppliers.mDeleteFunction = function(sCmd){oDeleteSupplierProduct.execute(sCmd);}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_medium">
		{include file='status_bar_html.tpl'}
		<fieldset id="sub_menu">
			<p {if $status eq 0}class="invisible"{/if}>
				<a name="form_widget" href="#">Kardex</a>
				<a name="form_widget" href="#">Lotes</a>
				<a name="form_widget" href="#">Reservados</a>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<div>
				<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
			  	<p>
			  		<label for="name">Nombre:*</label>
			  		<input name="form_widget" id="name" type="text" value="{$name}" maxlength="100"
			  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="name-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="bar_code">C&oacute;digo barra:*</label>
			  		<input name="form_widget" id="bar_code" type="text" value="{$bar_code}" maxlength="100"
			  			onblur="oSetProperty.execute('set_bar_code_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="bar_code-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="packaging">Presentaci&oacute;n:*</label>
			  		<input name="form_widget" id="packaging" type="text" value="{$packaging}" maxlength="150"
			  			onblur="oSetProperty.execute('set_packaging_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="packaging-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="description">Descripci&oacute;n:</label>
			  		<textarea name="form_widget" id="description" rows="5" cols="30"
			  			onblur="oSetProperty.execute('set_description_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if}>{$description}</textarea>
			  		<span id="description-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="manufacturer_id">Casa:*</label>
			  		<select name="form_widget" id="manufacturer_id"
			  			onblur="oSetProperty.execute('set_manufacturer_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if}>
		    			{section name=i loop=$manufacturer_list}
		    				<option value="{$manufacturer_list[i].manufacturer_id}" 
		    					{if $manufacturer_list[i].manufacturer_id eq $manufacturer_id}selected="selected"{/if}>
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
		    				<option value="{$um_list[i].unit_of_measure_id}"
		    					{if $um_list[i].unit_of_measure_id eq $um_id}selected="selected"{/if}>
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
			  			{if $deactivated eq 1}checked="checked"{/if}
			  			onblur="oSetProperty.execute('deactivate_object', (this.checked ? 1 : 0), this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="deactivated-failed" class="hidden">*</span>
			  	</p>
			  	<p><label>Cantidad:</label><span>{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span>{$available}&nbsp;</span></p>
			</div>
			<div>
			  	<p id="prod_supp_tb">
			  		<label for="supplier_id">Proveedor:</label>
			  		<select name="form_widget" id="supplier_id" {if $status eq 1}disabled="disabled"{/if}>
		    			{section name=i loop=$supplier_list}
		    				<option value="{$supplier_list[i].supplier_id}">
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
			  	<div id="details"></div>
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
oProductSuppliers.init('../xsl/product_suppliers.xsl', 'details', 'oProductSuppliers', 'add_supplier', 'save', 'oDeleteSupplierProduct', 'delete_supplier_product');
oProductSuppliers.update();
</script>