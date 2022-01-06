<?php

return [

    'imagesDirectory' => 'images',
    'filesDirectory' => 'files',
    'chunksDirectory' => 'chunks',
    'originalDirectory' => 'o',
    'thumbnailDirectory' => 't',
    'thumbnailSmallDirectory' => 's',
    'thumbnailMediumDirectory' => 'm',
    'thumbnailLargeDirectory' => 'l',
    'sliderDirectory' => 'slider',
    'sliderSmallDirectory' => 'slider-small',
    'sliderMediumDirectory' => 'slider-medium',
    'sliderLargeDirectory' => 'slider-large',
    'bannerDirectory' => 'banner',
    'bannerSmallDirectory' => 'banner-small',
    'bannerMediumDirectory' => 'banner-medium',
    'bannerLargeDirectory' => 'banner-large',
    'awardDirectory' => 'award',
    'iconDirectory' => 'icon',
    'offerDirectory' => 'offer',
    'partnerDirectory' => 'partner',
    'galleryDirectory' => 'gallery',
    'pageDirectory' => 'page',
    'roomDirectory' => 'room',

    'imageExtensions' => ['jpg', 'png', 'gif', 'jpeg'],
    'fileExtensions' => ['jpg', 'png', 'gif', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'rar', 'ppt', 'pptx'],
    'quality' => 90,

    'imageMaxWidth' => 1920,
    'imageMaxHeight' => 1920,

    'pageWidth' => 620,

    'thumbnailWidth' => 120,
    'thumbnailHeight' => 90,
    'thumbnailSmallWidth' => 320,
    'thumbnailSmallHeight' => 240,
    'thumbnailMediumWidth' => 600,
    'thumbnailMediumHeight' => 480,
    'thumbnailLargeWidth' => 800,
    'thumbnailLargeHeight' => 600,

    'sliderWidth' => 1920,
    'sliderHeight' => 540,
    'sliderSmallWidth' => 480,
    'sliderSmallHeight' => 200,
    'sliderMediumWidth' => 768,
    'sliderMediumHeight' => 250,
    'sliderLargeWidth' => 1280,
    'sliderLargeHeight' => 360,
    'sliderCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'offerWidth' => 620,
    'offerHeight' => 300,
    'offerCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'roomWidth' => 620,
    'roomHeight' => 400,
    'roomCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'partnerWidth' => 120,
    'partnerHeight' => 90,
    'partnerCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'bannerWidth' => 1920,
    'bannerHeight' => 480,
    'bannerSmallWidth' => 480,
    'bannerSmallHeight' => 200,
    'bannerMediumWidth' => 768,
    'bannerMediumHeight' => 250,
    'bannerLargeWidth' => 1280,
    'bannerLargeHeight' => 360,
    'bannerCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'iconWidth' => 80,
    'iconHeight' => 80,
    'iconCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'awardWidth' => 200,
    'awardHeight' => 200,
    'awardCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'galleryWidth' => 300,
    'galleryHeight' => 225,
    'galleryCanvasBackground' => [255, 255, 255, 0], // transparent [png] or white [jpg]

    'watermarkText' => storage_path('app/images/watermark-text.png'),
    'watermarkImage' => storage_path('app/images/watermark-logo.png'),
    'watermarkPosition' => 'center',
    'watermarkOffsetX' => 0,
    'watermarkOffsetY' => 0,

];
