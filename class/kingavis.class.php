<?php
/* Copyright (C) 2007-2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) 2014-2016  Juanjo Menent       <jmenent@2byte.es>
 * Copyright (C) 2015       Florian Henry       <florian.henry@open-concept.pro>
 * Copyright (C) 2015       Raphaël Doursenaud  <rdoursenaud@gpcsolutions.fr>
 * Copyright (C) 2018 			Kevin Seebach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file        class/kingavis.class.php
 * \ingroup     test
 * \brief       This file is a CRUD class file for kingavis (Create/Read/Update/Delete)
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class for kingavis
 */
class kingavis extends CommonObject
{
 /**
	* @var string ID to identify managed object
	*/
 public $element = 'kingavis';
 /**
	* @var string Name of table without prefix where object is stored
	*/
 public $table_element = 'kingavis';

 /**
	* @var array  Does this field is linked to a thirdparty ?
	*/
 protected $isnolinkedbythird = 1;
 /**
	* @var array  Does kingavis support multicompany module ? 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
	*/
 protected $ismultientitymanaged = 1;
 /**
	* @var string String with name of icon for kingavis
	*/
 public $picto = 'kingavis';


 /**
	*             'type' if the field format, 'label' the translation key, 'enabled' is a condition when the filed must be managed,
	*             'visible' says if field is visible in list (-1 means not shown by default but can be aded into list to be viewed)
	*             'notnull' if not null in database
	*             'index' if we want an index in database
	*             'position' is the sort order of field
	*             'searchall' is 1 if we want to search in this field when making a search from the quick search button
	*             'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
	*             'comment' is not used. You can store here any text of your choice.
	*/

 // BEGIN MODULEBUILDER PROPERTIES
 /**
	* @var array  Array with all fields and their property
	*/
 public $fields=array(
	 'rowid' => array('type'=>'integer', 'label'=>'TechnicalID', 'visible'=>-1, 'enabled'=>1, 'position'=>1, 'notnull'=>1, 'index'=>1, 'comment'=>'Id',),
	 'facid' => array('type'=>'integer', 'label'=>'Facid', 'visible'=>0, 'enabled'=>1, 'position'=>20, 'notnull'=>1, 'index'=>1,),
	 'date_creation' => array('type'=>'datetime', 'label'=>'DateCreation', 'visible'=>-1, 'enabled'=>1, 'position'=>500, 'notnull'=>1,),
 );

 public $rowid;
 public $facid;
 public $date_creation;
 // END MODULEBUILDER PROPERTIES




 /**
	* Constructor
	*
	* @param DoliDb $db Database handler
	*/
 public function __construct(DoliDB $db)
 {
	 $this->db = $db;
 }

public function createRecord($facid, $datecrea, $user)
{
	$this->facid = $facid;
	$this->date_creation = $datecrea;
	$this->createCommon($user);
}


public function alreadyDone($invoiceid)
{
	$sql = "SELECT * FROM llx_kingavis WHERE facid = ".$invoiceid;
	$resql=$this->db->query($sql);
	if ($resql)
	{
		return $this->db->num_rows($resql);
	}
  else {
    return 0;
  }
}

public function sendAvis($object)
{
      global $conf, $user, $langs, $db;
  require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
    $langs->load("kingavis@kingavis");
    $idm = dolibarr_get_const($this->db,"marchandID",1);
    $token = dolibarr_get_const($this->db,"marchandToken",1);
    $pkey = dolibarr_get_const($this->db,"marchandPrivateKey",1);

    if(empty($idm) || empty($token) || empty($pkey)){
      setEventMessages($langs->trans("ErrorSend"),"", 'errors');
      return 1;
    }

    $facnum = $object->ref;
    $ttc = $object->total_ttc;

    if($ttc == 0){ //if total is 0 we considering that as a sample order no reviews needed
      return 1;
    }

    $iso_currency = $object->multicurrency_code;
    require_once DOL_DOCUMENT_ROOT . "/societe/class/societe.class.php";
    $soc = new Societe($db);
    $soc->fetch($object->socid);
    $prenom = $soc->nom;
    $nom = "( ".$soc->name_alias." )";
    $email = $soc->email;
    if(empty($email)){ //pas d'email donc pas d'envoi
        setEventMessages($langs->trans("ErrorSendMail"),"", 'errors');
        return 1;
    }

    //we've got all the infos - proceed sending
    $curl = curl_init();
    $url = "https://king-avis.com/fr/merchantorder/add?id_merchant=".$idm."&token=".$token."&private_key=".$pkey."&ref_order=".$facnum."&email=".$email."&amount=".$ttc."&iso_currency=".$iso_currency."&firstname=".urlencode($prenom)."&lastname=".urlencode($nom)."&iso_lang=fr";
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    curl_close($curl);

    if($result === "OK"){
      setEventMessages($langs->trans("importSuccess"));
      return 0;
    }
    else{
      setEventMessages($langs->trans("ErrorGeneral"),"", 'errors');
      dol_syslog("Error ".$this->name,LOG_WARNING);
      return 1;
    }

}

}
