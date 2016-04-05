<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 10.02.2016
 * Time: 14:30
 */

namespace app\helpers;


use app\core\Object;
use app\exceptions\ErrorParseXml;
use DOMDocument;
use DOMElement;
use DOMText;


class Xml extends Object
{

    public $version = '1.0';
    public $rootTag = KvsObject::RESPONSE;
    public static $itemTag = 'item';


    public static function decode($data)
    {
        $simpleXml = simplexml_load_string((string) $data);

        if($simpleXml !== false){
            $data = json_decode(json_encode($simpleXml));
        } else {
            throw new ErrorParseXml('Error parse xml');
        }
        return isset($data) ? $data : null;
    }


    public static function encode(\stdClass $data)
    {
        $xmlObj = new self();
        return $xmlObj->format($data);
    }


    public function format($data, $charset = 'utf8')
    {
        $dom = new DOMDocument($this->version, $charset);
        $root = new DOMElement($this->rootTag);
        $dom->appendChild($root);

        if ($data !== null) {
            $this->buildXml($root, $data);
        }

        return $dom->saveXML();
    }

    /**
     * @param DOMElement $element
     * @param mixed $data
     */
    protected function buildXml($element, $data)
    {
        if(is_object($data) && ($data instanceof \stdClass || $data instanceof \SimpleXMLElement)) {
            $data = (array) $data;
        }

        if (is_array($data)) {
            foreach ($data as $name => $value) {

                if($value instanceof XmlNode){
                    $child = new DOMElement($value->name);
                    $element->appendChild($child);

                    if(is_array($value->value)){
                        //var_dump($value);die;
                        $this->buildXml($child, $value->value);
                    } else {
                        $child->appendChild(new DOMText((string) $value->value));
                    }


                    if(is_array($value->attributes)){
                        foreach($value->attributes as $attrName => $attrValue){
                            $child->setAttribute($attrName, $attrValue);
                        }
                    }
                }elseif (is_int($name) && is_object($value)) {
                    $this->buildXml($element, $value);
                } elseif (is_array($value) || is_object($value)) {
                    $child = new DOMElement(is_int($name) ? self::$itemTag : $name);
                    $element->appendChild($child);
                    $this->buildXml($child, $value);
                } else {
                    $child = new DOMElement(is_int($name) ? self::$itemTag : $name);
                    $element->appendChild($child);
                    $child->appendChild(new DOMText((string) $value));
                }
            }
        }  else {
            $element->appendChild(new DOMText((string) $data));
        }
    }
}
