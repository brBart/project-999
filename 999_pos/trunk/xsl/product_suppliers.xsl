<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="status" />
	<xsl:param name="details_obj" />
	<xsl:param name="delete_obj" />
	<xsl:template match="/">  
		<table>
			<xsl:if test="$status = 1">
				<xsl:attribute name="class">disabled</xsl:attribute>
           	</xsl:if>
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<xsl:element name="tr">
	      			<xsl:attribute name="onclick">
	      				<xsl:value-of select="$details_obj" />.setFocus();
	      			</xsl:attribute>
	      			<th>Proveedor</th> 
	         		<th>Codigo</th>
	         		<th id="btn_col">
	         			<xsl:element name="input">
		                	<xsl:attribute name="id">remove_detail</xsl:attribute>
		                  	<xsl:attribute name="type">button</xsl:attribute>
		                  	<xsl:attribute name="value">Quitar</xsl:attribute>
		                  	<xsl:attribute name="onclick">
		                  		<xsl:value-of select="$delete_obj" />.execute();
		                  	</xsl:attribute>
		                  	<xsl:attribute name="onfocus">this.blur();</xsl:attribute>
		                  	<xsl:attribute name="disabled">disabled</xsl:attribute>
		                </xsl:element>
	         		</th>
	      		</xsl:element>
	       	</thead>
	       	<tbody>
       			<xsl:if test="response/params/page_items > 0">
       				<xsl:call-template name="body" />
       			</xsl:if>
	       	</tbody>
		</table>
	</xsl:template>
  	<xsl:template name="menu">
    	<xsl:for-each select="response/params">
      		Total: <xsl:value-of select="page_items" />
    	</xsl:for-each>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
			<xsl:element name="tr">
	           	<xsl:attribute name="id">
	             	<xsl:value-of select="concat('tr', position())" />
	           	</xsl:attribute>
	           	<xsl:attribute name="onclick">
	           		<xsl:value-of select="$details_obj" />.clickRow(this);
	           	</xsl:attribute>
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