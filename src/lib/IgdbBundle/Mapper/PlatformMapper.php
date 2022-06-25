<?php

namespace App\lib\IgdbBundle\Mapper;

use App\Entity\Platform;
use Symfony\Component\Serializer;


class PlatformMapper
{
    public static function map(array $data, \Doctrine\ORM\EntityManager $em): ?Platform
    {
        if ($em->getRepository(Platform::class)->find($data['id'])) {

            return null;
        }

        // $platform =  $serializer->deserialize($data, Platform::class, 'json');

        $platform = new Platform();

        $platform->setId($data['id']);
        $platform->setName($data['name']);
        $platform->setSlug($data['slug'] ?? null);
        $platform->setUrl($data['url'] ?? null);

        return $platform;
    }
}
