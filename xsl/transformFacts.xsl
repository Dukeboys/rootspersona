<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona"
    xmlns:map="http://ed4becky.net/idMap">
    
    <xsl:output indent="yes" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:param name="hideDates"/>
    <xsl:param name="hidePlaces"/>
   
    <xsl:template name="factsPanel">
		<div class="rp_facts">
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
                            ;
                            <span class="rp_place">
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
                            <span class="rp_place">
                                <xsl:value-of select="persona:place" />
                            </span>
                            </xsl:if>
                        </li>
                    </xsl:if>
                </xsl:for-each>
            </ul>
		</div>
    </xsl:template>
 </xsl:transform>