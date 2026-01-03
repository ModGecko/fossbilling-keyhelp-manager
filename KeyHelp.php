<?php
/**
 * FOSSBilling – KeyHelp Server Manager
 *
 * This server manager integrates FOSSBilling with the KeyHelp API (v2).
 * It handles automated account provisioning, suspension, deletion,
 * and basic account management actions.
 *
 * Compatibility:
 * - KeyHelp API v2.13
 * - FOSSBilling v0.7.2
 *
 * Requirements:
 * - PHP >= 8.2
 *
 * Notes:
 * - The product must define a custom parameter named "id_hosting_plan"
 *   containing a valid KeyHelp hosting plan ID.
 * - The KeyHelp API key must be stored in the server password field.
 * - Username-based lookups are used for all account actions.
 *
 * Known Limitations:
 * - Changing usernames is not supported by the KeyHelp API.
 * - Changing domains and IP addresses is not supported.
 * - Reseller login URLs are not available.
 * - Account synchronization is limited to basic existence checks.
 * 
 * Coded and maintained by @Pyryxe (Hadrian)
 * https://github.com/Pyryxe
 */

class Server_Manager_KeyHelp extends Server_Manager
{
    /**
     * Expose FOSSBilling logger if needed
     */
    public function getLog(): ?Box_Log
    {
        return $this->_log;
    }

    /**
     * Tests API connectivity by calling the KeyHelp ping endpoint.
     * Throws an exception if the API key is invalid.
     */
    public function testConnection()
    {
        $result = $this->_call('GET', 'ping');

        // KeyHelp returns 401 if the API key is invalid
        if (isset($result['code']) && $result['code'] == 401) {
            throw new Server_Exception('Connection failed: Invalid API Key.');
        }

        return true;
    }

    /**
     * Creates a new KeyHelp client account
     */
    public function createAccount(Server_Account $a)
    {
        $client  = $a->getClient();
        $package = $a->getPackage();

        /**
         * Hosting plan ID is expected to be provided
         * via the product custom field: id_hosting_plan
         */
        $planId = $package->getCustomValue('id_hosting_plan');

        if (empty($planId)) {
            throw new Server_Exception(
                'The field "id_hosting_plan" is empty. ' .
                'Please ensure the product parameter is named "id_hosting_plan" and contains a valid plan ID.'
            );
        }

        /**
         * Build API payload according to KeyHelp API specification
         */
        $params = [
            'username'               => $a->getUsername(),
            'email'                  => $client->getEmail(),
            'password'               => $a->getPassword(),
            'language'               => 'en',
            'id_hosting_plan'        => (int) $planId,
            'is_suspended'           => false,
            'send_login_credentials' => true,
            'create_system_domain'   => true,

            // Client contact information
            'contact_data' => [
                'first_name' => $client->getFirstName() ?: 'Client',
                'last_name'  => $client->getLastName()  ?: 'User',
                'company'    => $client->getCompany()   ?: '',
                'address'    => $client->getAddress()   ?: '',
                'city'       => $client->getCity()      ?: '',
                'zip'        => $client->getZip()       ?: '',
                'country'    => $client->getCountry()   ?: 'US',
            ],
        ];

        // Create client in KeyHelp
        $this->_call('POST', 'clients', $params);

        return true;
    }

    /**
     * Suspends an existing account
     */
    public function suspendAccount(Server_Account $a)
    {
        $id = $this->_getUserId($a->getUsername());

        if ($id) {
            $this->_call('PUT', 'clients/' . $id, ['is_suspended' => true]);
        }

        return true;
    }

    /**
     * Unsuspends a suspended account
     */
    public function unsuspendAccount(Server_Account $a)
    {
        $id = $this->_getUserId($a->getUsername());

        if ($id) {
            $this->_call('PUT', 'clients/' . $id, ['is_suspended' => false]);
        }

        return true;
    }

    /**
     * Deletes the KeyHelp account permanently
     */
    public function cancelAccount(Server_Account $a)
    {
        $id = $this->_getUserId($a->getUsername());

        if ($id) {
            $this->_call('DELETE', 'clients/' . $id);
        }

        return true;
    }

    /**
     * Changes the account password
     */
    public function changeAccountPassword(Server_Account $a, $new_password)
    {
        $id = $this->_getUserId($a->getUsername());

        if ($id) {
            $this->_call('PUT', 'clients/' . $id, ['password' => $new_password]);
        }

        return true;
    }

    /**
     * Changes the hosting plan for an existing account
     */
    public function changeAccountPackage(Server_Account $a, Server_Package $p)
    {
        $id = $this->_getUserId($a->getUsername());

        if ($id) {
            $planId = $p->getCustomValue('id_hosting_plan');
            $this->_call('PUT', 'clients/' . $id, ['id_hosting_plan' => (int) $planId]);
        }

        return true;
    }

    /**
     * Returns a direct login URL for the client if available
     */
    public function getLoginUrl(?Server_Account $account = null)
    {
        if (!$account) {
            return 'https://' . $this->_getCleanHost();
        }

        try {
            $result = $this->_call('GET', 'login/name/' . $account->getUsername());

            if (isset($result['url'])) {
                return $result['url'];
            }
        } catch (Exception $e) {
            // Fallback handled below
        }

        return 'https://' . $this->_getCleanHost();
    }

    /**
     * Unsupported operations in KeyHelp
     */
    public function changeAccountUsername(Server_Account $a, $new_username)
    {
        throw new Server_Exception('Not supported');
    }

    public function changeAccountDomain(Server_Account $a, $new_domain)
    {
        throw new Server_Exception('Not supported');
    }

    public function changeAccountIp(Server_Account $a, $new_ip)
    {
        throw new Server_Exception('Not supported');
    }

    public function getResellerLoginUrl(?Server_Account $account = null)
    {
        return false;
    }

    public function synchronizeAccount(Server_Account $a)
    {
        return true;
    }

    /**
     * Fetches KeyHelp client ID by username
     */
    private function _getUserId($username)
    {
        try {
            $result = $this->_call('GET', 'clients/name/' . $username);
            return $result['id'] ?? false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Normalizes hostname from server configuration
     */
    private function _getCleanHost()
    {
        $host = $this->_config['hostname']
             ?? $this->_config['host']
             ?? $this->_config['ip']
             ?? '';

        // Remove protocol if present
        $host = preg_replace('#^https?://#', '', $host);

        // Strip any path parts
        $parts = explode('/', $host);

        return $parts[0];
    }

    /**
     * Core API request handler for KeyHelp
     */
    private function _call($method, $endpoint, $params = [])
    {
        $domain = $this->_getCleanHost();
        $url    = 'https://' . $domain . '/api/v2/' . $endpoint;

        // API key is stored in the server password field
        $apiKey = $this->_config['password'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Standard KeyHelp API headers
        $headers = [
            'X-API-Key: ' . $apiKey,
            'Accept: application/json',
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Handle HTTP method logic
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        } elseif ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } elseif ($method == 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Server_Exception('Curl Error: ' . curl_error($ch));
        }

        curl_close($ch);

        $json = json_decode($response, true);

        // Handle API errors
        if ($httpCode >= 400) {
            $msg = $json['message'] ?? 'Unknown Error';
            throw new Server_Exception('KeyHelp API (' . $httpCode . '): ' . $msg);
        }

        return $json;
    }
}
