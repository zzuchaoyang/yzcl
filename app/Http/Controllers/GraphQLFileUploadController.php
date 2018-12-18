<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use File;
use zgldh\QiniuStorage\QiniuStorage;

class GraphQLFileUploadController extends Controller
{
    /**
     * @param UploadGraphqlRequest $request
     *
     * @return array
     *
     * @throws GeneralException
     */
    public function upload(UploadGraphqlRequest $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $file = $request->file('file');
        $type = $request->input('type', 'common');
        $key  = uniqid("hytb/{$type}/").'.'.$file->getClientOriginalExtension();
        if ($disk->put($key, file_get_contents($file->getRealPath()))) {
            return [
                'url'        => $disk->downloadUrl($key),
                'key'        => $key,
                'created_at' => now()->toDateTimeString(),
            ];
        } else {
            throw new GeneralException('上传失败');
        }
    }
}
