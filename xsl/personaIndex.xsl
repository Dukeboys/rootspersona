
<xsl:transform xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
				xmlns:persona="http://ed4becky.net/rootsPersona" 
				xmlns:map="http://ed4becky.net/idMap" version="1.0">
				
	<xsl:output method="html" indent="yes"/>
	<xsl:param name="site_url"/>
	<xsl:param name="data_dir"/>

	<xsl:template match="/map:idMap">
		<table class="sortable" id="sortableIndex" cellpadding="0" cellspacing="0">
			<tr>
				<th>Surname</th>
				<th>Name</th>
				<th>Dates</th>
				<th>Link</th>
			</tr>
			<xsl:for-each select="map:entry">
				<xsl:sort select="document(concat($data_dir,concat(@personId,'.xml')))/persona:person/persona:characteristics/persona:characteristic[@type='surname']/text()"/>
				<xsl:call-template name="personRow">
					<xsl:with-param name="personNode" select="document(concat($data_dir,concat(@personId,'.xml')))/persona:person"/>
				</xsl:call-template>
			</xsl:for-each>
		</table>
	</xsl:template>

	<xsl:template name="personRow">
		<xsl:param name="personNode"/>
		<tr>
			<td>
				<xsl:value-of select="$personNode/persona:characteristics/persona:characteristic[@type='surname']/text()"/>
			</td>
			<td>
				<xsl:value-of select="text()"/>
			</td>
			<td>
				<xsl:value-of select="$personNode/persona:events/persona:event[@type='Birth']/persona:date/text()"/>-
				<xsl:value-of select="$personNode/persona:events/persona:event[@type='Death']/persona:date/text()"/>
			</td>
			<td>
				<a>
					<xsl:attribute name="href">
						<xsl:value-of select="$site_url"/>/?page_id=<xsl:value-of select="@pageId"/></xsl:attribute>Page <xsl:value-of select="@pageId"/></a>
			</td>
		</tr>
	</xsl:template>
</xsl:transform>