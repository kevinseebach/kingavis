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
 * \file    core/triggers/interface_99_modkingAvis_kingAvisTriggers.class.php
 * \ingroup kingavis
 * \brief   Example trigger.
 *
 * Put detailed description here.
 *
 * \remarks You can create other triggers by copying this one.
 * - File name should be either:
 *      - interface_99_modkingAvis_MyTrigger.class.php
 *      - interface_99_all_MyTrigger.class.php
 * - The file must stay in core/triggers
 * - The class name must be InterfaceMytrigger
 * - The constructor method must be named InterfaceMytrigger
 * - The name property name must be MyTrigger
 */

require_once DOL_DOCUMENT_ROOT.'/core/triggers/dolibarrtriggers.class.php';


/**
 *  Class of triggers for kingAvis module
 */
class InterfaceKingavisTriggers extends DolibarrTriggers
{
	/**
	 * @var DoliDB Database handler
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
		$this->name = preg_replace('/^Interface/i', '', get_class($this));
		$this->family = "development";
		$this->description = "kingAvis triggers.";
		// 'development', 'experimental', 'dolibarr' or version
		$this->version = '2';
		$this->picto = 'kingavis@kingavis';
	}

	/**
	 * Trigger name
	 *
	 * @return string Name of trigger file
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Trigger description
	 *
	 * @return string Description of trigger file
	 */
	public function getDesc()
	{
		return $this->description;
	}


	/**
	 * Function called when a Dolibarrr business event is done.
	 * All functions "runTrigger" are triggered if file
	 * is inside directory core/triggers
	 *
	 * @param string 		$action 	Event action code
	 * @param CommonObject 	$object 	Object
	 * @param User 			$user 		Object user
	 * @param Translate 	$langs 		Object langs
	 * @param Conf 			$conf 		Object conf
	 * @return int              		<0 if KO, 0 if no triggered ran, >0 if OK
	 */
	public function runTrigger($action, $object, User $user, Translate $langs, Conf $conf)
	{
        switch ($action) {

			    case 'BILL_VALIDATE':
							if($conf->global->kingavisAutomation == 1){
								$langs->load("kingavis@kingavis");

								if(empty($conf->global->marchandID) || empty($conf->global->marchandToken) || empty($conf->global->marchandPrivateKey)){
									setEventMessages($langs->trans("ErrorConfig"),"", 'errors');
									return 1;
								}

								if($object->total_ttc == 0) return 1;

								$iso_currency = $object->multicurrency_code;

								$prenom = $object->thirdparty->nom;
								$nom = "( ".$object->thirdparty->name_alias." )";
								$email = $object->thirdparty->email;
								if(empty($email)){ //pas d'email donc pas d'envoi
										setEventMessages($langs->trans("ErrorSendMail"),"", 'errors');
										return 1;
								}

								$availableLanguages = array("fr", "de", "en", "it");
								if($soc->default_lang != null && in_array(substr($soc->default_lang,0,2),$availableLanguages)){ $langMail = substr($soc->default_lang,0,2);  }
							  else if(in_array(substr($langs->defaultlang,0,2),$availableLanguages)){$langMail = substr($langs->defaultlang,0,2);}
							  else{$langMail = 'fr';}

								//we've got all the infos - proceed sending
								$curl = curl_init();
								$url = "https://king-avis.com/fr/merchantorder/add?id_merchant=".$conf->global->marchandID."&token=".$conf->global->marchandToken."&private_key=".$conf->global->marchandPrivateKey."&ref_order=".$object->ref."&email=".$email."&amount=".$object->total_ttc."&iso_currency=".$iso_currency."&firstname=".urlencode($prenom)."&lastname=".urlencode($nom)."&iso_lang=".$langMail;
								curl_setopt($curl, CURLOPT_URL, $url);
								curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								$result = curl_exec($curl);
								curl_close($curl);

								if($result === "OK"){
									setEventMessages($langs->trans("importSuccess"),"", 'mesgs');
									include_once '../../class/kingavis.class.php';
									global $db;
									$avis = new Kingavis($db);
									$avis->createRecord($object->id, new DateTime(), $user);
									return 0;
								}
								else{
									setEventMessages($langs->trans("ErrorGeneral"),"", 'errors');
									return 1;
								}
							}

				break;


		    }

		return 0;
	}
}
