<?php

    class Players
    {
        private $players;

        function __construct()
        {
            // Run gamesetup
            $this->createPlayers();
        }

        private function createPlayers()
        {
            // Display quick welcome message
            echo "Welcome to dominos \n";

            // Ask the user for how many players will be playing
            do {
                $playerCountInput = readline("Please enter how many people are playing (min 2, max 4 players): "); 
                Game::clearDisplay();
            } while( $this->validatePlayerCount($playerCountInput) === false );
            
            for( $i = 0; $i < $playerCountInput; $i++ ) {
                $this->players[] = New Player($i);
            }
        }

        private function validatePlayerCount($playerCountInput)
        {
            if( $playerCountInput < 2 || $playerCountInput > 4 ) {
                echo "Please enter a player count between 2 and 4. \n";
                return false;
            } elseif( is_numeric($playerCountInput) === false ) {
                echo "Please enter numbers only to set your player count. \n";
                return false;
            } else {
                return true;
            }
        }

        public function getPlayers()
        {
            return $this->players;
        }

        public function getPlayer($id)
        {  
            return $this->players[$id];
        }

        public function getPlayerCount()
        {
            return count($this->players);
        }

        public function getCurrentPlayersTurn()
        {
            foreach($this->players as $player)
                if( $player->getPlayerTurn() === true )
                    return $player->getPlayerId();
        }

    }

?>