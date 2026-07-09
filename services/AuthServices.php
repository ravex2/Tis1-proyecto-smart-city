<?php

require_once __DIR__ . '/../config/google.php';

class GoogleAuthService {
    private $client;

    public function __construct() {
        $this->client = GoogleConfig::getClient();
    }

    public function getAuthUrl(): string {
        return $this->client->createAuthUrl();
    }

    public function authenticate(string $code): ?object {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($token['error'])) {
            return null;
        }

        $this->client->setAccessToken($token);
        
        $oauth2 = new Google\Service\Oauth2($this->client);
        return $oauth2->userinfo->get();
    }
}