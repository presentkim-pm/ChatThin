<?php

/**
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author       PresentKim (debe3721@gmail.com)
 * @link         https://github.com/PresentKim
 * @license      https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\chatthin;

use kim\present\traits\removeplugindatadir\RemovePluginDataDirTrait;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use function preg_replace;

class Main extends PluginBase implements Listener{
	use RemovePluginDataDirTrait;

	public const THIN_TAG = TextFormat::ESCAPE . "\u{3000}";

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @priority HIGHEST
	 *
	 * @param DataPacketSendEvent $event
	 */
	public function onDataPacketSendEvent(DataPacketSendEvent $event) : void{
		foreach($event->getPackets() as $pk){
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
				foreach($pk->commandData as $commandData){
					$commandData->description = $this->toThin($commandData->description);
				}
			}
		}
	}

	public function toThin(string $str) : string{
		return preg_replace("/%*(([a-z0-9_]+\.)+[a-z0-9_]+)/i", "%$1", $str) . self::THIN_TAG;
	}
}
