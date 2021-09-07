
<?php
	include("Domino.php");
	$data = [ 'status' => '' ];
	header('Content-Type: application/json');
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$piece = $_POST["piece"];
		$direction = $_POST["direction"];
		
		
		
		$status = '';
		if ( ($direction == 'left' || $direction == 'right') && $piece)
		{
			
			session_start();
			$domino = new Domino();
			$player = new Player();
			$currentPlayer = $domino->getCurrentPlayer();
			$added = $domino->checkAndAddToBoard($piece, $direction);
			if ($added)
			{
				if (count($player->getPieces($currentPlayer)) === 0)
				{
					// game finished
					$status = '2';
				}
				else
				{
					$currentPlayer = $domino->nextPlayer($currentPlayer);
					
					// check if next player has a valid move
					$playable = $domino->checkPlayablePieces();	
					$playerCount = 1;
					
					// loop to check next valid player
					while( !$playable && $playerCount <= $domino->getNoOfPlayers() ) {
						$piece = $domino->pickRandom(1);
						
						if ($piece == null)
						{
							$currentPlayer = $domino->nextPlayer($currentPlayer);
							$playerCount++;
						}
						else
						{
							$player->addPiece($currentPlayer, $piece[0]);
						}
						$playable = $domino->checkPlayablePieces();
					}
					
					if (!$playable)
					{
						// game finished
						$status = '2';
					}
					else
					{
						// next players turn
						$status = '1';
					}
				}
			}
			
			$data = [ 
			'status' => $status, 
			'players' => $domino->getNoOfPlayers(), 
			'player1' => $player->getPieces(1), 
			'player2' => $player->getPieces(2) , 
			'player3' => $player->getPieces(3) , 
			'player4' => $player->getPieces(4) , 
			'boneyard' => count($domino->getBoneyard()), 
			'currentPlayer' => $currentPlayer , 
			'board' => $domino->getBoard() 
			];
		}
		
		
	}
	
	echo json_encode( $data );
	
?>
