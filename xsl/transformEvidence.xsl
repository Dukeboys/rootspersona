<xsl:transform version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:cite="http://ed4becky.net/evidence">

	<xsl:output indent="yes" encoding="utf-8"
		omit-xml-declaration="yes" />
	<xsl:param name="site_url" />
	<xsl:param name="data_dir" />
	<xsl:template name="evidencePanel">
		<xsl:variable name="currPerson" select="@id" />
		<xsl:value-of select="$currPerson" />
		<table class="rp_evi">

			<xsl:variable name="eviNode" select="document(concat($data_dir,'evidence.xml'))/cite:evidence"/>
			<xsl:for-each select="$eviNode//cite:citation/cite:person[@id=$currPerson]/ancestor::cite:source/cite:title">
				<tr>
					<td class="rp_evilink">
						<a>
							<xsl:attribute name="href">
									<xsl:variable name="page"
								select="ancestor::cite:source[@pageId]" />
									<xsl:if test="$page = ''">#</xsl:if>
									<xsl:if test="$page != ''"><xsl:value-of
								select="concat(concat($site_url,'/?page_id='),$page)" /></xsl:if>	
							</xsl:attribute>
							[
							<xsl:value-of select="ancestor::cite:source/@sourceId" />
							]
						</a>
					</td>

					<td class="rp_evisrc">
						<xsl:value-of select="text()" />
					</td>
				</tr>

				<xsl:for-each
					select="ancestor::cite:source//cite:person[@id=$currPerson]/ancestor::cite:citation/cite:detail">
					<xsl:variable name="cite" select="text()" />
					<xsl:if test="$cite != 'Unspecified'">
						<tr>
							<td class="rp_eviempty"></td>
							<td class="rp_evicite">
								<xsl:value-of select="$cite" />
							</td>
						</tr>
					</xsl:if>
				</xsl:for-each>
			</xsl:for-each>
		</table>
	</xsl:template>
</xsl:transform>



