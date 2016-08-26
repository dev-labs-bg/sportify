<?php

namespace Devlabs\SportifyBundle\Services;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Slack
 * @package Devlabs\SportifyBundle\Services
 */
class Slack
{
    use ContainerAwareTrait;

    private $httpClient;
    private $url;
    private $channel;
    private $text;

    public function __construct(ContainerInterface $container, $url, $channel)
    {
        $this->httpClient = new Client();
        $this->url = $url;
        $this->channel = $channel;
        $this->container = $container;
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
        $env = $this->container->get('kernel')->getEnvironment();

        if ($env == 'prod')
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
}