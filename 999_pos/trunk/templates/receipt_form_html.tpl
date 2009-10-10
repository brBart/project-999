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
<script type="text/javascript" src="../scripts/save.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript" src="../scripts/add_product_object.js"></script>
<script type="text/javascript" src="../scripts/add_product_entry.js"></script>
<script type="text/javascript" src="../scripts/delete_detail.js"></script>
<script type="text/javascript" src="../scripts/delete_product_object.js"></script>
<script type="text/javascript" src="../scripts/toolbar.js"></script>
<script type="text/javascript" src="../scripts/toolbar_text.js"></script>
<script type="text/javascript" src="../scripts/toolbar_date.js"></script>
<script type="text/javascript" src="../scripts/toolbar_barcode.js"></script>
<script type="text/javascript" src="../scripts/search_product.js"></script>
<script type="text/javascript" src="../scripts/search_product_details.js"></script>
<script type="text/javascript" src="../scripts/search_product_toolbar.js"></script>
<script type="text/javascript" src="../scripts/discard_document.js"></script>
{elseif $status eq 1}
<script type="text/javascript" src="../scripts/cancel_document.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oDetails = new DocumentPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{if $status eq 0}
	var oSetOrganization = new SetOrganizationCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oSave = new SaveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oQuantity = new ToolbarText();
	var oPrice = new ToolbarText();
	var oExpirationDate = new ToolbarDate();
	var oBarCode = new ToolbarBarCode();
	var oAddProductReceipt = new AddProductEntryCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oDetails);
	var oDeleteProductReceipt = new DeleteProductObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oDetails);
	var oSearchProduct = new SearchProduct(oSession, oConsole, Request.createXmlHttpRequestObject());
	var oSearchDetails = new SearchProductToolbar(oSession, oSearchProduct, oEventDelegator);
	var oDiscard = new DiscardDocumentCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{literal}
	// For the delete key pressed.
	oDetails.mDeleteFunction = function(sCmd){oDeleteProductReceipt.execute(sCmd);}
	{/literal}
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_large">
		<fieldset id="status_bar">
			<p>
				<label>Status:</label>
				<span id="status_label" {if $status eq 2}class="cancel_status"{/if}>
					{if $status eq 0}
						Creando...
					{elseif $status eq 1}
						Cerrado
					{else}
						Anulado
					{/if}
				</span>
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
		  	<div id="product_tb" class="large_tb">
		  		<div>
			  		<label for="quantity">Cantidad:</label>
			  		<input name="form_widget" id="quantity" type="text" class="tb_input" maxlength="11" />
			  		<span id="quantity-failed" class="hidden">*</span>
			  		<label for="price">Precio:</label>
			  		<input name="form_widget" id="price" type="text" class="tb_input" maxlength="13" />
			  		<span id="price-failed" class="hidden">*</span>
			  		<label for="expiration_date">Vence:</label>
			  		<input name="form_widget" id="expiration_date" type="text" class="tb_input" maxlength="10" />
			  		<span id="expiration_date-failed" class="hidden">*</span>
			  		<label for="bar_code">Barra:</label>
			  		<input name="form_widget" id="bar_code" type="text" maxlength="100" />
			  		<span id="bar_code-failed" class="hidden">*</span>
			  		<div id="search_product">
				    	<label for="product_name">Buscar:</label>
				    	<div>
				    		<input name="product_name" id="product_name" type="text" maxlength="100" />
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
		  	{else}
		  		{* Because Firefox css rule margin-top on table rule bug. *}
		  		<p>&nbsp;</p>
		  	{/if}
		  	<div id="details"></div>
		</fieldset>
		<fieldset id="controls">
			{if $status eq 0}
		  	<input name="form_widget" id="save" type="button" value="Guardar"
		  			onclick="if(confirm('Una vez guardado el documento no se podra editar mas. &iquest;Desea guardar?')) oSave.execute('{$foward_link}');" />
		  	<input name="form_widget" id="undo" type="button" value="Cancelar"
		  			onclick="oDiscard.execute('{$back_link}');" />
		  	{else}
		  	<input name="form_widget" id="cancel" type="button" value="Anular"
		  			onclick="oCancel.showForm();" {if $status eq 2}disabled="disabled"{/if} />
		  	<input name="form_widget" id="print" type="button" value="Imprimir"
		  			onclick="window.open('index.php?cmd=print_receipt&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		  	{/if}
		</fieldset>
		{if $status eq 1}
		<div id="authenticate_form" class="hidden">
			<div>
				<fieldset>
					<legend>Autorizaci&oacute;n</legend>
				  	<p>
						<label for="username">Usuario:</label>
						<input name="username" id="username" type="text" maxlength="20" />
				  	</p>
				  	<p>
						<label for="password">Contrase&ntilde;a:</label>
						<input name="password" id="password" type="password" maxlength="20" />
				  	</p>
				  	<div id="mini_console"></div>
				  	<p>
						<input id="authenticate" type="button" value="Aceptar" onclick="oCancel.execute('cancel_receipt');" />
						<input id="return" type="button" value="Cancelar" onclick="oCancel.hideForm();" />
				  	</p>
				 </fieldset>
			 </div>
		</div>
		{/if}
	</div>
</div>
<script type="text/javascript">
oEventDelegator.init();
{if $status eq 0}
StateMachine.setFocus('organization_id');
Toolbar.checkResolution('product_tb');
oQuantity.init('quantity', 'price');
oPrice.init('price', 'expiration_date');
oExpirationDate.init('expiration_date', 'bar_code');
oBarCode.init('bar_code', 'add_product');
oSearchDetails.init('product_name', 'oSearchDetails', 'bar_code');
oAddProductReceipt.init('bar_code', 'quantity', 'product_name', 'price', 'expiration_date');
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails', 'add_product', 'save', 'oDeleteProductReceipt', 'delete_product_receipt');
{else}
oDetails.init('../xsl/document_page.xsl', 'details', 'oDetails');
{/if}
oDetails.getLastPage();
{if $status eq 1}
var miniConsole = new Console('mini_console');
var oCancel = new CancelDocumentCommand(oSession, miniConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine);
oCancel.init('authenticate_form', 'username', 'password');
{/if}
</script>