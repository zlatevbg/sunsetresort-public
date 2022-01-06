<?php

namespace App\Services;

use Illuminate\Http\Request;
use Storage;
use File;

class FineUploader
{
    public $request;
    public $status = 200;
    public $allowedExtensions = [];
    public $sizeLimit = null;
    public $inputName = 'qqfile';
    public $chunksDirectory;
    public $chunksPath;
    public $chunksDisk = 'local-private';
    public $uploadDirectory;
    public $uploadPath;
    public $uploadDisk = 'local-public';
    public $isImage = false;
    public $isFile = false;
    public $thumbnail = true;
    public $thumbnailSmall = false;
    public $thumbnailMedium = false;
    public $thumbnailLarge = false;
    public $watermark = false;
    public $resize = false;
    public $slider = false;
    public $sliderSmall = false;
    public $sliderMedium = false;
    public $sliderLarge = false;
    public $banner = false;
    public $bannerSmall = false;
    public $bannerMedium = false;
    public $bannerLarge = false;
    public $icon = false;
    public $offer = false;
    public $partner = false;
    public $gallery = false;
    public $page = false;
    public $room = false;
    public $award = false;

    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week

    protected $uploadName;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->isImage = true;
        $this->chunksDirectory = \Config::get('upload.chunksDirectory');
        $this->allowedExtensions = \Config::get('upload.imageExtensions');
        $this->chunksPath = $this->getDiskPath($this->chunksDisk);
        $this->uploadPath = $this->getDiskPath($this->uploadDisk);
    }

    /**
     * Get the original filename
     */
    public function getName()
    {
        $name = null;
        if ($this->request->has('qqfilename')) {
            $name = $this->request->input('qqfilename');
        } elseif ($this->request->hasFile($this->inputName)) {
            $name = $this->request->file($this->inputName)->getClientOriginalName();
        }

        $ext = strtolower(File::extension($name));
        $name = str_slug(File::name($name)) . '.' . $ext; // use lowercase extension

        return $name;
    }

    /**
     * Get the name of the uploaded file
     */
    public function getUploadName()
    {
        return $this->uploadName;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function combineChunks()
    {
        $uuid = $this->request->input('qquuid');
        $chunksDirectory = $this->chunksDirectory . DIRECTORY_SEPARATOR . $uuid;
        if (Storage::disk($this->chunksDisk)->exists($chunksDirectory)) {
            $this->uploadName = $this->getName();
            $totalParts = (int)$this->request->input('qqtotalparts', 1);

            $rootDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . $uuid;
            if (!Storage::disk($this->uploadDisk)->exists($rootDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($rootDirectory);
            }

            if ($this->isImage) {
                $uploadDirectory = $rootDirectory . DIRECTORY_SEPARATOR . \Config::get('upload.originalDirectory');
                if (!Storage::disk($this->uploadDisk)->exists($uploadDirectory)) {
                    Storage::disk($this->uploadDisk)->makeDirectory($uploadDirectory);
                }
            } else {
                $uploadDirectory = $rootDirectory;
            }

            $uploadFile = $uploadDirectory . DIRECTORY_SEPARATOR . $this->getUploadName();

            $destination = fopen($this->uploadPath . $uploadFile, 'wb');

            for ($i = 0; $i < $totalParts; $i++) {
                $source = fopen($this->chunksPath . $chunksDirectory . DIRECTORY_SEPARATOR . $i, 'rb');
                stream_copy_to_stream($source, $destination);
                fclose($source);
            }

            fclose($destination);

            Storage::disk($this->chunksDisk)->deleteDirectory($chunksDirectory);

            $size = Storage::disk($this->uploadDisk)->size($uploadFile);
            if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
                Storage::disk($this->uploadDisk)->delete($uploadFile);
                $this->status = 413;
                return ['success' => false, 'uuid' => $uuid, 'preventRetry' => true];
            }

            if ($this->isImage) {
                $size = $this->processUploaded($rootDirectory, $this->getUploadName());
            }

            return [
                'success'=> true,
                'uuid' => $uuid,
                'fileName' => $this->getUploadName(),
                'fileExtension' => File::extension($this->getUploadName()),
                'fileSize' => $size,
            ];
        }
    }

    /**
     * Process the upload.
     * @param string $name Overwrites the name of the file.
     */
    public function handleUpload($name = null)
    {
        clearstatcache();

        if (File::isWritable($this->chunksPath . $this->chunksDirectory) && 1 == mt_rand(1, 1 / $this->chunksCleanupProbability)) {
            $this->cleanupChunks();
        }

        // Check that the max upload size specified in class configuration does not exceed size allowed by server config
        if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit || $this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit) {
            $neededRequestSize = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorServerMaxSize', ['size' => $neededRequestSize]), 'preventRetry' => true];
        }

        if (!File::isWritable($this->uploadPath . $this->uploadDirectory) && !is_executable($this->uploadPath . $this->uploadDirectory)) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorUploadDirectoryNotWritable'), 'preventRetry' => true];
        }

        $type = $this->request->server('HTTP_CONTENT_TYPE', $this->request->server('CONTENT_TYPE'));

        if (!$type) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorUpload')];
        } else if (strpos(strtolower($type), 'multipart/') !== 0) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorMultipart')];
        }

        $file = $this->request->file($this->inputName);
        $size = $this->request->input('qqtotalfilesize', $file->getSize());

        if (is_null($name)) {
            $name = $this->getName();
        }

        if (is_null($name) || empty($name)) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorFileNameEmpty')];
        }

        if (empty($size)) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorFileEmpty')];
        }

        if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorFileSize'), 'preventRetry' => true];
        }

        $ext = strtolower(File::extension($name));
        $this->uploadName = $name;

        if ($this->allowedExtensions && !in_array($ext, array_map('strtolower', $this->allowedExtensions))) {
            $these = implode(', ', $this->allowedExtensions);
            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorFileExtension', ['extensions' => $these]), 'preventRetry' => true];
        }

        $totalParts = (int)$this->request->input('qqtotalparts', 1);
        $uuid = $this->request->input('qquuid');

        if ($totalParts > 1) { // chunked upload
            $partIndex = (int)$this->request->input('qqpartindex');

            if (!File::isWritable($this->chunksPath . $this->chunksDirectory) && !is_executable($this->chunksPath . $this->chunksDirectory)){
                return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorChunksDirectoryNotWritable'), 'preventRetry' => true];
            }

            $chunksDirectory = $this->chunksDirectory . DIRECTORY_SEPARATOR . $uuid;

            if (!Storage::disk($this->chunksDisk)->exists($chunksDirectory)) {
                Storage::disk($this->chunksDisk)->makeDirectory($chunksDirectory);
            }

            $file->move($this->chunksPath . $chunksDirectory, $partIndex);

            return ['success' => true, 'uuid' => $uuid];
        } else { // non-chunked upload
            $rootDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . $uuid;
            if (!Storage::disk($this->uploadDisk)->exists($rootDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($rootDirectory);
            }

            if ($this->isImage) {
                $uploadDirectory = $rootDirectory . DIRECTORY_SEPARATOR . \Config::get('upload.originalDirectory');
                if (!Storage::disk($this->uploadDisk)->exists($uploadDirectory)) {
                    Storage::disk($this->uploadDisk)->makeDirectory($uploadDirectory);
                }
            } else {
                $uploadDirectory = $rootDirectory;
            }

            if (($response = $file->move($this->uploadPath . $uploadDirectory, $this->getUploadName())) !== false) {
                if ($this->isImage) {
                    $size = $this->processUploaded($rootDirectory, $this->getUploadName());
                }

                return [
                    'success'=> true,
                    'uuid' => $uuid,
                    'fileName' => $response->getFilename(),
                    'fileExtension' => $response->getExtension(),
                    'fileSize' => $size,
                ];
            }

            return ['error' => trans(\Locales::getNamespace() . '/fineuploader.errorSave')];
        }
    }

    protected function processUploaded($directory, $filename) {
        $file = $this->uploadPath . $directory . DIRECTORY_SEPARATOR . \Config::get('upload.originalDirectory') . DIRECTORY_SEPARATOR . $filename;

        if ($this->resize) {
            $img = \Image::make($file);

            if ($img->width() > \Config::get('upload.imageMaxWidth') || $img->height() > \Config::get('upload.imageMaxHeight')) {
                $img->resize(\Config::get('upload.imageMaxWidth'), \Config::get('upload.imageMaxHeight'), function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            if ($this->watermark) {
                // $img->fill(\Config::get('upload.watermarkText'));
                $img->insert(\Config::get('upload.watermarkImage'), \Config::get('upload.watermarkPosition'), \Config::get('upload.watermarkOffsetX'), \Config::get('upload.watermarkOffsetY'));
            }

            $img->save($this->uploadPath . $directory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            $size = $img->filesize();
        }

        if ($this->thumbnail) {
            $thumbnailDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.thumbnailDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($thumbnailDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($thumbnailDirectory);
            }

            $thumb = \Image::make($file);

            $thumb->resize(\Config::get('upload.thumbnailWidth'), \Config::get('upload.thumbnailHeight'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumb->save($this->uploadPath . $thumbnailDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->thumbnailSmall) {
            $thumbnailSmallDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.thumbnailSmallDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($thumbnailSmallDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($thumbnailSmallDirectory);
            }

            $thumb = \Image::make($file);

            $thumb->resize(\Config::get('upload.thumbnailSmallWidth'), \Config::get('upload.thumbnailSmallHeight'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumb->save($this->uploadPath . $thumbnailSmallDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->thumbnailMedium) {
            $thumbnailMediumDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.thumbnailMediumDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($thumbnailMediumDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($thumbnailMediumDirectory);
            }

            $thumb = \Image::make($file);

            $thumb->resize(\Config::get('upload.thumbnailMediumWidth'), \Config::get('upload.thumbnailMediumHeight'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumb->save($this->uploadPath . $thumbnailMediumDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->thumbnailLarge) {
            $thumbnailLargeDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.thumbnailLargeDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($thumbnailLargeDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($thumbnailLargeDirectory);
            }

            $thumb = \Image::make($file);

            $thumb->resize(\Config::get('upload.thumbnailLargeWidth'), \Config::get('upload.thumbnailLargeHeight'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $thumb->save($this->uploadPath . $thumbnailLargeDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->banner) {
            $bannerDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.bannerDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($bannerDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($bannerDirectory);
            }

            $banner = \Image::make($file);

            $banner->fit(\Config::get('upload.bannerWidth'), \Config::get('upload.bannerHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($banner->width() < \Config::get('upload.bannerWidth') || $banner->height() < \Config::get('upload.bannerHeight')) {
                $banner->resizeCanvas(\Config::get('upload.bannerWidth'), \Config::get('upload.bannerHeight'), 'center', false, \Config::get('upload.bannerCanvasBackground'));
            }

            $banner->save($this->uploadPath . $bannerDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));

            if (!$this->resize) {
                $size = $banner->filesize();
            }
        }

        if ($this->bannerSmall) {
            $bannerSmallDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.bannerSmallDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($bannerSmallDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($bannerSmallDirectory);
            }

            $bannerSmall = \Image::make($file);

            $bannerSmall->fit(\Config::get('upload.bannerSmallWidth'), \Config::get('upload.bannerSmallHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($bannerSmall->width() < \Config::get('upload.bannerSmallWidth') || $bannerSmall->height() < \Config::get('upload.bannerSmallHeight')) {
                $bannerSmall->resizeCanvas(\Config::get('upload.bannerSmallWidth'), \Config::get('upload.bannerSmallHeight'), 'center', false, \Config::get('upload.bannerCanvasBackground'));
            }

            $bannerSmall->save($this->uploadPath . $bannerSmallDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->bannerMedium) {
            $bannerMediumDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.bannerMediumDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($bannerMediumDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($bannerMediumDirectory);
            }

            $bannerMedium = \Image::make($file);

            $bannerMedium->fit(\Config::get('upload.bannerMediumWidth'), \Config::get('upload.bannerMediumHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($bannerMedium->width() < \Config::get('upload.bannerMediumWidth') || $bannerMedium->height() < \Config::get('upload.bannerMediumHeight')) {
                $bannerMedium->resizeCanvas(\Config::get('upload.bannerMediumWidth'), \Config::get('upload.bannerMediumHeight'), 'center', false, \Config::get('upload.bannerCanvasBackground'));
            }

            $bannerMedium->save($this->uploadPath . $bannerMediumDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->bannerLarge) {
            $bannerLargeDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.bannerLargeDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($bannerLargeDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($bannerLargeDirectory);
            }

            $bannerLarge = \Image::make($file);

            $bannerLarge->fit(\Config::get('upload.bannerLargeWidth'), \Config::get('upload.bannerLargeHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($bannerLarge->width() < \Config::get('upload.bannerLargeWidth') || $bannerLarge->height() < \Config::get('upload.bannerLargeHeight')) {
                $bannerLarge->resizeCanvas(\Config::get('upload.bannerLargeWidth'), \Config::get('upload.bannerLargeHeight'), 'center', false, \Config::get('upload.bannerCanvasBackground'));
            }

            $bannerLarge->save($this->uploadPath . $bannerLargeDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->icon) {
            $iconDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.iconDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($iconDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($iconDirectory);
            }

            $icon = \Image::make($file);

            $icon->fit(\Config::get('upload.iconWidth'), \Config::get('upload.iconHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($icon->width() < \Config::get('upload.iconWidth') || $icon->height() < \Config::get('upload.iconHeight')) {
                $icon->resizeCanvas(\Config::get('upload.iconWidth'), \Config::get('upload.iconHeight'), 'center', false, \Config::get('upload.iconCanvasBackground'));
            }

            $icon->save($this->uploadPath . $iconDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $icon->filesize();
            }
        }

        if ($this->slider) {
            $sliderDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.sliderDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($sliderDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($sliderDirectory);
            }

            $slider = \Image::make($file);

            $slider->fit(\Config::get('upload.sliderWidth'), \Config::get('upload.sliderHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($slider->width() < \Config::get('upload.sliderWidth') || $slider->height() < \Config::get('upload.sliderHeight')) {
                $slider->resizeCanvas(\Config::get('upload.sliderWidth'), \Config::get('upload.sliderHeight'), 'center', false, \Config::get('upload.sliderCanvasBackground'));
            }

            $slider->save($this->uploadPath . $sliderDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));

            if (!$this->resize) {
                $size = $slider->filesize();
            }
        }

        if ($this->sliderSmall) {
            $sliderSmallDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.sliderSmallDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($sliderSmallDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($sliderSmallDirectory);
            }

            $sliderSmall = \Image::make($file);

            $sliderSmall->fit(\Config::get('upload.sliderSmallWidth'), \Config::get('upload.sliderSmallHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($sliderSmall->width() < \Config::get('upload.sliderSmallWidth') || $sliderSmall->height() < \Config::get('upload.sliderSmallHeight')) {
                $sliderSmall->resizeCanvas(\Config::get('upload.sliderSmallWidth'), \Config::get('upload.sliderSmallHeight'), 'center', false, \Config::get('upload.sliderCanvasBackground'));
            }

            $sliderSmall->save($this->uploadPath . $sliderSmallDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->sliderMedium) {
            $sliderMediumDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.sliderMediumDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($sliderMediumDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($sliderMediumDirectory);
            }

            $sliderMedium = \Image::make($file);

            $sliderMedium->fit(\Config::get('upload.sliderMediumWidth'), \Config::get('upload.sliderMediumHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($sliderMedium->width() < \Config::get('upload.sliderMediumWidth') || $sliderMedium->height() < \Config::get('upload.sliderMediumHeight')) {
                $sliderMedium->resizeCanvas(\Config::get('upload.sliderMediumWidth'), \Config::get('upload.sliderMediumHeight'), 'center', false, \Config::get('upload.sliderCanvasBackground'));
            }

            $sliderMedium->save($this->uploadPath . $sliderMediumDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->sliderLarge) {
            $sliderLargeDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.sliderLargeDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($sliderLargeDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($sliderLargeDirectory);
            }

            $sliderLarge = \Image::make($file);

            $sliderLarge->fit(\Config::get('upload.sliderLargeWidth'), \Config::get('upload.sliderLargeHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($sliderLarge->width() < \Config::get('upload.sliderLargeWidth') || $sliderLarge->height() < \Config::get('upload.sliderLargeHeight')) {
                $sliderLarge->resizeCanvas(\Config::get('upload.sliderLargeWidth'), \Config::get('upload.sliderLargeHeight'), 'center', false, \Config::get('upload.sliderCanvasBackground'));
            }

            $sliderLarge->save($this->uploadPath . $sliderLargeDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }

        if ($this->offer) {
            $offerDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.offerDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($offerDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($offerDirectory);
            }

            $offer = \Image::make($file);

            $offer->fit(\Config::get('upload.offerWidth'), \Config::get('upload.offerHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($offer->width() < \Config::get('upload.offerWidth') || $offer->height() < \Config::get('upload.offerHeight')) {
                $offer->resizeCanvas(\Config::get('upload.offerWidth'), \Config::get('upload.offerHeight'), 'center', false, \Config::get('upload.offerCanvasBackground'));
            }

            $offer->save($this->uploadPath . $offerDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $offer->filesize();
            }
        }

        if ($this->partner) {
            $partnerDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.partnerDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($partnerDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($partnerDirectory);
            }

            $partner = \Image::make($file)->greyscale();

            $partner->resize(\Config::get('upload.partnerWidth'), \Config::get('upload.partnerHeight'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            if ($partner->width() < \Config::get('upload.partnerWidth') || $partner->height() < \Config::get('upload.partnerHeight')) {
                $partner->resizeCanvas(\Config::get('upload.partnerWidth'), \Config::get('upload.partnerHeight'), 'center', false, \Config::get('upload.partnerCanvasBackground'));
            }

            $partner->save($this->uploadPath . $partnerDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $partner->filesize();
            }
        }

        if ($this->gallery) {
            $galleryDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.galleryDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($galleryDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($galleryDirectory);
            }

            $gallery = \Image::make($file);

            $gallery->fit(\Config::get('upload.galleryWidth'), \Config::get('upload.galleryHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($gallery->width() < \Config::get('upload.galleryWidth') || $gallery->height() < \Config::get('upload.galleryHeight')) {
                $gallery->resizeCanvas(\Config::get('upload.galleryWidth'), \Config::get('upload.galleryHeight'), 'center', false, \Config::get('upload.galleryCanvasBackground'));
            }

            $gallery->save($this->uploadPath . $galleryDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $gallery->filesize();
            }
        }

        if ($this->page) {
            $pageDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.pageDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($pageDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($pageDirectory);
            }

            $page = \Image::make($file);

            $page->widen(\Config::get('upload.pageWidth'), function ($constraint) {
                $constraint->upsize();
            });

            $page->save($this->uploadPath . $pageDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $page->filesize();
            }
        }

        if ($this->room) {
            $roomDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.roomDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($roomDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($roomDirectory);
            }

            $room = \Image::make($file);

            $room->fit(\Config::get('upload.roomWidth'), \Config::get('upload.roomHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($room->width() < \Config::get('upload.roomWidth') || $room->height() < \Config::get('upload.roomHeight')) {
                $room->resizeCanvas(\Config::get('upload.roomWidth'), \Config::get('upload.roomHeight'), 'center', false, \Config::get('upload.roomCanvasBackground'));
            }

            $room->save($this->uploadPath . $roomDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
            if (!$this->resize) {
                $size = $room->filesize();
            }
        }

        if ($this->award) {
            $awardDirectory = $directory . DIRECTORY_SEPARATOR . \Config::get('upload.awardDirectory');
            if (!Storage::disk($this->uploadDisk)->exists($awardDirectory)) {
                Storage::disk($this->uploadDisk)->makeDirectory($awardDirectory);
            }

            $award = \Image::make($file);

            $award->fit(\Config::get('upload.awardWidth'), \Config::get('upload.awardHeight'), function ($constraint) {
                $constraint->upsize();
            });

            if ($award->width() < \Config::get('upload.awardWidth') || $award->height() < \Config::get('upload.awardHeight')) {
                $award->resizeCanvas(\Config::get('upload.awardWidth'), \Config::get('upload.awardHeight'), 'center', false, \Config::get('upload.awardCanvasBackground'));
            }

            $award->save($this->uploadPath . $awardDirectory . DIRECTORY_SEPARATOR . $filename, \Config::get('upload.quality'));
        }


        return $size;
    }

    /**
     * Deletes all file parts in the chunks directory for files uploaded
     * more than chunksExpireIn seconds ago
     */
    protected function cleanupChunks()
    {
        foreach (Storage::disk($this->chunksDisk)->directories($this->chunksDirectory) as $dir) {
            $path = $this->chunksDirectory . DIRECTORY_SEPARATOR . $dir;

            if ($time = @filemtime($this->chunksPath . $path)) {
                if (time() - $time > $this->chunksExpireIn) {
                    Storage::disk($this->chunksDisk)->deleteDirectory($path);
                }
            }
        }
    }

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str)
    {
        $val = (int)trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    protected function getDiskPath($disk = null)
    {
        if ($disk) {
            return Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix();
        } else {
            return Storage::getDriver()->getAdapter()->getPathPrefix();
        }
    }
}
