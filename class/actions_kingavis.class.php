<?php
/* Copyright (C) 2018 Kevin Seebach
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_test.class.php
 * \ingroup test
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

/**
 * Class Actionstest
 */
class Actionskingavis
{
    /**
     * @var DoliDB Database handler.
     */
    public $db;
    /**
     * @var string Error
     */
    public $error = '';
    /**
     * @var array Errors
     */
    public $errors = array();


	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;


	/**
	 * Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
	    $this->db = $db;
	}

	/**
	 * Overloading the addMoreActionsButtons function : add sending Button
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
	{
		global $conf, $user, $langs, $db;
		$error = 0;
	    if (in_array($parameters['currentcontext'], array('invoicecard'))) {
					if($conf->global->kingavisAutomation == 0){
						include_once DOL_DOCUMENT_ROOT . '/kingavis/class/kingavis.class.php';
						$avis = new KingAvis($db);
						if($avis->alreadyDone($object->id) == 0)
						{
							$langs->load("kingavis@kingavis");
							print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=sendKingAvis">'.$langs->trans("SendToKingAvis").'</a>';
						}
					}
		}
		if (! $error) {
			return 0;
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}



	function doActions($parameters, &$object, &$action, $hookmanager)
		{
		 $error = 0;
		 global $conf, $user, $langs, $db;
		 if (in_array($parameters['currentcontext'], array('invoicecard'))) {
			  if($action == "sendKingAvis"){
					include_once DOL_DOCUMENT_ROOT . '/kingavis/class/kingavis.class.php';
					$avis = new KingAvis($db);
					if($avis->sendAvis($object)==0){
						$avis->createRecord($object->id, new DateTime(), $user);
					}
				}
			}
			if (! $error)
			{
				return 0;
			}
			else
			{
				$this->errors[] = 'Error message';
				return -1;
			}
		}

}
