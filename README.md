# s3-filemanager

File Manager with S3 Intrgration and integrate with Trumbowyg Editor

## Installing s3-File Manager

- Run following command to install package: 
```php
composer require hnrtech/filemanager
```

- Add Publisher class in app.php of config folder
```php        
Hnrtech\Filemanager\FileManagerServiceProvider::class
```

- After successful installation, to publish files to project you need to run, 
```php 
php artisan vendor:publish
```

- After publish, path.php file will be added in config folder set 'folder_name' as per require directory to fetch images 

- To setup file upload use following,
```php 
For Upload Button : {!! config('path.button') !!} 
For Input file name : {!! config('path.input') !!}
```

- Include File Manager css
```php
<link rel="stylesheet" href="/css/filemanager-custom.css">
```

##### Note: Include Bootstrap css and js for modal style if not available in your project

- Include Modal in your view file where input and button are added
```php
@include('filemanager::file-manager.iframe')
```

- Include script to open File manager modal,=
```php
    $('#image-upload').on('click', function () {
        $('#fileManagerModal').modal('toggle');
    });
```    

- Change default value of "FILESYSTEM_DRIVER" in filesystems.php file to s3 and add update s3 array as follwoing
```php
'driver' => 's3',
'key' => env('AWS_KEY'),
'secret' => env('AWS_SECRET'),
'region' => env('AWS_REGION'),
'bucket' => env('AWS_BUCKET'),
'visibility' => 'public',
'url' => env('AWS_URL'),
```

- Final Step , Set following env variable in .env file with value
```php
AWS_KEY=
AWS_SECRET=
AWS_REGION=
AWS_BUCKET=
VISIBILITY='public'
AWS_BLOG_URL=
AWS_URL=
```

##### If you want to include file manager in trumbowyg editor please include following:
```php
{!! config('path.editor') !!} and include js with following code,
<script src="/js/file-manager/trumbowyg.js"></script>
{!! config('path.js') !!}
```

- Also, include css of trumbowyg editor
```php
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/ui/trumbowyg.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/plugins/emoji/ui/trumbowyg.emoji.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/plugins/table/ui/trumbowyg.table.min.css">
```

- Final steps for Editor with s3-filemanager
```php
$('#desc').trumbowyg();
```


