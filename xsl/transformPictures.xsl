<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:persona="http://ed4becky.net/rootsPersona"
    xmlns:map="http://ed4becky.net/idMap">
    
    <xsl:output indent="yes" encoding="utf-8" omit-xml-declaration="yes" />
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
   
    <xsl:template name="picturePanel">
		<div class="rp_pictures">
            <table class="personGallery" cellspacing="5px">
                <tbody>
                    <tr>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic1" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic1" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic2" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic2" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic3" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic3" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic4" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic4" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic5" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic5" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                            <td class="rp_picture">
                                <a>
                                    <xsl:attribute name="href"><xsl:value-of select="$pic6" /></xsl:attribute>
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="$pic6" /></xsl:attribute>
                                    </img>
                                </a>
                            </td>
                    </tr>
                    <tr>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap1" />
                            </td>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap2" />
                            </td>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap3" />
                            </td>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap4" />
                            </td>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap5" />
                            </td>
                            <td class="rp_caption">
                                <xsl:value-of select="$cap6" />
                            </td>
                    </tr>
                </tbody>
            </table>
		</div>
    </xsl:template>
</xsl:transform>