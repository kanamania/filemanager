<?php

namespace Kanamania\FileManager\Http\Controller;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Image;
use Kanamania\FileManager\Model\KanamaniaFile;

class FileManagerController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('f')) {
            return Response::json(['name' => 'Not found', 'size' => "NULL", 'error' => "No file"]);
        }
        $file = $request->file('f');
        $filename = hash("MD5", time() . $file->getClientOriginalName());

        if ($file->move(storage_path('app\public\KanamaniaFileManager\\'), $filename . "." . $file->getClientOriginalExtension())) {
            $upload = new KanamaniaFile;
            $upload->original_name = $file->getClientOriginalName();
            $upload->hash = $filename;
            $upload->ext = $file->getClientOriginalExtension();
            $upload->size = $file->getClientSize();
            $upload->mime = $file->getClientMimeType();
            if ($upload->save()) {
                $success = new \stdClass();
                $success->name = substr($upload->original_name, 0, 26);
                $success->size = $upload->size;
                $success->url = route('kfm.get', $upload->id);
                $success->deleteType = "GET";
                $success->id = $upload->id;
                $success->deleteUrl = route('kfm.delete', $upload->id);
                return Response::json(['id' => $upload->id, 'thumb' => $success->thumbnailUrl]);
            } else {
                return Response::json(['name' => 'NULL', 'size' => "NULL", 'error' => "Failed to save"]);
            }
        }
    }

    public function get(KanamaniaFile $file)
    {
        $path = storage_path('app\public\KanamaniaFileManager\\' . $file->hashname);
        if ((new Filesystem)->exists($path)) {
            return Response::file($path);
        } else {
            return Response::json(['error' => "File not found."]);
        }
    }

    public function delete(KanamaniaFile $file)
    {
        $path = storage_path('app\public\KanamaniaFileManager\\' . $file->hash . "." . $file->ext);
        if (File::exists($path)) {
            if (File::delete($path)) {
                if ($file->delete())
                    return Response::json(['status' => 'deleted']);
                else
                    return Response::json(['error' => "Failed to delete."]);
            }
        } else {
            return Response::json(['error' => "File not found."]);
        }
    }

    public function thumbnail(KanamaniaFile $file, int $x, int $y)
    {
        $path = storage_path('app\public\KanamaniaFileManager\\') . $file->hashname;
        if ((new Filesystem)->exists($path)) {
            return Response::make(Image::make($path)->resize($x, $y, function ($constraint) {
                $constraint->aspectRatio();
            }))->header('Content-Type', $file->mime);
        } else {
            return Response::json(['error' => "File not found."]);
        }
    }
}