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
		  		<label for="number">N&uacute;mero:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="number" type="text" maxlength="100"
		  			onblur="oSetProperty.execute('set_number_object', this.value, this.id);" />
		  		<span id="number-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$number}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="bank_id">Banco:*</label>
		  		<select name="form_widget" id="bank_id"
		  			onblur="oSetProperty.execute('set_bank_bank_account', this.value, this.id);"
		  			{if $status eq 1}disabled="disabled"{/if}>
	    			{section name=i loop=$bank_list}
	    				<option value="{$bank_list[i].id}" 
	    					{if $bank_list[i].id eq $bank_id}selected="selected"{/if}>
	    					{$bank_list[i].name}
	    				</option>
	    			{/section}
	    		</select>
		  		<span id="bank_id-failed" class="hidden">*</span>
		  	</p>
		</fieldset>
		{if $status eq 1}
			{assign var='edit_cmd' value=$edit_cmd}
			{assign var='focus_on_edit' value='bank_id'}
			{assign var='delete_cmd' value=$delete_cmd}
		{/if}
		{include file='controls_html.tpl'}
	</div>
</div>
{if $status eq 0}
<script type="text/javascript">
StateMachine.setFocus('number');
</script>
{/if}