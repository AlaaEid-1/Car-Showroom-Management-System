<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $carId = $this->route('car', 0);

        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => [
                'required',
                'integer',
                'between:1980,' . (date('Y') + 1),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],

            'status' => ['required', Rule::in(['draft', 'published', 'sold'])],

            'showroom_id' => ['nullable', 'exists:showrooms,id'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Allowed formats: jpg, jpeg, png, webp.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'car title',
            'brand' => 'brand',
            'model' => 'model',
            'year' => 'year',
            'price' => 'price',
            'description' => 'description',
            'images' => 'car images',
        ];
    }
}
