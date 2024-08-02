<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:5',
            'content' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
        ];
    }
    public function messages(): array
    {
        return [
<<<<<<< HEAD
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name may not be greater than 255 characters.',
            'name.min' => 'Name must be at least 5 characters.',
            'content.required' => 'Content is required.',
            'content.string' => 'Content must be a string.',
            'project_id.exists' => 'The selected project is invalid.',
=======
            'name.required' => 'Không được bỏ trống.',
            'name.min' => 'Tối thiểu phải có 5 ký tự.',
            'name.max' => 'Tối đa 255 ký tự.',
>>>>>>> b774656 (done search and fillter)
        ];
    }
}
