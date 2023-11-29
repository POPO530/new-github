<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ThanksController;
use App\Http\Controllers\ItemDeleteController;
use Illuminate\Support\Facades\Route;

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

// Routeファサードを使用してルーティングを定義します。

// GETリクエストの'/items' URLにアクセスされたときに、
// ItemControllerのindexメソッドを呼び出します。
// このルートはアイテムの一覧を表示するために使用されます。
// nameメソッドでこのルートに"items.index"という名前を付けています。
// これにより、アプリケーション内でこのルートを簡単に参照できるようになります。
Route::get('/items', [ItemController::class, 'index'])->name("items.index");

// POSTリクエストの'/order' URLにアクセスされたときに、
// ItemControllerのconfirmメソッドを呼び出します。
// 通常、フォームからのデータ送信やアクションのトリガーに使用されます。
// このルートは注文の確認プロセスに使用されます。
// "items.confirm"という名前を付けています。
Route::post('/order', [ItemController::class, 'confirm'])->name("items.confirm");

// POSTリクエストの'/thanks' URLにアクセスされたときに、
// ItemControllerのstoreメソッドを呼び出します。
// このルートは新しいアイテムをデータベースに保存するために使用されます。
// "items.store"という名前を付けています。
Route::post('/thanks', [ItemController::class, 'store'])->name("items.store"); 

// DELETEリクエストの'/items/delete/{itemId}' URLにアクセスされたときに、
// ItemControllerのdestroyメソッドを呼び出します。
// このルートは特定のアイテムを削除するために使用されます。
// URLの{itemId}部分は、削除するアイテムのidを指定します。
// "items.delete"という名前を付けています。
Route::delete('/items/delete/{itemId}', [ItemController::class, 'destroy'])->name("items.delete");

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
