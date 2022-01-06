<?php namespace App\Http\Controllers\Www;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Info;
use App\InfoFile;
use App\Offer;
use App\OfferFile;
use App\Banner;
use App\BannerFile;

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

    public function downloadOffer(Request $request, $id)
    {
        $file = OfferFile::findOrFail($id);
        $info = Offer::where('id', $file->offer_id)->first();

        $uploadDirectory = public_path('upload') . DIRECTORY_SEPARATOR . 'offers' . DIRECTORY_SEPARATOR . \Locales::getCurrent() . DIRECTORY_SEPARATOR . $info->slug . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory') . DIRECTORY_SEPARATOR . $file->uuid . DIRECTORY_SEPARATOR . $file->file;

        return response()->download($uploadDirectory);
    }

    public function downloadBanner(Request $request, $id)
    {
        $file = BannerFile::findOrFail($id);
        $info = Banner::where('id', $file->banner_id)->first();

        $uploadDirectory = public_path('upload') . DIRECTORY_SEPARATOR . 'banners' . DIRECTORY_SEPARATOR . \Locales::getCurrent() . DIRECTORY_SEPARATOR . $info->slug . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory') . DIRECTORY_SEPARATOR . $file->uuid . DIRECTORY_SEPARATOR . $file->file;

        return response()->download($uploadDirectory);
    }

}
