<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoursesRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認可ロジックを追加
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['nullable','string'],
            'runTime' => 'required|integer|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'runTime' => intval($this->runTimeHour) * 60 + intval($this->runTimeMinute)
        ]);
    }

    public function attributes()
    {
        return [
            'name' => 'コース名',
            'description' => '説明',
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'コース名は必須です。',
            'runTime' => '1分以上6時間未満で選択してください。',
        ];
    }
}
