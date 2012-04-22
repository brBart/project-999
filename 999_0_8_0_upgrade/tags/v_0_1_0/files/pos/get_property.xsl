<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="project">
		<root>
			<pos_dir>
				<xsl:value-of select="//target[@name='remove_pos']/delete/@dir" />
			</pos_dir>
		</root>
	</xsl:template>
</xsl:stylesheet>