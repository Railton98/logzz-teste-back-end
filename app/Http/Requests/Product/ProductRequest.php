<?php

namespace App\Http\Requests\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique(Product::class, 'name')
                    ->ignore($this->route('product')),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'description' => [
                'required',
                'string',
            ],
            'image_url' => [
                'nullable',
                'url',
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists(Category::class, 'id'),
            ],
        ];
    }
}
