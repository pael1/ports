<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataRequest extends FormRequest
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
            // 'formtype' => 'required',
            'assignedto' => 'required',
            'placeofcommission' => 'required',
            'similar' => 'required',
            'counterchargedetails' => 'required',
            'relateddetails' => 'required'
            // 'files.*' => 'mimes:pdf|max:2000'
        ];
    }
}
