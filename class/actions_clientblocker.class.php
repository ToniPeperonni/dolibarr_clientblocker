<?php

class ActionsClientBlocker
{
    public function formObjectOptions($parameters, &$object, &$action, $hookmanager)
    {
        global $conf, $db, $langs;

        $langs->loadLangs(array("clientblocker@clientblocker"));

        // Aviso al abrir la ficha del pedido
        //if ($parameters['currentcontext'] === 'ordercard' && !empty($object->id)) {
        $contexts = array('ordercard', 'propalcard', 'invoicecard');
        $currentcontext = $parameters['currentcontext'];
        if (in_array($currentcontext, $contexts) && !empty($object->id)) {
            $object->fetch_thirdparty();

            if (!empty($object->thirdparty->array_options['options_cliente_bloqueado'])) {
                print '<div class="error" style="margin-bottom: 10px;">';
                 print '<strong>' . $langs->trans("ClientBlockerWarningTitle") . ':</strong> ';
                 print $langs->trans("ClientBlockerOrderBlockedMessage");
                print '</div>';


        // Si la configuración está activa, desactivar el botón de validar
        if (!empty($conf->global->CLIENTBLOCKER_BLOCK_ORDER_VALIDATION)) {
        print '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var found = false;

                // Enlace tipo <a> usado en pedidos, presupuestos y facturas (varía entre action=validate y action=valid)
                var validateLinks = document.querySelectorAll("a.butAction[href*=\"action=validate\"], a.butAction[href*=\"action=valid\"]");

                validateLinks.forEach(function(link) {
                    link.classList.remove("butAction");
                    link.classList.add("butActionRefused");
                    link.removeAttribute("href");
                    link.style.pointerEvents = "none";
                    link.style.opacity = "0.5";
                    link.innerText = "' . dol_escape_js($langs->trans("ClientBlockerButtonBlocked")) . '";
                    link.title = "Bloqueado por cliente bloqueado";
                    found = true;
                });

                if (!found) {
                    console.log("ClientBlocker: botón de validación no detectado.");
                }
            });
        </script>';
        }

            }
        }

        // Aviso preventivo al pulsar el botón validar (antes de confirmar)
       // if ($parameters['currentcontext'] === 'ordercard' && $action === 'validate') {.
        if (in_array($currentcontext, $contexts) && $action === 'validate') {
            $object->fetch_thirdparty();

            if (!empty($object->thirdparty->array_options['options_cliente_bloqueado'])) {
                setEventMessages($langs->trans("ClientBlockerCannotValidate"), null, 'errors');
            }
        }

        // Bloqueo real al confirmar la validación
        //if ($parameters['currentcontext'] === 'ordercard' && $action === 'confirm_validate' && GETPOST('confirm', 'alpha') === 'yes') {
        if (in_array($currentcontext, $contexts) && $action === 'confirm_validate' && GETPOST('confirm', 'alpha') === 'yes') {
            $object->fetch_thirdparty();

            if (!empty($object->thirdparty->array_options['options_cliente_bloqueado'])) {
                setEventMessages($langs->trans("ClientBlockerCannotProcess"), null, 'errors');
                // Cancelamos la acción devolviendo -1
                return -1;
            }
        }

        return 0;
    }
}