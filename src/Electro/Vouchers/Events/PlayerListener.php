<?php

namespace Electro\Vouchers\Events;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class PlayerListener implements Listener
{
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if (!$item->getNamedTag()->hasTag("Voucher")) return;
        $item->setCount($item->getCount() - 1);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("§aYou have claimed a " . $item->getNamedTag()->getString("Name") . " Voucher!");
        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", '"' . $player->getName() . '"', $item->getNamedTag()->getString("Command")));
    }
}