<?php

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("clientblocker@clientblocker");

$title = $langs->trans("Module104000Name");
llxHeader('', $title);

print load_fiche_titre($title, '', 'object_generic');

print '<div class="div-table-responsive">';
print '<pre>';
@readfile(__DIR__.'/../README.md');
print '</pre>';
print '</div>';

llxFooter();