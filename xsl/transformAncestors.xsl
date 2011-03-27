<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona"
    xmlns:map="http://ed4becky.net/idMap">

    <xsl:output indent="yes" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:param name="site_url"/>
    <xsl:param name="data_dir"/>
    <xsl:param name="hideDates"/>
    <xsl:param name="hidePlaces"/>
    <xsl:key name="person2Page" match="map:entry" use="@personId" />
    <xsl:variable name="map-top" select="document(concat($data_dir,'idMap.xml'))/map:idMap" />

    <xsl:template name="ancestorPanel">
		<div class="rp_ancestors">
            <table cellpadding="0" cellspacing="0" class="ancestors">
                <tbody>
                    <xsl:variable name="fatherPid"
                        select="persona:relations/persona:relation[@type='father']/persona:person/@id" />
                    <xsl:variable name="fatherNode"
                        select="document(concat($data_dir,concat($fatherPid,'.xml')))/persona:person" />
                    <tr>
                        <td colspan="2" rowspan="6">&#160;</td>
                        <td colspan="3" rowspan="2">&#160;</td>
                        <td>&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode"
                                select="document(concat($data_dir,concat($fatherNode/persona:relations/persona:relation[@type='father']/persona:person/@id,'.xml')))/persona:person" />
                        </xsl:call-template>
                    </tr>
                    <tr>
                        <td class="rp_topleft">&#160;</td>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode" select="$fatherNode" />
                        </xsl:call-template>
                        <td class="rp_bottom">&#160;</td>
                        <td colspan="2" rowspan="2" class="rp_left">&#160;</td>
                    </tr>
                    <tr>
                        <td class="rp_topleft">&#160;</td>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="6" class="rp_left">&#160;</td>
                        <td class="rp_leftbottom">&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode"
                                select="document(concat($data_dir,concat($fatherNode/persona:relations/persona:relation[@type='mother']/persona:person/@id,'.xml')))/persona:person" />
                        </xsl:call-template>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="rp_nameBox">
                            <span style="color:blue">
                                <xsl:value-of select="persona:characteristics/persona:characteristic[@type='name']/text()" />
                            </span>
                        </td>
                        <td class="rp_bottom">&#160;</td>
                        <td colspan="2" rowspan="2">&#160;</td>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                    </tr>
                    <xsl:variable name="motherPid"
                        select="persona:relations/persona:relation[@type='mother']/persona:person/@id" />
                    <xsl:variable name="motherNode"
                        select="document(concat($data_dir,concat($motherPid,'.xml')))/persona:person" />
                    <tr>
                        <td colspan="2" rowspan="6">&#160;</td>
                        <td>&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode"
                                select="document(concat($data_dir,concat($motherNode/persona:relations/persona:relation[@type='father']/persona:person/@id,'.xml')))/persona:person" />
                        </xsl:call-template>
                    </tr>
                    <tr>
                        <td class="rp_topleft">&#160;</td>
                    </tr>
                    <tr>
                        <td class="rp_leftbottom">&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode" select="$motherNode" />
                        </xsl:call-template>
                        <td class="rp_bottom">&#160;</td>
                        <td colspan="2" rowspan="2" class="rp_left">&#160;</td>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="2">&#160;</td>
                        <td class="rp_leftbottom">&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode"
                                select="document(concat($data_dir,concat($motherNode/persona:relations/persona:relation[@type='mother']/persona:person/@id,'.xml')))/persona:person" />
                        </xsl:call-template>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                    </tr>
                </tbody>
            </table>
		</div>
    </xsl:template>
    
    <xsl:template name="personbox">
        <xsl:param name="personNode" />
        <td rowspan="2" class="rp_nameBox">
            <a>
                <xsl:attribute name="href">
                    <xsl:apply-templates select="$map-top">
                        <xsl:with-param name="curr-label" select="$personNode" />
                    </xsl:apply-templates>
                </xsl:attribute>
                <xsl:value-of select="$personNode/persona:characteristics/persona:characteristic[@type='name']/text()" />
            </a>
            <br />
            <xsl:if test="$hideDates != '1'">
            <xsl:value-of select="$personNode/persona:events/persona:event[@type='Birth']/persona:date/text()" />
            -
            <xsl:value-of select="$personNode/persona:events/persona:event[@type='Death']/persona:date/text()" />
        	</xsl:if>
        </td>
    </xsl:template>
    
    <xsl:template match="map:idMap">
        <xsl:param name="curr-label" />
        <xsl:value-of select="$site_url" />/?page_id=<xsl:value-of select="key('person2Page', $curr-label/@id)/@pageId" />
    </xsl:template>
</xsl:transform>