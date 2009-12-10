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
		{include file='status_bar_html.tpl'}
		<fieldset id="main_data">
		  	<p>
		  		<label for="name">Nombre:*</label><input name="form_widget" id="name" type="text"
		  			value="{$name}" maxlength="100"
		  			onblur="oSetProperty.execute('set_name_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="name-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="nit">Nit:*</label>
		  		<input name="form_widget" id="nit" type="text" value="{$nit}" maxlength="15"
		  			onblur="oSetProperty.execute('set_nit_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="nit-failed" class="hidden">*</span>
		  	</p>
		</fieldset>
		{if $status eq 1}
			{assign var='edit_cmd' value=$edit_cmd}
			{assign var='focus_on_edit' value='name'}
		{/if}
		{include file='unique_controls_html.tpl'}
	</div>
</div>