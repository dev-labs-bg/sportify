<?php

namespace Devlabs\SportifyBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
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
     * Set web-hook URL
     *
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get web-hook URL
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set Slack channel
     *
     * @param $channel
     * @return $this
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get Slack channel
     *
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set message text
     *
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get message text
     *
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Submit a POST request
     */
    public function post()
    {
        $env = $this->container->get('kernel')->getEnvironment();

        if ($env !== 'prod' || !filter_var($this->url, FILTER_VALIDATE_URL))
        {
            return new Response(
                400,
                array(),
                null,
                '1.1',
                'Env is not PROD or URL is invalid'
            );
        }

        try {
            return $this->httpClient->post(
                $this->url,
                [
                    'body' => json_encode(
                        [
                            'channel' => $this->channel,
                            'text' => $this->text
                        ]
                    ),
                    'allow_redirects' => false,
                    'timeout' => 5
                ]
            );
        } catch (RequestException $e) {
            return $e->getResponse();
        }
    }
}
