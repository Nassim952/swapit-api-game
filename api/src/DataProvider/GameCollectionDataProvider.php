<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use App\lib\IgdbBundle\IgdbWrapper\IgdbWrapper;
use App\Repository\GameRepository;
use App\lib\IgdbBundle\Mapper\GameMapper;
use App\Entity\Game;

final class GameCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $igdb;
    private $repository;
    private $collectionDataProvider;

    public function __construct(CollectionDataProviderInterface $collectionDataProvider, IgdbWrapper $igdb, GameRepository $gameRepository) {
        $this->igdb = $igdb;
        $this->repository = $gameRepository;
        $this->collectionDataProvider = $collectionDataProvider;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Game::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        if(isset($context['filters']['slug'])){
            $game = $this->repository->findOneByName($context['filters']['slug']);
            
            if(!$game){
                $this->searchGame($context['filters']['slug']);
            }
    }
        return $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);
    }

    private function searchGame(string $data)
    {
        $games = $this->igdb->searchGame($data);
        // dd($games);
        if ($games) {
            is_array($games) ? $this->igdb->serializeDatas($games,GameMapper::class) : $this->igdb->serializeData($games,GameMapper::class);
        }
    }
}