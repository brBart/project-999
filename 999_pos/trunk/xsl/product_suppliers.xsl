<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<xsl:element name="table">
			<xsl:attribute name="id">
	       		<xsl:value-of select="product_suppliers" />
	     	</xsl:attribute>
	     	<caption>
	     		<xsl:call-template name="menu"/>
	     	</caption>
	      	<thead>
	        	<th>Proveedor</th> 
	         	<th>Codigo</th>
	       	</thead>
	       	<tbody>
		        <xsl:for-each select="response/grid/row">
		          	<xsl:element name="tr">
		            	<xsl:attribute name="id">
		              		<xsl:value-of select="product_supplier_id" />
		            	</xsl:attribute>
		            	<td><xsl:value-of select="supplier" /></td>
		            	<td><xsl:value-of select="product_sku" /> </td>
		          	</xsl:element>
		        </xsl:for-each>
	       	</tbody>
		</xsl:element>
	</xsl:template>
  	<xsl:template name="menu">
    	<xsl:for-each select="response/params">
      		<xsl:value-of select="total_items" /> Total
    	</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>