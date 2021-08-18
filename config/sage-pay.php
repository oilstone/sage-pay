<?php

return [

    /*
     * https://test.sagepay.com/documentation
     *
     * Requests to the Sage Pay API require authentication. SagePay uses HTTP Basic authentication for a simple,
     * yet secure method of enforcing access controls.
     *
     * In order to access our protected resources you must authenticate with our API by providing us with your:
     * Integration Key (Username)
     * Integration Password (Password)
     *
     * As the Sage Pay API is available in both test and live environments, you will be provided with different
     * credentials for both.
     */

    'vendor_name' => env('SAGEPAY_VENDOR'),

    'environment' => env('SAGEPAY_ENV', 'LIVE'),


    /*
     * Sage Pay Reporting & Admin API
     */

    'reporting_url' => env('SAGEPAY_WEB_URL', 'https://live.sagepay.com/access/access.htm'),

    'reporting_username' => env('SAGEPAY_WEB_USERNAME'),

    'reporting_password' => env('SAGEPAY_WEB_PASSWORD'),

    /**
     * HTTP client config
     */
    'http_config' => [
        'proxy' => env('SAGEPAY_HTTP_PROXY', ''),
    ],

    /*
     * Sage Pay error codes
     */

    'error_codes' => [
        '1000' => ['http_status' => 400, 'error_code' => 1000, 'error_message' => 'Incorrect request format'],
        '1001' => ['http_status' => 401, 'error_code' => 1001, 'error_message' => 'Authentication values are missing'],
        '1002' => ['http_status' => 401, 'error_code' => 1002, 'error_message' => 'Authentication failed'],
        '1003' => ['http_status' => 422, 'error_code' => 1003, 'error_message' => 'Missing mandatory field'],
        '1004' => ['http_status' => 422, 'error_code' => 1004, 'error_message' => 'Invalid length'],
        '1005' => ['http_status' => 422, 'error_code' => 1005, 'error_message' => 'Contains invalid characters'],
        '1006' => ['http_status' => 404, 'error_code' => 1006, 'error_message' => 'Merchant session key not found'],
        '1007' => ['http_status' => 422, 'error_code' => 1007, 'error_message' => 'The card number has failed our validity checks and is invalid'],
        '1008' => ['http_status' => 422, 'error_code' => 1008, 'error_message' => 'The card is not supported'],
        '1009' => ['http_status' => 422, 'error_code' => 1009, 'error_message' => 'Contains invalid value'],
        '1010' => ['http_status' => 422, 'error_code' => 1010, 'error_message' => 'Currency does not exist'],
        '1011' => ['http_status' => 422, 'error_code' => 1011, 'error_message' => 'Merchant session key or card identifier invalid'],
        '1012' => ['http_status' => 404, 'error_code' => 1012, 'error_message' => 'Transaction not found'],
        '1013' => ['http_status' => 403, 'error_code' => 1013, 'error_message' => 'Transaction type not supported'],
        '1014' => ['http_status' => 403, 'error_code' => 1014, 'error_message' => 'Transaction status not applicable'],
        '1015' => ['http_status' => 422, 'error_code' => 1015, 'error_message' => 'The request entity was empty'],
        '1016' => ['http_status' => 422, 'error_code' => 1016, 'error_message' => 'This parameter requires an integer'],
        '1017' => ['http_status' => 403, 'error_code' => 1017, 'error_message' => 'Operation not allowed for this transaction'],
        '1018' => ['http_status' => 403, 'error_code' => 1018, 'error_message' => 'This refund amount will exceed the amount of the original transaction'],
        '1019' => ['http_status' => 404, 'error_code' => 1019, 'error_message' => 'Transaction instructions not found'],
        '1020' => ['http_status' => 422, 'error_code' => 1020, 'error_message' => 'Unable to save a card identifier that is already reusable'],
        '1021' => ['http_status' => 403, 'error_code' => 1021, 'error_message' => 'This release amount will exceed the amount of the original transaction'],
        '9998' => ['http_status' => 408, 'error_code' => 9998, 'error_message' => 'Request timeout'],
        '9999' => ['http_status' => 500, 'error_code' => 9999, 'error_message' => 'An internal error occurred at Sage Pay'],
    ],

];
