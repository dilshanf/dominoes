
<?php
	include("Domino.php");
	$data = [ 'status' => '' ];
	header('Content-Type: application/json');
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$players = $_POST["players"];
		$dominoes = $_POST["dominoes"];
		
		if (empty($players)) 
		{
		}
		else
		{
			if ($players == 2 || $players == 3 || $players == 4)
			{
				
				$domino = new Domino();
				$domino->shufflePieces();
				session_start();
				$_SESSION = array();
				
				$_SESSION['players'] = $players;
				
				// assign 7 pieces to each player
				for($i=1; $i <= $players; $i++) 
				{
					if ($dominoes)
					{
						$pieces = $domino->pickRandom(intval($dominoes));
					}
					else
					{
						$pieces = $domino->pickRandom(7);
					}
					sort($pieces);
					$player = new Player();
					$player->set($i, $pieces);
				}
				
				// get 1st player and the piece
				$firstPlayer = $domino->checkFirstPlayer();
				$player->removePiece($firstPlayer[0], $firstPlayer[1]);
				
				// add first piece to board
				$domino->addPiece($firstPlayer[1], '');
				
				$currentPlayer = $domino->nextPlayer($firstPlayer[0]);
				$playable = $domino->checkPlayablePieces();
				
				while( !$playable ) {
					$piece = $domino->pickRandom(1);
					$player->addPiece($currentPlayer, $piece[0]);
					$playable = $domino->checkPlayablePieces();
				}
				
				$data = [ 
				'status' => '1', 
				'players' => $players, 
				'player1' => $player->getPieces(1), 
				'player2' => $player->getPieces(2) , 
				'player3' => $player->getPieces(3) , 
				'player4' => $player->getPieces(4) , 
				'boneyard' => count($domino->getBoneyard()), 
				'currentPlayer' => $currentPlayer , 
				'firstPiece' => $firstPlayer[1]  
				];
			}
			else
			{
			}
		}
		
	}
	
	echo json_encode( $data );
	
	
	
?>
