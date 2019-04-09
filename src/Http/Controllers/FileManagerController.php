<?php

namespace Hnrtech\Filemanager\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;
use Session;

class FileManagerController extends Controller
{
    public function index()
    {
//        if ($this->client_id()) {
//            $client_id = $this->client_id();
//        } else {
//            $client_id = $this->get_secondary_user()['client_id'];
//        }
//        $folder_name = config('path.folder_name');
        $folder_name = config('path.folder_name');
        $path = $_GET;
        $files = [];

        $folders = Storage::directories($path['path']);
        $tempFiles = Storage::files($path['path']);

        foreach ($tempFiles as $key => $value) {
            $dt = Storage::LastModified($value);
            $name = explode('/', $value);
            $size = Storage::Size($value);
            $imgSize = round(($size / 1024) * 100) / 100;
            $files[] = [
                'name' => end($name),
                'modified' => $dt,
                'size' => $imgSize,
            ];
        }
        $modified = array();
        foreach ($files as $key => $value)
        {
            $modified[$key] = $value['modified'];
        }
        array_multisort($modified, SORT_DESC, $files);
        $final = [
            'folders' => $folders,
            'files' => $files
        ];

        return view('filemanager::file-manager.index')->with(array('events'=> 'true','final' => $final, 'path' => $path['path'], 'folder_path' => $path['path'],'client_id' => $folder_name));

    }

    public function upload(Request $request)
    {
        $data = $request->all();
        $image_array = array();
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            if (count($files) > 1) {
                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $name = str_replace(" ","-",$name);
                    $filePath = $data['path'] . '/' . $name;
                    $results = Storage::disk('s3')->put($filePath, file_get_contents($file));
                    $image_array[] = $name;
                }
            } else {
                $name = $files[0]->getClientOriginalName();
                $name = str_replace(" ","-",$name);
                $filePath = $data['path'] . '/' . $name;
                $results = Storage::disk('s3')->put($filePath, file_get_contents($files[0]));
                $image_array[] = $name;
            }

            if ($results) {
                return redirect()->back()->with('flash', 'success')->with('message', 'Image uploaded successfully')->with('image_name', $image_array);
            } else {
                return redirect()->back()->with('flash', 'danger')->with('error', 'Image not uploaded.');
            }
        }
    }

    public function addfolder(Request $request)
    {
        $data = $request->all();
        $dir = $data['path'] . '/' . $data['folder_name'];
        $result = Storage::disk('s3')->makeDirectory($dir);
        if ($result) {
            return redirect()->back()->with('flash', 'success')->with('message', 'Folder Created successfully');
        } else {
            return redirect()->back()->with('flash', 'danger')->with('error', 'Folder not Created.');
        }
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        $file = $data['path'] . '/' . $data['name'];
        if ($data['type'] == 'folder') {
            $result = Storage::disk('s3')->deleteDirectory($file);
        } else {
            $result = Storage::disk('s3')->delete($file);
        }
        if ($result) {
            return 'true';
        } else {
            return 'false';
        }
    }
}