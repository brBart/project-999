{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine('1');
	var oSetProperty = new SetPropertyCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
</script>
<div id="content">
	<div id="frm" class="content_small">
		{include file='status_bar_html.tpl' status='1'}
		<fieldset id="main_data">
		  	<p>
		  		<label for="percentage">Porcentaje(%):*</label>
		  		<input name="form_widget" id="percentage" type="text"
		  			value="{$percentage}" maxlength="5"
		  			onblur="oSetProperty.execute('set_percentage_object', this.value, this.id);"
		  			disabled="disabled" />
		  		<span id="percentage-failed" class="hidden">*</span>
		  	</p>
		</fieldset>
		{include file='unique_object_controls_html.tpl' edit_cmd=$edit_cmd focus_on_edit='percentage'}
	</div>
</div>