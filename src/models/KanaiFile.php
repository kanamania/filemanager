<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 08/04/19
 * Time: 10:09
 */

namespace Kanamania\FileManager\Model;


use Illuminate\Database\Eloquent\Model;

class KanamaniaFile extends Model
{
    protected $table = 'Kanamania_file_manager_files';
    protected $fillable = [
        'hash',
        'original_name',
        'mime',
        'ext',
        'size',
    ];
    protected $appends = [
        'hashname',
        'url',
        'path',
        'base64',
    ];

    public function getHashnameAttribute()
    {
        if($this->hash&&$this->ext)
            return $this->hash.'.'.$this->ext;
        else return null;
    }
    public function getUrlAttribute()
    {
        return route('file', $this->id);
    }
    public function getPathAttribute()
    {
        return storage_path('app\\public\\KanamaniaFileManager\\' .$this->hashname);
    }
    public function getBase64Attribute()
    {
        $data = file_get_contents($this->path);
        $base64 = 'data:' . $this->mime . ';base64,' . base64_encode($data);
        return $base64;
    }

}
