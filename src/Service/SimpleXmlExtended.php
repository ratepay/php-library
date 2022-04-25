<?php

/*
 * Ratepay PHP-Library
 *
 * This document contains trade secret data which are the property of
 * Ratepay GmbH, Berlin, Germany. Information contained herein must not be used,
 * copied or disclosed in whole or part unless permitted in writing by Ratepay GmbH.
 * All rights reserved by Ratepay GmbH.
 *
 * Copyright (c) 2019 Ratepay GmbH / Berlin / Germany
 */

namespace RatePAY\Service;

class SimpleXmlExtended extends \SimpleXMLElement
{
    /**
     * create CData child.
     *
     * @param string $sName
     * @param string $sValue
     *
     * @return \SimpleXMLElement
     */
    public function addCDataChild($sName, $sValue)
    {
        $oNodeOld = dom_import_simplexml($this);
        $oNodeNew = new \DOMNode();
        $oDom = new \DOMDocument();
        $oDataNode = $oDom->appendChild($oDom->createElement($sName));
        $oDataNode->appendChild($oDom->createCDATASection($this->removeSpecialChars($sValue)));
        $oNodeTarget = $oNodeOld->ownerDocument->importNode($oDataNode, true);
        $oNodeOld->appendChild($oNodeTarget);

        return simplexml_import_dom($oNodeTarget);
    }

    /**
     * This method replaced all zoot signs.
     *
     * @param string $str
     *
     * @return string
     */
    private function removeSpecialChars($str)
    {
        if (!is_string($str)) {
            return '';
        }

        $search = ['–', '´', '‹', '›', '‘', '’', '‚', '“', '”', '„', '‟', '•', '‒', '―', '—', '™', '¼', '½', '¾'];
        $replace = ['-', "'", '<', '>', "'", "'", ',', '"', '"', '"', '"', '-', '-', '-', '-', 'TM', '1/4', '1/2', '3/4'];

        return str_replace($search, $replace, $str);
    }
}
