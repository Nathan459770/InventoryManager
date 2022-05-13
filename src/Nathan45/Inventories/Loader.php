<?php

namespace Nathan45\Inventories;

use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    protected function onEnable(): void
    {
        $db = DatabaseManager::getInstance();

        if(!$db->isEnabled()){
            $db->init();
        }
    }
}
