# Information 
 - This plugin allows admins to put a command in a Voucher. Its great for Rank Vouchers, Money Vouchers, etc!
# Command
- /voucher info | Sends the voucher info on the item you are holding.
- /voucher create <Player> <VoucherName> <Command> | Creates a voucher. (Use {player} when giving the voucher claimer their reward.)
# Important
- Use {player} when giving the voucher claimer their reward.
# Example
- /voucher create El3ctr0Games "YouTube Rank" setgroup {player} YouTube
# Permissions
- vouchers.cmd
# Config
```
# {VoucherName} will display the vouchers name. Example "{VoucherName} Voucher" would display "Rank Voucher" if the voucher name was "Rank".

Voucher_Name: §r§l§6{VoucherName} Voucher

Voucher_Lore: §r§7Right Click/Tap To Claim This Voucher

Voucher_Claimed: §aYou have claimed a {VoucherName} Voucher!

# Timezones can be found at https://www.php.net/manual/en/timezones.php if you don't know what your doing, keep this at "America/Chicago" (OR IT WILL BREAK THE PLUGIN).
TimeZone: America/Chicago

```
# Credits
- Icon from www.flaticon.com
