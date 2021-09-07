<?php
	include("Player.php");
	class Domino
	{
		
		private $boneyard = array('01', '02', '03', '04', '05', '06', '12', '13', '14', '15', '16', '23', '24', '25', '26', '34', '35', '36', '45', '46', '56', '00', '11', '22', '33', '44', '55', '66');		
		
		private $board = array();
		
		public function shufflePieces()
		{
			shuffle($this->boneyard);
		}
		
		public function pickRandom(int $amount): ? array
		{
			if ($amount <= count($this->boneyard)) {
				$boneyard = array_splice($this->boneyard, 0, $amount);
				$this->setBoneyard();
				return $boneyard;
			}
			return null;
		}
		
		public function getPieces(): array
		{
			return $this->boneyard;
		}
		
		public function getBoard(): array
		{
			$this->board = $_SESSION['board'];
			return $this->board;
		}
		
		public function getBoneyard(): array
		{
			$this->boneyard = $_SESSION['boneyard'];
			return $this->boneyard;
		}
		
		public function setBoard()
		{
			$_SESSION['board'] = $this->board;
		}
		
		public function addPiece(string $piece, string $direction)
		{
			if ($direction == 'left')
			{
				array_unshift($this->board, $piece);
			}
			else
			{
				array_push($this->board, $piece);
			}
			$_SESSION['board'] = $this->board;
		}
		
		public function setBoneyard()
		{
			$_SESSION['boneyard'] = $this->boneyard;
		}
		
		public function checkFirstPlayer(): array
		{	
			
			$doubleFound = '';
			$currentLargest = 0;
			$firstPlayer = '';
			for($i=$_SESSION['players']; $i >= 1; $i--) 
			{
				$player = new Player();
				$pieces = $player->getPieces($i);
				foreach ($pieces as $value) {
					if ($value[0] == $value[1])
					{
						if ($value[0] >= $currentLargest[0])
						{
							$currentLargest = $value;
							$firstPlayer = $i;
						}
						$doubleFound = true;
					}
				}
			}
			
			if ($doubleFound == '')
			{
				for($i=1; $i <= $_SESSION['players']; $i++) 
				{
					$player = new Player();
					$pieces = $player->getPieces($i);
					foreach ($pieces as $value) {
						
						if ($value[0] + $value[1] > $currentLargest)
						{
							$currentLargest = $value;
							$firstPlayer = $i;
						}
					}
				}
			}
			
			return array($firstPlayer , $currentLargest);
		}
		
		public function nextPlayer(int $currentPlayer)
		{	
			$players = $_SESSION['players'];
			if ($currentPlayer == $players) {
				$_SESSION['currentPlayer'] = 1;
				return 1;
			}
			else
			{
				$currentPlayer++;
				$_SESSION['currentPlayer'] = $currentPlayer;
				return $currentPlayer;
			}
		}
		
		public function getNoOfPlayers()
		{	
			return $_SESSION['players'];
		}
		
		public function getCurrentPlayer()
		{	
			return $_SESSION['currentPlayer'];
		}
		
		public function checkPlayablePieces(): ? bool
		{	
			$currentPlayer = $_SESSION['currentPlayer'];
			$player = new Player();
			$pieces = $player->getPieces($currentPlayer);
			
			if ($this->checkSides('', $pieces)[0] == true)
			{
				return true;
			}
			
			return false;
		}
		
		private function checkSides(string $direction, array $pieces): array
		{	
			$board = $this->getBoard();
			if (count($board) == 1)
			{
				$left = $board[0][0];
				$right = $board[0][1];
			}
			else
			{
				$left = $board[0][0];
				$right = $board[ count($board) - 1 ][1];
			}
			
			foreach ($pieces as $piece)
			{
				if ($direction )
				{
					if ($direction == 'left')
					{
						// check if piece needs to be flipped
						if($piece[0] == $left)
						{
							return array(true,true);
						}
						if($piece[1] == $left)
						{
							return array(true,false);
						}
					}
					if ($direction == 'right')
					{
						// check if piece needs to be flipped
						if($piece[1] == $right)
						{
							return array(true,true);
						}
						if($piece[0] == $right)
						{
							return array(true,false);
						}
					}
				}
				else
				{
					if ($piece[0] == $left || $piece[1] == $right || $piece[0] == $right || $piece[1] == $left)
					{
						return array(true);
					}
				}
			}
			
			return array(false);
		}
		
		
		public function checkAndAddToBoard(string $piece, string $direction): bool
		{	
			$currentPlayer = $_SESSION['currentPlayer'];
			$player = new Player();
			$pieces = $player->getPieces($currentPlayer);
			$board = $this->getBoard();
			
			// check if valid piece
			$pos = array_search($piece, $pieces );
			if ( isset($pieces[$pos]) )
			{
				$pieces = array();
				array_push($pieces, $piece);
				$validatePiece = $this->checkSides($direction, $pieces);
				if ($validatePiece[0] == true)
				{
					$player->removePiece($currentPlayer, $piece);
					
					// flip the piece
					if ($validatePiece[1] == true)
					{
						$flippedPiece = $piece[1] . $piece[0];
						$this->addPiece($flippedPiece, $direction);
						return true;
					}
					else
					{
						$this->addPiece($piece, $direction);
						return true;
					}
				}
				else
				{
					return false;
				}
				
			}
			else
			{
				return false;
			}
		}
		
		
	}
	
?>
