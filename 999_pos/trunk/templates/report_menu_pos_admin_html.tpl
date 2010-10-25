{* Smarty * }
<div id="third_menu">
	<ul>
	    <li id="li_last">
	    	<form method="post" action="show_" onsubmit="return oSession.setIsLink(true);">
	    		<p>
	    			Productos con mas de
	    			<select name="months" id="months">
	    			{section name=i loop=13 start=1}
	    				<option value="{$smarty.section.i.index}">
	    					{$smarty.section.i.index} {if $smarty.section.i.index eq 1}mes{else}meses{/if}
    					</option>
	    			{/section}
	    			</select>
	    			sin movimiento
	    			<input type="submit" value="Consultar" />
	    		</p>
	    	</form>
	    </li>
	</ul>
</div>