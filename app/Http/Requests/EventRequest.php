<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'price' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required',
            'title.max' => 'The event title must not exceed 255 characters',
            'description.required' => 'The event description is required',
            'date.required' => 'The event date is required',
            'date.date' => 'Please provide a valid date',
            'time.required' => 'The event time is required',
            'location.required' => 'The event location is required',
            'location.max' => 'The location must not exceed 255 characters',
            'price.required' => 'The event price is required',
            'price.max' => 'The price must not exceed 255 characters',
        ];
    }
} 