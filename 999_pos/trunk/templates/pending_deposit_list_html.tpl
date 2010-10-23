{* Smarty *}
<script type="text/javascript" src="../scripts/core_libs.js"></script>
<script type="text/javascript" src="../scripts/form_libs.js"></script>
<script type="text/javascript" src="../scripts/alter_object.js"></script>
<script type="text/javascript" src="../scripts/confirm_deposit.js"></script>
<script type="text/javascript">
	var oConsole = new Console('console');
	var oMachine = new StateMachine(0);
	var oConfirmDeposit = new ConfirmDepositCommand(oSession, oConsole, Request.createXmlHttpRequestObject(), '', 'confirm_deposit', {$page});
</script>
<div id="content">
	<table id="list" class="content_medium">
		{include file='list_caption_html.tpl'}
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Deposito No.</th>
				<th>Boleta No.</th>
				<th>Cuenta No.</th>
				<th>Banco</th>
				<th>Monto</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		{section name=i loop=$list}
			<tr>
				<td>{$list[i].created_date}</td>
				<td>{$list[i].id}</td>
				<td>{$list[i].number|htmlchars}</td>
				<td>{$list[i].bank_account_number|htmlchars}</td>
				<td>{$list[i].bank|htmlchars}</td>
				<td>{$list[i].total}</td>
				<td>
					<input type="button" value="Confirmar" onclick="if(confirm('Esta seguro que desea confirmar?'))oConfirmDeposit.execute({$list[i].id});" />
				</td>
			</tr>
		{/section}
		</tbody>
	</table>
</div>