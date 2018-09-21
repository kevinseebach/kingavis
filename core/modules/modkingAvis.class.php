<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
  * Copyright (C) 2018 Kevin Seebach
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
 * 	\defgroup   kingavis     Module kingAvis
 *  \brief      kingAvis module descriptor.
 *
 *  \file       htdocs/kingavis/core/modules/modkingAvis.class.php
 *  \ingroup    kingavis
 *  \brief      Description and activation file for module kingAvis
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


// The class name should start with a lower case mod for Dolibarr to pick it up
// so we ignore the Squiz.Classes.ValidClassName.NotCamelCaps rule.
// @codingStandardsIgnoreStart
/**
 *  Description and activation class for module kingAvis
 */
class modkingAvis extends DolibarrModules
{
	// @codingStandardsIgnoreEnd
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
    global $langs,$conf;
    $this->db = $db;
		// Id for module (must be unique).
		$this->numero = 5286217;		// TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve id number for your module
		$this->rights_class = 'kingavis';
		$this->family = "other";
		$this->module_position = 500;
		$this->name = "KingAvis";
		$this->description = "Envoyez vos factures sur la plateforme King-Avis";
		$this->descriptionlong = "Envoyez automatique vos factures sur la plateforme de recolte d'avis client King-Avis et améliorer votre force commerciale.";
		$this->editor_name = 'Kevin Seebach';
		$this->editor_url = 'https://www.example.com';
		$this->version = '2.0';
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->picto='kingavis@kingavis';
		$this->module_parts = array('triggers' => 1,
			'hooks'=>array('invoicecard')
		);
		$this->dirs = array();
		$this->config_page_url = array("setup.php@kingavis");
		$this->hidden = false;			// A condition to hide module
		$this->depends = array();		// List of module class names as string that must be enabled if this module is enabled
		$this->requiredby = array();	// List of module ids to disable if this one is disabled
		$this->conflictwith = array();	// List of module class names as string this module is in conflict with
		$this->phpmin = array(5,3);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(4,0);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("kingavis@kingavis");
		$this->warnings_activation = array();                     // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->warnings_activation_ext = array();                 // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
    $this->tabs = array();
		if (! isset($conf->kingavis) || ! isset($conf->kingavis->enabled))
        {
        	$conf->kingavis=new stdClass();
        	$conf->kingavis->enabled=0;
        }
		$this->dictionaries=array();
		$this->menu = array();
		$r=0;
	}

	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	public function init($options='')
	{
		$sql = array();

		$result=$this->_load_tables('/kingavis/sql/');

		return $this->_init($sql, $options);
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param      string	$options    Options when enabling module ('', 'noboxes')
	 * @return     int             	1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();
		return $this->_remove($sql, $options);
	}

}
