{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
{if $status eq 0}
<script type="text/javascript" src="../scripts/set_serial_number.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{if $status eq 0}
	var oSetSerialNumber = new SetSerialNumberCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_fit">
		<fieldset id="status_bar">
			<p>
				<label>Status:</label>
				<span id="status_label" {if $status eq 2 or $status eq 4}class="cancel_status"{/if}>
					{if $status eq 0}
						Creando...
					{elseif $status eq 1}
						Inactivo
					{elseif $status eq 2}
						Vencido
					{elseif $status eq 3}
						Activo
					{else}
						Agotado
					{/if}
				</span>
			</p>
		</fieldset>
		<fieldset id="main_data">
		  	<p>
		  		<label>Fecha Ingreso:</label>
		  		<span>{$created_date}&nbsp;</span>
		  	</p>
			<p>
		  		<label for="serial_number">Serie No:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="serial_number" type="text" maxlength="10"
		  			onblur="oSetSerialNumber.execute(this.value, this.id);" />
		  		<span id="serial_number-failed" class="hidden">*</span>
		  		{else}
		  		<span id="serial_number">{$serial_number|escape}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="resolution_number">Resoluci&oacute;n No:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="resolution_number" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_resolution_number_correlative', this.value, this.id);" />
		  		<span id="resolution_number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$resolution_number|escape}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="resolution_date">Fecha Resoluci&oacute;n:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="resolution_date" type="text" maxlength="10"
		  			onblur="oSetProperty.execute('set_resolution_date_correlative', this.value, this.id);" />
		  		<span id="resolution_date-failed" class="hidden">*</span>
		  		<span class="hint">dd/mm/aaaa</span>
		  		{else}
		  		<span>{$resolution_date}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="regime">R&eacute;gimen:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="regime" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_regime_correlative', this.value, this.id);" />
		  		<span id="regime-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$regime|escape}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label>No. Inicial (Del):</label>
		  		<span id="initial_number">{$initial_number}&nbsp;</span>
		  	</p>
		  	<p>
		  		<label for="final_number">No. Final (Al):{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="final_number" type="text" maxlength="20"
		  			onblur="oSetProperty.execute('set_final_number_correlative', this.value, this.id);" />
		  		<span id="final_number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$final_number}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label>No. Actual:</label>
		  		<span>{$actual_number}</span>
		  	</p>
		</fieldset>
		{include file='correlative_controls_html.tpl' serial_number_span='serial_number'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('serial_number');
oSetSerialNumber.init('initial_number');
</script>
{/if}