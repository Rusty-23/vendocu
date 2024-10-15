<?php
class GoogleClient
{
    private $client;

    public function __construct(string $application_name, string $client_id, string $client_secret, string $redirect_uri) {

        $client = new Google\Client();
        $client->setApplicationName($application_name);
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);

        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function verifyIdToken($token) {
        return $this->client->verifyIdToken($token);
    }
}