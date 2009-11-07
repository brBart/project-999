{* Smarty *}
<script type="text/javascript" src="../scripts/add_product_object.js"></script>
<script type="text/javascript" src="../scripts/add_product_withdraw.js"></script>
<script type="text/javascript" src="../scripts/toolbar.js"></script>
<script type="text/javascript" src="../scripts/toolbar_text.js"></script>
<script type="text/javascript" src="../scripts/toolbar_barcode.js"></script>
<script type="text/javascript" src="../scripts/search_product.js"></script>
<script type="text/javascript" src="../scripts/search_product_details.js"></script>
<script type="text/javascript" src="../scripts/search_product_toolbar.js"></script>
<script type="text/javascript">
var oQuantity = new ToolbarText();
var oBarCode = new ToolbarBarCode();
var oAddProductObj = new AddProductWithdrawCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, {$details_obj}, 'add_product_count');
var oSearchProduct = new SearchProduct(oSession, oConsole, Request.createXmlHttpRequestObject());
var oSearchDetails = new SearchProductToolbar(oSession, oSearchProduct, {$event_delegator_obj});
</script>
<div id="product_tb" class="small_tb">
 	<div>
 		<label for="quantity">Cantidad:</label>
  		<input name="form_widget" id="quantity" type="text" class="tb_input" value="1" maxlength="11" />
  		<span id="quantity-failed" class="hidden">*</span>
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
  			onclick="oAddProductObj.execute();" />
		<span id="receipt_product-failed" class="hidden">*</span>
		<input name="form_widget" id="upload_file" type="button" value="Subir archivo..." />
	</div>
</div>
<script type="text/javascript">
Toolbar.checkResolution('product_tb');
oQuantity.init('quantity', 'bar_code');
oBarCode.init('bar_code', 'add_product');
oSearchDetails.init('product_name', 'oSearchDetails', 'bar_code');
oAddProductObj.init('bar_code', 'quantity', 'product_name');
</script>