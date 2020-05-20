<?php

    class Game
    {
        
        private $deck;
        private $players;
        private $gameOrder;

        function __construct()
        {
            // Clear the game display quickly
            Game::clearDisplay();

            // Generate deck
            $this->deck = New Deck();

            // Now setup players
            $this->players = New Players();

            // Now allow players to select their hand
            $this->playerHandSelection();   

            // Create the player order
            $this->createGameOrder();
            
            // Starting PLaying the game
            $this->playGame();
            
            // Once we are out of the playgame stage and it is determind some is either out of peices or 
            // they can go and pick add up the remaining totals to display a winner message
            $this->gernerateWinnerMessage();

        }

        private function playerHandSelection()
        {
            // Its now time for the players to select their 7 starting tiles and loop each player each time
            for( $i = 0; $i < 7; $i++ ) {
                foreach( $this->players->getPlayers() as $player ) {
                    Game::clearDisplay();
                    $this->selectDominoFromDeck($player);
                }
            }

        }

        private function playGame()
        {
            do {
                foreach( $this->gameOrder as $player ) {

                    $currentPlayer = $this->players->getPlayer($player);

                    $currentPlayer->setPlayerTurn(true);

                    Game::clearDisplay();
                    
                    if( $this->checkPlayersCanMakeMove($currentPlayer)) {
                        do {
                            Game::clearDisplay();

                            echo $currentPlayer->getName() . "(player " . $currentPlayer->getPlayerVisualNumber() . "'s) turn \n\n";

                            if(isset($dominId))
                                echo "Please select a valid domino and direction to play\n\n";

                            // Show the cure
                            $this->showGameBoard();
                            $this->showPlayerHand();

                            $selectedDomino = readline("Enter domino number here: ");
                            $selectedDirection = readline("Enter domino direction, Left-to-Right (LR) and Right-to-Left (RL): ");
                            
                            // To get the domino hand id just remove one from the visual number
                            $selectedDominoId = $selectedDomino - 1;

                            // To get the domino id related to the deck we need to get the domino hand object which contains the id
                            $dominId = $currentPlayer->getDominoFromHand($selectedDominoId);

                        } while($this->deck->validateDominoChoice($dominId, $selectedDirection) == false);

                        $this->deck->addToBoard($dominId, $selectedDirection);
                        $currentPlayer->removeDominoFromHand($dominId);

                        Game::clearDisplay();

                        $this->selectDominoFromDeck($currentPlayer);

                        unset($dominId);
                    } else {
                        echo $currentPlayer->getName() . " (player " . $currentPlayer->getPlayerVisualNumber() . ") You can't go, press enter to continue\n\n";
                        readline("");
                        Game::clearDisplay();
                        echo "Please select a domino to pick up as you couldn't go.\n\n";
                        $this->selectDominoFromDeck($currentPlayer);
                    }

                    $currentPlayer->setPlayerTurn(false);
                }
            } while($this->checkPlayersCanMakeMove());

            Game::clearDisplay();
        }

        private function selectDominoFromDeck($player) 
        {
            if(empty($this->deck->getDominoStillInDeck()))
                return false;
            
            do {
                
                // If dominoId is still set, we know we are still in the do while loop so should show an error message
                if( isset($dominoId) ) {
                    Game::clearDisplay();
                    echo "Error. Please make sure you are entering a number only and selecting an avalible domino \n\n";
                }

                // Show Avalible Domino
                $this->showRemainingDominoes();

                // Wait for the users input on what domino they would like to select from the remaining
                $dominoNumber = readline($player->getName() . "(player " . $player->getPlayerVisualNumber() . ") please select an avalible domino by entering a number between 1 to 28: " );
                
                // To get the id we just need to remove 1 from the display number
                if(is_numeric($dominoNumber))
                    $dominoId = $dominoNumber - 1;
                else
                    $dominoId = $dominoNumber;

            } while($this->deck->validatePickup($dominoId) === false);

            // Add domino to players hand
            $player->addDominoToHand($dominoId);

            // Remover saved id
            unset($dominoId);
        }

        private function showRemainingDominoes()
        {
            $renderString = "Available Dominoes Left: ";

            // Now loop the array of domino still in the deck and echo out the display number
            foreach( $this->deck->getDominoStillInDeck() as $key => $item )
                $renderString = $renderString . $item['displayNumber'] . ", ";

            echo $renderString . "\n\n";

            unset($renderString);
        }

        private function createGameOrder()
        {
            $this->gameOrder = array();

            $highestDouble = 0;
            $startingPlayerId = 0;

            foreach( $this->players->getPlayers() as $id => $player ){
                foreach( $player->getPlayerHand() as $handId => $dominoId ) {
                    $domino = $this->deck->getDomino($dominoId['id']);
                    if( $domino->getHead() == $domino->getTail() && $domino->getWeight() > $highestDouble ) {
                        $startingId = $player->getPlayerId();
                        $highestDouble = $domino->getWeight();
                    }
                }
            }

            $this->players->getPlayer($startingId)->setPlayerTurn(true);

            Game::clearDisplay();

            echo $this->players->getPlayer($startingId)->getName() . "(player " . $this->players->getPlayer($startingId)->getPlayerVisualNumber() . ") will be going first. Press enter to start";
            readline("");

            for($i = 0; $i < count($this->players->getPlayers()); $i++) {
                if( $i == $this->players->getCurrentPlayersTurn() )    
                    array_unshift($this->gameOrder,$i);
                else
                    $this->gameOrder[] = $i;
            }
        }

        private function showPlayerHand()
        {
            $renderString = "Your Hand: ";
            $playerHand = $this->players->getPlayer($this->players->getCurrentPlayersTurn())->getPlayerHand();
            
            foreach($playerHand as $dominoId) {
                $domino = $this->deck->getDomino($dominoId['id']);
                $renderString = $renderString . "<" . $domino->getHead() . ":" . $domino->getTail() . "> ";                
            }

            echo $renderString . "\n\nSelect a domino in your hand by entering a number from 1 to " . count($playerHand) . ", correlating to the domino position in your hand.\n\n";
        }

        private function showGameBoard()
        {
            $renderString = "Current Board: ";
            
            if($this->deck->getBoard())
                foreach($this->deck->getBoard() as $domino)
                    $renderString = $renderString . "<" . $domino['left'] . ":" . $domino['right'] . ">";

            echo $renderString . "\n\n";
        }

        private function checkPlayersCanMakeMove($player = null)
        {
            // If board is false is must be empty and so this is only the first move
            if($this->deck->getBoard() == false)
                return true;

            // Get players as we us them in two places within this script
            $players = $this->players->getPlayers();

            // if null check all players
            if( $player == null ) {
                foreach($players as $player) {
                    foreach($player->getPlayerHand() as $dominoInHand) {
                        $dominId = $dominoInHand['id'];     
                        if( $this->deck->validateDominoChoice($dominId, "LR") || $this->deck->validateDominoChoice($dominId, "RL") )
                            $playableOptions = true;         
                    }
                }  
            // if isn't null check the inputted player only
            } else {
                $playableOptions = false;

                foreach($player->getPlayerHand() as $dominoInHand) {
                    $dominId = $dominoInHand['id'];     
                    if( $this->deck->validateDominoChoice($dominId, "LR") || $this->deck->validateDominoChoice($dominId, "RL") )
                        $playableOptions = true;           
                }

            }

            // We also need to check that no one has ran out of domino, if so the game must end
            foreach($players as $player)
                if( count($player->getPlayerHand()) < 1 )
                    $playableOptions = false;

            return $playableOptions;
        }

        private function gernerateWinnerMessage()
        {
            foreach($this->players->getPlayers() as $player) {

                $playerTotal = 0;

                foreach($player->getPlayerHand() as $dominoInHand){
                    $domino = $this->deck->getDomino($dominoInHand['id']);
                    $playerTotal = $playerTotal + $domino->getWeight();
                }

                $player->setPlayerScore($playerTotal);

                if( isset($highestScore) == false || $player->getPlayerScore() <= $highestScore ) {
                    $highestScore = $player->getPlayerScore();
                    $winningPlayer = $player->getPlayerId();
                }
            }

            // Print Winner Message
            $winningPlayer = $this->players->getPlayer($winningPlayer);
            echo "\n\nWell done " . $winningPlayer->getName() . "(player " . $winningPlayer->getPlayerVisualNumber() . ") you have won the game with a score of " . $winningPlayer->getPlayerScore() . "\n\n";
        }

        public static function clearDisplay()
        {
            echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
        }

    }

?>