<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table>
	       	<tbody>
       			<xsl:choose>
		  			<xsl:when test="response/params/total_items > 0">
		  				<xsl:call-template name="body" />
		  			</xsl:when>
		  			<xsl:otherwise>
		  				<tr>
			       			<td colspan="5"></td>
			       		</tr>
		  			</xsl:otherwise>
		  		</xsl:choose>
	       	</tbody>
	       	<tfoot>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Sub-Total:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/sub_total" /></td>
	       			<td></td>
	       		</tr>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Descuento:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/discount" /></td>
	       			<td></td>
	       		</tr>
	       		<tr>
	       			<td colspan="3"></td>
	       			<td class="total_col">Total:</td>
	       			<td class="total_col"><xsl:value-of select="response/params/total" /></td>
	       			<td></td>
	       		</tr>
	       	</tfoot>
		</table>
	</xsl:template>
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
			<xsl:element name="tr">
				<xsl:if test="is_bonus = 0">
		           	<xsl:attribute name="id">
		             	<xsl:value-of select="concat('tr', row_pos)" />
		           	</xsl:attribute>
	           	</xsl:if>
	           	<xsl:choose>
		  			<xsl:when test="position() mod 2 != 0 and is_bonus = 1">
		  				<xsl:attribute name="class">even bonus</xsl:attribute>
		  			</xsl:when>
		  			<xsl:when test="position() mod 2 != 0">
		  				<xsl:attribute name="class">even</xsl:attribute>
		  			</xsl:when>
		  			<xsl:when test="is_bonus = 1">
		  				<xsl:attribute name="class">bonus</xsl:attribute>
		  			</xsl:when>
		  		</xsl:choose>
		  		<xsl:choose>
	       			<xsl:when test="is_bonus = 0">
	       				<td>
			       			<xsl:attribute name="id">
				             	<xsl:value-of select="detail_id" />
				           	</xsl:attribute>
			       			<xsl:value-of select="quantity" />
		       			</td>
	       			</xsl:when>
	       			<xsl:otherwise>
	       				<td></td>
	       			</xsl:otherwise>
	       		</xsl:choose>
	       		<td><xsl:value-of select="product" /></td>
	       		<td><xsl:value-of select="packaging" /></td>
	       		<td><xsl:value-of select="price" /></td>
	       		<td class="total_col"><xsl:value-of select="total" /></td>
	       		<xsl:choose>
	       			<xsl:when test="is_bonus = 0">
	       				<td><xsl:value-of select="row_pos" /></td>
	       			</xsl:when>
	       			<xsl:otherwise>
	       				<td></td>
	       			</xsl:otherwise>
	       		</xsl:choose>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>