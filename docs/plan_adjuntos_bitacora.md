Plan de Implementación Detallado: Adjuntos Multimedia y Enlaces en Bitácora de Equipo

**Objetivo:** Amplificar las entradas de la bitácora de equipo permitiendo a los usuarios adjuntar archivos multimedia (imágenes, audio, documentos) y enlaces a contenido externo (videos, URLs genéricas, imágenes externas), con una interfaz intuitiva inspirada en Google Classroom.

**Tecnologías Clave:**
*   **Spatie Media Library:** Para la gestión robusta de archivos adjuntos y enlaces.
*   **Livewire:** Para una experiencia de usuario dinámica y reactiva en el frontend (subidas, previsualizaciones, eliminación temporal).
*   **Alpine.js:** Para interacciones de UI más finas (ej. drag-and-drop, toggles).
*   **Laravel Storage:** Para el almacenamiento seguro de los archivos.

---

**1. Consideraciones de Diseño (Interfaz tipo Classroom para Adjuntos):**

La interfaz de adjuntos se integrará en el formulario de creación/edición de la bitácora (`x-forms.composer`) y se visualizará de forma clara en las entradas existentes.

*   **Sección de Adjuntos en el Formulario:**
    *   Un área visible para "Arrastrar y soltar archivos aquí" o un botón "Seleccionar archivos".
    *   Un botón "Añadir Enlace" que abrirá un pequeño modal o desplegará un campo de texto para la URL y un selector de "Tipo de Enlace" (Video, Imagen Externa, Enlace Genérico).
    *   Una lista dinámica de los archivos y enlaces que se están adjuntando (antes de guardar la entrada de bitácora). Cada elemento tendrá:
        *   Un icono representativo (miniatura para imágenes, icono de archivo para documentos, icono de enlace para URLs).
        *   El nombre del archivo/enlace.
        *   Un botón "X" para eliminar el adjunto antes de la publicación.
        *   Indicador de progreso de subida para archivos grandes.
*   **Visualización en Entradas de Bitácora:**
    *   Los adjuntos se mostrarán debajo del contenido principal de la entrada.
    *   **Imágenes/PDFs/Documentos:** Miniaturas o iconos clicables que abran el archivo en una nueva pestaña o un modal de previsualización.
    *   **Audio:** Un reproductor de audio incrustado.
    *   **Videos (enlace):** Un reproductor de video incrustado (ej. YouTube, Vimeo) si el tipo de enlace es "Video".
    *   **Enlaces Genéricos:** Un enlace clicable con un icono de enlace externo.
    *   **Archivos de Código/Texto:** Un icono de archivo y un enlace de descarga.

---

**2. Pasos de Implementación:**

**Paso 1: Configuración de Spatie Media Library (Verificado)**
*   **Estado:** Verificado. La migración `2025_10_07_040454_create_media_table.php` existe y la librería `spatie/laravel-medialibrary` está instalada.

**Paso 2: Modificación del Modelo `TeamLog`**
*   **Implementar `HasMedia`:** Añadir `use HasMedia;` y `use InteractsWithMedia;` al modelo `app/Models/TeamLog.php`.
*   **Definir Colecciones de Medios:** En el modelo `TeamLog`, definir colecciones para organizar los adjuntos. Por ejemplo:
    ```php
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
             ->useDisk('public_uploads'); // O un disco específico para bitácoras
        $this->addMediaCollection('links'); // Para enlaces externos
    }
    ```
*   **Propiedades Personalizadas para Enlaces:** Para diferenciar los tipos de enlaces (video, imagen externa, genérico), se usarán propiedades personalizadas de Spatie Media Library.
    ```php
    // En el modelo TeamLog
    public function registerMediaConversions(Media $media = null): void
    {
        // Opcional: para generar miniaturas de imágenes adjuntas
        $this->addMediaConversion('thumb')->width(100)->height(100);
    }
    ```

**Paso 3: Modificación del `TeamLogController`**
*   **Validación:** Extender la validación para incluir los campos de archivos y enlaces.
    *   `attachments.*`: `file|max:10240` (10MB por archivo), `mimes:jpeg,png,jpg,gif,mp3,wav,mp4,mov,pdf,doc,docx,txt,zip,rar,json,xml,html,css,js,php,py,java,c,cpp` (ejemplo de tipos permitidos).
    *   `links.*.url`: `url|max:2048`.
    *   `links.*.type`: `in:video,image,external`.
*   **Manejo de Subidas de Archivos:**
    *   Después de crear la entrada de `TeamLog`, iterar sobre los archivos subidos (`$request->file('attachments')`).
    *   Usar `$teamLog->addMediaFromRequest('attachments')->toMediaCollection('attachments');` para cada archivo.
*   **Manejo de Enlaces:**
    *   Iterar sobre los enlaces enviados (`$request->input('links')`).
    *   Para cada enlace, usar `$teamLog->addMediaFromUrl($link['url'])->withCustomProperties(['link_type' => $link['type']])->toMediaCollection('links');`.

**Paso 4: Actualización de la Vista `team-logs/index.blade.php` (Frontend)**
*   **Integración con `x-forms.composer`:**
    *   Añadir un nuevo slot o modificar el `composer` para incluir la interfaz de adjuntos.
    *   **Input de Archivos:**
        *   Un `<input type="file" multiple>` oculto, activado por un botón.
        *   Un área de drag-and-drop (usando Alpine.js para la lógica).
    *   **Input de Enlaces:**
        *   Un campo de texto para la URL.
        *   Un `<select>` para el tipo de enlace (Video, Imagen Externa, Enlace Genérico).
        *   Un botón "Añadir".
    *   **Previsualización Dinámica:**
        *   Usar Livewire para gestionar el estado de los adjuntos antes de la publicación.
        *   Mostrar una lista de los archivos/enlaces pendientes de adjuntar, con miniaturas/iconos y un botón para eliminar.
*   **Modificación del `TeamLogController` (para Livewire):** Si se usa Livewire para la interfaz de adjuntos, el `TeamLogController` se simplificaría, y la lógica de subida/asociación se movería a un componente Livewire. Esto es lo más recomendable para una interfaz tipo Classroom.

**Paso 5: Visualización de Adjuntos en `team-logs/index.blade.php`**
*   **Iterar sobre `TeamLog->getMedia()`:**
    *   Para cada `media` item, verificar su colección (`attachments` o `links`) y sus propiedades personalizadas (`link_type`).
    *   **Archivos (colección 'attachments'):**
        *   **Imágenes:** `<img>` con `media->getUrl('thumb')` o `media->getUrl()`.n        *   **PDFs:** `<a>` con `media->getUrl()` y un icono de PDF.
        *   **Audio:** `<audio controls src="{{ $media->getUrl() }}"></audio>`.
        *   **Otros:** `<a>` con `media->getUrl()` y un icono genérico de archivo.
    *   **Enlaces (colección 'links'):**
        *   **Video:** Usar `<iframe>` para incrustar videos de YouTube/Vimeo (requiere parsear la URL).
        *   **Imagen Externa:** `<img>` con `media->getUrl()`.
        *   **Enlace Genérico:** `<a>` con `media->getUrl()`.

**Paso 6: Permisos (Revisión)**
*   El permiso `crear-bitacora` debería ser suficiente para adjuntar archivos y enlaces. No se requiere un permiso adicional a menos que se desee un control muy granular.

**Paso 7: Pruebas**
*   **Unitarias:** Probar la asociación de medios con el modelo `TeamLog`.
*   **Funcionales:** Probar la subida de archivos, la adición de enlaces, la validación y la visualización correcta en la interfaz.

---

**Consideraciones Adicionales:**

*   **Almacenamiento:** Configurar un disco de almacenamiento específico para los adjuntos de la bitácora (ej. `config/filesystems.php`).
*   **Seguridad:** Asegurar que los archivos subidos sean escaneados por malware si es crítico.
*   **Rendimiento:** Optimizar la carga de miniaturas y la incrustación de videos para no ralentizar la interfaz.
*   **Gestión de Errores:** Mostrar mensajes claros al usuario si la subida falla o el enlace no es válido.

Este plan nos permitirá construir una funcionalidad de adjuntos muy completa y fácil de usar, alineada con la visión de "esteroides" para la bitácora.
