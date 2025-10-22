<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TeamLog extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'area_id',
        'user_id',
        'title',
        'content',
        'type',
    ];

    /**
     * Get the area that owns the team log.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the user who created the team log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs of a specific type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Register media collections for this model.
     */
    public function registerMediaCollections(): void
    {
        // Colección para archivos locales (imágenes, documentos, audio, etc.)
        $this->addMediaCollection('attachments')
             ->useDisk('public')
             ->acceptsMimeTypes([
                 // Imágenes
                 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                 // Documentos
                 'application/pdf',
                 'application/msword',
                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                 'application/vnd.ms-excel',
                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                 'application/vnd.ms-powerpoint',
                 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                 // Audio
                 'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4',
                 // Código/Texto
                 'text/plain', 'application/json', 'text/xml', 'text/html',
                 'text/css', 'application/javascript',
                 // Comprimidos
                 'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
             ]);

        // Colección para enlaces externos (videos, imágenes externas, URLs)
        $this->addMediaCollection('links')
             ->useDisk('public');
    }

    /**
     * Register media conversions for this model.
     *
     * Conversiones de imágenes a formatos modernos (webp/avif)
     * Se procesan de forma asíncrona en cola.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Solo procesar conversiones para imágenes en la colección 'attachments'
        if ($media && $media->collection_name === 'attachments' && str_starts_with($media->mime_type, 'image/')) {
            // Conversión a WebP (mejor soporte)
            $this->addMediaConversion('webp')
                 ->format('webp')
                 ->quality(85)
                 ->performOnCollections('attachments')
                 ->queued(); // ← Procesar en cola

            // Conversión a AVIF (más moderno, mejor compresión)
            $this->addMediaConversion('avif')
                 ->format('avif')
                 ->quality(80)
                 ->performOnCollections('attachments')
                 ->queued(); // ← Procesar en cola

            // Thumbnail para previsualización rápida (pequeño, sync)
            $this->addMediaConversion('thumb')
                 ->width(300)
                 ->height(300)
                 ->format('webp')
                 ->quality(70)
                 ->performOnCollections('attachments')
                 ->nonQueued(); // ← Procesar inmediatamente para preview
        }
    }
}