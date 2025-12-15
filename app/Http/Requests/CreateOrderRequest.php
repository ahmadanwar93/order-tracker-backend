<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // authorization is the decision of whether we are letting an authenticated user to make this request
        // for authorization logic, there is a few places to put other than here
        // middlware, controller or policy
        // use policy for reusability
        // putting authorization logic here might mix up authorize logic and validation logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255']
        ];
    }
}
