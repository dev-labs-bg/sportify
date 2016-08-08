<?php

namespace Devlabs\SportifyBundle\Entity;

/**
 * Class ApiMappingRepository
 * @package Devlabs\SportifyBundle\Entity
 */
class ApiMappingRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * MethodMethod for getting a single ApiMapping object
     * by passing Entity Object, Entity type and API name
     *
     * @param $entityObject
     * @param $entityType
     * @param $apiType
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
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
