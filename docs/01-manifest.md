# Manifiesto del Proyecto: Junior
**Versión:** 2.0
**Fecha:** 2025-10-16
**Autor:** Equipo del Proyecto

## 1. Resumen Ejecutivo

Junior es una plataforma de gestión empresarial interna diseñada para centralizar y automatizar las operaciones de la compañía. El proyecto busca unificar las distintas áreas (Dirección General, Marketing, Finanzas, Producción y Recursos Humanos) en un solo ecosistema digital. El Producto Mínimo Viable (MVP) incluirá funcionalidades esenciales para todas las áreas, sentando las bases de un sistema de gestión integral con un modelo de roles y permisos flexible.

## 2. Declaración del Problema

La gestión actual de la empresa es descentralizada. Cada área opera con sus propias herramientas y métodos, resultando en una falta de conexión y conocimiento sobre las tareas y asignaciones de los demás. Este enfoque manual y fragmentado provoca que el contexto se pierda fácilmente, generando ineficiencias, duplicidad de trabajo y una nula visibilidad estratégica para la dirección. La falta de un sistema de roles unificado impide que los miembros del equipo colaboren en distintas áreas de forma estructurada.

## 3. Visión y Solución Propuesta

**Visión:** Convertir a Junior en el sistema nervioso central de la organización, donde toda la información y los procesos fluyan de manera transparente, automatizada y accesible, permitiendo una toma de decisiones ágil y basada en datos.

**Solución:** Desarrollar una plataforma web modular que integre todas las áreas de la empresa. El sistema se construirá sobre un núcleo de gestión de usuarios robusto que permita a una persona tener múltiples roles y permisos combinados, reflejando la naturaleza fluida de las responsabilidades en la empresa.

## 4. Perfiles de Usuario (Target Audience)

*   **Dirección General:** Rol de supervisión con acceso a un panel para monitorear la actividad de todas las áreas. Puede delegar tareas a los directores de área, gestionar un calendario global y enviar mensajes generales. También puede ser miembro de un área y tener roles subordinados.
*   **Director de Área:** Responsable de un área. Recibe tareas de Dirección General y las desglosa en subtareas para los miembros de su equipo. Gestiona los recursos y el progreso de su área.
*   **Miembro de Producción:** Visualiza tareas asignadas. Gestiona un calendario personal de disponibilidad semanal. Consulta un calendario de equipo para coordinar con sus compañeros. Marca sus tareas como completadas.
*   **Administrador de RRHH:** Gestiona el ciclo de vida de los usuarios (altas, bajas, modificaciones). Envía notificaciones individuales o por área.
*   **Empleado General:** Usuario base que pertenece a una o más áreas, tiene roles asignados y consume información, notificaciones y eventos del calendario.

## 5. Objetivos Principales (Goals)

*   **Para el Negocio/Organización:**
    *   [ ] **Centralizar la Gestión:** Unificar la administración y visibilidad de tareas de todas las áreas en una sola plataforma.
    *   [ ] **Flexibilidad Operativa:** Implementar un sistema de roles que permita a los empleados colaborar en múltiples áreas y funciones sin fricción.
*   **Para el Usuario:**
    *   [ ] **Claridad de Tareas:** Proveer a cada empleado una vista unificada de sus asignaciones y subtareas.
    *   [ ] **Fomentar Colaboración:** Facilitar la coordinación entre miembros de un equipo (ej. calendario de disponibilidad de Producción).

## 6. Alcance del Proyecto (Scope)

### Funcionalidades INCLUIDAS (Producto Mínimo Viable - MVP)

*   **Núcleo - Sistema de Usuarios y Permisos:**
    *   Modelo de datos para usuarios, roles y permisos aditivos.
    *   Gestión del ciclo de vida de usuarios (CRUD) por RRHH.
*   **Módulo - Tareas y Colaboración:**
    *   Asignación de tareas (Dirección -> Director) y subtareas (Director -> Miembro).
    *   Calendario global, de equipo y de disponibilidad personal.
    *   Bitácora de actividades por área/equipo.
*   **Módulo - Comunicación y Trazabilidad:**
    *   Sistema de notificaciones y mensajes generales.
    *   Registro de auditoría (trazabilidad) para acciones clave, visible para Dirección y RRHH.
*   **Módulo - Finanzas (Planificación):**
    *   Gestión de clientes.
    *   Catálogo y calculadora de costos.
    *   Gestión de presupuestos (Budgets).
    *   Generación de cotizaciones con cálculo de rentabilidad.
    *   Reportes de consumo de presupuestos y proyecciones.
*   **Módulo - Marketing (Gestión):**
    *   Planificación y gestión de campañas.
    *   Asignación de tareas de marketing.
    *   Registro básico de leads por campaña.
    *   Reportes de actividad de campañas.

### Funcionalidades EXCLUIDAS (Fuera de Alcance para la v1.0)

*   **Finanzas (Contabilidad):** Facturación, conciliación bancaria, contabilidad formal.
*   **Marketing (Ejecución):** Herramientas para envío de emails masivos o publicación automática en redes sociales.
*   **Analítica Avanzada:** Dashboards con analítica predictiva o Business Intelligence complejo.
*   **Integraciones Externas:** Conexión con sistemas de terceros (nóminas, ERPs existentes, etc.).
*   **Gestión de Documentos:** Repositorio avanzado de archivos y versionado.

## 7. Stack Tecnológico

*   **Backend:** Laravel 11
*   **Frontend:** Livewire & Alpine.js
*   **Panel de Admin:** Filament (Sugerido)
*   **Autenticación:** Laravel Breeze / Jetstream
*   **Base de Datos:** MySQL / MariaDB
*   **Servidor:** Apache

## 8. Stakeholders Clave

*   **Propietario del Producto:** Equipo del Proyecto
*   **Líder de Desarrollo:** Gemini Agent

## 9. Métricas de Éxito

*   **Adopción:** 100% de los empleados y sus roles correctamente migrados a la plataforma.
*   **Trazabilidad:** El 100% de las nuevas tareas entre áreas se gestionan a través de la plataforma.
*   **Eficiencia:** Reducción del tiempo necesario para coordinar sesiones de producción gracias al calendario de disponibilidad.

## 10. Línea de Tiempo Estimada

El alcance del MVP ha sido expandido. La nueva implementación se dividirá en los siguientes Sprints:

*   **Sprint 0: Configuración y Núcleo del Sistema**
    *   **Objetivo:** Establecer las bases del proyecto y el sistema de usuarios, roles y permisos.
    *   **Entregables:** Entorno de desarrollo, modelo de datos principal, CRUD de usuarios.

*   **Sprint 1: Tareas, Comunicación y Calendarios**
    *   **Objetivo:** Construir las herramientas de colaboración y organización diaria.
    *   **Entregables:** Módulos de Tareas, Notificaciones y Calendarios funcionales.

*   **Sprint 2: Módulo de Finanzas (MVP)**
    *   **Objetivo:** Implementar la gestión de costos, presupuestos y cotizaciones.
    *   **Entregables:** Catálogo de costos, calculadora, gestión de presupuestos y cotizaciones mejoradas.

*   **Sprint 3: Módulo de Marketing (MVP) y Trazabilidad**
    *   **Objetivo:** Añadir la gestión de marketing y las herramientas de supervisión.
    *   **Entregables:** Módulo de Campañas y Tareas de Marketing, registro de leads y panel de trazabilidad/auditoría.

*   **Sprint 4: Integración, Pruebas y Despliegue**
    *   **Objetivo:** Asegurar la cohesión, calidad y estabilidad de toda la plataforma.
    *   **Entregables:** Pruebas E2E completas, UI refinada y preparación para el despliegue del MVP.

## 11. Riesgos y Consideraciones

*   **Complejidad del Modelo de Datos:** El sistema de roles y permisos múltiples es complejo y debe ser diseñado cuidadosamente para ser escalable.
*   **Scope Creep (Ampliación del Alcance):** El alcance del MVP ha crecido significativamente. Es crucial mantenernos enfocados en las funcionalidades definidas en esta versión del manifiesto para evitar retrasos.
*   **Adopción de Usuarios:** Puede haber resistencia al cambio. Se requerirá capacitación y una interfaz de usuario muy intuitiva.

---

**Notas Finales:**
Este documento es la única fuente de verdad para el alcance y los objetivos del MVP. Cualquier cambio debe ser discutido y reflejado aquí.
