<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;

class UploadGraphqlRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|max:20480',   // 上传必须为图片, 并且不能超过 10 M
        ];
    }

    public function attributes()
    {
        return [
            'file' => '上传文件',
        ];
    }
}