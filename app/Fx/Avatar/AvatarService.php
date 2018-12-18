<?php

namespace App\Fx\Avatar;

use Laravolt\Avatar\Avatar;
use zgldh\QiniuStorage\QiniuStorage;

class AvatarService
{
    protected $disk;

    protected $avatar;

    public function __construct()
    {
        $this->disk = QiniuStorage::disk('qiniu');

        $this->avatar = new Avatar(config('laravolt.avatar'));
    }

    /**
     * @param $name
     *
     * @return string|null
     */
    public function put($name = '头像')
    {
        if (( preg_match('/^[\x7f-\xff]+$/', $name) )) {
            $name = mb_strlen($name) > 2 ? mb_substr($name, 1, 3) : $name;
        }

        $file = $this->avatar->create($name)->toBase64();

        $key = uniqid("hytb/avatar/");

        if ($this->disk->put($key, file_get_contents($file->encoded))) {
            return $this->disk->downloadUrl($key);
        }

        return null;
    }

    /**
     * @param $AvatarUrl
     *
     * @return boolean
     */
    public function delete($AvatarUrl)
    {
        return $this->disk->delete($AvatarUrl);
    }

}
