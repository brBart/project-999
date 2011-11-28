<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:param name="status" />
	<xsl:param name="details_obj" />
	<xsl:param name="delete_obj" />
	<xsl:template match="/">  
		<table>
			<xsl:if test="$status > 0">
				<xsl:attribute name="class">read_only</xsl:attribute>
           	</xsl:if>
	     	<caption>
	     		<xsl:call-template name="menu" />
	     	</caption>
	      	<thead>
	      		<xsl:element name="tr">
	      			<xsl:if test="$status = 0">
		      			<xsl:attribute name="onclick">
		      				<xsl:value-of select="$details_obj" />.setFocus();
		      			</xsl:attribute>
	      			</xsl:if>
	        		<th>Barra</th> 
	         		<th>Casa</th>
	         		<th>Nombre</th>
	         		<th>UM</th>
	         		<th>Cantidad</th>
	         		<th>Precio</th>
	         		<th>Sub Total</th>
	         		<th>Vence</th>
	         		<th id="btn_col">
	         			<xsl:if test="$status = 0">
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
		                </xsl:if>
	         		</th>
	         	</xsl:element>
	       	</thead>
	       	<tbody>
       			<xsl:choose>
		  			<xsl:when test="response/params/total_items > 0">
		  				<xsl:call-template name="body" />
		  			</xsl:when>
		  			<xsl:otherwise>
		  				<tr>
			       			<td colspan="9"></td>
			       		</tr>
		  			</xsl:otherwise>
		  		</xsl:choose>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="5"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/total" /></td>
	       			<td colspan="2"></td>
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
	           	<xsl:attribute name="id">
	             	<xsl:value-of select="concat('tr', position())" />
	           	</xsl:attribute>
	           	<xsl:if test="$status = 0">
		           	<xsl:attribute name="onclick">
		           		<xsl:value-of select="$details_obj" />.clickRow(this);
		           	</xsl:attribute>
	           	</xsl:if>
	           	<xsl:if test="$status = 0">
		           	<xsl:if test="position() mod 2 = 0">
		           		<xsl:attribute name="class">even</xsl:attribute>
		           	</xsl:if>
	           	</xsl:if>
	       		<td>
	       			<xsl:attribute name="id">
		             	<xsl:value-of select="detail_id" />
		           	</xsl:attribute>
	       			<xsl:value-of select="bar_code" />
	       		</td>
	       		<td><xsl:value-of select="manufacturer" /></td>
	       		<td><xsl:value-of select="product" /></td>
	       		<td><xsl:value-of select="um" /></td>
	       		<td><xsl:value-of select="quantity" /></td>
	       		<td><xsl:value-of select="price" /></td>
	       		<td class="total_col"><xsl:value-of select="total" /></td>
	       		<td colspan="2"><xsl:value-of select="expiration_date" /></td>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>