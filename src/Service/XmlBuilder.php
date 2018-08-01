<?php

namespace RatePAY\Service;

/**
 * Class XmlBuilder
 * @package Service
 */
class XmlBuilder
{

    /**
     * Initial xml base string including ratepay urn
     */
    const XML_BASE_STRING = '<?xml version="1.0"?><request xmlns="urn://www.ratepay.com/payment/1_0" version="1.0"></request>';

    /**
     * Returns SimpleXmlExtended element
     *
     * @param array $array
     * @return SimpleXmlExtended
     */
    public static function getXmlElement($array) {
        $simpleXmlExtended = new SimpleXmlExtended(self::XML_BASE_STRING);
        $simpleXmlExtended = self::arrayToSimpleXml($simpleXmlExtended, $array);

        return $simpleXmlExtended;
    }

    /**
     * Converts array in SimpleXml structure
     *
     * @param SimpleXMLExtended $xml
     * @param array $array
     * @return SimpleXMLExtended
     */
    private static function arrayToSimpleXml($xml, $array) {

        // ToDo: Complete refactoring of method, especially multiple case. No continue and unset anymore.

        foreach ($array as $elementName => $elementContent) {
            // Special behavior in case of multiple values
            if (key_exists(0, $elementContent)) {
                foreach ($elementContent as $subElementContent) {
                    if (key_exists('cdata', $subElementContent)) {
                        $element = $xml->addCDataChild($elementName, $subElementContent['cdata']);
                        unset($subElementContent['cdata']);
                    } else {
                        $element = $xml->addChild($elementName);
                    }

                    self::arrayToSimpleXml($element, $subElementContent);
                }
                continue;
            }

            if ($elementName == "attributes") {
                foreach ($elementContent as $attribute => $attributeValue) {
                    $xml->addAttribute($attribute, $attributeValue['value']);
                }
                continue;
            }

            if (key_exists('cdata', $elementContent)) {
                $element = $xml->addCDataChild($elementName, $elementContent['cdata']);         // If value is an array with cdata key, set value in CData tag
                unset($elementContent['cdata']);

            } elseif (key_exists('value', $elementContent)) {
                $element = $xml->addChild($elementName, $elementContent['value']);
                unset($elementContent['value']);

            } else {
                $element = $xml->addChild($elementName);
            }

            if (key_exists('attributes', $elementContent)) {
                foreach ($elementContent['attributes'] as $attributeName => $attributeValue) {  // If value is an array with attributes key, save array values as attributes to element
                    $element->addAttribute($attributeName, $attributeValue['value']);
                }
                unset($elementContent['attributes']);
            }

            self::arrayToSimpleXml($element, $elementContent);                                  // If value is an array, recursive call‚
        }
        return $xml;
    }

}