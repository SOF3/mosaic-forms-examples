<?php

/*
 * mosaic-forms-examples
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any
 * means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <http://unlicense.org>
 */

declare(strict_types=1);

namespace SOFe\Tests\MosaicForms\SimpleWarpUI;

use falkirks\simplewarp\SimpleWarp;
use falkirks\simplewarp\Warp;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\form\layout\MenuForm;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	/** @var SimpleWarp */
	private $swApi;

	public function onEnable() : void{
		$this->swApi = $this->getServer()->getPluginManager()->getPlugin("SimpleWarp");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($command->getName() === "warpui"){
			if(!($sender instanceof Player)){
				$sender->sendMessage("Please run this command in-game");
				return true;
			}

			$form = new MenuForm("Warp list", "Click on a warp to visit");

			foreach($this->swApi->getWarpManager() as $warp){
				/** @var Warp $warp */
				if($warp->canUse($sender)){
					$form->add(new WarpMenuOption($warp, $sender)); // no warp icon :P
				}
			}

			$sender->sendMenuForm($form, function(MenuForm $form) use ($sender){
				/** @var WarpMenuOption $option */
				$option = $form->getSelectedOption(); // where are the generics >.<
				$option->getWarp()->teleport($sender);
			});

			return true;
		}

		return false;
	}
}
