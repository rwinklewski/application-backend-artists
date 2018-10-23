<?php

namespace App\Utils;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\Query\ResultSetMapping;

class TokenGenerator extends AbstractIdGenerator
{
    const LENGTH = 6;
    const TOKEN_NAME = 'default';

    /**
     * @param EntityManager $em
     * @param object $entity
     * @return mixed
     */
    public function generate(EntityManager $em, $entity): string
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('next_id', 'next_id');

        $queryBuilder = $em->createNativeQuery("SELECT GetNextTokenId(:token_name) AS next_id", $rsm);
        $queryBuilder->setParameter('token_name', self::TOKEN_NAME);
        $result = $queryBuilder->getResult();
        $tokenId = $result[0]['next_id'];

        //convert decimal number to number with base 36 (0-9A-Z)
        $token = base_convert($tokenId, 10, 36);
        $token = str_pad($token, self::LENGTH, '0', STR_PAD_LEFT);
        return strtoupper($token);
    }
}