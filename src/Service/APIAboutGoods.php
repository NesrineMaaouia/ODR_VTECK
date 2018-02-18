<?php

namespace App\Service;

use App\Entity\Participation;
use GuzzleHttp\Client;

/**
 * Class APIAboutGoods
 */
class APIAboutGoods
{
    const ALL_PENDING = 'document/all/pending';

    private $client;
    private $token;
    private $secret;
    private $campaign;
    private $operation;
    private $document;
    private $uploadDir;

    public function __construct($api, $token, $secret, $campaign, $operation, $uploadDir)
    {
        $this->token = $token;
        $this->secret = $secret;
        $this->campaign = $campaign;
        $this->operation = $operation;
        $this->document = 'document/'.$operation;
        $this->uploadDir = $uploadDir;

        $this->client = new Client(['base_uri' => $api.$campaign.'/']);
    }

    /**
     * Put document.
     *
     * @param $id
     * @param $body
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function putDocument($id, $body)
    {
        return 201 == $this->client->put($this->document.'.'.$id, [
            'http_errors' => false,
            'auth' => [$this->token, $this->secret],
            'Media type' => 'binary/octet-stream',
            'Type' => 'object',
            'body' => $body
        ])->getStatusCode();
    }

    /**
     * Get document.
     *
     * @param $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getDocument($id)
    {
        try {
            return json_encode($this->client->get($this->document.'.'.$id, [
                'auth' => [$this->token, $this->secret]
            ])->getBody()->getContents());
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all pending document.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAllPendingDocument()
    {
        return $this->client->get(self::ALL_PENDING, [
            'auth' => [$this->token, $this->secret]
        ]);
    }

    /**
     * Remove document.
     *
     * @param $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function deleteDocument($id)
    {
        try {
            return 204 == $this->client->delete($this->document.'.'.$id, [
                    'auth' => [$this->token, $this->secret],
                ])->getStatusCode();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Participation $participation
     */
    public function sendParticipationTickets(Participation $participation)
    {
        foreach ($participation->getInvoice() as $file) {
            $filePath = $this->uploadDir.$file;
            if (is_file($filePath)) {
                dump($this->putDocument($file, file_get_contents($filePath)));
            }
        }
    }
}
