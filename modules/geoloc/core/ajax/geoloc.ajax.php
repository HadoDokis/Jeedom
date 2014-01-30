<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('401 Unauthorized');
    }

    if (init('action') == 'cmdForDistance') {
        $return = array();
        foreach (geoloc::byType('geoloc') as $eqLogic) {
            foreach ($eqLogic->getCmd() as $cmd) {
                if ($cmd->getConfiguration('mode') != 'distance') {
                    $infoCmd = array(
                        'id' => $cmd->getId(),
                        'human_name' => $cmd->getHumanName()
                    );
                    $return[] = $infoCmd;
                }
            }
        }
        ajax::success($return);
    }
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
