<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskSubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_submission_id',
        'filename',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the submission this file belongs to.
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(TaskSubmission::class, 'task_submission_id');
    }

    /**
     * Get the user who uploaded this file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the file URL for download.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('submissions')->url($this->path);
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Get file icon based on mime type.
     */
    public function getIconAttribute(): string
    {
        return match (true) {
            str_starts_with($this->mime_type, 'image/') => 'photo',
            str_starts_with($this->mime_type, 'video/') => 'video-camera',
            str_starts_with($this->mime_type, 'audio/') => 'musical-note',
            $this->mime_type === 'application/pdf' => 'document',
            str_contains($this->mime_type, 'spreadsheet') || str_contains($this->mime_type, 'excel') => 'table-cells',
            str_contains($this->mime_type, 'presentation') || str_contains($this->mime_type, 'powerpoint') => 'presentation-chart-bar',
            str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document') => 'document-text',
            str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'archive') => 'archive-box',
            default => 'paper-clip',
        };
    }

    /**
     * Delete the file from storage when the model is deleted.
     */
    protected static function booted(): void
    {
        static::deleting(function (TaskSubmissionFile $file) {
            Storage::disk('submissions')->delete($file->path);
        });
    }
}
