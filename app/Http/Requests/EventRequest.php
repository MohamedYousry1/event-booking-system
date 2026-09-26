<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->routeIs('events.update')) {
            return [
                'title' => 'sometimes|string|min:2|max:255',
                'description' => 'sometimes|string',
                'location' => 'sometimes|string|min:3|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after:start_date',
                'capacity' => 'sometimes|integer|max:1000',
                'available_seats' => 'sometimes|integer|max:1000|lte:capacity',
                'price' => 'sometimes|decimal:2',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'sometimes|string|in:draft,published,cancelled,completed',
                'category_id' => 'sometimes|exists:categories,id',
            ];
        }

        return [
            'title' => 'required|string|min:2|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|min:3|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'capacity' => 'required|integer|max:1000',
            'available_seats' => 'required|integer|max:1000|lte:capacity',
            'price' => 'required|decimal:2',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'string|in:draft,published,cancelled,completed',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
