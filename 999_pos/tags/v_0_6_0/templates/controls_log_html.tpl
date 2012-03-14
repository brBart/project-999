{* Smarty *}
<fieldset>
	<input type="button" value="Imprimir" onclick="window.open('index.php?cmd={$print_cmd}&start_date={$start_date|escape:'url'}&end_date={$end_date|escape:'url'}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
</fieldset>