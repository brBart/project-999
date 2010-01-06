{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_details.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine(0);
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oProductReserves = new ObjectDetails(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_reserves');
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
			  	<p><label>Nombre:</label><span>{$name}</span></p>
			  	<p><label>C&oacute;digo barra:</label><span>{$bar_code}</span></p>
			  	<p><label>Presentaci&oacute;n:</label><span>{$packaging}</span></p>
			  	<p><label>Precio:</label><span>{$price}</span></p>
			  	<p><label>Cantidad:</label><span>{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span>{$available}&nbsp;</span></p>
			</div>
			<div id="reserves">
			  	<div id="details" class="items"></div>
			</div>
		</fieldset>
	</div>
</div>
<script type="text/javascript">
oEventDelegator.init();
oProductReserves.init('../xsl/product_reserves.xsl', 'details', 'oProductReserves');
oProductReserves.update();
</script>