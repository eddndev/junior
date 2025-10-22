# Configuración de Colas para Producción - Sistema de Bitácora

**Fecha de creación:** 2025-10-22
**Módulo:** Team Log (Bitácora de Equipo) - Sistema de adjuntos multimedia

---

## 📋 Descripción General

El sistema de adjuntos multimedia de la Bitácora de Equipo utiliza **Laravel Queues** para procesar conversiones de imágenes de forma asíncrona. Esto proporciona una mejor experiencia de usuario al evitar tiempos de espera largos durante la publicación de entradas con archivos adjuntos.

### Conversiones Procesadas en Cola:
- **WebP:** Formato moderno con buen soporte, calidad 85%
- **AVIF:** Formato de última generación, calidad 80%

### Procesamiento Inmediato (sin cola):
- **Thumbnail:** Conversión a WebP de 300x300px para preview inmediato

---

## 🔧 Configuración del Driver de Colas

Laravel soporta múltiples drivers de colas. A continuación se detallan las opciones recomendadas para producción:

### Opción 1: Redis (Recomendado para Alta Carga)

**Ventajas:**
- Muy rápido (almacenamiento en memoria)
- Soporte para prioridades de cola
- Escalable horizontalmente
- Reintento automático de trabajos fallidos

**Requisitos:**
- Redis Server instalado y corriendo
- Extensión PHP `phpredis` o paquete `predis/predis`

**Configuración en `.env`:**
```env
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

**Instalación de Redis (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

**Instalación de dependencias PHP:**
```bash
# Opción 1: Usar extensión nativa (recomendado)
sudo apt install php-redis

# Opción 2: Usar Predis (paquete PHP puro)
composer require predis/predis
```

---

### Opción 2: Database (Recomendado para Carga Media)

**Ventajas:**
- No requiere software adicional
- Más simple de configurar
- Persistencia automática
- Funciona con cualquier base de datos soportada por Laravel

**Desventajas:**
- Más lento que Redis
- Mayor carga en la base de datos

**Configuración en `.env`:**
```env
QUEUE_CONNECTION=database
```

**Crear tabla de jobs (solo primera vez):**
```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

---

### Opción 3: Sync (Solo para Desarrollo/Testing)

**NO usar en producción**. Esta opción procesa los trabajos de forma síncrona (sin cola), bloqueando la respuesta HTTP.

```env
QUEUE_CONNECTION=sync
```

---

## 🚀 Configuración del Worker de Colas

Los workers son procesos que ejecutan los trabajos en segundo plano. Es fundamental que estos procesos se ejecuten de forma continua en producción.

### Comando Básico del Worker

```bash
php artisan queue:work
```

### Opciones Recomendadas para Producción

```bash
php artisan queue:work redis \
  --tries=3 \
  --timeout=90 \
  --max-time=3600 \
  --max-jobs=1000 \
  --sleep=3
```

**Explicación de parámetros:**
- `redis`: Driver de cola a usar (cambiar según tu configuración)
- `--tries=3`: Reintentar hasta 3 veces si falla
- `--timeout=90`: Tiempo máximo por trabajo (90 segundos)
- `--max-time=3600`: Reiniciar worker cada 1 hora
- `--max-jobs=1000`: Reiniciar worker después de 1000 trabajos
- `--sleep=3`: Dormir 3 segundos cuando no hay trabajos

---

## 🔄 Supervisión con Supervisor (Recomendado)

**Supervisor** es un gestor de procesos que mantiene los workers corriendo de forma continua, reiniciándolos automáticamente si fallan.

### Instalación de Supervisor (Ubuntu/Debian)

```bash
sudo apt update
sudo apt install supervisor
```

### Configuración del Worker

Crear archivo de configuración: `/etc/supervisor/conf.d/junior-worker.conf`

```ini
[program:junior-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/junior/artisan queue:work redis --tries=3 --timeout=90 --max-time=3600 --max-jobs=1000 --sleep=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/junior/storage/logs/worker.log
stopwaitsecs=3600
```

**Explicación de parámetros:**
- `process_name`: Nombre del proceso (con número)
- `command`: Comando completo del worker (usar ruta absoluta a PHP y artisan)
- `autostart=true`: Iniciar automáticamente con Supervisor
- `autorestart=true`: Reiniciar si el worker falla
- `user=www-data`: Usuario que ejecuta el worker (debe tener permisos de escritura en storage/)
- `numprocs=2`: Número de workers en paralelo (ajustar según carga)
- `stdout_logfile`: Archivo de logs del worker
- `stopwaitsecs=3600`: Esperar 1 hora antes de forzar detención (permite que trabajos largos terminen)

### Comandos de Supervisor

```bash
# Recargar configuración
sudo supervisorctl reread
sudo supervisorctl update

# Iniciar workers
sudo supervisorctl start junior-worker:*

# Detener workers
sudo supervisorctl stop junior-worker:*

# Reiniciar workers
sudo supervisorctl restart junior-worker:*

# Ver estado
sudo supervisorctl status

# Ver logs en tiempo real
sudo tail -f /var/www/junior/storage/logs/worker.log
```

---

## 🔄 Alternativa: Systemd (Ubuntu 16.04+)

Si prefieres usar systemd en lugar de Supervisor:

### Crear servicio: `/etc/systemd/system/junior-worker.service`

```ini
[Unit]
Description=Junior Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/junior
ExecStart=/usr/bin/php /var/www/junior/artisan queue:work redis --tries=3 --timeout=90 --max-time=3600 --sleep=3
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

### Comandos de Systemd

```bash
# Recargar configuración
sudo systemctl daemon-reload

# Habilitar inicio automático
sudo systemctl enable junior-worker

# Iniciar servicio
sudo systemctl start junior-worker

# Ver estado
sudo systemctl status junior-worker

# Ver logs
sudo journalctl -u junior-worker -f
```

---

## 📊 Monitoreo con Laravel Horizon (Opcional)

**Laravel Horizon** proporciona un dashboard visual para monitorear colas, métricas y trabajos fallidos. Requiere Redis.

### Instalación

```bash
composer require laravel/horizon
php artisan horizon:install
php artisan migrate
```

### Configuración en `config/horizon.php`

```php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'processes' => 10,
            'tries' => 3,
            'timeout' => 90,
        ],
    ],
],
```

### Ejecutar Horizon

```bash
php artisan horizon
```

**Nota:** Usar Supervisor o Systemd para mantener Horizon corriendo (reemplazar `queue:work` con `horizon` en el comando).

### Acceder al Dashboard

URL: `https://tudominio.com/horizon`

**IMPORTANTE:** Proteger el dashboard en producción. En `app/Providers/HorizonServiceProvider.php`:

```php
protected function gate()
{
    Gate::define('viewHorizon', function ($user) {
        return in_array($user->email, [
            'admin@junior.com',
        ]);
    });
}
```

---

## 🔍 Gestión de Trabajos Fallidos

### Ver trabajos fallidos

```bash
php artisan queue:failed
```

### Reintentar trabajos fallidos

```bash
# Reintentar todos
php artisan queue:retry all

# Reintentar uno específico
php artisan queue:retry <job-id>
```

### Eliminar trabajos fallidos

```bash
# Eliminar todos
php artisan queue:flush

# Eliminar uno específico
php artisan queue:forget <job-id>
```

### Tabla de trabajos fallidos

Si no existe, crearla:

```bash
php artisan queue:failed-table
php artisan migrate
```

---

## 🧪 Testing de Colas en Desarrollo

### Modo Sync (sin cola)

En `.env`:
```env
QUEUE_CONNECTION=sync
```

Los trabajos se procesan de forma inmediata. Útil para debugging.

### Modo Database (con cola)

```bash
# Terminal 1: Servidor de desarrollo
php artisan serve

# Terminal 2: Worker de desarrollo
php artisan queue:work --tries=1
```

### Forzar procesamiento inmediato en tests

En tests PHPUnit:

```php
use Illuminate\Support\Facades\Queue;

public function test_creates_team_log_with_attachments()
{
    Queue::fake();

    // ... código de test ...

    Queue::assertPushed(function (PerformConversions $job) {
        return $job->media->collection_name === 'attachments';
    });
}
```

---

## 📝 Checklist de Configuración para Producción

- [ ] Configurar driver de cola en `.env` (Redis o Database)
- [ ] Crear tablas de jobs si usas database: `php artisan queue:table && php artisan migrate`
- [ ] Instalar y configurar Supervisor o Systemd
- [ ] Configurar worker con parámetros adecuados (tries, timeout, max-time)
- [ ] Ajustar número de procesos (`numprocs`) según carga esperada
- [ ] Configurar permisos: usuario del worker debe poder escribir en `storage/`
- [ ] Configurar logs: verificar que `storage/logs/worker.log` sea escribible
- [ ] Proteger dashboard de Horizon si lo usas
- [ ] Configurar monitoreo de trabajos fallidos
- [ ] Configurar alertas si un worker cae (opcional: healthcheck endpoint)
- [ ] Documentar procedimiento de reinicio para deploys

---

## 🔄 Reinicio de Workers en Deploy

**IMPORTANTE:** Los workers mantienen el código en memoria. Debes reiniciarlos después de cada deploy.

### Con Supervisor

```bash
php artisan queue:restart
```

Este comando marca un flag que hace que los workers terminen gracefully después del trabajo actual.

Luego, Supervisor los reinicia automáticamente con el código actualizado.

### Agregar en script de deploy

```bash
# deploy.sh
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar workers
php artisan queue:restart

# Esperar a que terminen los trabajos actuales
sleep 5

echo "Deploy completado!"
```

---

## 🐛 Troubleshooting

### Workers no procesan trabajos

**Verificar:**
1. ¿El worker está corriendo? `sudo supervisorctl status`
2. ¿Hay trabajos en cola? `php artisan queue:work --once` (procesa uno y para)
3. ¿Logs de error? `tail -f storage/logs/laravel.log`

### Trabajos fallan constantemente

**Verificar:**
1. Permisos de escritura en `storage/app/public`
2. Extensión GD o Imagick instalada para conversiones de imagen
3. Límites de memoria en `php.ini` (mínimo 256MB recomendado)
4. Timeout configurado apropiadamente

### Worker consume mucha memoria

**Soluciones:**
1. Reducir `--max-jobs` para reiniciar workers más frecuentemente
2. Reducir `--max-time` para reiniciar workers más frecuentemente
3. Aumentar RAM del servidor
4. Usar `--memory=512` para reiniciar worker al alcanzar 512MB

```bash
php artisan queue:work --memory=512 --max-jobs=500
```

---

## 📚 Recursos Adicionales

- [Laravel Queues Documentation](https://laravel.com/docs/10.x/queues)
- [Laravel Horizon Documentation](https://laravel.com/docs/10.x/horizon)
- [Spatie Media Library - Queue Conversions](https://spatie.be/docs/laravel-medialibrary/v10/converting-images/queueing-conversions)
- [Supervisor Documentation](http://supervisord.org/)

---

**Última actualización:** 2025-10-22
**Autor:** Claude Code
**Módulo:** Team Log - Sistema de adjuntos multimedia
