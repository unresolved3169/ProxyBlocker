<?php

namespace emretr1\proxyblocker;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase implements Listener{

	protected $acceptedIps = [];

	public function onEnable(){
		$this->reloadConfig();

		$this->acceptedIps = array_merge($this->getConfig()->get("ignored-ips"), [$this->getServer()->getIp(), "localhost", "0.0.0.0", "127.0.0.1"]);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onClientPacket(DataPacketReceiveEvent $event) : void{
		$packet = $event->getPacket();
		$player = $event->getPlayer();

		if($packet instanceof LoginPacket){
			if(!in_array($packet->serverAddress, $this->acceptedIps)){
				$player->kick("Proxy Detected! Please try joining to server without proxy");
				$event->setCancelled();
			}
		}
	}
}