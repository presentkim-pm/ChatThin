<?php

/**            __   _____
 *  _ __ ___ / _| |_   _|__  __ _ _ __ ___
 * | '__/ _ \ |_    | |/ _ \/ _` | '_ ` _ \
 * | | |  __/  _|   | |  __/ (_| | | | | | |
 * |_|  \___|_|     |_|\___|\__,_|_| |_| |_|
 *
 * @author  ref-team
 * @link    https://github.com/refteams
 *
 *  &   ／l、
 *    （ﾟ､ ｡ ７
 *   　\、ﾞ ~ヽ   *
 *   　じしf_, )ノ
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\chatthin;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use function count;
use function is_dir;
use function preg_replace;
use function rmdir;
use function scandir;

class Main extends PluginBase implements Listener{
    public const THIN_TAG = TextFormat::ESCAPE . "\u{3000}";

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder) && count(scandir($dataFolder)) <= 2){
            rmdir($dataFolder);
        }
    }

    /**
     * @priority HIGHEST
     *
     * @param DataPacketSendEvent $event
     */
    public function onDataPacketSendEvent(DataPacketSendEvent $event) : void{
        foreach($event->getPackets() as $_ => $pk){
            if($pk instanceof TextPacket){
                switch($pk->type){
                    case TextPacket::TYPE_POPUP:
                    case TextPacket::TYPE_JUKEBOX_POPUP:
                    case TextPacket::TYPE_TIP:
                        // Not apply to tip and popup
                        break;
                    case TextPacket::TYPE_TRANSLATION:
                        $pk->message = $this->toThin($pk->message);
                        break;
                    default:
                        $pk->message .= self::THIN_TAG;
                        break;
                }
            }elseif($pk instanceof AvailableCommandsPacket){
                foreach($pk->commandData as $name => $commandData){
                    $commandData->description = $this->toThin($commandData->description);
                }
            }
        }
    }

    public function toThin(string $str) : string{
        return preg_replace("/%*(([a-z0-9_]+\.)+[a-z0-9_]+)/i", "%$1", $str) . self::THIN_TAG;
    }
}