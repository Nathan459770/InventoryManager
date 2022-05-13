<?php

namespace Nathan45\Inventories;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class InventoryManager
{
    use SingletonTrait;

    private DatabaseManager $db;

    public function __construct()
    {
        $this->db = DatabaseManager::getInstance();
    }

    public array $inventories = [];

    public function getAllData(): array{
        return $this->inventories;
    }

    public function setAllData(array $inv): void{
        $this->inventories = $inv;
    }

    /**
     * @param Player|string $player
     * @return array if the inventories doesn't exist, it will return an empty array
     * This function returns all inventories of the given player in the order
     */
    public function getInventoriesFor(Player|string $player): array{
        $this->adjustPlayer($player);
        return $this->inventories[$player];
    }

    /**
     * @param Player|string $player
     * @param string|int $inventoryId
     * @return array if the inventory doesn't exist, it will return an empty array
     * This function returns the player's inventory corresponding to the given id
     */
    public function getInventoryFor(Player|string $player, string|int $inventoryId): array{
        $this->adjustPlayer($player);
        return $this->inventories[$player][$inventoryId] ?? [];
    }

    /**
     * @param Player|string $player
     * @param array $inventories
     * @return void
     * This function sets all ths inventories of the given player in the order
     */
    public function setInventoriesFor(Player|string $player, array $inventories): void{
        $this->adjustPlayer($player);
        $this->inventories[$player] = $inventories;
        $this->updateDataFor($player);
    }

    /**
     * @param Player|string $player
     * @param string|int $id
     * @param array $inventories
     * @return void
     * This function sets the player's inventory corresponding to the given id
     */
    public function setInventoryFor(Player|string $player, string|int $id, array $inventories): void{
        $this->adjustPlayer($player);
        $this->inventories[$player][$id] = $inventories;
        $this->updateDataFor($player);
    }

    /**
     * @param string $player
     * @return void
     * This function stores the player's inventories in the database
     */
    public function updateDataFor(string $player): void{
        $this->db->sendData("UPDATE player_data SET inventories = '" . base64_encode(serialize($this->inventories[$player])) . "' WHERE player = '" . $player . "'");
    }

    public function adjustPlayer(Player|string &$player): void{
       $player = $player instanceof Player ? $player->getName() : $player;

       if(!isset($this->inventories[$player])){
           $this->inventories[$player] = [];
       }
    }
}