<?php

namespace Electro\Vouchers;

use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\event\Listener;

use pocketmine\utils\Config;

class Vouchers extends PluginBase implements Listener{

    public $player;

    public function onEnable()
    {
        date_default_timezone_set($this->getConfig()->get("TimeZone"));
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch($command->getName()) {
            case "voucher":
                if (!$sender->hasPermission("vouchers.cmd")){
                    $sender->sendMessage("§cYou do not have permissions to use this command");
                    return true;
                }
                if (!isset($args[0])){
                    $sender->sendMessage("§l§cUsage: §r§a/voucher <create/info>");
                    return true;
                }
                switch (strtolower($args[0])) {
                    case "create":
                        if (!isset($args[1])) {
                            $sender->sendMessage("§l§cUsage: §r§a/voucher create <Player> <VoucherName> <Command>");
                            return true;
                        }
                        if (!$this->getServer()->getPlayer($args[1]) instanceof Player) {
                            $sender->sendMessage("§l§cERROR: §r§aYou have entered an invalid Player Username.");
                            return true;
                        }
                        if (!isset($args[2])) {
                            $sender->sendMessage("§l§cERROR: §r§aYou have entered an invalid Voucher Name.");
                            return true;
                        }
                        if (!isset($args[3])) {
                            $sender->sendMessage("§l§cERROR: §r§aYou have entered an invalid Command.");
                            return true;
                        }

                        $player = $this->getServer()->getPlayer($args[1]);
                        $player->sendMessage("§aYou have given " . $player->getname() . " a " . $args[2] . " Voucher!");
                        $item = Item::get(Item::PAPER);
                        $item->setCustomName(str_replace("{VoucherName}", $args[2], $this->getConfig()->get("Voucher_Name")));
                        #$item->setCustomName("§r§l§6" . $args[2] . " Voucher");
                        $item->setLore([str_replace("{VoucherName}", $args[2], $this->getConfig()->get("Voucher_Lore"))]);
                        #$item->setLore(["§r§7Right Click/Tap To Claim This Voucher"]);
                        $item->setNamedTagEntry(new StringTag("Creator", $sender->getName()));
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
                            $sender->sendMessage("§cYou must be in-game to run this command");
                            return true;
                        }
                        $item = $sender->getInventory()->getItemInHand();
                        if (!$item->getNamedTag()->hasTag("Voucher")) {
                            $sender->sendMessage("§l§cError: §r§aYou must be holding a Voucher");
                            return true;
                        }
                        $sender->sendMessage("§aVoucher Created By: §b" . $item->getNamedTag()->getString("Creator") . "\n§aVoucher Creation Date/Time: §b" . date("Y-m-d H:i")  . "\n§aVoucher Command: §b/" . $item->getNamedTag()->getString("Command"));
                        break;
                    default:
                        $sender->sendMessage("§l§cUsage: §r§a/voucher <create/info>");
                        return true;
                }
        }
        return true;
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if (!$item->getNamedTag()->hasTag("Voucher")) {
            return true;
        }
        $item->setCount($item->getCount() - 1);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage(str_replace("{VoucherName}", $item->getNamedTag()->getString("Name"), $this->getConfig()->get("Voucher_Claimed")));
        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", '"' . $player->getName() . '"', $item->getNamedTag()->getString("Command")));
    }
}