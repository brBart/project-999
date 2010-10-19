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
		  		<label>Reporte:</label>
		  		<span>Kardex</span>
		  	</p>
		  	<p>
		  		<label>C&oacute;digo:</label>
		  		<span>{$id}</span>
		  	</p>
		  	<p>
		  		<label>Nombre:</label>
		  		<span>{$name}</span>
		  	</p>
		  	<p>
		  		<label>C&oacute;digo barra:</label>
		  		<span>{$bar_code}</span>
		  	</p>
		  	<p>
		  		<label>Presentaci&oacute;n:</label>
		  		<span>{$packaging}</span>
		  	</p>
		</fieldset>
		<p id="separator">&nbsp;</p>
		<table>
	     	<caption>{$total_items} de {$total_items}</caption>
	      	<thead>
	      		<tr>
	      			<th colspan="6"></th>
	      			<th>Vienen: {$balance}</th>
	      		</tr>
	      		<tr>
	        		<th>Fecha</th> 
	         		<th>Documento</th>
	         		<th>No.</th>
	         		<th>Lote</th>
	         		<th>Entraron</th>
	         		<th>Salieron</th>
	         		<th>Saldo</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			{section name=i loop=$kardex}
				<tr>
					<td>{$kardex[i].created_date}</td>
					<td>{$kardex[i].document|htmlchars}</td>
					<td>{$kardex[i].number|htmlchars}</td>
					<td>{$kardex[i].lot_id}</td>
					<td>{$kardex[i].entry}</td>
					<td>{$kardex[i].withdraw}</td>
					<td>{$kardex[i].balance}</td>
				</tr>
				{/section}
	       	</tbody>
		</table>
	</div>
</body>
</html>