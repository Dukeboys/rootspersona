<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:map="http://ed4becky.net/idMap"
	xmlns:cite="http://ed4becky.net/evidence">

	<xsl:output indent="yes" encoding="utf-8"
		omit-xml-declaration="yes" />

	<xsl:param name="sid" />
	<xsl:param name="site_url" />
	<xsl:param name="data_dir" />

	<xsl:template match="/cite:evidence">
		<div class="rp_source">
			<h3>Source:</h3>
			<p>
				<xsl:value-of disable-output-escaping="yes" select="cite:source[@sourceId=$sid]/cite:title/text()" />
			</p>

			<xsl:if
				test="cite:source[@sourceId=$sid]/cite:citation/cite:detail/text() != 'Unspecified'">
				<h3>Citations:</h3>
			</xsl:if>
			<xsl:for-each select="cite:source[@sourceId=$sid]/cite:citation/cite:detail">
				<xsl:if test="text() != 'Unspecified'">
					<p>
						<xsl:value-of  disable-output-escaping="yes" select="text()" />
					</p>

				</xsl:if>
			</xsl:for-each>

			<h3>Person(s) of Interest:</h3>
			<ul>
				<xsl:for-each
					select="cite:source[@sourceId=$sid]/cite:citation/cite:person">

					<xsl:variable name="pid" select="@id" />

					<xsl:variable name="node"
						select="document(concat($data_dir,'/idMap.xml'))/map:idMap/map:entry[@personId=$pid]" />
					<xsl:if test="$node/text() != ''">
						<li>
							<a>
								<xsl:attribute name="href">
								<xsl:value-of
									select="concat(concat($site_url,'?page_id='),$node/@pageId)" />
							</xsl:attribute>
								<xsl:value-of select="$node/text()" />
							</a>
						</li>
					</xsl:if>

				</xsl:for-each>
			</ul>

			<xsl:if
				test="cite:source[@sourceId=$sid]/cite:note/text() != ''">
				<h3>Notes:</h3>
			</xsl:if>
			<ul>
				<xsl:for-each select="cite:source[@sourceId=$sid]/cite:note">
					<li>
						<xsl:value-of  disable-output-escaping="yes" select="text()" />
					</li>
				</xsl:for-each>
			</ul>
		</div>
	</xsl:template>
</xsl:stylesheet>