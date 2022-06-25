<?php

namespace App\lib\IgdbBundle\Mapper;

use App\Entity\Genre;
use Symfony\Component\Serializer;

class GenreMapper
{
    public static function map(array $data, \Doctrine\ORM\EntityManager $em): ?Genre
    {
        
        if ($em->getRepository(Genre::class)->find($data['id'])) {

            return null;
        }

        // $genre =  $serializer->deserialize($data, Genre::class, 'json');


        $genre = new Genre();

        $genre->setId($data['id']);
        $genre->setName($data['name']);
        $genre->setSlug($data['slug'] ?? null);
        return $genre;
    }

}
