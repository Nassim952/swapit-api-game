<?php

namespace App\Controller;

use App\Entity\Game;
use App\lib\IgdbBundle\IgdbWrapper\IgdbWrapper;

class PatchCoverController{

    private $igdb;

    public function __construct(IgdbWrapper $igdb){
        $this->igdb = $igdb;
    }

    public function __invoke(Game $data): Game {

        $cover = $this->igdb->getGameCovers($data->getId());

        $data->setCover($cover);
        return $data;
    }
}