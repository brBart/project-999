<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="status" />
	<xsl:template match="/">  
		<table name="form_widget">
			<xsl:if test="$status = 1">
				<xsl:attribute name="class">disabled</xsl:attribute>
           	</xsl:if>
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<tr onclick="oProductSuppliers.setFocus();">
	        		<th>Proveedor</th> 
	         		<th>Codigo</th>
	         		<th id="btn_col">
			       		<input name="form_widget" id="remove_detail" type="button" value="Quitar" onclick="oDeleteSupplierProduct.execute('delete_supplier_product');" onfocus="this.blur();" disabled="disabled" />
	         		</th>
	         	</tr>
	       	</thead>
	       	<tbody>
       			<xsl:if test="response/params/total_items > 0">
       				<xsl:call-template name="body" />
       			</xsl:if>
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
	             	<xsl:value-of select="concat('tr', position())" />
	           	</xsl:attribute>
	           	<xsl:attribute name="onclick">oProductSuppliers.clickRow(this);</xsl:attribute>
	           	<xsl:if test="position() mod 2 = 0">
	           		<xsl:attribute name="class">even</xsl:attribute>
	           	</xsl:if>
	       		<td>
	       			<xsl:attribute name="id">
		             	<xsl:value-of select="product_supplier_id" />
		           	</xsl:attribute>
	       			<xsl:value-of select="supplier" />
	       		</td>
			    <td colspan="2"><xsl:value-of select="product_sku" /></td>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>