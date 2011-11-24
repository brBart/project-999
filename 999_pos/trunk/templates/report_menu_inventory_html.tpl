{* Smarty * }
<div id="third_menu">
	<ul>
	    <li><a href="index.php?cmd=show_expired_lot_list" onclick="return oSession.setIsLink(true);">Lotes Vencidos</a></li>
	    <li><a href="index.php?cmd=show_near_expiration_lot_list" onclick="return oSession.setIsLink(true);">Lotes Proximos a Vencer</a></li>
	    <li><a href="index.php?cmd=show_in_stock_list" onclick="return oSession.setIsLink(true);">En Stock</a></li>
	    <li id="li_last"><a href="index.php?cmd=show_purchases_summary_product_list" onclick="return oSession.setIsLink(true);">Resumen de Compras por Producto</a></li>
	</ul>
</div>