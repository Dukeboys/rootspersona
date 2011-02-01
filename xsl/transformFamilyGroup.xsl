<xsl:transform version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:persona="http://ed4becky.net/rootsPersona"
    xmlns:map="http://ed4becky.net/idMap">

    <xsl:output indent="yes" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:param name="site_url"/>
    <xsl:param name="data_dir"/>
    <xsl:param name="hideDates"/>
    <xsl:param name="hidePlaces"/>
    <xsl:key name="person2Page" match="map:entry" use="@personId" />
    <xsl:variable name="map-top" select="document(concat($data_dir,'/idMap.xml'))/map:idMap" />

    <xsl:template match="/persona:familyGroup">
        <xsl:if test="@id!='f000'">
        <table class="familygroup">
            <tbody>
                <xsl:apply-templates />
            </tbody>
        </table>
        </xsl:if>
        <xsl:if test="@id='f000'">
        <br/>
        </xsl:if>
    </xsl:template>

    <xsl:template match="persona:parents/persona:relation">
        <xsl:variable name="pid" select="persona:person/@id" />
        <xsl:variable name="node" select="document(concat($data_dir,concat($pid,'.xml')))/persona:person" />

        <tr>
            <td class="full" colspan="4">PARENT
                (<xsl:value-of select="$node/persona:characteristics/persona:characteristic[@type='gender']/text()" />)&#160;
                <a>
                <xsl:attribute name="href">
                    <xsl:apply-templates select="$map-top">
                        <xsl:with-param name="curr-label" select="$node" />
                    </xsl:apply-templates>
                </xsl:attribute>
                <xsl:value-of select="$node/persona:characteristics/persona:characteristic[@type='name']/text()" />
                </a>
            </td>
        </tr>
        <tr>
            <xsl:variable name="myRows" select="4 + count($node/persona:events/persona:event[@type='marriage'])" />
            <td class="inset" rowspan="{$myRows}"></td>
            <td class="label">Birth</td>
            <td class="date"><xsl:if test="$hideDates != '1'"><xsl:value-of select="$node/persona:events/persona:event[@type='birth']/persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="bPlace" select="$node/persona:events/persona:event[@type='birth']/persona:place/text()" />
            <xsl:if test="$bPlace!=''">
            in <xsl:value-of select="$bPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        <tr>
            <td class="label">Death</td>
            <td class="date"><xsl:if test="$hideDates != '1'"><xsl:value-of select="$node/persona:events/persona:event[@type='death']/persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="dPlace" select="$node/persona:events/persona:event[@type='death']/persona:place/text()" />
            <xsl:if test="$dPlace!=''">
            in <xsl:value-of select="$dPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        <xsl:for-each select="$node/persona:events/persona:event[@type='marriage']">
        <tr>
            <td class="label">Marriage</td>
            <td class="date">&#160;<xsl:if test="$hideDates != '1'"><xsl:value-of select="persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:variable name="spid" select="persona:person/@id" />
            <xsl:if test="$spid!='' and $spid !='p000'">
            to <a>
               <xsl:attribute name="href">
			     <xsl:apply-templates select="$map-top">
				   <xsl:with-param name="curr-label" select="persona:person" />
				 </xsl:apply-templates>
			   </xsl:attribute>
                <xsl:value-of
                    select="document(concat($data_dir,concat($spid,'.xml')))/persona:person/persona:characteristics/persona:characteristic[@type='name']/text()" />

               </a>
            </xsl:if>
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="mPlace" select="persona:place/text()" />
            <xsl:if test="$mPlace!=''">
            in <xsl:value-of select="$mPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        </xsl:for-each>
        <tr>
            <td class="label">Father</td>
            <td class="parent" colspan="2">
               <xsl:variable name="fid"  select="$node/persona:relations/persona:relation[@type='father']/persona:person/@id" />
               <xsl:if test="$fid!='' and $fid!='p000'">
                <xsl:variable name="fNode"
                        select="document(concat($data_dir,concat($fid,'.xml')))/persona:person" />
               <a>
               <xsl:attribute name="href">
                 <xsl:apply-templates select="$map-top">
                   <xsl:with-param name="curr-label" select="$fNode" />
                 </xsl:apply-templates>
               </xsl:attribute>
                  <xsl:value-of
                    select="$fNode/persona:characteristics/persona:characteristic[@type='name']/text()" />
                </a>
                </xsl:if>
            </td>
        </tr>
        <tr>
            <td class="label">Mother</td>
            <td class="parent" colspan="2">
                <xsl:variable name="mid"  select="$node/persona:relations/persona:relation[@type='mother']/persona:person/@id" />
                <xsl:if test="$mid!='' and $mid!='p000'">
                    <xsl:variable name="mNode"
                        select="document(concat($data_dir,concat($mid,'.xml')))/persona:person" />
                               <a>
               <xsl:attribute name="href">
                 <xsl:apply-templates select="$map-top">
                   <xsl:with-param name="curr-label" select="$mNode" />
                 </xsl:apply-templates>
               </xsl:attribute>
                  <xsl:value-of
                    select="$mNode/persona:characteristics/persona:characteristic[@type='name']/text()" />
                </a>
                </xsl:if>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="persona:children">
        <tr>
            <td class="full" colspan="4">CHILDREN</td>
        </tr>
        <xsl:apply-templates />
    </xsl:template>

     <xsl:template match="persona:relation">
        <xsl:variable name="pid" select="persona:person/@id" />
        <xsl:variable name="node" select="document(concat($data_dir,concat($pid,'.xml')))/persona:person" />
        <tr>
            <td class="gender"><xsl:value-of select="$node/persona:characteristics/persona:characteristic[@type='gender']/text()" /></td>
            <td class="child" colspan="3">
            <a>
                <xsl:attribute name="href">
                    <xsl:apply-templates select="$map-top">
                        <xsl:with-param name="curr-label" select="$node" />
                    </xsl:apply-templates>
                </xsl:attribute>
                <xsl:value-of select="$node/persona:characteristics/persona:characteristic[@type='name']/text()" />
                </a>
            </td>
        </tr>
        <tr>
            <xsl:variable name="myRows" select="2 + count($node/persona:events/persona:event[@type='marriage'])" />
            <td class="inset" rowspan="{$myRows}"></td>
            <td class="label">Birth</td>
            <td class="date"><xsl:if test="$hideDates != '1'"><xsl:value-of select="$node/persona:events/persona:event[@type='birth']/persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="bPlace" select="$node/persona:events/persona:event[@type='birth']/persona:place/text()" />
            <xsl:if test="$bPlace!=''">
            in <xsl:value-of select="$bPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        <tr>
            <td class="label">Death</td>
            <td class="date"><xsl:if test="$hideDates != '1'"><xsl:value-of select="$node/persona:events/persona:event[@type='death']/persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="dPlace" select="$node/persona:events/persona:event[@type='death']/persona:place/text()" />
            <xsl:if test="$dPlace!=''">
            in <xsl:value-of select="$dPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        <xsl:for-each select="$node/persona:events/persona:event[@type='marriage']">
        <tr>
            <td class="label">Marriage</td>
            <td class="date">&#160;<xsl:if test="$hideDates != '1'"><xsl:value-of select="persona:date/text()" /></xsl:if></td>
            <td class="notes">&#160;
            <xsl:variable name="spid" select="persona:person/@id" />
            <xsl:if test="$spid!='' and $spid !='p000'">
            to <a>
               <xsl:attribute name="href">
			     <xsl:apply-templates select="$map-top">
				   <xsl:with-param name="curr-label" select="persona:person" />
				 </xsl:apply-templates>
			   </xsl:attribute>
                <xsl:value-of
                    select="document(concat($data_dir,concat($spid,'.xml')))/persona:person/persona:characteristics/persona:characteristic[@type='name']/text()" />

               </a>
            </xsl:if>
            <xsl:if test="$hidePlaces != '1'">
            <xsl:variable name="mPlace" select="persona:place/text()" />
            <xsl:if test="$mPlace!=''">
            in <xsl:value-of select="$mPlace" />
            </xsl:if>
            </xsl:if>
            </td>
        </tr>
        </xsl:for-each>
    </xsl:template>

    <xsl:template match="map:idMap">
        <xsl:param name="curr-label" />
        <xsl:value-of select="$site_url" />/?page_id=<xsl:value-of select="key('person2Page', $curr-label/@id)/@pageId" />
    </xsl:template>
</xsl:transform>