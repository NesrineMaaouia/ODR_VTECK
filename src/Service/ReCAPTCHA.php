<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use GuzzleHttp\Client;

/**
 * Class ReCAPTCHA
 */
class ReCAPTCHA
{
    const URL_API = 'https://www.google.com/recaptcha/api/siteverify';
    const INPUT_NAME = 'g-recaptcha-response';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $secret;

    /**
     * ReCAPTCHA constructor.
     *
     * @param string       $secret
     * @param RequestStack $requestStack
     */
    public function __construct($secret, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->secret = $secret;
    }

    /**
     * Get current request.
     *
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Verify captcha.
     *
     * @return mixed
     */
    public function verify()
    {
        $data = [
            'http_errors' => false,
            'form_params' => [
                'secret' => $this->secret,
                'response' => $this->getRequest()->get(self::INPUT_NAME)
            ]
        ];
        $client = new Client();
        $response = $client->post(self::URL_API, $data);
        $content = json_decode($response->getBody()->getContents());

        return isset($content->success) ? $content->success : false;
    }
}