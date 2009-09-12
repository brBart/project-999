<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="status" />
	<xsl:template match="/">  
		<table>
			<xsl:if test="$status = 1">
				<xsl:attribute name="class">disabled</xsl:attribute>
           	</xsl:if>
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<tr onclick="oDetails.setFocus();">
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>Presentacion</th>
	         		<th>UM</th>
	         		<th>Cantidad</th>
	         		<th>Precio</th>
	         		<th>Sub Total</th>
	         		<th>Vence</th>
	         		<th id="btn_col">
			       		<input id="remove_detail" type="button" value="Quitar" onclick="oDeleteDetail.execute('delete_detail');" onfocus="this.blur();" disabled="disabled" />
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
	  		<span>
	  			<xsl:value-of select="first_item" /> - <xsl:value-of select="last_item" /> de <xsl:value-of select="total_items" />
	  		</span>
	  		<span>
	  			Pagina <xsl:value-of select="page" /> de <xsl:value-of select="total_pages" />
	  		</span>
	  		<xsl:choose>
	  			<xsl:when test="previous_link != ''">
	  				<xsl:element name="a">
	                	<xsl:attribute name = "href" ><xsl:value-of select="previous_link" /></xsl:attribute>
	                  	<xsl:attribute name = "onclick">oSession.setIsLink(true);</xsl:attribute>
	                  	Anterior
	                </xsl:element>
	  			</xsl:when>
	  			<xsl:otherwise>
	  				Anterior
	  			</xsl:otherwise>
	  		</xsl:choose>|
	  		<xsl:choose>
	  			<xsl:when test="next_link != ''">
	  				<xsl:element name="a">
	                	<xsl:attribute name = "href" ><xsl:value-of select="next_link" /></xsl:attribute>
	                  	<xsl:attribute name = "onclick">oSession.setIsLink(true);</xsl:attribute>
	                  	Siguiente
	                </xsl:element>
	  			</xsl:when>
	  			<xsl:otherwise>
	  				Siguiente
	  			</xsl:otherwise>
	  		</xsl:choose>
    	</xsl:for-each>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
			<xsl:element name="tr">
	           	<xsl:attribute name="id">
	             	<xsl:value-of select="concat('tr', position())" />
	           	</xsl:attribute>
	           	<xsl:attribute name="onclick">oDetails.clickRow(this);</xsl:attribute>
	           	<xsl:if test="position() mod 2 = 0">
	           		<xsl:attribute name="class">even</xsl:attribute>
	           	</xsl:if>
	       		<td>
	       			<xsl:attribute name="id">
		             	<xsl:value-of select="detail_id" />
		           	</xsl:attribute>
	       			<xsl:value-of select="bar_code" />
	       		</td>
	       		<td><xsl:value-of select="manufacturer" /></td>
	       		<td><xsl:value-of select="product" /></td>
	       		<td><xsl:value-of select="packaging" /></td>
	       		<td><xsl:value-of select="um" /></td>
	       		<td><xsl:value-of select="quantity" /></td>
	       		<td><xsl:value-of select="price" /></td>
	       		<td><xsl:value-of select="total" /></td>
	       		<td colspan="2"><xsl:value-of select="expiration_date" /></td>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>