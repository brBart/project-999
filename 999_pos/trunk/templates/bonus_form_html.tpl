{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_medium">
		<fieldset id="main_data">
			<div>
				<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
			  	<p><label>Nombre:</label><span>{$name}</span></p>
			  	<p><label>C&oacute;digo barra:</label><span>{$bar_code}</span></p>
			  	<p><label>Presentaci&oacute;n:</label><span>{$packaging}</span></p>
			  	<p><label>Precio:</label><span>{$price}</span></p>
			  	<p><label>Cantidad:</label><span>{$quantity}&nbsp;</span></p>
			  	<p><label>Disponible:</label><span>{$available}&nbsp;</span></p>
			</div>
			<div>
			  	<p id="bonus_tb">
			  		<label for="quantity">Cantidad:</label>
			  		<input name="form_widget" id="quantity" type="text" maxlength="11" />
			  		<span id="quantity-failed" class="hidden">*</span>
			  		<label for="percentage">Descuento(%):</label>
			  		<input name="form_widget" id="percentage" type="text" maxlength="8" />
			  		<span id="percentage-failed" class="hidden">*</span>
			  		<label for="expiration_date">Vence:</label>
			  		<input name="form_widget" id="expiration_date" type="text" maxlength="10" />
			  		<span id="expiration_date-failed" class="hidden">*</span>
			  		<input name="form_widget" id="add_bonus" type="button" value="Crear Oferta"
			  			onclick="oCreateBonus.execute();" />
			  	</p>
			  	<div id="details"></div>
			</div>
		</fieldset>
	</div>
</div>
<script type="text/javascript">
StateMachine.setFocus('quantity');
</script>