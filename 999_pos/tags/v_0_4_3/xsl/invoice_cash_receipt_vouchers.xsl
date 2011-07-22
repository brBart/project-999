<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table class="read_only">
			<caption>Tarjetas: <xsl:value-of select="response/params/page_items" /></caption>
	      	<thead>
	      		<tr>
	      			<th>Transaccion</th>
	         		<th>Tarjeta No.</th>
	         		<th>Tipo</th>
	         		<th>Marca</th>
	         		<th>Nombre</th>
	         		<th>Fecha Vence</th>
	         		<th>Monto</th>
	      		</tr>
	       	</thead>
	       	<tbody>
       			<xsl:if test="response/params/page_items > 0">
       				<xsl:call-template name="body" />
       			</xsl:if>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="5"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/total" /></td>
	       		</tr>
	       	</tfoot>
		</table>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
           	<tr>
	       		<td>
	       			<xsl:value-of select="transaction_number" />
	       		</td>
	       		<td><xsl:value-of select="number" /></td>
			    <td><xsl:value-of select="type" /></td>
			    <td><xsl:value-of select="brand" /></td>
			    <td><xsl:value-of select="name" /></td>
			    <td><xsl:value-of select="expiration_date" /></td>
			    <td class="total_col"><xsl:value-of select="amount" /></td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>