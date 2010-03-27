{* Smarty *}
{* status = 0 Edit, status = 1 Idle, status = 2 Cancelled *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
{if $status eq 0}
<script type="text/javascript" src="../scripts/create_comparison.js"></script>
<script type="text/javascript" src="../scripts/text_range.js"></script>
{else}
<script type="text/javascript" src="../scripts/event_delegator.js"></script>
<script type="text/javascript" src="../scripts/details.js"></script>
<script type="text/javascript" src="../scripts/object_page.js"></script>
<script type="text/javascript" src="../scripts/comparison_page.js"></script>
{/if}
<script type="text/javascript">
	var oConsole = new Console('console');
	{if $status eq 0}
	var oCreateComparison = new CreateComparisonCommand(oSession, oConsole, Request.createXmlHttpRequestObject());
	{else}
	var oMachine = new StateMachine({$status});
	var oRemoveObject = new RemoveSessionObjectCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key});
	var oEventDelegator = new EventDelegator();
	oEventDelegator.init();
	var oDetails = new ComparisonPage(oSession, oConsole, Request.createXmlHttpRequestObject(), {$key}, oMachine, oEventDelegator);
	{literal}
	window.onunload = function(){
		oRemoveObject.execute();
	}
	{/literal}
	{/if}
</script>
<div id="content">
	<div id="frm" class="content_large">
		{include file='status_bar_doc_html.tpl'}
		{include file='header_data_html.tpl' document_name='Comparaci&oacute;n'}
		<fieldset id="main_data">
			{if $status eq 0}
		  	<p>
		  		<label for="count_id">Conteo No:*</label>
		  		<input name="form_widget" id="count_id" type="text" maxlength="11" />
		  		<span id="count_id-failed" class="hidden">*</span>
		  	</p>
		  	{/if}
			<p>
		  		<label for="reason">Motivo:{if $status eq 0}*{/if}</label>
		  		{if $status eq 0}
		  		<input name="form_widget" id="reason" type="text" maxlength="150" />
		  		<span id="reason-failed" class="hidden">*</span>
		  		{else}
		  		<span>{$reason|htmlchars}</span>
		  		{/if}
		  	</p>
		  	<p>
		  		<label for="general">General:</label>
		  		<input name="form_widget" id="general" type="checkbox"
		  			{if $status eq 1}
		  				{if $general eq 1}checked="checked"{/if}
		  				disabled="disabled"
		  			{/if} />
		  		<span id="general-failed" class="hidden">*</span>
		  	</p>
		  	{if $status eq 1}
		  		<p>&nbsp;</p>
		  		<div id="details" class="items"></div>
		  	{/if}
		</fieldset>
		<fieldset id="controls">
			{if $status eq 0}
		  	<input name="form_widget" id="save" type="button" value="Guardar"
		  		onclick="oCreateComparison.execute('{$foward_link}');" />
		  	<input name="form_widget" id="undo" type="button" value="Cancelar"
		  		onclick="oSession.loadHref('{$back_link}');" />
		  	{else}
		  	<input name="form_widget" id="print" type="button" value="Imprimir"
  				onclick="window.open('index.php?cmd=print_comparison&key={$key}', '', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1');" />
		  	{/if}
		</fieldset>
	</div>
</div>
<script type="text/javascript">
{if $status eq 0}
StateMachine.setFocus('count_id');
oCreateComparison.init('reason', 'count_id', 'general');
{else}
oDetails.init('../xsl/comparison_page.xsl', 'details', 'oDetails');
oDetails.getLastPage();
{/if}
</script>