<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "visitor_name"=>["required"],
            "visitor_document"=>["required"],
            "laboratory_id"=>[Rule::requiredIf(fn ()=> $this->user()->hasRole(["professor"])),"nullable","exists:App\Models\Laboratory,id"],

        ];
    }
}
