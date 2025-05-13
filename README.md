# Bloquear Cliente (Client Blocker) para Dolibarr

**Versión:** 1.0.1  
**Compatible con:** Dolibarr 15 a 21+  
**Autor:** Alvaro H – Globalten Technology S.L.  
**Licencia:** GPL v3+

## Descripción

Este módulo permite bloquear procesos de venta para clientes marcados como "bloqueados".  
Es útil para controlar la facturación y evitar que validen y procesen presupuestos, pedidos o facturas a clientes con incidencias o deudas.

## Funcionalidades

- Añade un campo extra ("Cliente Bloqueado") en la ficha del cliente.
- Muestra avisos visuales en los pedidos si el cliente está bloqueado.
- Opción para desactivar el botón de **VALIDAR** en los presupuestos, pedidos y facturas.
- Bloquea completamente la validación del proceso si se intenta confirmar.
- Traducciones disponibles en español e inglés.

## Instalación

1. Copia la carpeta `clientblocker` dentro de `/htdocs/custom/`.
2. Ve a **Inicio > Configuración > Módulos** y activa el módulo **BLOQUEAR CLIENTE**.
3. Configura el módulo desde su página de ajustes si lo deseas.

## Configuración

- Accede a la configuración del módulo desde el menú de módulos.
- Activa la opción "Desactivar botón VALIDAR" si deseas impedir la validación manual. Si no, simplemente se muestran advertencias durante todo el proceso.
- Si el cliente está marcado como bloqueado, se impedirá validar el pedido incluso con confirmación.

## Soporte

Este módulo se distribuye tal cual.  
Para soporte personalizado o mejoras, contacta con **Alvaro H – Globalten Technology S.L.**