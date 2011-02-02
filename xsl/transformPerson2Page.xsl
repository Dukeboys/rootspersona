<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona"
    xmlns:map="http://ed4becky.net/idMap">
    <xsl:import href="./transformFamilyGroup.xsl" />
    <xsl:output indent="yes" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:param name="site_url"/>
    <xsl:param name="data_dir"/>
    <xsl:param name="pic0"/>
    <xsl:param name="pic1"/>
    <xsl:param name="pic2"/>
    <xsl:param name="pic3"/>
    <xsl:param name="pic4"/>
    <xsl:param name="pic5"/>
    <xsl:param name="pic6"/>
    <xsl:param name="cap1"/>
    <xsl:param name="cap2"/>
    <xsl:param name="cap3"/>
    <xsl:param name="cap4"/>
    <xsl:param name="cap5"/>
    <xsl:param name="cap6"/>
    <xsl:param name="hideHdr"/>
    <xsl:param name="hideFac"/>
    <xsl:param name="hideAnc"/>
    <xsl:param name="hideFam"/>
    <xsl:param name="hidePic"/>
    <xsl:param name="hideEvi"/>
    <xsl:param name="hideBanner"/>
    <xsl:param name="hideDates"/>
    <xsl:param name="hidePlaces"/>
    <xsl:key name="person2Page" match="map:entry" use="@personId" />
    <xsl:variable name="map-top" select="document(concat($data_dir,'idMap.xml'))/map:idMap" />

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
        <xsl:if test="$hideFam!='1'">
        	<xsl:call-template name="familyGroup" />
        </xsl:if>
        <xsl:if test="$hidePic!='1'">
        	<xsl:call-template name="pictures" />
        </xsl:if>
        <xsl:if test="$hideEvi!='1'">
        	<xsl:call-template name="evidence" />
        </xsl:if>        
        <xsl:if test="$hideBanner != '1'">
        	<div class="personBanner"><br/></div>
        </xsl:if>
    </xsl:template>

    <xsl:template name="evidence">
    	<xsl:if test="$hideBanner != '1'">
        	<div class="personBanner">Evidence</div>
        </xsl:if>
        <div class="truncate">
        <ul>
            <xsl:for-each select="persona:evidence/persona:source">
				<li><xsl:value-of select="text()"/></li>
            </xsl:for-each>
        </ul>
        </div>
    </xsl:template> 
       
    <xsl:template name="familyGroup">
    	<xsl:if test="$hideBanner != '1'">
        	<div class="personBanner">Family Group</div>
        </xsl:if>
        <div class="truncate">
            <xsl:for-each
                select="document(concat($data_dir,concat(persona:references/persona:familyGroups/persona:familyGroup/@refId,'.xml')))">
                <xsl:apply-templates />
            </xsl:for-each>
        </div>
    </xsl:template>
    
    <xsl:template name="personHeader">
        <div class="truncate">
            <a>
                <xsl:attribute name="href"><xsl:value-of select="$pic0" /></xsl:attribute>
                <img class="headerBox">
                    <xsl:attribute name="src"><xsl:value-of select="$pic0" /></xsl:attribute>
                </img>
            </a>
            <div class="headerBox">
                <span class="headerBox">
                    <xsl:value-of select="persona:characteristics/persona:characteristic[@type='name']/text()" />
                </span>
                <span class="headerBox" style="align:right;color:#EBDDE2">
                    &#160;<xsl:value-of select="@id" />
                </span>
                <br />
                <xsl:if test="$hideDates != '1'">
                b:
                <xsl:value-of select="persona:events/persona:event[@type='birth']/persona:date/text()" />
                <br />
                d:
                <xsl:value-of select="persona:events/persona:event[@type='death']/persona:date/text()" />
            	</xsl:if>
            </div>
        </div>
    </xsl:template>
    
    <xsl:template name="facts">
    	<xsl:if test="$hideBanner != '1'">
      	  <div class="personBanner">Facts</div>
      	</xsl:if>
        <div class="truncate">
            <ul>
                <xsl:for-each select="persona:characteristics/persona:characteristic">
                    <xsl:if test="(@type!='name') and (@type!='gender') and (@type!='surname')">
                        <li>
                            <xsl:value-of select="text()" />
                        </li>
                    </xsl:if>
                </xsl:for-each>
                <xsl:for-each select="persona:events/persona:event">
                    <xsl:if test="@type!='marriage'">
                        <li>
                        	<xsl:if test="$hideDates != '1'">
                            	<xsl:value-of select="persona:date" />
                            -
                            </xsl:if>
                            <xsl:value-of
                                select="concat(translate(substring(@type,1,1),'abcdefghijklmnopqrstuvwxyz',
                     'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring(@type,2))" />
                     	<xsl:if test="$hidePlaces != '1'">
                            &#160;in
                            <span class="place">
                                <xsl:value-of select="persona:place" />
                            </span>
                        </xsl:if>
                        </li>
                    </xsl:if>
                    <xsl:if test="@type='marriage'">
                        <li>
                        	<xsl:if test="$hideDates != '1'">
                            <xsl:value-of select="persona:date" />
                            -
                            </xsl:if>
                            <xsl:value-of
                                select="concat(translate(substring(@type,1,1),'abcdefghijklmnopqrstuvwxyz',
                     'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring(@type,2))" />
                     		
                            &#160;to
                            <xsl:variable name="pid" select="persona:person/@id" />
                            <xsl:if test="$pid!='p000' and $pid!=''">
                                <a>
                                    <xsl:attribute name="href">
										<xsl:apply-templates select="$map-top">
											<xsl:with-param name="curr-label" select="persona:person" />
										</xsl:apply-templates>
									</xsl:attribute>
                                    <xsl:value-of
                                        select="document(concat($data_dir,concat($pid,'.xml')))/persona:person/persona:characteristics/persona:characteristic[@type='name']/text()" />
                                </a>
                            </xsl:if>
                            <xsl:if test="$hidePlaces != '1'">
                            &#160;in
                            <span class="place">
                                <xsl:value-of select="persona:place" />
                            </span>
                            </xsl:if>
                        </li>
                    </xsl:if>
                </xsl:for-each>
            </ul>
        </div>
    </xsl:template>
    
    <xsl:template name="ancestors">
    	<xsl:if test="$hideBanner != '1'">
        	<div class="personBanner">Ancestors</div>
        </xsl:if>
        <div class="truncate">
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
                        <td class="topleft">&#160;</td>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode" select="$fatherNode" />
                        </xsl:call-template>
                        <td class="bottom">&#160;</td>
                        <td colspan="2" rowspan="2" class="left">&#160;</td>
                    </tr>
                    <tr>
                        <td class="topleft">&#160;</td>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="6" class="left">&#160;</td>
                        <td class="leftbottom">&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode"
                                select="document(concat($data_dir,concat($fatherNode/persona:relations/persona:relation[@type='mother']/persona:person/@id,'.xml')))/persona:person" />
                        </xsl:call-template>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="nameBox">
                            <span style="color:blue">
                                <xsl:value-of select="persona:characteristics/persona:characteristic[@type='name']/text()" />
                            </span>
                        </td>
                        <td class="bottom">&#160;</td>
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
                        <td class="topleft">&#160;</td>
                    </tr>
                    <tr>
                        <td class="leftbottom">&#160;</td>
                        <xsl:call-template name="personbox">
                            <xsl:with-param name="personNode" select="$motherNode" />
                        </xsl:call-template>
                        <td class="bottom">&#160;</td>
                        <td colspan="2" rowspan="2" class="left">&#160;</td>
                    </tr>
                    <tr>
                        <td>&#160;</td>
                        <td>&#160;</td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="2">&#160;</td>
                        <td class="leftbottom">&#160;</td>
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
    
    <xsl:template name="pictures">
    	<xsl:if test="$hideBanner != '1'">
        	<div class="personBanner">Pictures</div>
        </xsl:if>
        <div class="truncate">
            <table class="personGallery" cellspacing="5px">
                <tbody>
                    <tr>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic1" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic1" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic2" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic2" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic3" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic3" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic4" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic4" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic5" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic5" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic6" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic6" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                    </tr>
                    <tr>
                            <td class="caption">
                                <xsl:value-of select="$cap1" />
                            </td>
                            <td class="caption">
                                <xsl:value-of select="$cap2" />
                            </td>
                            <td class="caption">
                                <xsl:value-of select="$cap3" />
                            </td>
                            <td class="caption">
                                <xsl:value-of select="$cap4" />
                            </td>
                            <td class="caption">
                                <xsl:value-of select="$cap5" />
                            </td>
                            <td class="caption">
                                <xsl:value-of select="$cap6" />
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </xsl:template>
    
    <xsl:template name="personbox">
        <xsl:param name="personNode" />
        <td rowspan="2" class="nameBox">
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
            <xsl:value-of select="$personNode/persona:events/persona:event[@type='birth']/persona:date/text()" />
            -
            <xsl:value-of select="$personNode/persona:events/persona:event[@type='death']/persona:date/text()" />
        	</xsl:if>
        </td>
    </xsl:template>
    
    <xsl:template match="map:idMap">
        <xsl:param name="curr-label" />
        <xsl:value-of select="$site_url" />/?page_id=<xsl:value-of select="key('person2Page', $curr-label/@id)/@pageId" />
    </xsl:template>
</xsl:transform>