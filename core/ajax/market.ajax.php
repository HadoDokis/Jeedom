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
    require_once(dirname(__FILE__) . '/../../core/php/core.inc.php');
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('401 Unauthorized');
    }

    if (init('action') == 'install') {
        $market = market::byId(init('id'));
        $market->install();
        ajax::success();
    }

    if (init('action') == 'remove') {
        $market = market::byId(init('id'));
        $market->remove();
        ajax::success();
    }

    if (init('action') == 'save') {
        $market_ajax = json_decode(init('market'), true);
        try {
            $market = market::byId($market_ajax['id']);
        } catch (Exception $e) {
            $market = new market();
        }
        utils::a2o($market, $market_ajax);
        $market->save();
        ajax::success();
    }

    if (init('action') == 'getInfo') {
        ajax::success(market::getInfo(init('logicalId')));
    }

    throw new Exception('Aucune methode correspondante à : ' . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
