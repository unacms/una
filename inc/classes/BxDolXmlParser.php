<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolXmlParser extends BxDol implements iBxDolSingleton
{
    protected $rParser;

    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolXmlParser();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Get the value of specified attribute for specified tag.
     */
    function getAttribute($sXmlContent, $sXmlTag, $sXmlAttribute)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        $sTag = strtoupper($sXmlTag);
        if(!isset($aIndexes[$sTag]))
        	return false;

        $iFieldIndex = $aIndexes[$sTag][0];
        $sAttribute = strtoupper($sXmlAttribute);
		if(!isset($aValues[$iFieldIndex]['attributes'][$sAttribute]))
			return false;

        return $aValues[$iFieldIndex]['attributes'][$sAttribute];
    }

    /**
     * Get an array of attributes for specified tag or an array of tags with the same name.
     */
    function getAttributes($sXmlContent, $sXmlTagName, $sXmlTagIndex = -1)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        /**
         * gets two-dimensional array of attributes.
         * tags-attributes
         */
        if($sXmlTagIndex == -1) {
            $aResult = array();
            $aTagIndexes = $aIndexes[strtoupper($sXmlTagName)];
            if(count($aTagIndexes) <= 0) return NULL;
            foreach($aTagIndexes as $iTagIndex)
                $aResult[] = $aValues[$iTagIndex]['attributes'];
            return $aResult;
        } else {
            $iTagIndex = $aIndexes[strtoupper($sXmlTagName)][$sXmlTagIndex];
            return $aValues[$iTagIndex]['attributes'];
        }
    }

    /**
     * Get an array of tags or one tag if its index is specified.
     */
    function getTags($sXmlContent, $sXmlTagName, $iXmlTagIndex = -1)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        //--- Get an array of tags ---//
        if($iXmlTagIndex == -1) {
            $aResult = array();
            $aTagIndexes = $aIndexes[strtoupper($sXmlTagName)];
            if(count($aTagIndexes) <= 0) return NULL;
            foreach($aTagIndexes as $iTagIndex)
                $aResult[] = $aValues[$iTagIndex];
            return $aResult;
        } else {
            $iTagIndex = $aIndexes[strtoupper($sXmlTagName)][$iXmlTagIndex];
            return $aValues[$iTagIndex];
        }
    }

    /**
     * Gets the values of the given tag.
     */
    function getValues($sXmlContent, $sXmlTagName)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        $sTag = strtoupper($sXmlTagName);
        $aTagIndexes = isset($aIndexes[$sTag]) ? $aIndexes[$sTag] : array();

        $aReturnValues = array();
        foreach($aTagIndexes as $iTagIndex) {
            $aReturnValues[$aValues[$iTagIndex]['attributes']['NAME']] =
                isset($aValues[$iTagIndex]['value']) ? $aValues[$iTagIndex]['value'] : NULL;
        }
        return $aReturnValues;
    }

    /**
     * Sets the values of tag where attribute "key" equals to specified.
     */
    function setValues($sXmlContent, $sXmlTagName, $aKeyValues)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        $aTagIndexes = $aIndexes[strtoupper($sXmlTagName)];
        if(count($aTagIndexes) == 0) return $this->getContent();
        foreach($aTagIndexes as $iTagIndex)
            foreach($aKeyValues as $sKey => $sValue)
                if($aValues[$iTagIndex]['attributes']['KEY'] == $sKey) {
                    $aValues[$iTagIndex]['value'] = $sValue;
                    break;
                }
        return $this->getContent($aValues);
    }

    /**
     * Adds given values to XML content.
     */
    function addValues($sXmlContent, $sXmlTagName, $aKeyValues)
    {
        $aValues = $aIndexes = array();
        $rParser = xml_parser_create("UTF-8");
        xml_parse_into_struct($rParser, $sXmlContent, $aValues, $aIndexes);
        xml_parser_free($rParser);

        $aTagIndexes = $aIndexes[strtoupper($sXmlTagName)];
        $iLastTagIndex = $aTagIndexes[count($aTagIndexes) - 1];
        $iAddsCount = count($aKeyValues);
        $iLevel = $aValues[$iLastTagIndex]["level"];

        for($i=count($aValues)-1; $i>$iLastTagIndex; $i--)
            $aValues[$i+$iAddsCount] = $aValues[$i];

        $i = $iLastTagIndex;
        foreach($aKeyValues as $sKey => $sValue) {
            $i++;
            $aValues[$i] = Array("tag" => $sXmlTagName, "type" => "complete", "level" => $iLevel, "attributes" => Array("KEY" => $sKey), "value" => $sValue);
        }
        return $this->getContent($aValues);
    }

    /**
     * get content in XML format from given values array
     */
    function getContent($aValues = array())
    {
        $sContent = "";
        foreach($aValues as $aValue) {
            $sTagName = strtolower($aValue['tag']);
            switch($aValue['type']) {
                case "open":
                    $sContent .= "<" . $sTagName . ">";
                    break;

                case "complete":
                    $sContent .= "<" . $sTagName;
                    if(isset($aValue['attributes']))
                        foreach($aValue['attributes'] as $sAttrKey => $sAttrValue)
                            $sContent .= " " . strtolower($sAttrKey) . "=\"" . $sAttrValue . "\"";
                    $sContent .= isset($aValue['value']) ? "><![CDATA[" . $aValue['value'] . "]]></" . $sTagName . ">" : " />";
                    break;

                case "close":
                    $sContent .= "</" . $sTagName . ">";
                    break;
            }
        }
        return $sContent;
    }
}

/** @} */
