<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use App\Entity\Participation;
use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;

/**
 * API Sogec.
 */
class APISogec
{
    /**
     * Authentication URI
     */
    const API_AUTH = 'auths.json';
    /**
     * Participation URI
     */
    const API_PARTICIPATION = 'odr/participations.json';

    const API_ERR_OUT_OF = 'Une erreur s\'est produite, veuillez réessayer dans quelques minutes';
    const API_ERR_NOT_FOUND = 'Erreur, aucun utilisateur ne disposant de ces informations';

    private $client;
    private $url;
    private $login;
    private $password;
    private $operation;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct($url, $login, $password, $operation, $curlTimeout, RouterInterface $router, SerializerInterface $serializer)
    {
        $this->url = $url;
        $this->login = $login;
        $this->password = $password;
        $this->operation = $operation;
        $this->router = $router;
        $this->serializer = $serializer;

        $this->client = new Client(['base_uri' => $url, 'timeout' => $curlTimeout]);
    }

    /**
     * Get schema and http host.
     *
     * @return string
     */
    private function getBaseUrl()
    {
        return $this->router->generate('cms_homepage', [], RouterInterface::ABSOLUTE_URL);
    }
    /**
     * Get authentication token.
     *
     * @return string
     */
    public function getToken()
    {
        $response = $this->postAndDecode(self::API_AUTH, [
            'form_params' => [
                'login' => $this->login,
                'password' => $this->password
            ]
        ]);

        return (isset($response['token'])) ? $response['token'] : '';
    }

    /**
     * Send post request in API and json_decode response.
     *
     * @param $path service uri
     * @param $data post data
     *
     * @return array|null
     */
    private function postAndDecode($path, $data)
    {
        try {
            $res = $this->client->post($path, $data);
            $jsonResponse = json_decode($res->getBody()->getContents(), true);

            return $jsonResponse;
        } catch (RequestException $exception) {
            return null;
        }
    }

    /**
     * Serialize User.
     *
     * @param Participation $participation
     *
     * @return string
     */
    private function serializeData(Participation $participation)
    {
        $consumerCtx = SerializationContext::create()->setGroups(['consumer']);
        $globalCtx = SerializationContext::create()->setGroups(['global']);

        return json_encode([
            'participation' => [
                'global' => json_decode($this->serializer->serialize($participation, 'json', $globalCtx)),
                'consumer' => json_decode($this->serializer->serialize($participation->getUser(), 'json', $consumerCtx)),
                'images' => [
                    ['src' => $this->getBaseUrl().User::UPLOAD_DIR.$participation->getInvoice()->getFilename()]
                ]
            ]
        ]);
    }

    /**
     * Envoie participation.
     *
     * @param Participation $participation
     * @param string        $token
     *
     * @return array
     */
    public function sendParticipation(Participation $participation, $token = null)
    {
        $token = is_null($token) ? $this->getToken() : $token;

        return $this->postAndDecode(self::API_PARTICIPATION, [
            'http_errors' => false,
            'form_params' => [
                'token' => $token,
                'operation' => $this->operation,
                'data' => $this->serializeData($participation)
            ]
        ]);
    }

    /**
     * Get participation state.
     *
     * @param Participation $participation
     * @param string        $token
     *
     * @return bool
     */
    public function getParticipationState(Participation $participation, $token = null)
    {
        $data = [
            'token' => is_null($token) ? $this->getToken() : $token,
            'operation' => $this->operation,
            'participation_id' => $participation->getNum(),
            'status_only' => 0
        ];
        $response = $this->client->get(self::API_PARTICIPATION . '?' . http_build_query($data), ['http_errors' => false]);
        if ($response->getStatusCode() == 200) {
            $content = json_decode($response->getBody()->getContents());
            $operation = $content->{$this->operation}[0];
            $global = $operation->global;
            $financial = isset($operation->financial[0]) ? $operation->financial[0] : null;

            return [
                'is_conform'            => is_null($global->is_conform) ? null : boolval($global->is_conform),
                'reason_not_conformity' => isset($global->reason_not_conformity) ? json_decode($global->reason_not_conformity)[0]->libelle_court : '',
                'non_conformity_type'   => isset($global->non_conformity_type) ? $global->non_conformity_type : null,
                'financial_status'      => ($financial) ? $financial->status : null,
                'financial_date'        => ($financial) ? date('d-m-Y', strtotime($financial->date)) : null,
            ];
        } else {
            return false;
        }
    }

}
