<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table class="read_only">
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<tr>
	      			<th>Lote</th> 
	         		<th>Ingreso</th>
	         		<th>Vence</th>
	         		<th>Precio</th>
	         		<th>Cantidad</th>
	         		<th>Disponible</th>
	      		</tr>
	       	</thead>
	       	<tbody>
       			<xsl:if test="response/params/page_items > 0">
       				<xsl:call-template name="body" />
       			</xsl:if>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Totales:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/quantity" /></td>
	       			<td class="total_col"><xsl:value-of select="response/params/available" /></td>
	       		</tr>
	       	</tfoot>
		</table>
	</xsl:template>
  	<xsl:template name="menu">
    	<xsl:for-each select="response/params">
      		Total: <xsl:value-of select="page_items" />
    	</xsl:for-each>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
			<tr>
				<td><xsl:value-of select="lot_id" /></td>
				<td><xsl:value-of select="entry_date" /></td>
				<td><xsl:value-of select="expiration_date" /></td>
				<td><xsl:value-of select="price" /></td>
				<td class="total_col"><xsl:value-of select="quantity" /></td>
				<td class="total_col"><xsl:value-of select="available" /></td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>