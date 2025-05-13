<?php
$res = @include("../main.inc.php");
if (! $res) $res = @include("../../main.inc.php");

require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT . "/custom/clientblocker/core/modules/modClientBlocker.class.php";

$langs->load("admin");
$langs->load("clientblocker");

if (! $user->admin) {
    accessforbidden();
}

llxHeader('', $langs->trans("ClientBlockerSetup"));

print load_fiche_titre($langs->trans("ClientBlockerSetup"), '', 'title_setup');
print '<div class="info">'.$langs->trans("ClientBlockerSetupPageInfo").'</div>';

llxFooter();