{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_details.js"></script>
<script type="text/javascript" src="../scripts/create_bonus.js"></script>
<script type="text/javascript" src="../scripts/alter_object.js"></script>
<script type="text/javascript" src="../scripts/delete_bonus.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine(0);
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	var oProductBonus = new ObjectDetails(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator, 'get_product_bonus');
	var oCreateBonus = new CreateBonusCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oProductBonus);
	var oDeleteBonus = new DeleteBonusCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, 'delete_bonus', oProductBonus);
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
			  	<p><label>Cantidad:</label><span>{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span>{$available}&nbsp;</span></p>
			</div>
			<div id="bonus">
			  	<div id="bonus_tb">
			  		<label for="quantity">Cantidad:</label>
			  		<input name="form_widget" id="quantity" type="text" maxlength="11" />
			  		<span id="quantity-failed" class="hidden">*</span>
			  		<label for="percentage">Descuento(%):</label>
			  		<input name="form_widget" id="percentage" type="text" maxlength="8" />
			  		<span id="percentage-failed" class="hidden">*</span>
			  		<label for="expiration_date">Vence:</label>
			  		<div>
			  			<input name="form_widget" id="expiration_date" type="text" maxlength="10" />
			  			<span class="hint">dd/mm/aaaa</span>
			  		</div>
			  		<span id="expiration_date-failed" class="hidden">*</span>
			  		<input name="form_widget" id="add_bonus" type="button" value="Crear Oferta"
			  			onclick="oCreateBonus.execute();" />
			  		<span id="bonus-failed" class="hidden">*</span>
			  	</div>
			  	<div id="details" class="items"></div>
			</div>
		</fieldset>
	</div>
</div>
<script type="text/javascript">
StateMachine.setFocus('quantity');
oCreateBonus.init('quantity', 'percentage', 'expiration_date');
oEventDelegator.init();
oProductBonus.init('../xsl/product_bonus.xsl', 'details', 'oProductBonus', null, null, 'oDeleteBonus', null);
oProductBonus.update();
</script>