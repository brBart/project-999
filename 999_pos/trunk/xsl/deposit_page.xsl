<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="details_obj" />
	<xsl:template match="/">  
		<table class="read_only">
			<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
			<thead>
	      		<tr>
	      			<th>Recibo No.</th>
	      			<th>Factura</th>
	         		<th>Total efectivo</th>
	         		<th>Depositado</th>
	      		</tr>
	       	</thead>
	       	<tbody>
       			<xsl:choose>
		  			<xsl:when test="response/params/total_items > 0">
		  				<xsl:call-template name="body" />
		  			</xsl:when>
		  			<xsl:otherwise>
		  				<tr>
			       			<td colspan="4"></td>
			       		</tr>
		  			</xsl:otherwise>
		  		</xsl:choose>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="2"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/total" /></td>
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
			<tr>
	           	<td><xsl:value-of select="receipt_id" /></td>
       			<td><xsl:value-of select="invoice" /></td>
       			<td><xsl:value-of select="received" /></td>
    			<td class="total_col"><xsl:value-of select="deposited" /></td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>