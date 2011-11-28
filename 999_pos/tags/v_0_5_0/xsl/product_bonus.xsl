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
	      		<tr>
	      			<th>Cantidad</th> 
	         		<th>Descuento(%)</th>
	         		<th>Creado</th>
	         		<th>Vence</th>
	         		<th id="btn_col"></th>
	      		</tr>
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
			<tr>
				<td><xsl:value-of select="quantity" /></td>
				<td><xsl:value-of select="percentage" /></td>
				<td><xsl:value-of select="created_date" /></td>
				<td><xsl:value-of select="expired_date" /></td>
				<td>
					<xsl:element name="input">
						<xsl:attribute name="type">button</xsl:attribute>
			           	<xsl:attribute name="value">Eliminar</xsl:attribute>
			           	<xsl:attribute name="onclick">
			           		if(confirm('Esta seguro que desea eliminar?'))<xsl:value-of select="$delete_obj" />.execute('<xsl:value-of select="bonus_id" />');
			           	</xsl:attribute>
					</xsl:element>
				</td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>