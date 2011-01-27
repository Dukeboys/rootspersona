
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona" xmlns:map="http://ed4becky.net/idMap" version="1.0">

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
				<xsl:value-of select="$personNode/persona:events/persona:event[@type='birth']/persona:date/text()"/>-
				<xsl:value-of select="$personNode/persona:events/persona:event[@type='death']/persona:date/text()"/>
			</td>
			<td>
				<a>
					<xsl:attribute name="href">
						<xsl:value-of select="$site_url"/>/?page_id=<xsl:value-of select="@pageId"/></xsl:attribute>Page <xsl:value-of select="@pageId"/></a>
			</td>
		</tr>
	</xsl:template>
</xsl:stylesheet><!-- Stylus Studio meta-information - (c) 2004-2009. Progress Software Corporation. All rights reserved.

<metaInformation>
	<scenarios>
		<scenario default="yes" name="Scenario1" userelativepaths="yes" externalpreview="no" url="..\xml\idMap.xml" htmlbaseurl="" outputurl="" processortype="saxon8" useresolver="yes" profilemode="0" profiledepth="" profilelength="" urlprofilexml=""
		          commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext="" validateoutput="no" validator="internal" customvalidator="">
			<parameterValue name="data_dir" value="'file:///c:/development/XMLProjects/rootsPersonaData/'"/>
			<parameterValue name="site_url" value="'http://thompson-hayward-snypes-moore.net'"/>
			<advancedProp name="sInitialMode" value=""/>
			<advancedProp name="bXsltOneIsOkay" value="true"/>
			<advancedProp name="bSchemaAware" value="true"/>
			<advancedProp name="bXml11" value="false"/>
			<advancedProp name="iValidation" value="0"/>
			<advancedProp name="bExtensions" value="true"/>
			<advancedProp name="iWhitespace" value="0"/>
			<advancedProp name="sInitialTemplate" value=""/>
			<advancedProp name="bTinyTree" value="true"/>
			<advancedProp name="bWarnings" value="true"/>
			<advancedProp name="bUseDTD" value="false"/>
			<advancedProp name="iErrorHandling" value="fatal"/>
		</scenario>
	</scenarios>
	<MapperMetaTag>
		<MapperInfo srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
		<MapperBlockPosition>
			<template match="/map:idMap"></template>
		</MapperBlockPosition>
		<TemplateContext></TemplateContext>
		<MapperFilter side="source"></MapperFilter>
	</MapperMetaTag>
</metaInformation>
-->