<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:map="http://ed4becky.net/idMap"
	xmlns:cite="http://ed4becky.net/evidence">

	<xsl:output indent="yes" encoding="utf-8"
		omit-xml-declaration="yes" />

	<xsl:param name="site_url" />
	<xsl:param name="data_dir" />

	<xsl:template match="/cite:evidence">
		<table>
		<tr><th>Source ID</th><th>Abbreviated Title</th></tr>
		<xsl:for-each select="cite:source">
			<xsl:sort select="@id"/>
				<tr>
					<xsl:variable name="page" select="@pageId" />
					<td>
						<xsl:if test="$page != ''">
							<a>
							<xsl:attribute name="href">
								<xsl:value-of
								select="concat(concat($site_url,'/?page_id='),$page)" />	
							</xsl:attribute>
							<xsl:value-of select="@sourceId" />
							</a>
						</xsl:if>
						
						<xsl:if test="$page = ''"><xsl:value-of select="@sourceId" /></xsl:if>
					</td>
					<td><xsl:value-of select="cite:abbr/text()" /></td>
				</tr>
		</xsl:for-each>
		</table>
	</xsl:template>
</xsl:stylesheet> 