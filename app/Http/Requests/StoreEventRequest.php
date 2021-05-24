<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
            'user_id' => ['nullable', 'integer', 'min:1'],
            'event_status_id' => ['nullable', 'integer', 'min:1'],
            'name' => ['nullable', 'string', 'min: 5', 'max: 255'],
            'description' => ['nullable'],
            'start_date' => ['nullable',],
            'end_date' => ['nullable'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'display_start_time' => ['nullable', 'integer', 'min: 0', 'max:1'],
            'display_end_time' => ['nullable', 'integer', 'min: 0', 'max:1'],

            'category_ids' => ['nullable', 'array'],
//            'artiste_ids' => ['nullable', 'array'],
        ];
    }
}
