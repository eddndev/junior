<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware/policies
        // Only users with 'gestionar-usuarios' permission should reach here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the user being updated from route parameter
        $userId = $this->route('user')->id;

        return [
            // Basic user information
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Email must be unique, except for this user
                Rule::unique('users', 'email')->ignore($userId),
            ],

            // Password is optional when updating
            // Only validate if provided
            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => [
                'nullable',
                'required_with:password',
            ],

            // Status
            'is_active' => [
                'nullable',
                'boolean',
            ],

            // Areas assignment
            'areas' => [
                'nullable',
                'array',
            ],
            'areas.*' => [
                'integer',
                'exists:areas,id',
            ],

            // Roles assignment (can be simple array or array of objects with area_id)
            'roles' => [
                'nullable',
                'array',
            ],
            'roles.*.role_id' => [
                'required_with:roles',
                'integer',
                'exists:roles,id',
            ],
            'roles.*.area_id' => [
                'nullable',
                'integer',
                'exists:areas,id',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'is_active' => 'estado activo',
            'areas' => 'áreas',
            'areas.*' => 'área',
            'roles' => 'roles',
            'roles.*.role_id' => 'rol',
            'roles.*.area_id' => 'área del rol',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password_confirmation.required_with' => 'Debe confirmar la contraseña.',
            'areas.*.exists' => 'El área seleccionada no es válida.',
            'roles.*.role_id.exists' => 'El rol seleccionado no es válido.',
            'roles.*.area_id.exists' => 'El área del rol seleccionada no es válida.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure is_active is a boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        // If password is empty string, set it to null so it's not validated
        if ($this->password === '') {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }
    }
}
