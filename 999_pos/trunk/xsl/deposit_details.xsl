<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table>
			<thead>
	      		<tr>
	      			<th>No.</th>
	      			<th>Recibo No.</th>
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
	<xsl:template name="body">
		<xsl:for-each select="response/grid/row">
			<xsl:element name="tr">
				<xsl:attribute name="id">
	             	<xsl:value-of select="concat('tr', position())" />
	           	</xsl:attribute>
	           	<xsl:if test="position() mod 2 = 0">
	           		<xsl:attribute name="class">even</xsl:attribute>
	           	</xsl:if>
	           	<td>
	           		<xsl:attribute name="id">
		             	<xsl:value-of select="detail_id" />
		           	</xsl:attribute>
	           		<xsl:value-of select="position()" />
	           	</td>
	           	<td>
	       			<xsl:value-of select="receipt_id" />
       			</td>
       			<td><xsl:value-of select="received" /></td>
    			<td class="total_col"><xsl:value-of select="deposited" /></td>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>