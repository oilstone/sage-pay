<?php

namespace Oilstone\SagePay\Reports;

use Oilstone\SagePay\Exceptions\SagePayException;
use Oilstone\SagePay\Exceptions\SagePayReportsException;
use Oilstone\SagePay\Http\Client;
use Oilstone\SagePay\Registries\Config;
use Sabre\Xml\Reader;
use function Sabre\Xml\Deserializer\keyValue;

/**
 * Class Command
 * @package Oilstone\SagePay\Reports
 */
class Command
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $additionalFields;

    /**
     * @var Xml
     */
    protected $xml;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var
     */
    protected $response;

    /**
     * @var
     */
    protected $commandXml;

    /**
     * @var array
     */
    protected $elementMap;

    /**
     * Command constructor.
     * @param string $command
     * @param array $additionalFields
     * @throws SagePayReportsException
     */
    public function __construct(string $command, array $additionalFields = [])
    {
        $this->command = $command;

        $this->additionalFields = $additionalFields;

        $this->xml = new Xml();

        $this->client = new Client();

        $this->elementMap = [
            '{}transaction' => function (Reader $reader) {
                return keyValue($reader, '');
            }
        ];

        $this->make();
    }

    /**
     * @return Command
     * @throws SagePayReportsException
     */
    public function make(): Command
    {
        $fields = array_merge([
            'command' => $this->command,
            'vendor' => Config::get('vendor_name'),
            'user' => Config::get('reporting_username'),
        ], $this->additionalFields);

        $to_hash = $this->xml->toFragment(array_merge($fields, ['password' => Config::get('reporting_password')]));

        $fields['signature'] = md5($to_hash);

        $this->commandXml = $this->xml->toFragment(['vspaccess' => $fields]);

        return $this;
    }

    /**
     * @return array
     * @throws SagePayException
     * @throws SagePayReportsException
     */
    public function send(): array
    {
        $response = $this->client->post(Config::get('reporting_url'), [
            'form_params' => ['XML' => $this->commandXml],
            'verify' => false,
        ]);

        $this->response = $this->xml->toArray($response->getBody()->getContents(), $this->elementMap);

        $this->checkResponse();

        return $this->xml->toNameValuePairs($this->response);
    }

    /**
     * @return $this
     * @throws SagePayException
     */
    protected function checkResponse()
    {
        $error = [];

        foreach ($this->response as $node) {
            if (isset($node['name']) && $node['name'] === 'errorcode') {
                if ($node['value'] !== '0000') {
                    $error['code'] = $node['value'];
                }
            }

            if (isset($node['name']) && $node['name'] === 'error') {
                if ($node['value'] !== '0000') {
                    $error['message'] = $node['value'];
                }
            }
        }

        if($error) {
            throw new SagePayException($error['code'] ?? 500, $error['message'] ?? "Sage Pay reporting error");
        }

        return $this;
    }
}