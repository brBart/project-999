{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Imprimiendo...</title>
<link href="../styles/print.css" rel="stylesheet" type="text/css" />
</head>
<body onload="print();">
	<div id="wrapper">
		<fieldset id="main_data">
			<p>
		  		<label>Motivo:</label>
		  		<span>{$reason|escape|wordwrap:50:"<br />":true}</span>
		  	</p>
		</fieldset>
		{include file='header_data_html.tpl' document_name='Conteo'}
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>UM</th>
	         		<th>Cantidad</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].bar_code|escape|wordwrap:24:"<br />":true}</td>
					<td>{$details[i].manufacturer|escape|wordwrap:19:"<br />":true}</td>
					<td>{$details[i].product|escape|wordwrap:38:"<br />":true}</td>
					<td>{$details[i].um|escape|wordwrap:10:"<br />":true}</td>
					<td class="total_col">{$details[i].quantity}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col">{$total}</td>
	       		</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>