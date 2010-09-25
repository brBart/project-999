{* Smarty *}
<fieldset id="controls">
  	<input name="form_widget" id="cancel" type="button" value="Anular"
  			{if $status eq 1}onclick="oCancel.showForm();"{else}disabled="disabled"{/if} />
</fieldset>
{include file='authentication_form_html.tpl'}