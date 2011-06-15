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
	      			<th colspan="6"></th>
	      			<th>Vienen: <xsl:value-of select="response/params/balance" /></th>
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
       			<xsl:choose>
		  			<xsl:when test="response/params/total_items > 0">
		  				<xsl:call-template name="body" />
		  			</xsl:when>
		  			<xsl:otherwise>
		  				<tr>
			       			<td colspan="7"></td>
			       		</tr>
		  			</xsl:otherwise>
		  		</xsl:choose>
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
	       		<td><xsl:value-of select="created_date" /></td>
	       		<td><xsl:value-of select="document" /></td>
	       		<td><xsl:value-of select="number" /></td>
	       		<td><xsl:value-of select="lot_id" /></td>
	       		<td><xsl:value-of select="entry" /></td>
	       		<td><xsl:value-of select="withdraw" /></td>
	       		<td><xsl:value-of select="balance" /></td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>