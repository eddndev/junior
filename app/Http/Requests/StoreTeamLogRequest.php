<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must have permission to create team log entries
        $user = $this->user();

        if (!$user || !$user->hasPermission('crear-bitacora')) {
            return false;
        }

        // User must belong to the area they're posting in
        $areaId = $this->input('area_id');
        return $user->areas->pluck('id')->contains($areaId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'area_id' => ['required', 'exists:areas,id'],
            'type' => ['required', 'string', 'in:decision,event,note,meeting'],

            // Validación de archivos adjuntos
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => [
                'file',
                'max:10240', // 10MB por archivo
                'mimes:jpeg,png,jpg,gif,webp,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,wav,ogg,m4a,txt,json,xml,html,css,js,zip,rar,7z'
            ],

            // Validación de enlaces
            'links' => ['nullable', 'array', 'max:10'],
            'links.*.url' => ['required', 'url', 'max:2048'],
            'links.*.type' => ['required', 'string', 'in:external,video,image'],
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'content' => 'contenido',
            'area_id' => 'área',
            'type' => 'tipo',
            'attachments' => 'archivos adjuntos',
            'attachments.*' => 'archivo',
            'links' => 'enlaces',
            'links.*.url' => 'URL del enlace',
            'links.*.type' => 'tipo de enlace',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El :attribute es obligatorio.',
            'title.max' => 'El :attribute no puede exceder :max caracteres.',
            'content.required' => 'El :attribute es obligatorio.',
            'content.max' => 'El :attribute no puede exceder :max caracteres.',
            'area_id.required' => 'Debes seleccionar un :attribute.',
            'area_id.exists' => 'El :attribute seleccionada no es válida.',
            'type.required' => 'Debes seleccionar un :attribute de entrada.',
            'type.in' => 'El :attribute seleccionado no es válido.',

            // Mensajes para archivos adjuntos
            'attachments.max' => 'No puedes adjuntar más de :max archivos.',
            'attachments.*.file' => 'El :attribute debe ser un archivo válido.',
            'attachments.*.max' => 'El :attribute no puede superar 10MB.',
            'attachments.*.mimes' => 'El :attribute tiene un formato no permitido.',

            // Mensajes para enlaces
            'links.max' => 'No puedes agregar más de :max enlaces.',
            'links.*.url.required' => 'La :attribute es obligatoria.',
            'links.*.url.url' => 'La :attribute debe ser una URL válida.',
            'links.*.url.max' => 'La :attribute no puede exceder :max caracteres.',
            'links.*.type.required' => 'El :attribute es obligatorio.',
            'links.*.type.in' => 'El :attribute no es válido.',
        ];
    }
}
