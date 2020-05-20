<?php

    class Deck
    {
        private $dominoes;
        private $dominoStillInDeck;
        private $board;

        function __construct()
        {
            // Run gamesetup
            $this->generateDeck();
        }

        private function generateDeck()
        {
            $y = 0;

            for ($i = 0; $i <= 6; $i++)
                for ($j = $i; $j <= 6; $j++, $y++)
                    $this->dominoes[] = New Domino($y,$i,$j);
            
            // Shuffle Deck
            shuffle($this->dominoes);
        }

        public function getDominoStillInDeck()
        {
            $returnArray = array();

            foreach( $this->dominoes as $key => $domino )
                if( $domino->getInDeck() )
                    $returnArray[] = ['id' => $key, 'displayNumber' => ($key + 1)];

            return $returnArray;
        }

        public function validatePickup($dominoId)
        {
            if( is_numeric($dominoId) == false)
                return false;

            if( $dominoId > 27 )
                return false;

            if( $dominoId < 0 )
                return false;

            if( $this->dominoes[$dominoId]->getInDeck() === false )
                return false;
            
            $this->dominoes[$dominoId]->setInDeck(false);
        }

        public function addToBoard($dominoId, $dir)
        {
            $domino = $this->getDomino($dominoId);

            // CHANGE TO AUTO SELECT DIRECTION
            if($dir == 'LR')
                $this->board[] = ['left' => $domino->getHead(), 'right' => $domino->getTail()];
            elseif($dir == 'RL')
                $this->board[] = ['left' => $domino->getTail(), 'right' => $domino->getHead()];

            $domino->setInDeck(false);
        }

        public function validateDominoChoice($dominoId, $dir)
        {   
            if( is_numeric($dominoId) != true )
                return false;
            
            if( $dir != 'LR' && $dir != 'RL' )
                return false;

            if($this->board == false)
                return true;

            $domino = $this->getDomino($dominoId);

            $lastBoardItem = end($this->board);
            
            if($dir == 'LR' && $lastBoardItem['right'] == $domino->getHead()) {
                return true;
            } elseif($dir == 'RL'&& $lastBoardItem['right'] == $domino->getTail()) {
                return true;
            } else
                return false;
        }

        public function getDomino($id)
        {
            return $this->dominoes[$id];
        }

        public function getBoard()
        {
            return $this->board;
        }

    }

?>