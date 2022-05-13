<?php

namespace Nathan45\Inventories;

use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class DatabaseManager
{
    use SingletonTrait;

    private bool $enabled = false;
    private string $path;
    protected \SQLite3 $db;

    public function isEnabled(): bool{
        return $this->enabled;
    }

    /**
     * @return void
     * this function must be called every time your server is restarted.
     * You can check if it has already been called with the isEnabled() function
     */
    public function init(): void{
        $this->path = "/home/container/plugin_data/InventoryManager/inventories.db";

        $this->db = new \SQLite3($this->path);
        $this->db->query("CREATE TABLE IF NOT EXISTS `player_data` (`username` VARCHAR(255), `inventories` TEXT)");
        $this->db->close();
        Server::getInstance()->getAsyncPool()->submitTask(new LoadItTask($this->path));
    }

    public function sendData(string $query): void{
        Server::getInstance()->getAsyncPool()->submitTask(new SendDataTask($query, $this->path));
    }
}