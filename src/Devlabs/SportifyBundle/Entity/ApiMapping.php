<?php

namespace Devlabs\SportifyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Devlabs\SportifyBundle\Entity\ApiMappingRepository")
 * @ORM\Table(name="api_mappings", uniqueConstraints={
 *      @ORM\UniqueConstraint(
 *          name="entity_id_type_api_name", columns={"entity_id", "entity_type", "api_name"}
 *      ),
 *      @ORM\UniqueConstraint(
 *          name="entity_type_api_name_object_id", columns={"entity_type", "api_name", "api_object_id"}
 *      )
 * })
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
     * @ORM\Column(type="string", length=50, name="api_name")
     */
    private $apiName;

    /**
     * @ORM\Column(type="integer", name="api_object_id")
     */
    private $apiObjectId;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return ApiMapping
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return ApiMapping
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set entityType
     *
     * @param string $entityType
     *
     * @return ApiMapping
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set apiName
     *
     * @param string $apiName
     *
     * @return ApiMapping
     */
    public function setApiName($apiName)
    {
        $this->apiName = $apiName;

        return $this;
    }

    /**
     * Get apiName
     *
     * @return string
     */
    public function getApiName()
    {
        return $this->apiName;
    }

    /**
     * Set apiObjectId
     *
     * @param integer $apiObjectId
     *
     * @return ApiMapping
     */
    public function setApiObjectId($apiObjectId)
    {
        $this->apiObjectId = $apiObjectId;

        return $this;
    }

    /**
     * Get apiObjectId
     *
     * @return integer
     */
    public function getApiObjectId()
    {
        return $this->apiObjectId;
    }
}
