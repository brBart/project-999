{* Smarty * }
<div id="content">
	<div id="frm" class="content_tiny">
		<fieldset id="working_day">
			<p><label>Jornada:</label><span>{$date}</span></p>
			<p>
				<label>Status:</label>
				<span class="{if $status eq 1}pos_open_status{else}pos_closed_status{/if}">
					{if $status eq 1}Abierto{else}Cerrado{/if}
				</span>
			</p>
		</fieldset>
		<fieldset id="pos_menu">
			<ul>
				<li><a id="sales" href="#" onclick="mainWindow.loadSalesSection();">Facturaci&oacute;n</a></li>
				<li><a href="#" onclick="mainWindow.loadDepositSection();">Depositos</a></li>
				<li><a href="#" onclick="mainWindow.loadCashReceiptSection();">Caja Opciones</a></li>
    			<li><a href="index.php?cmd=logout">Logout</a></li>
    		</ul>
		</fieldset>
	</div>
</div>
<script type="text/javascript">
var oLink = document.getElementById('sales');
oLink.focus();
</script>