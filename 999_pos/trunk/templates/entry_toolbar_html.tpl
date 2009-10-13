{* Smarty *}
<script type="text/javascript" src="../scripts/add_product_object.js"></script>
<script type="text/javascript" src="../scripts/add_product_entry.js"></script>
<script type="text/javascript" src="../scripts/toolbar.js"></script>
<script type="text/javascript" src="../scripts/toolbar_text.js"></script>
<script type="text/javascript" src="../scripts/toolbar_date.js"></script>
<script type="text/javascript" src="../scripts/toolbar_barcode.js"></script>
<script type="text/javascript" src="../scripts/search_product.js"></script>
<script type="text/javascript" src="../scripts/search_product_details.js"></script>
<script type="text/javascript" src="../scripts/search_product_toolbar.js"></script>
<script type="text/javascript">
var oQuantity = new ToolbarText();
var oPrice = new ToolbarText();
var oExpirationDate = new ToolbarDate();
var oBarCode = new ToolbarBarCode();
var oAddProductObj = new AddProductEntryCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, {$details_obj});
var oSearchProduct = new SearchProduct(oSession, oConsole, Request.createXmlHttpRequestObject());
var oSearchDetails = new SearchProductToolbar(oSession, oSearchProduct, {$event_delegator_obj});
</script>
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
<script type="text/javascript">
Toolbar.checkResolution('product_tb');
oQuantity.init('quantity', 'price');
oPrice.init('price', 'expiration_date');
oExpirationDate.init('expiration_date', 'bar_code');
oBarCode.init('bar_code', 'add_product');
oSearchDetails.init('product_name', 'oSearchDetails', 'bar_code');
oAddProductObj.init('bar_code', 'quantity', 'product_name', 'price', 'expiration_date');
</script>