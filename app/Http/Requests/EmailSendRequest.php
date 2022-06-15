<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'required|string|max:998',
            'text_content' => 'required_without:html_content|string',
            'html_content' => 'required_without:text_content|string',
            'attachments' => 'nullable',
            'attachments.*' => 'mimes:jpeg,jpg,png,pdf,csv,xls,xlsx,doc,docx|max:2048'
        ];
    }
}
