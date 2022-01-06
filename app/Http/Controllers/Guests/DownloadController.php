<?php namespace App\Http\Controllers\Guests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Info;
use App\InfoFile;

class DownloadController extends Controller {

    public function __construct()
    {

    }

    public function download(Request $request, $id)
    {
        $file = InfoFile::findOrFail($id);
        $info = Info::where('id', $file->info_id)->first();

        $uploadDirectory = public_path('upload') . DIRECTORY_SEPARATOR . 'info' . DIRECTORY_SEPARATOR . \Locales::getCurrent() . DIRECTORY_SEPARATOR . $info->slug . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory') . DIRECTORY_SEPARATOR . $file->uuid . DIRECTORY_SEPARATOR . $file->file;

        return response()->download($uploadDirectory);
    }

}
