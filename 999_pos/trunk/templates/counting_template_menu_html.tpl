{* Smarty * }
{literal}
<script type="text/javascript">
function openWindow(sForm) 
{ 
    window.open('', 'myNewWin', 'left=0,top=0,width=' + (screen.availWidth - 50) + ',height=' + (screen.availHeight - 100) + ',menubar=0,toolbar=0,resizable=0,scrollbars=1'); 
    window.setTimeout(sForm + '.submit();', 100); 
}
</script>
{/literal}
<div id="third_menu">
	<ul>
	    <li>
	    	<form method="post" action="index.php?cmd=print_counting_template_by_product" target="myNewWin">
	    		<label for="product_first">De Producto:</label>
	    		<select name="first" id="product_first">
	    			{section name=i loop=$product_list}
	    				<option value="{$product_list[i].name|htmlchars}">
	    					{$product_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="product_last">A:</label>
	    		<select name="last" id="product_last">
	    			{section name=i loop=$product_list}
	    				<option value="{$product_list[i].name|htmlchars}">
	    					{$product_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
	    		<input type="submit" value="Imprimir" onclick="openWindow('document.forms[0]');" />
	    	</form>
	    </li>
	    <li id="li_last">
	    	<form method="post" action="index.php?cmd=print_counting_template_by_manufacturer" target="myNewWin">
	    		<label for="manufacturer_first">De Casa:</label>
	    		<select name="first" id="manufacturer_first">
	    			{section name=i loop=$manufacturer_list}
	    				<option value="{$manufacturer_list[i].name|htmlchars}">
	    					{$manufacturer_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
	    		<label for="manufacturer_last">A:</label>
	    		<select name="last" id="manufacturer_last">
	    			{section name=i loop=$manufacturer_list}
	    				<option value="{$manufacturer_list[i].name|htmlchars}">
	    					{$manufacturer_list[i].name|htmlchars}
	    				</option>
	    			{/section}
	    		</select>
	    		<input type="submit" value="Imprimir" onclick="openWindow('document.forms[1]');" />
	    	</form>
	    </li>
	</ul>
</div>