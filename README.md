# s3-filemanager
File Manager with S3 Intrgration and integrate with Trumbowyg Editor

Steps to Install s3-File Manager

Run following command to install package: composer require hnrtech/filemanager

Add class in Publisher in app.php of config,         
Hnrtech\Filemanager\FileManagerServiceProvider::class,

#After successful installation run to publish required file to project, 
#php artisan vendor:publish

To setup file upload or to select file include following,
For Upload Button : {!! config('path.button') !!} 
For Input file name : {!! config('path.input') !!}

Include File Manager css ,
<link rel="stylesheet" href="/css/filemanager-custom.css">

Note: Include Bootstrap css and js for modal 

Include Modal in your view file where input and button are added,
@include('filemanager::file-manager.iframe')

Include script to open File manager modal at bottom of page,
		$('#image-upload').on('click', function () {
            $('#fileManagerModal').modal('toggle');
        });

Change default value in filesystems.php file to s3 and add update s3 array as follwoing
'driver' => 's3',
'key' => env('AWS_KEY'),
'secret' => env('AWS_SECRET'),
'region' => env('AWS_REGION'),
'bucket' => env('AWS_BUCKET'),
'visibility' => 'public',
'url' => env('AWS_URL'),

Final Step , Set following env variable in .env file,
AWS_KEY=
AWS_SECRET=
AWS_REGION=
AWS_BUCKET=
VISIBILITY='public'
AWS_BLOG_URL=
AWS_URL=

If you want to include file manager in trumbowyg text editor please include following,
{!! config('path.editor') !!} and include js with following code,
<script src="/js/file-manager/trumbowyg.js"></script>
{!! config('path.js') !!}

Also, include css of trumbowyg editor
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/ui/trumbowyg.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/plugins/emoji/ui/trumbowyg.emoji.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.15.1/plugins/table/ui/trumbowyg.table.min.css">

Final steps for Editor with s3-filemanager
$('#desc').trumbowyg();


