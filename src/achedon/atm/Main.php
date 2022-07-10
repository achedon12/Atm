<?php

namespace achedon\atm;

use achedon\atm\commands\Atm;
use achedon\atm\tasks\AtmTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase{

    private static Main $instance;
    public static int $value;
    public static string $prefix;

    protected function onEnable(): void
    {
        self::$instance = $this;

        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");

        self::$value = (int)self::config()->get("value");
        self::$prefix = self::config()->get("prefix")." §r";

        $this->getLogger()->info("§2Atm enable");

        $this->getServer()->getCommandMap()->registerAll('commands',[new Atm()]);

        $this->getScheduler()->scheduleRepeatingTask(new AtmTask(),20);

        if(!$this->getServer()->getPluginManager()->getPlugin("BedrockEconomy")){
            $this->getLogger()->alert("You don't have BedrockEconomy on your server, please download it before use this plugin");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    protected function onDisable(): void
    {
        $this->getLogger()->info("§4Atm disable");
    }

    public static function getInstance(): self{
        return self::$instance;
    }

    public static function config(): Config{
        return new Config(self::$instance->getDataFolder()."config.yml",Config::YAML);
    }

}