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
		  		<label for="username">Cuenta:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="username" type="text" maxlength="50"
		  			onblur="oSetProperty.execute('set_username_user_account', this.value, this.id);" />
		  		<span id="username-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$username}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="role_id">Rol:*</label>
		  		<select name="form_widget" id="role_id"
		  			onblur="oSetProperty.execute('set_role_user_account', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if}>
	    			{section name=i loop=$role_list}
	    				<option value="{$role_list[i].id}" 
	    					{if $role_list[i].id eq $role_id}selected="selected"{/if}>
	    					{$role_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="role_id-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="first_name">Nombres:*</label>
		  		<input name="form_widget" id="first_name" type="text" value="{$first_name}" maxlength="50"
		  			onblur="oSetProperty.execute('set_first_name_user_account', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="first_name-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="last_name">Apellidos:*</label>
		  		<input name="form_widget" id="last_name" type="text" value="{$last_name}" maxlength="50"
		  			onblur="oSetProperty.execute('set_last_name_user_account', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="last_name-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="password">Contrase&ntilde;a:*</label>
		  		<input name="form_widget" id="password" type="text" maxlength="20"
		  			onblur="oSetProperty.execute('set_password_user_account', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="password-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="confirm_password">Confirmar:*</label>
		  		<input name="form_widget" id="confirm_password" type="text" maxlength="20"
		  			{if $status eq 1}disabled="disabled"{/if} />
		  		<span id="confirm_password-failed" class="hidden">*</span>
		  	</p>
		  	<p>
		  		<label for="deactivated">Desactivado:</label>
		  		<input name="form_widget" id="deactivated" type="checkbox"
		  			onblur="oSetProperty.execute('deactivate_object', (this.checked ? 1 : 0), this.id);"
		  			{if $status eq 1}
		  				{if $deactivated eq 1}checked="checked"{/if}
		  				disabled="disabled"
		  			{/if} />
		  		<span id="deactivated-failed" class="hidden">*</span>
		  	</p>
		</fieldset>
		{if $status eq 1}
			{assign var='edit_cmd' value=$edit_cmd}
			{assign var='focus_on_edit' value='role_id'}
			{assign var='delete_cmd' value=$delete_cmd}
		{/if}
		{include file='controls_html.tpl'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('username');
</script>
{/if}