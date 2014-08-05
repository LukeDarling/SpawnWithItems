<?php

namespace LDX\SpawnWithItems;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;

class Main extends PluginBase implements Listener {
  public function onLoad() {
    $this->getLogger()->info(TextFormat::YELLOW . "Loading SpawnWithItems v2.0 by LDX...");
  }
  public function onEnable() {
    if(!file_exists($this->getDataFolder() . "config.yml")) {
      @mkdir($this->getDataFolder());
      file_put_contents($this->getDataFolder() . "config.yml",$this->getResource("config.yml"));
    }
    $c = yaml_parse(file_get_contents($this->getDataFolder() . "config.yml"));
    $num = 0;
    foreach ($c["items"] as $i) {
      $r = explode(":",$i);
      $this->itemdata[$num] = array($r[0],$r[1],$r[2]);
      $num++;
    }
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    $this->getLogger()->info(TextFormat::YELLOW . "Enabling SpawnWithItems...");
  }
  /**
  * @param PlayerRespawnEvent $event
  *
  * @priority HIGHEST
  * @ignoreCancelled true
  */
  public function playerSpawn(PlayerRespawnEvent $event) {
    if($event->getPlayer()->hasPermission("spawnwithitems") || $event->getPlayer()->hasPermission("spawnwithitems.receive")) {
      foreach($this->itemdata as $i) {
        $item = new Item($i[0],$i[1],$i[2]);
        $event->getPlayer()->getInventory()->addItem($item);
      }
    }
  }
  public function onDisable() {
    $this->getLogger()->info(TextFormat::YELLOW . "Disabling SpawnWithItems...");
  }
}
?>
