class PaypalServices {
    private $clientId;
    private $secret;
    private $baseUrl;

    public function __construct() {
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->secret = getenv('PAYPAL_CLIENT_SECRET');
        $this->baseUrl = getenv('BASE_URL');
    }

    private function authenticate() {
        $url = $this->baseUrl . "/v1/oauth2/token";
        $headers = array(
            "Accept: application/json",
            "Accept-Language: en_US",
        );
        $postFields = "grant_type=client_credentials";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ":" . $this->secret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle error
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
    public function createOrder($requestData) {
        $accessToken = $this->authenticate();
        if (!$accessToken) {
            return null; // Authentication failed
        }

        $url = $this->baseUrl . "/v2/checkout/orders";
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $accessToken['access_token']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle error
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function captureOrder($orderID) {
        $accessToken = $this->authenticate();
        if (!$accessToken) {
            return null; // Authentication failed
        }

        $url = $this->baseUrl . "/v2/checkout/orders/{$orderID}/capture";
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $accessToken['access_token']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle error
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function getOrder($orderID) {
        $accessToken = $this->authenticate();
        if (!$accessToken) {
            return null; // Authentication failed
        }

        $url = $this->baseUrl . "/v2/checkout/orders/{$orderID}";
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $accessToken['access_token']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle error
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
?>
