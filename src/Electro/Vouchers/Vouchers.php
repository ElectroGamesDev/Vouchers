<?php

namespace Electro\Vouchers;

use Electro\Vouchers\Events\PlayerListener;
use Electro\Vouchers\Interfaces\Messages;

use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\command\{Command, CommandSender};

class Vouchers extends PluginBase{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch($command->getName()) {
            case "voucher":
                if (!$sender->hasPermission("vouchers.cmd")){
                    $sender->sendMessage(Messages::PERMISSION);
                    return true;
                }
                if (!isset($args[0])){
                    $sender->sendMessage("§l§cUsage: §r§a/voucher <create/info>");
                    return true;
                }
                switch (strtolower($args[0])) {
                    case "create":
                        if (!isset($args[1])) {
                            $sender->sendMessage(Messages::USAGE);
                            return false;
                        }
                        if (!$this->getServer()->getPlayer($args[1]) instanceof Player) {
                            $sender->sendMessage(Messages::INVALID_PLAYER_NAME);
                            return false;
                        }
                        if (!isset($args[2])) {
                            $sender->sendMessage(Messages::INVALID_VOUCHER_NAME);
                            return false;
                        }
                        if (!isset($args[3])) {
                            $sender->sendMessage(Messages::INVALID_COMMAND_NAME);
                            return false;
                        }

                        $player = $this->getServer()->getPlayer($args[1]);
                        $player->sendMessage("§aYou have given " . $player->getname() . " a " . $args[2] . " Voucher!");
                        $item = Item::get(Item::PAPER);

                        $item
                            ->setCustomName("§r§l§6" . $args[2] . " Voucher")
                            ->setLore(["§r§7Right Click/Tap To Claim This Voucher"])
                            ->setNamedTagEntry(new StringTag("Creator", $sender->getName()));

                        $item->setNamedTagEntry(new StringTag("Name", $args[2]));
                        array_shift($args);
                        array_shift($args);
                        array_shift($args);
                        $item->setNamedTagEntry(new StringTag("Command", trim(implode(" ", $args))));
                        $item->setNamedTagEntry(new StringTag("Voucher"));
                        $player->getInventory()->addItem($item);
                        break;
                    case "info":
                        if (!$sender instanceof Player){
                            $sender->sendMessage(Messages::COMMAND_INGAME);
                            return false;
                        }
                        $item = $sender->getInventory()->getItemInHand();
                        if (!$item->getNamedTag()->hasTag("Voucher")) {
                            $sender->sendMessage(Messages::HOLDING_VOUCHER);
                            return false;
                        }
                        $sender->sendMessage("§aVoucher Created By: §b" . $item->getNamedTag()->getString("Creator") . "\n§aVoucher Command: §b/" . $item->getNamedTag()->getString("Command"));
                        break;
                    default:
                        $sender->sendMessage(Messages::USAGE_BASE);
                        return false;
                }
        }
        return true;
    }
}