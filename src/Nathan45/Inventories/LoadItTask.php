<?php

namespace Nathan45\Inventories;

use pocketmine\scheduler\AsyncTask;

class LoadItTask extends AsyncTask
{
    private string $path;
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function onRun(): void
    {
        $results = [];
        $db = new \SQLite3($this->path);
        $data = $db->query("SELECT * FROM `player_data`");
        while($resultArr = $data->fetchArray(SQLITE3_ASSOC)) $results[$resultArr["username"]] = unserialize(base64_decode($resultArr["inventories"]));
        $this->setResult($results);
        $db->close();
    }

    public function onCompletion(): void
    {
        $results = $this->getResult();
        InventoryManager::getInstance()->setAllData($results);
    }
}