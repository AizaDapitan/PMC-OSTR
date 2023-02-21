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
            // 'cost_code' => 'required|max:255',
            'requested_by' => 'required',
            'remarks' => 'max:255',
            'origin' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'dept.required' => "The Department is required.",
            'date_needed.required' => "The Date Needed is required.",
            // 'cost_code.required' => "The Cost Code is required.",
            'remarks.max' => 'The Remarks must not be greater than 255 characters.',
            'cost_code.max' => 'The Remarks must not be greater than 255 characters.',
            'origin.required' => 'Origin is required.'

        ];
    }
}
