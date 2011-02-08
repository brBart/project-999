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
	      			<th>Codigo</th>
	      			<th>Nombre</th> 
	         		<th>Presentacion</th>
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
				<td><xsl:value-of select="product_id" /></td>
				<td><xsl:value-of select="name" /></td>
				<td><xsl:value-of select="packaging" /></td>
			</tr>
        </xsl:for-each>
	</xsl:template>
</xsl:stylesheet>