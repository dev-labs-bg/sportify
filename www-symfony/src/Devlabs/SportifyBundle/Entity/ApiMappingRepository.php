<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class ApiMappingRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class ApiMappingRepository extends \Doctrine\ORM\EntityRepository
{
    public function getByEntityTypeAndApiObjectId($entityType, $apiName, $apiObjectId)
    {
        $query =  $this->getEntityManager()->createQueryBuilder()
            ->select('am')
            ->from('DevlabsSportifyBundle:ApiMapping', 'am')
            ->where('am.entityType = :entity_type')
            ->andWhere('am.apiName = :api_name')
            ->andWhere('am.apiObjectId = :api_object_id')
            ->setParameters(array(
                'entity_type' => $entityType,
                'api_name' => $apiName,
                'api_object_id' => $apiObjectId
            ));

        try {
            return $query->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * MethodMethod for getting a single ApiMapping object
     * by passing Entity Object, Entity type and API name
     *
     * @param $entityObject
     * @param $entityType
     * @param $apiName
     * @return mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByEntityAndApiProvider($entityObject, $entityType, $apiName)
    {
        $query =  $this->getEntityManager()->createQueryBuilder()
            ->select('am')
            ->from('DevlabsSportifyBundle:ApiMapping', 'am')
            ->where('am.entityId = :entity_id')
            ->andWhere('am.entityType = :entity_type')
            ->andWhere('am.apiName = :api_name')
            ->setParameters(array(
                'entity_id' => $entityObject->getId(),
                'entity_type' => $entityType,
                'api_name' => $apiName
            ));

        try {
            return $query->getQuery()->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}
