<?php
return [
    'route_prefix' => 'autocrud',
    'lang' => [
        'available' => [
            'en' => 'English',
            'id' => 'Indonesian',
        ],
        'default' => 'en',
    ],
    'max_filesize' => [
        'file' => 20,
        'image' => 20,
    ],

    'google_map_api_key' => env('GOOGLE_MAP_API_KEY'),

    'image' => [
        'driver' => 'gd',
        'enable_webp' => true,
        'thumbs' => [
            'extralarge' => [
                'label' => "Extra Large",
                'type' => 'keep-ratio',
                'width' => 1500,
                'height' => 1500,
            ],
            'large' => [
                'label' => "Large",
                'type' => 'keep-ratio',
                'width' => 1200,
                'height' => 1200,
            ],
            'medium' => [
                'label' => "Medium",
                'type' => 'keep-ratio',
                'width' => 700,
                'height' => 700,
            ],
            'small' => [
                'label' => "Small",
                'type' => 'keep-ratio',
                'width' => 400,
                'height' => 400,
            ],
            'cropped' => [
                'label' => "Cropped",
                'type' => 'fit',
                'width' => 400,
                'height' => 400,
            ],
            'thumb' => [
                'label' => "Thumb",
                'type' => 'fit',
                'width' => 100,
                'height' => 100,
            ],
        ],
        'origin_maximum_width' => 2000, //set to null to disable
        'quality' => 80,        
    ],

    'max_export_row_limit' => 1000000,

    'asset_url' => 'autocrud-assets/',
    'asset_dependency' => [
        'load_bootstrap' => true,
        'load_jquery' => true,
        'load_iconify' => true,
        'load_plugins' => true,
    ],

    'renderer' => [
        'table' => 'autocrud::datatable.table-generator',
        'table_asset' => 'autocrud::datatable.asset-generator',
        'form' => 'autocrud::form.generator',
    ],
];