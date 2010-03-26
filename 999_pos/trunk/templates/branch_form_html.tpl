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
			<p><label>C&oacute;digo:</label><span>{$id}&nbsp;</span></p>
		  	<p>
		  		<label for="name">Nombre:*</label>
		  		<input name="form_widget" id="name" type="text" value="{$name|htmlchars}" maxlength="100"
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
		  	<p>
		  		<label for="telephone">Telefono:*</label>
		  		<input name="form_widget" id="telephone" type="text" value="{$telephone|htmlchars}" maxlength="50"
		  			onblur="oSetProperty.execute('set_telephone_organization', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="telephone-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="address">Direcci&oacute;n:*</label>
		  		<input name="form_widget" id="address" type="text" value="{$address|htmlchars}" maxlength="150"
		  			onblur="oSetProperty.execute('set_address_organization', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="address-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="email">Email:</label>
		  		<input name="form_widget" id="email" type="text" value="{$email}" maxlength="100"
		  			onblur="oSetProperty.execute('set_email_organization', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="email-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="contact">Contacto:</label>
		  		<input name="form_widget" id="contact" type="text" value="{$contact|htmlchars}" maxlength="100"
		  			onblur="oSetProperty.execute('set_contact_object', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="contact-failed" class="hidden">*</span>
		  	</p>
		</fieldset>
		{if $status eq 1}
			{assign var='edit_cmd' value=$edit_cmd}
			{assign var='focus_on_edit' value='name'}
			{assign var='delete_cmd' value=$delete_cmd}
		{/if}
		{include file='controls_html.tpl'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('name');
</script>
{/if}