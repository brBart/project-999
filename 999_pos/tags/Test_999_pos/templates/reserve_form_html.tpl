{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_details.js"></script>
<script type="text/javascript" src="../scripts/get_product_balance.js"></script>
<script type="text/javascript" src="../scripts/alter_object.js"></script>
<script type="text/javascript" src="../scripts/delete_reserve.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine(0);
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oProductReserves = new ObjectDetails(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_reserves');
	var oProductBalance = new GetProductBalanceCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oDeleteReserve = new DeleteReserveCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, 'delete_reserve', oProductReserves, oProductBalance);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_medium">
		<fieldset id="main_data">
			<div id="product_info">
				<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
			  	<p><label>Nombre:</label><span>{$name|escape}</span></p>
			  	<p><label>C&oacute;digo barra:</label><span>{$bar_code|escape}</span></p>
			  	<p><label>Presentaci&oacute;n:</label><span>{$packaging|escape}</span></p>
			  	<p><label>Precio:</label><span>{$price}</span></p>
			  	<p><label>Cantidad:</label><span id="quantity">{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span id="available">{$available}&nbsp;</span></p>
			</div>
			<div id="reserve_items">
			  	<div id="details" class="items"></div>
			</div>
		</fieldset>
	</div>
</div>
<script type="text/javascript">
oEventDelegator.init();
oProductBalance.init('quantity', 'available');
oProductReserves.init('../xsl/product_reserves.xsl', 'details', 'oProductReserves', null, null, 'oDeleteReserve', null);
oProductReserves.update();
</script>