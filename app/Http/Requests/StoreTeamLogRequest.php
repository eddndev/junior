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
        ];
    }
}
