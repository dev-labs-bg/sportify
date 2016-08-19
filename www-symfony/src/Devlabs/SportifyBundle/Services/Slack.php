<?php

namespace Devlabs\SportifyBundle\Services;

use GuzzleHttp\Client;

/**
 * Class Slack
 * @package Devlabs\SportifyBundle\Services
 */
class Slack
{
    private $httpClient;
    private $url;
    private $channel;
    private $text;

    public function __construct($url, $channel)
    {
        $this->httpClient = new Client();
        $this->url = $url;
        $this->channel = $channel;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $channel
     * @return $this
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Method for submitting a POST request
     */
    public function post()
    {
        $this->httpClient->post(
            $this->url,
            [
                'body' => json_encode(
                    [
                        'channel' => $this->channel,
                        'text' => $this->text
                    ]
                ),
                'allow_redirects' => false,
                'timeout'         => 5
            ]
        );
    }
}