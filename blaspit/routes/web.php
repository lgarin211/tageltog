<?php

use Illuminate\Support\Facades\Route;
// insert db
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $artapot = DB::table('artapot')->get();
    dump($artapot);
    return view('Artikel', ['artapot' => $artapot]);
});

function ollpo($imageUrl){
    $response= Http::get('http://127.0.0.1:5000/predict?image_url='.$imageUrl);
    return $response->json()['prediction'];
}

Route::post('/', function () {
    $request = request();
    $image = $request->file('image');
    
    if ($image && $image->isValid()) {
        $image->move(public_path('storage/filter'), $image->getClientOriginalName());

        $response = ollpo($image->getClientOriginalName());
        dump($response);

        if ($response) {
            $artapot = DB::table('artapot')->where('categori', $response)->get();
            dump($artapot);
            return view('Artikel', ['artapot' => $artapot]);
        } else {
            dump("wrong tag 2");
        }
    } else {
        dump("wrong tag 1");
    }
});



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
