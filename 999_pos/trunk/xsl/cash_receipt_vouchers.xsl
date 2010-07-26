<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">  
		<table>
	      	<thead>
	      		<tr>
	      			<th>No.</th>
	      			<th>Transaccion</th>
	         		<th>Tarjeta No.</th>
	         		<th>Tipo</th>
	         		<th>Marca</th>
	         		<th>Nombre</th>
	         		<th>Fecha Vence</th>
	         		<th>Monto</th>
	      		</tr>
	       	</thead>
	       	<tbody>
       			<xsl:if test="response/params/page_items > 0">
       				<xsl:call-template name="body" />
       			</xsl:if>
	       	</tbody>
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
		             	<xsl:value-of select="transaction_number" />
		           	</xsl:attribute>
	           		<xsl:value-of select="position()" />
	           	</td>
	       		<td>
	       			<xsl:value-of select="transaction_number" />
	       		</td>
	       		<td><xsl:value-of select="number" /></td>
			    <td><xsl:value-of select="type" /></td>
			    <td><xsl:value-of select="brand" /></td>
			    <td><xsl:value-of select="name" /></td>
			    <td><xsl:value-of select="expiration_date" /></td>
			    <td><xsl:value-of select="amount" /></td>
			</xsl:element>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>