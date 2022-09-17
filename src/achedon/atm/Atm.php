<?php

namespace achedon\atm;

use achedon\atm\commands\AtmCommand;
use achedon\atm\tasks\AtmTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Atm extends PluginBase{

    use SingletonTrait;

    public static int $value;
    public static string $prefix;

    protected function onEnable(): void
    {
        self::$instance = $this;

        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");

        self::$value = (int)self::config()->get("value");
        self::$prefix = self::config()->get("prefix")." §r";
        
        $this->getServer()->getCommandMap()->register('atm',new AtmCommand());

        $this->getScheduler()->scheduleRepeatingTask(new AtmTask(),20);

        if(!$this->getServer()->getPluginManager()->getPlugin("BedrockEconomy")){
            $this->getLogger()->alert("You don't have BedrockEconomy on your server, please download it before use this plugin");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    private function config(): Config{
        return new Config(self::$instance->getDataFolder()."config.yml",Config::YAML);
    }

}