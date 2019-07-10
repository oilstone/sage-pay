<?php

namespace Oilstone\SagePay\Reports;

use Oilstone\SagePay\Exceptions\SagePayReportsException;
use Sabre\Xml\Element\Cdata as CData;
use Sabre\Xml\ParseException;
use Sabre\Xml\Service as SabreService;
use Sabre\Xml\XmlSerializable;

/**
 * Class Xml
 * @package Oilstone\SagePay\Reports
 */
class Xml extends SabreService
{
    /**
     * @var bool
     */
    private $auto_index_xml = false;

    /**
     * Convert an array to an XML fragment
     *
     * @param array $array
     * @return mixed
     * @throws SagePayReportsException
     */
    public function toFragment(array $array)
    {
        $fragment_xml = $this->write('root', $array);

        return $this->getFragment($fragment_xml, 'root');
    }

    /**
     * Override the Sabre XML Service write method to disable the auto indent
     *
     * @param string $rootElementName
     * @param array|XmlSerializable|string $value
     * @param string $contextUri
     * @return string
     */
    function write(string $rootElementName, $value, string $contextUri = null)
    {
        $w = $this->getWriter();
        $w->openMemory();
        $w->contextUri = $contextUri;
        $w->setIndent($this->isAutoIndexXml());
        $w->startDocument();
        $w->writeElement($rootElementName, $value);

        return $w->outputMemory();
    }

    /**
     * @return bool
     */
    public function isAutoIndexXml(): bool
    {
        return $this->auto_index_xml;
    }

    /**
     * @param bool $auto_index_xml
     * @return Xml
     */
    public function setAutoIndexXml(bool $auto_index_xml): Xml
    {
        $this->auto_index_xml = $auto_index_xml;

        return $this;
    }

    /**
     * Get the child nodes as a string for a given node
     *
     * @param $xml
     * @param $node
     * @return mixed
     * @throws SagePayReportsException
     */
    public function getFragment($xml, $node)
    {
        $current_map = $this->elementMap;

        $this->elementMap = [
            $node => 'Sabre\Xml\Element\XmlFragment',
        ];

        try {
            $fragment = $this->parse($xml)->getXml();
        } catch (ParseException $e) {
            throw new SagePayReportsException('Unable to parse report XML');
        }

        $this->elementMap = $current_map;

        return $fragment;
    }

    /**
     * Convert the given XML to an array
     *
     * @param $xml
     * @param array $element_map
     * @return array
     * @throws SagePayReportsException
     */
    public function toArray($xml, array $element_map = [])
    {
        $this->elementMap = $element_map;

        try {
            return $this->stripNameSpace($this->parse($xml));
        } catch (ParseException $e) {
            throw new SagePayReportsException('Unable to parse report XML');
        }
    }

    /**
     * Remove namespace prefixes from converted data
     *
     * @param array $array
     * @return array
     */
    public function stripNameSpace(array $array)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->stripNameSpace($item);
            } else {
                return preg_replace("/\{.*?\}/", "", $item);
            }
        }, $array);
    }

    /**
     * Convert array data containing name and value pairs to indexed array data
     *
     * @param array $array
     * @return array
     */
    public function toNameValuePairs(array $array)
    {
        $paired = [];

        foreach ($array as $idx => $item) {
            if (isset($item['name']) && isset($item['value'])) {
                if (is_array($item['value'])) {
                    $item['value'] = array_map(function ($part) {
                        return $this->toNameValuePairs([$part]);
                    }, $item['value']);
                }

                if (isset($paired[$item['name']])) {
                    if (is_array($paired[$item['name']])) {
                        $paired[$item['name']] = array_merge($paired[$item['name']], $item['value']);
                    } else {
                        $paired[$item['name']] = [$paired[$item['name']], $item['value']];
                    }
                } else {
                    $paired[$item['name']] = $item['value'];
                }
            } else {
                $paired[$idx] = $item;
            }
        }

        return $paired;
    }

    /**
     * @param $value
     * @return CData|null
     */
    public function cdata($value)
    {
        return $value ? new CData($value) : null;
    }
}