<?php
namespace App\lib\IgdbBundle\Mapper;

use App\Entity\Game;
use App\Entity\Mode;
use App\Entity\Genre;
use App\Entity\Company;
use App\Entity\Platform;
use Symfony\Component\Serializer;

class GameMapper
{

    public static function map(array $data, \Doctrine\ORM\EntityManager $em): ?Game 
    {

        if ($em->getRepository(Game::class)->find($data['id'])) {
            return null;
        }
        $game = new Game();

        // $game =  $serializer->deserialize($data, Game::class, 'json');
        
        $game->setId($data['id']);
        $game->setName($data['name']);
        $game->setSlug($data['slug']);
        $game->setFirstReleaseDate($data['first_release_date'] ?? 0);
        $game->setStatus($data['status'] ?? '');
        $game->setStoryline($data['storyline'] ?? '');
        $game->setSummary($data['summary'] ?? '');
        $game->setVersionTitle($data['version_title'] ?? '');
        $game->setAggregatedRating($data['aggregated_rating'] ?? 0.0);
        $game->setAggregatedRatingCount($data['aggregated_rating_count'] ?? 0);
        $game->setFollows($data['follows'] ?? null);

        if (array_key_exists('genres', $data)) {
            foreach ($data['genres'] as $genreId) {

                $genre = $em->getRepository(Genre::class)->find($genreId);
                if ($genre != null) {
                    $game->addGenre($genre);
                }


            }
        }

        if(array_key_exists('involved_companies', $data)) {
            foreach ($data['involved_companies'] as $companyId) {

                $company = $em->getRepository(Company::class)->find($companyId);
                if ($company != null) {
                    $game->addInvolvedCompany($company);
                }
            }
        }
        

        if(array_key_exists('platforms', $data)) {
            foreach ($data['platforms'] as $platformId) {

                
                $platform = $em->getRepository(Platform::class)->find($platformId);
                if ($platform != null) {
                    $game->addPlatform($platform);
                }
            }
        }

        if(array_key_exists('game_modes', $data)) {
            foreach ($data['game_modes'] as $modeId) {
                $mode = $em->getRepository(Mode::class)->find($modeId);
                if ($mode != null) {
                    $game->addMode($mode);
                }
            }
        }

        return $game;
    }

}
