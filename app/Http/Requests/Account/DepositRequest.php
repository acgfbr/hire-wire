<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $accountId = $this->route('account');
        $account = $this->user()->accounts()->where('id', $accountId)->first();
        
        if (!$account) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        }
        
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
            'amount' => 'required|numeric|min:0.01'
        ];
    }
}
