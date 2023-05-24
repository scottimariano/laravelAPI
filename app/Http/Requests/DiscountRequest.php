<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class DiscountRequest extends FormRequest
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
        $rules = [
            "name" => "alpha_num:ascii|max:100|unique:discounts",
            "active" => "boolean",
            "brand_id" => [
                Rule::exists('brands', 'id')->where(function (Builder $query) {
                    return $query->where('active', true);
                })
            ],
            "access_type_code" => "exists:access_types,code",
            "priority" => "numeric|between:1,1000",
            "region_id" => "exists:regions,id",
            "discount_ranges" => "max:3",
            "discount_ranges.*.from_days" => "numeric|lt:discount_ranges.*.to_days",
            "discount_ranges.*.to_days" => "numeric",
            "discount_ranges.*.code" => "alpha_num:ascii|nullable",
            "discount_ranges.*.discount" => "numeric|nullable",
            "start_date" => "date_format:d/m/Y",
            "end_date" => "date_format:d/m/Y"
        ];

        if ($this->isMethod("PUT")) {
            $rules["discount_ranges.*.id"] = "required|numeric|exists:discount_ranges";
        }

        if ($this->isMethod("POST")) {
            $requiredFields = [
                "name",
                "access_type_code",
                "priority",
                "region_id",
                "discount_ranges",
                "discount_ranges.*.from_days",
                "discount_ranges.*.to_days",
                "start_date",
                "end_date"
            ];
            
            foreach ($requiredFields as $field) {
                $rules[$field] .= "|required";
            }

            array_push($rules["brand_id"], "required");
            $rules["discount_ranges.*.code"] .= "|required_if:discount_ranges.*.discount,null";
            $rules["discount_ranges.*.discount"] .= "|required_if:discount_ranges.*.code,null";
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'discount_ranges.*.from_days.numeric' => 'The #:position discount_ranges from_days field must be a number.',
            'discount_ranges.*.from_days.required' => 'The #:position discount_ranges from_days field is required.',
            'discount_ranges.*.to_days.numeric' => 'The #:position discount_ranges from_days field must be a number.',
            'discount_ranges.*.to_days.required' => 'The #:position discount_ranges from_days field is required.',
            'discount_ranges.*.from_days.lt' => 'The #:position discount_ranges from_days field must be less than to_days.',
            "discount_ranges.*.code" => 'The #:position discount_ranges code or discount are required',
            "discount_ranges.*.discount" => 'The #:position discount_ranges code or discount are required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'erros' => $validator->errors()
        ], 400));
    }
}
