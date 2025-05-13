<?php

/**
 * Trigger ClientBlocker
 * Bloquea la validación de documentos si el cliente está marcado como bloqueado
 */

class InterfaceClientBlockerTriggers
{
    public $family = 'clientblocker';
    public $description = 'Evita la validación de documentos si el cliente está marcado como bloqueado';
    public $version = '1.0.2';
    public $picto = 'generic';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function runTrigger($action, $object, $user, $langs, $conf)
    {
        // Acciones que queremos interceptar
        $blockedActions = array('ORDER_VALIDATE', 'PROPAL_VALIDATE', 'BILL_VALIDATE');

        if (in_array($action, $blockedActions)) {
            // Cargar configuración global
            dolibarr_get_const($this->db, 'CLIENTBLOCKER_BLOCK_ORDER_VALIDATION');
            $block_enabled = !empty($conf->global->CLIENTBLOCKER_BLOCK_ORDER_VALIDATION);

            // Cargar cliente si no está
            if (empty($object->thirdparty) && method_exists($object, 'fetch_thirdparty')) {
                $object->fetch_thirdparty();
            }

            // Si el cliente está bloqueado
            if (!empty($object->thirdparty->array_options['options_cliente_bloqueado'])) {
                $langs->load("clientblocker@clientblocker");

                // Si el bloqueo está activado, detenemos la validación
                if ($block_enabled) {
                    $message = $langs->trans("ClientBlockerCannotValidate");
                    $this->error = $message;

                    if (method_exists($object, 'errors')) {
                        $object->errors[] = $message;
                    }

                    return -1; // Bloquear acción
                }

                // Si el bloqueo no está activado, permitimos continuar (modo solo informativo)
            }
        }

        return 0;
    }
}