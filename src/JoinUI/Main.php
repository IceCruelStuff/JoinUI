<?php

declare(strict_types=1);

namespace JoinUI;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getLogger()->info("§aJoinUI Enabled!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        
        $this->FormAPI = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        if (!$this->FormAPI or $this->FormAPI->isDisabled()) {
            $this->getLogger()->warning("§cPlugin FormAPI not found, disabling JoinUI...");
            $this->getLogger()->warning("§ePlease install FormAPI - Download HERE: poggit.pmmp.io/p/FormAPI");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    
    public function onDisable()
    {
        $this->getLogger()->info("§cJoinUI Disabled!");
    }
    
    public function onJoin(PlayerJoinEvent $event)
    {
        if ($this->getConfig()->get("enabled-joinui") == "true") {
            $player = $event->getPlayer();
            if ($player instanceof Player) {
                $this->openUI($player);
            }
        }
        
        if($this->getConfig()->get("enabled-joinui") == "false") {
            $player = $event->getPlayer();
            $player->sendMessage($this->getConfig()->get("no-joinui-message"));
        }
    }
    
    public function openUI($player)
    {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        
        $form->setTitle($this->getConfig()->get("joinui-title"));
        $form->setContent(str_replace(["{player}", "&"], [$player->getName(), "§"], $this->getConfig()->get("joinui-message")));
        $form->addButton("§alOK", 0, "textures/ui/accessibility_glyph_color"));
        $form->sendToPlayer($player);
        return true;
    }
}

