{* Smarty * }
<script type="text/javascript">
var objectStatus = {$working_day_status};
</script>
<div id="content">
	<div id="frm" class="content_small">
		<fieldset id="working_day">
			<p><label>Jornada:</label><span>{$date}</span></p>
			<p>
				<label>Status:</label>
				<span id="object_status" class="{if $working_day_status eq 1}pos_open_status{else}pos_closed_status{/if}">
					{if $working_day_status eq 1}Abierto{else}Cerrado{/if}
				</span>
			</p>
		</fieldset>
	</div>
</div>