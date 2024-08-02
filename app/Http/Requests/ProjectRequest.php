<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'exists:projects,name',
        ];
    }
}
