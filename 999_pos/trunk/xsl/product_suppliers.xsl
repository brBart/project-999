<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table>
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<tr>
	        		<th>Proveedor</th> 
	         		<th>Codigo</th>
	         	</tr>
	       	</thead>
	       	<tbody>
	       		<xsl:choose>
	       			<xsl:when test="response/params/total_items > 0">
	       				<xsl:call-template name="body" />
	       			</xsl:when>
	       			<xsl:otherwise>
	       				<tr><td colspan="2"></td></tr>
	       			</xsl:otherwise>
	       		</xsl:choose>
	       	</tbody>
		</table>
	</xsl:template>
  	<xsl:template name="menu">
    	<xsl:for-each select="response/params">
      		Total: <xsl:value-of select="total_items" />
    	</xsl:for-each>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
          	<xsl:element name="tr">
            	<xsl:attribute name="id">
              		<xsl:value-of select="product_supplier_id" />
            	</xsl:attribute>
            	<td><xsl:value-of select="supplier" /></td>
            	<td><xsl:value-of select="product_sku" /> </td>
          	</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>