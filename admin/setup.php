<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018 Kevin Seebach
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
 * \file    admin/setup.php
 * \ingroup kingavis
 * \brief   kingAvis setup page.
 */
// Load Dolibarr environment
$res=0;
if (! $res && ! empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res=@include($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php");
$tmp=empty($_SERVER['SCRIPT_FILENAME'])?'':$_SERVER['SCRIPT_FILENAME'];$tmp2=realpath(__FILE__); $i=strlen($tmp)-1; $j=strlen($tmp2)-1;
while($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i]==$tmp2[$j]) { $i--; $j--; }
if (! $res && $i > 0 && file_exists(substr($tmp, 0, ($i+1))."/main.inc.php")) $res=@include(substr($tmp, 0, ($i+1))."/main.inc.php");
if (! $res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php")) $res=@include(dirname(substr($tmp, 0, ($i+1)))."/main.inc.php");
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php");
if (! $res) die("Include of main fails");
global $langs, $user;
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/kingAvis.lib.php';
$langs->load("kingavis@kingavis");
// Access control
if (! $user->admin) accessforbidden();
// Parameters
$action = GETPOST('action', 'alpha');
/*
 * Actions
 */

 include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';
 if ((float) DOL_VERSION >= 6)
 {
 	include DOL_DOCUMENT_ROOT.'/core/actions_setmoduleoptions.inc.php';
 }
/*
 * View
 */
$page_name = "kingAvisSetup";
llxHeader('', $langs->trans($page_name));
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';
print load_fiche_titre($langs->trans($page_name), $linkback);
// Configuration header
$head = kingavisAdminPrepareHead();
dol_fiche_head($head,'settings',$langs->trans("modkingAvis"),0,"kingavis@kingavis");
// Setup page goes here
echo $langs->trans("kingAvisSetupPage");
$identification_parameters=array('marchandID'=>array('marchandID'=>''), 'marchandToken'=>array('marchandToken'=>''), 'marchandPrivateKey'=>array('marchandPrivateKey'=>''));
$process_parameters = array('kingavisAutomation'=>array('kingavisAutomation'=>''));
if ($action == 'update' && empty($_POST["cancel"]))
{
	dolibarr_set_const($db, "marchandID",$_POST["marchandID"],'chaine',0,'',$conf->entity);
	dolibarr_set_const($db, "marchandToken",$_POST["marchandToken"],'chaine',0,'',$conf->entity);
	dolibarr_set_const($db, "marchandPrivateKey",$_POST["marchandPrivateKey"],'chaine',0,'',$conf->entity);
	dolibarr_set_const($db, "kingavisAutomation",$_POST["kingavisAutomation"],'chaine',0,'',$conf->entity);
}
if ($action == 'edit')
{
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="action" value="update">';
	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';
	foreach($identification_parameters as $key => $val)
	{
		print '<tr class="oddeven"><td>';
		print $form->textwithpicto($langs->trans($key),$langs->trans('FindYourInfo'));
		print '</td><td><input name="'.$key.'"  class="flat" value="' . $conf->global->$key . '"></td></tr>';
	}
	foreach($process_parameters as $key => $val)
	{
		print '<tr class="oddeven"><td>';
		print $form->textwithpicto($langs->trans($key));
		print '</td><td>';
		print $form->selectyesno($key, $conf->global->$key , 1).'</td></tr>';
	}
	print '</table>';
	print '<br><div class="center">';
	print '<input class="button" type="submit" value="'.$langs->trans("Save").'">';
	print '</div>';
	print '</form>';
	print '<br>';
}
else
{
	print '<table class="noborder" width="100%">';
	print '<tr class="liste_titre"><td class="titlefield">'.$langs->trans("Parameter").'</td><td>'.$langs->trans("Value").'</td></tr>';
	foreach($identification_parameters as $key => $val)
	{
		print '<tr class="oddeven"><td>';
		print $form->textwithpicto($langs->trans($key),$langs->trans('FindYourInfo'));
		print '</td><td>' . $conf->global->$key . '</td></tr>';
	}
	foreach($process_parameters as $key => $val)
	{
		print '<tr class="oddeven"><td>';
		print $form->textwithpicto($langs->trans($key),$langs->trans('AutomationYN'));
		print '</td><td>'.yn($conf->global->$key).'</td></tr>';
	}
	print '</table>';
	print '<div class="tabsAction">';
	print '<a class="butAction" href="'.$_SERVER["PHP_SELF"].'?action=edit">'.$langs->trans("Modify").'</a>';
	print '</div>';
}
// Page end
dol_fiche_end();
llxFooter();
