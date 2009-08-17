{* Smarty *}
{* status = 0 Edit, status = 1 Consult *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/sync.js"></script>
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/state_machine.js"></script>
<script type="text/javascript" src="../scripts/async.js"></script>
<script type="text/javascript" src="../scripts/remove_session_object.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/product_suppliers.js"></script>
<script type="text/javascript" src="../scripts/add_supplier_product.js"></script>
{if $status eq 1}
<script type="text/javascript" src="../scripts/edit.js"></script>
<script type="text/javascript" src="../scripts/delete.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console();
	var oMachine = new StateMachine({$status});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oProductSuppliers = new ProductSuppliers(oSession, oConsole, createXmlHttpRequestObject(), {$key}, oMachine);
	var oAddSupplierProduct = new AddSupplierProductCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key}, oProductSuppliers);
	{if $status eq 1}
	var oEdit = new EditCommand(oSession, oConsole, createXmlHttpRequestObject(), oMachine);
	var oDelete = new DeleteCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_medium">
		<fieldset id="status_bar">
			<p>
				<label>Status:</label>
				<span id="status_label">
					{if $status eq 0}
						Creando...
					{else}
						Consulta
					{/if}
				</span>
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
			  		<input name="form_widget" id="price" type="text" value="{$price}" maxlength="15"
			  			onblur="oSetProperty.execute('set_price_product', this.value, this.id);"
			  			{if $status eq 1}disabled="disabled"{/if} />
			  		<span id="price-failed" class="hidden">*</span>
			  	</p>
			  	<p>
			  		<label for="deactivated">Desactivado:</label>
			  		<input name="form_widget" id="deactivated" type="checkbox"
			  			{if $deactivated eq 1}checked="checked"{/if}
			  			onblur="oSetProperty.execute('deactivate_object', this.checked, this.id);"
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
			  		<input name="form_widget" id="product_sku" type="text" {if $status eq 1}disabled="disabled"{/if} />
			  		<span id="product_sku-failed" class="hidden">*</span>
			  		<input name="form_widget" id="add_supplier" type="button" value="Agregar"
			  			onclick="oAddSupplierProduct.execute(document.getElementById('supplier_id'), document.getElementById('product_sku'));"
			  			{if $status eq 1}disabled="disabled"{/if}  />
			  		<span id="product_suppliers-failed" class="hidden">*</span>
			  	</p>
			  	<div id="details"></div>
			</div>
		</fieldset>
		<fieldset id="controls">
		  	<input name="form_widget" id="save" type="button" value="Guardar"
		  		onclick="oSave.execute('{$foward_link}');" {if $status eq 1}disabled="disabled"{/if}  />
		  	<input name="form_widget" id="edit" type="button" value="Editar"
		  		{if $status eq 1}
		  			onclick="oEdit.execute('edit_manufacturer', 'name');"
		  		{else}
		  			disabled="disabled"
		  		{/if} />
		  	<input name="form_widget" id="delete" type="button" value="Eliminar"
		  		{if $status eq 1}
		  			onclick="if(confirm('¿Esta seguro que desea eliminar?')) oDelete.execute('delete_manufacturer', '{$back_link}');"
		  		{else}
		  			disabled="disabled"
		  		{/if} />
		  	<input name="form_widget" id="cancel" type="button" value="Cancelar"
		  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$foward_link}{/if}');"
		  			{if $status eq 1}disabled="disabled"{/if} />
		</fieldset>
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
oProductSuppliers.init('../xsl/product_suppliers.xsl', 'details');
oProductSuppliers.update();
</script>
{/if}