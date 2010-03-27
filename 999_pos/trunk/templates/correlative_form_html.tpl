{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
{if $status eq 0}
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{if $status eq 0}
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{/if}
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_small">
		{include file='status_bar_html.tpl'}
		<fieldset id="main_data">
			<p>
		  		<label for="serial_number">Serie No:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="serial_number" type="text" maxlength="10"
		  			onblur="oSetProperty.execute('set_serial_number_correlative', this.value, this.id);" />
		  		<span id="serial_number-failed" class="hidden">*</span>
		  		{else}
		  		<span id="serial_number">{$serial_number|htmlchars}{if $is_default eq 1} (Predeterminado){/if}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="resolution_number">Resoluci&oacute;n No:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="resolution_number" type="text" maxlength="100"
		  			onblur="oSetProperty.execute('set_resolution_number_correlative', this.value, this.id);" />
		  		<span id="resolution_number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$resolution_number|htmlchars}</span>
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
		  		<label for="initial_number">No. Inicial:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="initial_number" type="text" maxlength="20"
		  			onblur="oSetProperty.execute('set_initial_number_correlative', this.value, this.id);" />
		  		<span id="initial_number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$initial_number}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="final_number">No. Final:{if $status eq 0}*{/if}</label>
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
</script>
{/if}