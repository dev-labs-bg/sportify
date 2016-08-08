<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\ApiMappingRepository")
 * @ORM\Table(name="api_mappings")
 */
class ApiMapping
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="entity_id")
     */
    private $entityId;

    /**
     * @ORM\Column(type="string", length=50, name="entity_type")
     */
    private $entityType;

    /**
     * @ORM\Column(type="integer", name="api_id")
     */
    private $apiId;

    /**
     * @ORM\Column(type="string", length=50, name="api_type")
     */
    private $apiType;
}
