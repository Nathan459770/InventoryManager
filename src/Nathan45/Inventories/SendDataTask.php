<?php

namespace Nathan45\Inventories;

use pocketmine\scheduler\AsyncTask;

class SendDataTask extends AsyncTask
{
    public function __construct(private string $query, private string $path)
    {
    }

    public function onRun(): void
    {
        $db = new \SQLite3($this->path);
        $db->query($this->query);
        $db->close();
    }
}