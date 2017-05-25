<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post('/uploads/{id}/avatar', function ($id) {
    if (app('request')->hasFile('avatar')) {
        $file = app('request')->file('avatar');
        $destinationPath = 'puloads/avatar/' . $id . '/';
        $fileName = str_random('12') . '.' . $file->guessExtension();
        $file->move($destinationPath, $fileName);

        return response()->json([
            'status'  => 'success',
            'code'    => '200',
            'message' => '上传文件成功',
            'data'    => [
                'folder'   => env('APP_URL') . '/' . $destinationPath,
                'fileName' => $fileName,
                'files'    => env('APP_URL') . '/' . $destinationPath . '/' . $fileName
            ]
        ]);
    }

    return response()->json(['status' => 'error', 'code' => '422', 'message' => '没有上传任何文件'], 422);
});

$app->get('/user/{id}/avatar', function ($id) {
    $destinationPath = 'puloads/avatar/' . $id . '/';
    $handler = opendir($destinationPath);
    $files = [];
    while (($filename = readdir($handler)) !== false) {//务必使用!==，防止目录下出现类似文件名“0”等情况
        if ($filename != "." && $filename != "..") {
            $files[] = $filename ;
        }
    }
    foreach ($files as $file){
        return response()->json([
            'avatar' =>  env('APP_URL') . '/' . $destinationPath . $file
        ]);
    }
});

