<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|in:1,3,6,12', // الشهور المسموحة
            'percent' => 'required|integer|min:0|max:100',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
{
    return [
        // 'name.required'     => 'اسم الباقة مطلوب.',
        // 'duration.required' => 'مدة الباقة مطلوبة.',
        // 'duration.in'       => 'مدة الباقة لازم تكون 1 أو 3 أو 6 أو 12 شهر.',
        // 'percent.required'  => 'نسبة الخصم مطلوبة.',
        // 'percent.min'       => 'النسبة لازم تكون أكبر من أو تساوي 0.',
        // 'percent.max'       => 'النسبة لازم تكون أقل من أو تساوي 100.',
    ];
}

}
