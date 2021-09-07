<?php
	
	class Player
	{
		
		private $pieces = array();
		
		public function getPieces(int $player): array
		{	
			$this->pieces = isset($_SESSION['player' . $player]) ? $_SESSION['player' . $player] : array();  ;
			return $this->pieces;
		}
		public function set(int $player, array $pieces)
		{	
			$this->pieces = $pieces;
			$_SESSION['player' . $player] = $pieces;
		}
		
		public function removePiece(int $player, string $piece)
		{	
			/* $this->getPieces($player);
			
			$pos = array_search($piece, $this->pieces );
			unset($this->pieces[$pos]);
			$this->set($player, $this->pieces); */
			
			$pieces = $this->getPieces($player);
			$piecesNew = array();
			foreach ($pieces as $value)
			{
				if ($value != $piece)
				{
					array_push($piecesNew, $value);
				}
			}
			$this->set($player, $piecesNew);
		}
		
		public function addPiece(int $player, string $piece)
		{	
			$this->getPieces($player);
			array_push($this->pieces, $piece);
			$this->set($player, $this->pieces);
		}
		
	}
	
?>
