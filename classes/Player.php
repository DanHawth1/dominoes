<?php

    class Player
    {
        private $playerId;
        private $playerVisualNumber;
        private $name;
        private $playerTurn;
        private $playerHand;
        private $playerScore;

        function __construct($id)
        {          
            $this->setPlayerId($id);
            $this->playerSetup();
        }

        private function playerSetup()
        {
            $playerName = readline("Please enter player " . $this->getPlayerVisualNumber() . "'s name: ");
            $this->setName($playerName);
            $this->setPlayerTurn(false);
        }

        /** PLAYER ID METHODS */
        private function setPlayerId($id)
        {
            $this->playerId = $id;
            $this->setPlayerVisualNumber($id);
        }

        public function getPlayerId()
        {
            return $this->playerId;
        }
        
        /** PLAYER NUMBER METHODS */
        private function setPlayerVisualNumber($id)
        {
            $this->playerVisualNumber = $id + 1;
        }

        public function getPlayerVisualNumber()
        {
            return $this->playerVisualNumber;
        }

        /** PLAYER NAME METHODS */
        private function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }

        /** PLAYER TURN METHODS */
        public function setPlayerTurn($status)
        {
            $this->playerTurn = $status;
        }

        public function getPlayerTurn()
        {
            return $this->playerTurn;
        }

        /** PLAYER HAND METHODS */
        public function addDominoToHand($dominoId) 
        {
            $this->playerHand[] = ['id' => $dominoId];
        }

        public function removeDominoFromHand($dominoId) 
        {
            foreach( $this->playerHand as $id => $domino )
                if( $domino['id'] == $dominoId )
                    unset($this->playerHand[$id]);

            $this->playerHand = array_values($this->playerHand);
        }

        public function getPlayerHand(){
            return $this->playerHand;
        }

        public function getDominoFromHand($id){
            return $this->playerHand[$id]['id'];
        }

        public function showPlayerHand()
        {   
            $renderString = "Select a domino in your hand by entering a number from 1 to 7, correlating to the domino position in your hand.\nYour Hand: ";

            $i = 1;

            foreach( $this->playerHand as $handId => $domino ) {
                $renderString = $renderString . "<" . $domino['dominoData']->getHead() . ":" . $domino['dominoData']->getTail() . "> ";
                
            }

            return $renderString;
        }

        public function setPlayerScore($score)
        {
            $this->playerScore = $score;
        }

        public function getPlayerScore()
        {
            return $this->playerScore;
        }

    }

?>