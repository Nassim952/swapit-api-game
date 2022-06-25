<?php

namespace App\Command;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\lib\IgdbBundle\IgdbWrapper\IgdbWrapper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAllGamesCoverCommand extends Command {

    private $igdb;
    private $em;

    public function __construct(IgdbWrapper $igdb, EntityManagerInterface $em)
    {
        $this->igdb = $igdb;
        $this->em = $em;
        parent::__construct();
    }

    public function updateCovers(){
        $games = new Game();
        
        $games = $this->em->getRepository(Game::class)->findAll();

        foreach($games as $game) {
            if($game->getCover() == null) {
                $cover = $this->igdb->getGameCovers($game->getId());
                if($cover != null) {
                    $game->setCover($cover);
                    $this->em->persist($game);
                    $this->em->flush();
                }
            }
        }
    }
    

    protected function configure () {
        // On set le nom de la commande
        $this->setName('app:getcovers');

        // On set la description
        $this->setDescription("Récupère les donnés de l'api IGDB");

        // On set l'aide
        $this->setHelp("This commande allow you to load into de database data from the IGDB API");

    }

    public function execute (InputInterface $input, OutputInterface $output) {
        ini_set('memory_limit', '1024M');
        $this->updateCovers();
        $output->write('Covers mis à jour avec succès !');
        return Command::SUCCESS;
    }
}