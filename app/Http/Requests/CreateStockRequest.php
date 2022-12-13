<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dept' => 'required',
            'date_needed' => 'required',
            'cost_code' => 'required',
            'requested_by' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'dept.required' => "The Department is required.",
            'date_needed.required' => "The Date Needed is required.",
            'cost_code.required' => "The Cost Code is required.",
        ];
    }
}
