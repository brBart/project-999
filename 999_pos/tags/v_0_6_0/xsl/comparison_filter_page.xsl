<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="status" />
	<xsl:param name="details_obj" />
	<xsl:param name="delete_obj" />
	<xsl:template match="/">  
		<table class="read_only">
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<xsl:element name="tr">
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>UM</th>
	         		<th>Fisico</th>
	         		<th>Sistema</th>
	         		<th>Diferencia</th>
	         		<xsl:if test="response/params/include_prices = 1">
	         		<th>Precio</th>
	         		<th>Sub Total</th>
	         		</xsl:if>
	         	</xsl:element>
	       	</thead>
	       	<tbody>
	       		<xsl:choose>
	       			<xsl:when test="response/params/total_items > 0">
		  				<xsl:call-template name="body" />
		  			</xsl:when>
		  			<xsl:otherwise>
		  				<xsl:choose>
		  					<xsl:when test="response/params/include_prices = 1">
				  				<tr>
					       			<td colspan="9"></td>
					       		</tr>
					       	</xsl:when>
				       		<xsl:otherwise>
				       			<tr>
					       			<td colspan="7"></td>
					       		</tr>
				       		</xsl:otherwise>
		  				</xsl:choose>
		  			</xsl:otherwise>
		  		</xsl:choose>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Totales:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/physical_total" /></td>
	       			<td class="total_col"><xsl:value-of select="response/params/system_total" /></td>
	       			<td class="total_col"><xsl:value-of select="response/params/total_diference" /></td>
	       			<xsl:if test="response/params/include_prices = 1">
	       			<td></td>
	       			<td class="total_col"><xsl:value-of select="response/params/price_total" /></td>
	       			</xsl:if>
	       		</tr>
	       	</tfoot>
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
	  			<xsl:when test="previous_page != ''">
	  				<xsl:element name="a">
	                	<xsl:attribute name="href">#</xsl:attribute>
	                  	<xsl:attribute name="onclick">
	                  		<xsl:value-of select="$details_obj" />.getPage(<xsl:value-of select="previous_page" />);
	                  	</xsl:attribute>
	                  	Anterior
	                </xsl:element>
	  			</xsl:when>
	  			<xsl:otherwise>
	  				Anterior
	  			</xsl:otherwise>
	  		</xsl:choose>|
	  		<xsl:choose>
	  			<xsl:when test="next_page != ''">
	  				<xsl:element name="a">
	                	<xsl:attribute name="href">#</xsl:attribute>
	                  	<xsl:attribute name="onclick">
	                  		<xsl:value-of select="$details_obj" />.getPage(<xsl:value-of select="next_page" />);
	                  	</xsl:attribute>
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
	       		<td><xsl:value-of select="bar_code" /></td>
	       		<td><xsl:value-of select="manufacturer" /></td>
	       		<td><xsl:value-of select="product" /></td>
	       		<td><xsl:value-of select="um" /></td>
	       		<td class="total_col"><xsl:value-of select="physical" /></td>
	       		<td class="total_col"><xsl:value-of select="system" /></td>
	       		<td class="total_col"><xsl:value-of select="diference" /></td>
	       		<xsl:if test="price != ''">
	       		<td><xsl:value-of select="price" /></td>
	       		</xsl:if>
	       		<xsl:if test="total != ''">
	       		<td class="total_col"><xsl:value-of select="total" /></td>
	       		</xsl:if>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>