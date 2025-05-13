<?php
/**
 * Module Client Blocker for Dolibarr 15
 * Blocks orders if the customer has the "Cliente Bloqueado" extrafield active
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';
include_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';

class modClientBlocker extends DolibarrModules
{
    public function __construct($db)
    {
        global $langs, $conf;
        $langs->load("clientblocker@clientblocker");
        $this->db = $db;

        $this->numero = 104071;

        $this->rights_class = 'clientblocker';

        $this->family = "crm";
        $this->module_position = 500;
        $this->name = preg_replace('/^mod/i', '', get_class($this));

        $this->description = $langs->trans("ClientBlockerModuleDescription");

        $this->version = '1.0.2';
        $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
        $this->picto = 'globalten@clientblocker';
        $this->editor_name = 'Alvaro H'; // Opcional
        $this->editor_url = 'https://globalten.es'; // Opcional
        

		$this->module_parts = array(
                                    	'triggers' => 1,                                 	// Set this to 1 if module has its own trigger directory (core/triggers)
            //							'login' => 0,                                    	// Set this to 1 if module has its own login method directory (core/login)
            //							'substitutions' => 0,                            	// Set this to 1 if module has its own substitution function file (core/substitutions)
            //							'menus' => 0,                                    	// Set this to 1 if module has its own menus handler directory (core/menus)
            //							'theme' => 0,                                    	// Set this to 1 if module has its own theme directory (theme)
            //                        	'tpl' => 0,                                      	// Set this to 1 if module overwrite template dir (core/tpl)
            //							'barcode' => 0,                                  	// Set this to 1 if module has its own barcode directory (core/modules/barcode)
            //							'models' => 0,                                   	// Set this to 1 if module has its own models directory (core/modules/xxx)
              //                          'css' => 'clientblocker/css/clientblocker.css',	// Set this to relative path of css file if module has its own css file
             //							'js' => array('/clientblocker/js/clientblocker.js'),          // Set this to relative path of js file if module must load a js on all pages
             							'langfiles' => array('clientblocker'),
                                        'hooks' => array(
                                            'ordercard',
                                            //'ordersuppliercard',
                                            'propalcard',
                                            //'supplier_invoicecard',
                                            'invoicecard')
                                            //'expeditioncard',
                                            //'deliverycard',
                                            //'receptioncard',
                                            //'stocktransfercard',
                                            //'thirdpartycard',
                                            //'commonfooter')  	// Set here all hooks context managed by module
            //							'dir' => array('output' => 'othermodulename'),      // To force the default directories names
            //							'workflow' => array('WORKFLOW_MODULE1_YOURACTIONTYPE_MODULE2'=>array('enabled'=>'! empty($conf->module1->enabled) && ! empty($conf->module2->enabled)', 'picto'=>'yourpicto@mymodule')) // Set here all workflow context managed by module
            );
        $this->dirs = array();
        $this->config_page_url = array("clientblocker_setup.php@clientblocker");
        $this->hidden = false;
        $this->always_enabled = false;
        $this->dictionaries = array();
        //$this->hooks = array('ordercard');
        $this->depends = array();
        //$this->langfiles = array("clientblocker@clientblocker");
    }

    public function init($options = '')
    {
            global $db, $langs;

            dolibarr_set_const($this->db, 'CLIENTBLOCKER_BLOCK_ORDER_VALIDATION', 1, 'chaine', 0, '', $conf->entity);

			// Cargamos la clase ExtraFields
			require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
			$extrafields = new ExtraFields($db);

			// Lista de extrafields a crear
			$campos = [
				[
					'name' => 'cliente_bloqueado',
					'label' => 'Cliente Bloqueado',
					'type' => 'boolean',
					'size' => 0,
					'options' => '',
					'entity' => 'societe',
					'position' => 200,
					'perms' => '$user->hasRight(\'societe\', \'creer\')' // Permiso vacío = siempre editable
				]
			];
			

			// Iterar y crear cada campo en la entidad "user"
			foreach ($campos as $campo) {
				$result = $extrafields->addExtraField(
					$campo['name'],   // Nombre en la BD
					$campo['label'],  // Nombre visible en la UI
					$campo['type'],   // Tipo (boolean para checkbox, double para horas)
					$campo['position'], // Posición en la ficha
					$campo['size'],   // Tamaño (no necesario para estos tipos)
					$campo['entity'],           // Entidad "user" (usuarios)
					0,                // No es único
					0,                // No es obligatorio
					'0',               // Valor por defecto
					'',               // Parámetro adicional
					1,                // SIEMPRE EDITABLE
					$campo['perms'],               // Permisos
					'-1',             // Lista (no aplica)
					'',               // Ayuda (tooltip)
					'',               // Computado
					'',               // Entidad
					'',               // Archivo de idioma
					'1',              // Habilitado
					0,                // No totalizable
					0,                // No imprimible
					array()           // Parámetros adicionales
				);
		

				if ($result < 0) {
					dol_print_error($db, $extrafields->error);
					return $result;
				}
			}
		$sql = array();

		//$this->_load_tables('/clientblocker/sql/');

		return $this->_init($sql, $options);
	}

    public function remove($options = '')
    {
        global $db, $langs;
        $extrafields = new ExtraFields($this->db);
        $res = $extrafields->delete('cliente_bloqueado', 'societe');

        if ($res < 0) {
            dol_syslog("Failed to delete extrafield cliente_bloqueado", LOG_ERR);
        }

        return $this->_remove(array(), $options);
    }
}
