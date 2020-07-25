<?php

/*
 *
 *  ____  _             _         _____
 * | __ )| |_   _  __ _(_)_ __   |_   _|__  __ _ _ __ ___
 * |  _ \| | | | |/ _` | | '_ \    | |/ _ \/ _` | '_ ` _ \
 * | |_) | | |_| | (_| | | | | |   | |  __/ (_| | | | | | |
 * |____/|_|\__,_|\__, |_|_| |_|   |_|\___|\__,_|_| |_| |_|
 *                |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  Blugin team
 * @link    https://github.com/Blugin
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace blugin\chatthin;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class ChatThin extends PluginBase implements Listener{
    public const THIN_TAG = TextFormat::ESCAPE . "　";

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @priority HIGHEST
     *
     * @param DataPacketSendEvent $event
     */
    public function onDataPacketSendEvent(DataPacketSendEvent $event) : void{
        $pk = $event->getPacket();
        if(!$pk instanceof TextPacket || $pk->type === TextPacket::TYPE_TIP || $pk->type === TextPacket::TYPE_POPUP || $pk->type === TextPacket::TYPE_JUKEBOX_POPUP)
            return;

        $pk->message .= self::THIN_TAG;
    }
}