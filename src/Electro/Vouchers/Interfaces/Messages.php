<?php

namespace Electro\Vouchers\Interfaces;

interface Messages
{
    const PERMISSION = "§cYou do not have permissions to use this command";
    const USAGE = "§l§cUsage: §r§a/voucher create <Player> <VoucherName> <Command>";
    const USAGE_BASE = "§l§cUsage: §r§a/voucher <create/info>";
    const INVALID_PLAYER_NAME = "§l§cERROR: §r§aYou have entered an invalid Player Username.";
    const INVALID_VOUCHER_NAME = "§l§cERROR: §r§aYou have entered an invalid Voucher Name.";
    const INVALID_COMMAND_NAME = "§l§cERROR: §r§aYou have entered an invalid Command.";
    const COMMAND_INGAME = "§cYou must be in-game to run this command";
    const HOLDING_VOUCHER = "§l§cError: §r§aYou must be holding a Voucher";
}