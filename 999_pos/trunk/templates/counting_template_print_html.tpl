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
		<div id="console" class="console_display">
		{if $notify eq 1}
			<p class="{$type}">{$message}</p>
		{/if}
		</div>
		<fieldset id="header_data">
			<p>
		  		<label>Reporte:</label>
		  		<span>Selectivo</span>
		  	</p>
		  	<p>
		  		<label>Fecha:</label>
		  		<span>{$date}</span>
		  	</p>
		  	<p>
		  		<label>Ordenado por:</label>
		  		<span>{$order_by}</span>
		  	</p>
		</fieldset>
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	      			<th>Codigo</th>
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>Presentacion</th>
	         		<th>Contado</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$details}
				<tr>
					<td>{$details[i].id}</td>
					<td>{$details[i].bar_code|escape|wordwrap:16:"<br />":true}</td>
					<td>{$details[i].manufacturer|escape|wordwrap:11:"<br />":true}</td>
					<td>{$details[i].product|escape|wordwrap:11:"<br />":true}</td>
					<td>{$details[i].packaging|escape|wordwrap:11:"<br />":true}</td>
					<td>__________</td>
				</tr>
				{/section}
	       	</tbody>
		</table>
	</div>
</body>
</html>