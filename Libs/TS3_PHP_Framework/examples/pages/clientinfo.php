<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework Example
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Sven 'ScP' Paulsen
 * @copyright Copyright (c) 2010 by Planet TeamSpeak. All rights reserved.
 */

try
{
  /* connect to server, login and get TeamSpeak3_Node_Host object by URI */
  $ts3_ServerInstance = TeamSpeak3::factory("serverquery://" . $cfg["user"] . ":" . $cfg["pass"] . "@" . $cfg["host"] . ":" . $cfg["query"] . "/#no_query_clients");

  /* access server instance address using __toString() */
  echo "<h1>Client Information - " . $ts3_ServerInstance . "</h1>\n";

  /* display server select form */
  $selected_sid = form_server_selector($ts3_ServerInstance->serverList());

  /* get TeamSpeak3_Node_Server object by ID */
  $ts3_VirtualServer = $ts3_ServerInstance->serverGetById($selected_sid);

  /* display server select form */
  $selected_clid = form_client_selector($ts3_VirtualServer->clientList());

  /* get TeamSpeak3_Node_Client object by ID */
  $ts3_Client = $ts3_VirtualServer->clientGetById($selected_clid);

  /* display channel info from assoc array */
  echo "<table class=\"list\">\n";
  echo "<tr>\n" .
       "  <th>Ident</th>\n" .
       "  <th>Value</th>\n" .
       "</tr>\n";
  foreach($ts3_Client->getInfo(TRUE, TRUE) as $ident => $value)
  {
    echo "<tr>\n" .
         "  <td>" . $ident . "</td>\n" .
         "  <td>" . htmlspecialchars($value) . "</td>\n" .
         "</tr>\n";
  }
  echo "</table>\n";

  /* display client avatar */
  if($ts3_Client["client_flag_avatar"])
  {
    $download = $ts3_VirtualServer->transferInitDownload($ts3_Client->getId(), 0, $ts3_Client->avatarGetName());

    echo "<br /><img src='ts3icon.php?ftdata=" . base64_encode(serialize($download)) . "' alt='' align='top' />";
  }

  /* display runtime from adapter profiler */
  echo "<p>Executed " . $ts3_ServerInstance->getAdapter()->getQueryCount() . " queries in " . $ts3_ServerInstance->getAdapter()->getQueryRuntime() . " seconds</p>\n";
}
catch(Exception $e)
{
  /* catch exceptions and display error message if anything went wrong */
  echo "<span class='error'><b>Error " . $e->getCode() . ":</b> " . $e->getMessage() . "</span>\n";
}
