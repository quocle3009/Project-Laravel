<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|min:5|max:255'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Không được bỏ trống.',
            'name.min' => 'Tối thiểu phải có 5 ký tự.',
            'name.max' => 'Tối đa 255 ký tự.',
        ];
    }
}
