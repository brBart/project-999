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
		  		<span>{$reason|escape}</span>
		  	</p>
		  	<p>
		  		<label>General:</label>
		  		<span>{$general}</span>
		  	</p>
		</fieldset>
		{include file='header_data_html.tpl' document_name='Comparaci&oacute;n'}
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>Presentacion</th>
	         		<th>UM</th>
	         		<th>Fisico</th>
	         		<th>Sistema</th>
	         		<th>Diferencia</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].bar_code|escape}</td>
					<td>{$details[i].manufacturer|escape}</td>
					<td>{$details[i].product|escape}</td>
					<td>{$details[i].packaging|escape}</td>
					<td>{$details[i].um|escape}</td>
					<td class="total_col">{$details[i].physical}</td>
					<td class="total_col">{$details[i].system}</td>
					<td class="total_col">{$details[i].diference}</td>
				</tr>
				{/section}
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="4"></td>
	       			<td class="total_col">Totales:</td>
	       			<td class="total_col">{$physical_total}</td>
	       			<td class="total_col">{$system_total}</td>
	       			<td class="total_col">{$total_diference}</td>
	       		</tr>
	       	</tfoot>
		</table>
	</div>
</body>
</html>