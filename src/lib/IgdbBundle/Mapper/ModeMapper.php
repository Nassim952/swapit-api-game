<?php

namespace App\lib\IgdbBundle\Mapper;

use App\Entity\Mode;
use Symfony\Component\Serializer;

class ModeMapper
{
    public static function map(array $data, \Doctrine\ORM\EntityManager $em): ?Mode
    {

        if ($em->getRepository(Mode::class)->find($data['id'])) {

            return null;
        }

        // $mode =  $serializer->deserialize($data, Mode::class, 'json');

        $mode = new Mode();

        $mode->setId($data['id']);
        $mode->setName($data['name']);
        $mode->setSlug($data['slug'] ?? null);
        $mode->setUrl($data['url'] ?? null);

        return $mode;
    }
}
