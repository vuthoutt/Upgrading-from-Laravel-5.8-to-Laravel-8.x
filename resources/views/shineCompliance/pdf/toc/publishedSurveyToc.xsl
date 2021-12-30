<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:outline="http://wkhtmltopdf.org/outline"
                version="2.0"
                xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
                doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
                indent="yes"/>
    <xsl:template match="outline:outline">
        <html>
            <head>
                <title>Table of Contents</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style>
                    body {
                    font-size: 10pt;
                    font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                    background:#FFF;
                    }
                    li {
                    list-style: none;
                    margin-top: 20px;
                    font-weight: bold;
                    color:#333;
                    }
                    ul {
                    padding-left: 0em;
                    }
                    ul li ul li {
                    font-size: 9pt;
                    margin-top: 2px;
                    font-weight: normal;
                    color: #666;
                    }
                    a {
                    text-decoration:none;
                    }
                    span {
                    padding-left: 10px;
                    width: 35px;
                    display: inline-block;
                    }
                    .topTitle {
                    text-align: left;
                    font-size: 1.5em;
                    font-weight: bold;
                    padding-left: 10px;
                    margin-top: 20px;
                    color:#333;
                    }
                </style>
            </head>
            <body>
                <div class="topTitle">Content Page</div>
                <ul style="margin-top: 30px;">
                    <xsl:apply-templates select="outline:item/outline:item"/>
                </ul>
            </body>
        </html>
    </xsl:template>
    <xsl:template match="outline:item">
        <li>
            <xsl:if test="@title!=''">
                <div>
                    <a>
                        <xsl:if test="@link">
                            <xsl:attribute name="href">
                                <xsl:value-of select="@link"/>
                            </xsl:attribute>
                        </xsl:if>
                        <xsl:if test="@backLink">
                            <xsl:attribute name="name">
                                <xsl:value-of select="@backLink"/>
                            </xsl:attribute>
                        </xsl:if>
                        <span>
                            <xsl:value-of select="@page"/>
                        </span>
                        <xsl:value-of select="@title"/>
                    </a>
                </div>
            </xsl:if>
            <ul>
                <xsl:comment>added to prevent self-closing tags in QtXmlPatterns</xsl:comment>
                <xsl:apply-templates select="outline:item"/>
            </ul>
        </li>
    </xsl:template>
</xsl:stylesheet>