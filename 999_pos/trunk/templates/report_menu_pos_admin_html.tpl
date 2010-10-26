{* Smarty * }
<div id="third_menu">
	<ul>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=show_inactive_product_list&page=1" onsubmit="return oSession.setIsLink(true);">
	    		<p>
	    			Productos con mas de
	    			<select name="days" id="days">
	    				<option value="15">15 dias</option>
	    			{section name=i loop=150 start=30 step=30}
	    				<option value="{$smarty.section.i.index}">
	    					{$smarty.section.i.index} dias
    					</option>
	    			{/section}
	    				<option value="180">180 dias</option>
	    				<option value="365">365 dias</option>
	    			</select>
	    			sin movimiento
	    			<input type="submit" value="Consultar" />
	    		</p>
	    	</form>
	    </li>
	</ul>
</div>