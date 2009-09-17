{* Smarty *}
<script type="text/javascript" src="../scripts/console.js"></script>
<script type="text/javascript" src="../scripts/url.js"></script>
<script type="text/javascript" src="../scripts/http_request.js"></script>
<script type="text/javascript" src="../scripts/command.js"></script>
<script type="text/javascript" src="../scripts/async.js"></script>
<script type="text/javascript" src="../scripts/set_organization.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/sync.js"></script>
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/state_machine.js"></script>
<script type="text/javascript" src="../scripts/remove_session_object.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/document_page.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/add_product_object.js"></script>
<script type="text/javascript" src="../scripts/add_product_entry.js"></script>
<script type="text/javascript" src="../scripts/get_property.js"></script>
<script type="text/javascript" src="../scripts/get_amount.js"></script>
<script type="text/javascript" src="../scripts/delete_detail.js"></script>
<script type="text/javascript" src="../scripts/delete_product_object.js"></script>
<script type="text/javascript">
	var oConsole = new Console();
	var oMachine = new StateMachine(0);
	var oSetOrganization = new SetOrganizationCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oDetails = new DocumentPage(oSession, oConsole, createXmlHttpRequestObject(), {$key}, oMachine);
	var oGetTotal = new GetAmountCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key});
	var oAddProductReceipt = new AddProductEntryCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key}, oDetails, oGetTotal);
	var oDeleteProductReceipt = new DeleteProductObjectCommand(oSession, oConsole, createXmlHttpRequestObject(), {$key}, oDetails, oGetTotal);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	// For the delete key pressed.
	oDetails.mDeleteFunction = function(sCmd){oDeleteProductReceipt.execute(sCmd);}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="status_bar">
			<p>
				<label>Status:</label><span id="status_label">Creando...</span>
			</p>
		</fieldset>
		<fieldset id="header_data">
			<p>
				<label>Recibo No:</label><span>{$id}&nbsp;</span>
			</p>
			<p>
				<label>Fecha:</label><span>{$date_time}</span>
			</p>
			<p>
				<label>Usuario:</label><span>{$username}</span>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<p>
		  		<label for="organization_id">Proveedor:*</label>
		  		<select name="form_widget" id="organization_id"
		  			onblur="oSetOrganization.execute('set_supplier_document', this.value, this.id);">
	    			{section name=i loop=$supplier_list}
	    				<option value="{$supplier_list[i].supplier_id}">
	    					{$supplier_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="organization_id-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="shipment_number">Env&iacute;o No:*</label>
		  		<input name="form_widget" id="shipment_number" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_shipment_number_receipt', this.value, this.id);" />
		  		<span id="shipment_number-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="shipment_total">Total env&iacute;o:*</label>
		  		<input name="form_widget" id="shipment_total" type="text" maxlength="13"
		  			onblur="oSetProperty.execute('set_shipment_total_receipt', this.value, this.id);" />
		  		<span id="shipment_total-failed" class="hidden">*</span>
		  	</p>
		  	<div id="product_tb" class="large_tb">
		  		<div>
			  		<label for="quantity">Cantidad:</label>
			  		<input name="form_widget" id="quantity" type="text" maxlength="11" />
			  		<span id="quantity-failed" class="hidden">*</span>
			  		<label for="price">Precio:</label>
			  		<input name="form_widget" id="price" type="text" maxlength="13" />
			  		<span id="price-failed" class="hidden">*</span>
			  		<label for="expiration_date">Vence:</label>
			  		<input name="form_widget" id="expiration_date" type="text" maxlength="10" />
			  		<span id="expiration_date-failed" class="hidden">*</span>
			  		<label for="bar_code">Barra:</label>
			  		<input name="form_widget" id="bar_code" type="text" maxlength="100" />
			  		<span id="bar_code-failed" class="hidden">*</span>
			  		<div id="search_product">
				    	<label for="product_name">Buscar:</label>
				    	<div>
				    		<input name="product_name" id="product_name" type="text"/>
				    		<div>
				    			<div id="scroll"></div>
				    		</div>
				    	</div>
			    	</div>
			  		<input name="form_widget" id="add_product" type="button" value="Agregar"
			  			onclick="oAddProductReceipt.execute('add_product_receipt');" />
			  		<span id="receipt_product-failed" class="hidden">*</span>
		  		</div>
		  	</div>
		  	<div id="details"></div>
		  	<p>Total: <span id="total"></span></p>
		</fieldset>
		<fieldset id="controls">
		  	<input name="form_widget" id="save" type="button" value="Guardar"
		  			onclick="oSave.execute('{$foward_link}');" />
		  	<input name="form_widget" id="cancel" type="button" value="Cancelar"
		  			onclick="oSession.loadHref('{if $status eq 0}{$back_link}{else}{$foward_link}{/if}');" />
		</fieldset>
	</div>
</div>
<script type="text/javascript">
StateMachine.setFocus('organization_id');
oAddProductReceipt.init('bar_code', 'quantity', 'product_name', 'price', 'expiration_date');
oGetTotal.init('get_object_total', 'total');
oDetails.init('../xsl/document_page.xsl', 'details', 'add_product', 'save', 'oDetails', 'oDeleteProductReceipt', 'delete_product_receipt');
oDetails.getLastPage();
</script>