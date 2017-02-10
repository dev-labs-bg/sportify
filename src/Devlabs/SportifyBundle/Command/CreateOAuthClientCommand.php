<?php

namespace Devlabs\SportifyBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CreateOAuthClientCommand
 * @package Devlabs\SportifyBundle\Command
 */
class CreateOAuthClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create OAuth Client')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Client Name?'
            )
            ->addArgument(
                'redirectUri',
                InputArgument::REQUIRED,
                'Redirect URI?'
            )
            ->addArgument(
                'grantType',
                InputArgument::REQUIRED,
                'Grant Type?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $oauthServer = $container->get('fos_oauth_server.server');

        $name = $input->getArgument('name');
        $redirectUri = $input->getArgument('redirectUri');
        $grantType = $input->getArgument('grantType');

        $clientManager = $container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($name);
        $client->setRedirectUris([$redirectUri]);
        $client->setAllowedGrantTypes([$grantType]);
        $clientManager->updateClient($client);

        $output->writeln(
            sprintf(
                "<info>The client <comment>%s</comment> was created with <comment>%s</comment> as public id and <comment>%s</comment> as secret</info>",
                $client->getName(),
                $client->getPublicId(),
                $client->getSecret()
            )
        );

        $users = $container->get('doctrine')
            ->getRepository('DevlabsSportifyBundle:User')
            ->findAll();

        foreach ($users as $user) {
            $queryData = [];
            $queryData['client_id'] = $client->getPublicId();
            $queryData['redirect_uri'] = $client->getRedirectUris()[0];
            $queryData['response_type'] = 'code';
            $authRequest = new Request($queryData);

            $oauthServer->finishClientAuthorization(true, $user, $authRequest, $grantType);

            $output->writeln(
                sprintf(
                    "<info>Customer <comment>%s</comment> linked to client <comment>%s</comment></info>",
                    $user->getId(),
                    $client->getName()
                )
            );
        }
    }
}
