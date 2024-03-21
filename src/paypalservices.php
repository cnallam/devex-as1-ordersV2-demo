<?php
class PayPalServices
{
    private $clientId;
    private $secret;
    private $baseUrl;

    public function __construct()
    {
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->secret = getenv('PAYPAL_CLIENT_SECRET');
        $this->baseUrl = getenv('BASE_URL');
    }

    private function authenticate()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v1/oauth2/token");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->secret);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $response = curl_exec($curl);
        if (empty($response)) {
            // Handle error
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            // Handle error
        }

        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        
        return $jsonResponse['access_token'];
    }

    public function createOrder($response)
    {
        $accessToken = $this->authenticate();
    
        // This is an example payload, Added manually example payload to test end to end.
        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '100.00'
                    ]
                ]
            ]
        ];
        // Converted $orderData .
        // You would need to set up the order details according to your application's needs.
        // $orderData = [
        //     'intent' => 'CAPTURE',
        //     'purchase_units' => [
        //         [
        //             'amount' => [
        //                 'currency_code' => $Session['currencyCodeType'],
        //                 'value' => $Session['PaymentAmount']
        //             ],
        //             'description' => $requestData['PaymentType']
        //         ]
        //     ],
        //     'application_context' => [
        //         'return_url' => $requestData['returnURL'],
        //         'cancel_url' => $requestData['cancelURL']
        //     ]
        // ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($orderData));

        $result = curl_exec($curl);
        if (empty($result)) {
            // Handle error
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function captureOrder($response, $orderID)
    {
        $accessToken = $this->authenticate();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders/$orderID/capture");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ]);

        $result = curl_exec($curl);
        if (empty($result)) {
            // Handle error
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] != 201) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 201);
    }

    public function getOrderDetails($response, $orderID)
    {
        $accessToken = $this->authenticate();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "{$this->baseUrl}/v2/checkout/orders/$orderID");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken"
        ]);

        $result = curl_exec($curl);
        if (empty($result)) {
            // Handle error
        }

        $info = curl_getinfo($curl);
        if ($info['http_code'] != 200) {
            // Handle error
        }

        curl_close($curl);
        return $response->withJson(json_decode($result), 200);
    }
}
?>
