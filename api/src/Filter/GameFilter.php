<?php
// api/src/Filter/RegexpFilter.php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use App\Repository\GameRepository;
use Symfony\Component\PropertyInfo\Type;
use App\lib\IgdbBundle\IgdbWrapper;
use Doctrine\ORM\EntityManagerInterface;

final class GameFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // otherwise filter is applied to order and page as well
        // if (
        //     !$this->isPropertyEnabled($property, $resourceClass) ||
        //     !$this->isPropertyMapped($property, $resourceClass)
        // ) {
        //     return;
        // }

        // if ($property !== 'search') {
        //     return;
        // }

        $alias = $queryBuilder->getRootAliases()[0];
        $parameterName = $queryNameGenerator->generateParameterName($property); // Generate a unique parameter name to avoid collisions with other filters
 

        // if ($property  == 'name') {

        //     $query = $entityManager->getRepository(Game::class)->createQueryBuilder('g')
        //         ->where('g.name LIKE :name')
        //         ->setParameter('name', '%' . $value . '%')
        //         ->getQuery();

        //     if (!$query->getResult()) {
        //         $games = $igdb->searchGame($value);
        //         if ($games) {
        //             is_array($games) ? $igdb->serializeDatas($games,'Game') : $igdb->serializeData($games,'Game');
        //         }
        //     }
  
        //     $queryBuilder = $queryBuilder->andWhere("$alias.name LIKE :$parameterName")
        //         ->setParameter($parameterName,"%{$value}%");
        // }

        // if ($property  == 'ids') {
        //     $queryBuilder = $queryBuilder->where("$alias.id IN (:$parameterName)")
        //         ->setParameter($parameterName, $value);
        // }

        if($property  == 'involved_companies'){
            $queryBuilder =  $queryBuilder->Join("$alias.$property", 'ge')->andWhere("ge.id IN (:$parameterName)")
                ->setParameter($parameterName, $value);
        }
        if($property  == 'genres') {
            $queryBuilder = $queryBuilder->Join("$alias.$property", 'ic' )->andWhere("ic.id IN (:$parameterName)")
                ->setParameter($parameterName, $value);
        }

        if($property  == 'platforms'){
            $queryBuilder = $queryBuilder->Join("$alias.$property", 'p')->andWhere("p.id IN (:$parameterName)")
                ->setParameter($parameterName, $value);
        }

        if($property  == 'modes'){
            $queryBuilder = $queryBuilder->Join("$alias.$property", 'm')->andWhere("m.id IN (:$parameterName)")
                ->setParameter($parameterName, $value);
        }

        if ($property  == 'popular') {
            $queryBuilder->andWhere("$alias.aggregated_rating >= 70")
                ->andWhere("$alias.aggregated_rating_count >= 20")
                ->addOrderBy("$alias.aggregated_rating")
                ->addOrderBy("$alias.aggregated_rating_count")
                ->setMaxResults(20);}
    }

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["filter_$property"] = [
                'property' => $property,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'swagger' => [
                    'description' => 'Filter games. This will appear in the Swagger documentation!',
                    'name' => 'Custom name to use in the Swagger documentation',
                    'type' => 'Will appear below the name in the Swagger documentation',
                ],
            ];
        }

        return $description;
    }
}