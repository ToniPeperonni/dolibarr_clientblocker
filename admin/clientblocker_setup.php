<?php

// Load Dolibarr environment
$res = @include("../../main.inc.php");
if (! $res) $res = @include("../../../main.inc.php");

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
dol_include_once("/clientblocker/core/modules/modClientBlocker.class.php");

// Load languages
$langs->load("admin");
$langs->load("clientblocker@clientblocker");

// Security
if (! $user->admin) accessforbidden();

// Actions
if (GETPOST('action', 'aZ09') == 'setvalue') {
    $entity = (!empty($conf->entity) ? $conf->entity : 1); // Garantiza la entidad correcta en Dolibarr 15

    if (GETPOST('CLIENTBLOCKER_BLOCK_ORDER_VALIDATION', 'alpha')) {
        dolibarr_set_const($db, 'CLIENTBLOCKER_BLOCK_ORDER_VALIDATION', 1, 'chaine', 0, '', $entity);
    } else {
        dolibarr_set_const($db, 'CLIENTBLOCKER_BLOCK_ORDER_VALIDATION', 0, 'chaine', 0, '', $entity);
    }

    setEventMessages($langs->trans("SetupSaved"), null, 'mesgs');
}

// View
llxHeader('', $langs->trans("ClientBlockerSetup"));

$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print load_fiche_titre($langs->trans("ClientBlockerSetup"), $linkback, 'title_setup');
print '<div class="opacitymedium">'.$langs->trans("ClientBlockerSetupPageInfo").'</div><br>';

print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="setvalue">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameter").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print "</tr>\n";

print '<tr>';
print '<td>'.$langs->trans("ClientBlockerBlockOrderValidation").'</td>';
print '<td>';
print '<input type="checkbox" name="CLIENTBLOCKER_BLOCK_ORDER_VALIDATION" value="1" '.(!empty($conf->global->CLIENTBLOCKER_BLOCK_ORDER_VALIDATION) ? 'checked' : '').'>';
print '</td>';
print '</tr>';

print '</table>';

print '<br><div class="center">';
print '<input type="submit" class="button" value="'.$langs->trans("Save").'">';
print '</div>';

print '</form>';

llxFooter();