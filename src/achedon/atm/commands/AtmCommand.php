<?php

namespace achedon\atm\commands;

use achedon\atm\Atm;
use achedon\atm\tasks\AtmTask;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\SingletonTrait;

class AtmCommand extends Command implements PluginOwned {

    use SingletonTrait;

    public function __construct(string $name = "atm", Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            return;
        }
        if(isset(AtmTask::$cache[$sender->getXuid()])){
            $value = AtmTask::$cache[$sender->getXuid()];
            if($value < 60){
                $sender->sendMessage(Atm::$prefix."Please wait more time to win money");
                return;
            }
            AtmTask::$cache[$sender->getXuid()] -= round($value);
            $amount = round($value) * Atm::$value;
            $sender->sendMessage(Atm::$prefix."You have won {$amount}");
            BedrockEconomyAPI::legacy()->addToPlayerBalance($sender->getName(),$amount);
        }
    }

    public function getOwningPlugin(): Plugin
    {
        return Atm::getInstance();
    }
}
