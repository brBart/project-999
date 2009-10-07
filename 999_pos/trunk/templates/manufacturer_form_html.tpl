{* Smarty *}
{* status = 0 Edit, status = 1 Idle *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/set_property.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine({$status});
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
		<fieldset id="status_bar">
			<p>
				<label>Status:</label>
				<span id="status_label">
					{if $status eq 0}
						Creando...
					{else}
						Consulta
					{/if}
				</span>
			</p>
		</fieldset>
		<fieldset id="main_data">
			<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
		  	<p><label for="name">Nombre:*</label><input name="form_widget" id="name" type="text"
		  			value="{$name}" maxlength="100"
		  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  	<span id="name-failed" class="hidden">*</span></p>
		</fieldset>
		{include file='controls_html.tpl' edit_cmd='edit_manufacturer' focus_on_edit='name' delete_cmd='delete_manufacturer'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}