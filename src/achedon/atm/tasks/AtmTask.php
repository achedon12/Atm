<?php

namespace achedon\atm\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class AtmTask extends Task{

    public static array $cache = [];

    public function __construct(){
    }

    public function onRun(): void
    {
        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            if($player->isConnected()){
                if(isset(self::$cache[$player->getXuid()])){
                    self::$cache[$player->getXuid()]++;
                    var_dump(self::$cache);
                }else{
                    AtmTask::$cache[$player->getXuid()] = 0;
                }

            }
        }
    }
}