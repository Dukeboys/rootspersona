<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona"
	xmlns:map="http://ed4becky.net/idMap">
	<xsl:import href="./transformFamilyGroup.xsl" />
	<xsl:import href="./transformAncestors.xsl" />
	<xsl:import href="./transformPictures.xsl" />
	<xsl:import href="./transformFacts.xsl" />

	<xsl:output indent="yes" encoding="utf-8"
		omit-xml-declaration="yes" />
	<xsl:param name="site_url" />
	<xsl:param name="data_dir" />
	<xsl:param name="pic0" />
	<xsl:param name="pic1" />
	<xsl:param name="pic2" />
	<xsl:param name="pic3" />
	<xsl:param name="pic4" />
	<xsl:param name="pic5" />
	<xsl:param name="pic6" />
	<xsl:param name="cap1" />
	<xsl:param name="cap2" />
	<xsl:param name="cap3" />
	<xsl:param name="cap4" />
	<xsl:param name="cap5" />
	<xsl:param name="cap6" />
	<xsl:param name="hideHdr" />
	<xsl:param name="hideFac" />
	<xsl:param name="hideAnc" />
	<xsl:param name="hideFamC" />
	<xsl:param name="hideFamS" />
	<xsl:param name="hidePic" />
	<xsl:param name="hideEvi" />
	<xsl:param name="hideBanner" />
	<xsl:param name="hideDates" />
	<xsl:param name="hidePlaces" />

	<xsl:template match="/persona:person">
		<xsl:if test="$hideHdr!='1'">
			<xsl:call-template name="personHeader" />
		</xsl:if>
		<xsl:if test="$hideFac!='1'">
			<xsl:call-template name="facts" />
		</xsl:if>
		<xsl:if test="$hideAnc!='1'">
			<xsl:call-template name="ancestors" />
		</xsl:if>
		<xsl:if test="$hideFamC!='1'">
			<xsl:call-template name="familyGroup-FAMC" />
		</xsl:if>
		<xsl:if test="$hideFamS!='1'">
			<xsl:call-template name="familyGroup-FAMS" />
		</xsl:if>
		<xsl:if test="$hidePic!='1'">
			<xsl:call-template name="pictures" />
		</xsl:if>
		<xsl:if test="$hideEvi!='1'">
			<xsl:call-template name="evidence" />
		</xsl:if>
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">
				<br />
			</div>
		</xsl:if>
	</xsl:template>

	<xsl:template name="evidence">
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">Evidence</div>
		</xsl:if>
		<div class="rp_truncate">
			<div class="rp_evidence">
				<ul>
					<xsl:for-each select="persona:evidence/persona:source">
						<li>
							<xsl:value-of select="text()" />
						</li>
					</xsl:for-each>
				</ul>
			</div>
		</div>
	</xsl:template>

	<xsl:template name="familyGroup-FAMC">
		<xsl:for-each
			select="persona:references/persona:familyGroups/persona:familyGroup[@selfType='child']">
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">Family Group - Child to Family</div>
		</xsl:if>
		<div class="rp_truncate">
			<xsl:for-each
				select="document(concat($data_dir,concat(@refId,'.xml')))">
				<xsl:call-template name="familyGroupPanel">
					<xsl:with-param name="famType" select="'FAMC'" />
				</xsl:call-template>
			</xsl:for-each>
		</div>
		</xsl:for-each>
	</xsl:template>

	<xsl:template name="familyGroup-FAMS">
		<xsl:for-each
			select="persona:references/persona:familyGroups/persona:familyGroup[@selfType='parent']">
			<xsl:if test="$hideBanner != '1'">
				<div class="rp_banner">Family Group - Spouse to Family</div>
			</xsl:if>
			<div class="truncate">
				<xsl:for-each select="document(concat($data_dir,concat(@refId,'.xml')))">
					<xsl:call-template name="familyGroupPanel">
						<xsl:with-param name="famType" select="'FAMS'" />
					</xsl:call-template>
				</xsl:for-each>
			</div>
		</xsl:for-each>
	</xsl:template>

	<xsl:template name="personHeader">
		<div class="rp_truncate">
			<div class="rp_header">
				<a>
					<xsl:attribute name="href"><xsl:value-of
						select="$pic0" /></xsl:attribute>
					<img class="rp_headerbox">
						<xsl:attribute name="src"><xsl:value-of
							select="$pic0" /></xsl:attribute>
					</img>
				</a>
				<div class="rp_headerbox">
					<span class="rp_headerbox">
						<xsl:value-of
							select="persona:characteristics/persona:characteristic[@type='name']/text()" />
					</span>
					<span class="rp_headerbox" style="align:right;color:#EBDDE2">
						&#160;
						<xsl:value-of select="@id" />
					</span>
					<br />
					<xsl:if test="$hideDates != '1'">
						b:
						<xsl:value-of
							select="persona:events/persona:event[@type='birth']/persona:date/text()" />
						<br />
						d:
						<xsl:value-of
							select="persona:events/persona:event[@type='death']/persona:date/text()" />
					</xsl:if>
				</div>
			</div>
		</div>
	</xsl:template>

	<xsl:template name="facts">
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">Facts</div>
		</xsl:if>
		<div class="rp_truncate">
			<xsl:call-template name="factsPanel" />
		</div>
	</xsl:template>

	<xsl:template name="ancestors">
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">Ancestors</div>
		</xsl:if>
		<div class="rp_truncate">
			<xsl:call-template name="ancestorPanel" />
		</div>
	</xsl:template>

	<xsl:template name="pictures">
		<xsl:if test="$hideBanner != '1'">
			<div class="rp_banner">Pictures</div>
		</xsl:if>
		<div class="rp_truncate">
			<xsl:call-template name="picturePanel" />
		</div>
	</xsl:template>

</xsl:transform>
