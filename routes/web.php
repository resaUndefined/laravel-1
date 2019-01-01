<?php

use App\Post;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect( '/about');
});

Route::get('/about', function(){
	return 'Hi, This is about page';
});

Route::get('/blog', 'PostController@index');

// Route::get('/post/create', 'PostController@create');

// Route::post('/post/store', 'PostController@store');

// Route::get('/post/{id}', ['as' => 'postDetail', function($id){ 
// 	echo "Post $id";
// 	echo "<br/>Body post in ID $id";
// }]);

Route::resource('post', 'PostController');

// jajal dari documentasi
Route::get('/user/{name}', function ($name) {
    return $name;
})->where('name', '[A-Za-z]+');

Route::get('/user/{id}', function ($id) {
    return $id;
})->where('id', '[0-9]+');

Route::get('user/{id}/{name}', function ($id, $name) {
    return $id . ' => ' . $name;
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);



// belajar create/insert data ke db
Route::get('/create', function(){
	// DB::insert('insert into posts (title, isi, user_id) values (?,?,?)',
	// 			['Belajar Laravel', 'Laravel is the best framework', 1]);
	$data = [
		'title' => 'Judul post keenam',
		'isi' => 'Isi judul post keenam',
		'user_id' => 1,
	]; 
	DB::table('posts')->insert($data);
	echo 'Data berhasil ditambahkan';
});

// belajar read/select data dari db
Route::get('/read', function(){
	// $query = DB::select('select * from posts where id = ?', [2]);

	// return berupa array
	// $query = DB::table('posts')->where('id', 3)->get();

	// return berupa 1 field saja tidak bisa lebih dari 1
	// $query = DB::table('posts')->where('id', 3)->value('title');

	// return berupa objek (cuman return data pertama saja)
	$query = DB::table('posts')->where('id', 3)->first();
	return var_dump($query);

	// coba chunk
	// $cunk = DB::table('posts')->orderBy('id')->chunk(3, function($pos){
	// 	return var_dump($pos);
	// });
});

// belajar update data di db
Route::get('/update', function(){
	// $updated = DB::update('update posts set title = "Judul 1 diupdate 1" where id = ?',[2]);

	// cara kedua dengan query builder
	$data = [
		'title' => 'Judul 3 diupdate nih',
		'isi' => 'Isi judul 3 diupdate juga nih',
	];
	$updated = DB::table('posts')->where('id', 3)->update($data);
	// susunannya emang fungsi update nya dibelakang, soalnya query akan
	// menyeleksi dlu mana yang mau diupdate baru dilakukan proses update
	// jika fungsi update di depan, maka yang ke update semua field, dan error
	return $updated;
});

// belajar delete data di db
Route::get('/delete', function(){
	// $delete = DB::delete('delete from posts where id = ?', [2]);

	// cara delete dengan query builder
	$delete = DB::table('posts')->where('id',9)->delete();
	return $delete . ' | Data berhasil dihapus';
});

// coba pake eloquent model
Route::get('/posts', function(){
	$posts = Post::all();
	return $posts;
});

// find function
Route::get('/find', function(){
	// $post = Post::find(3); //without array of PK
	$post = Post::find([3,5]); //with array of PK
	return $post;
});

// findwhere
Route::get('/findwhere', function(){
	$posts = Post::where('user_id',1)->orderBy('id', 'desc')->take(1)->get();
	// method take untuk mengambil jumlah data
	return $posts;
});

// create data with method save
Route::get('/posts/create', function(){
	$post = new Post();
	$post->title = 'Ini judul dari ORM';
	$post->isi = 'Ini isian dari ORM';
	$post->user_id = Auth::user()->id;

	$proses = $post->save();
	if ($proses) {
		echo "Data berhasil ditambahkan gais";
	}else{
		echo "Data gagal ditambahkan gais";
	}
});

// create data without method save
Route::get('/posts/createpost', function(){
	$data = [
		'title' => 'Judul Data dengan method create',
		'isi' => 'Isian Data dengan method create',
		'user_id' => 1,
	];
	$post = Post::create($data);
	if ($post) {
		echo "Data berhasil masuk";
	}else{
		echo "Data gagal masuk";
	}
});

// update data with eloquent
Route::get('/posts/updatepost', function(){
	$data = [
		'title' => 'Update Judul Data dengan method update',
		'isi' => 'Update Isian Data dengan method update',
	];
	$post = Post::find(5);
	$apdet = $post->update($data);
	if ($apdet) {
		echo "Data berhasil diupdate";
	}else{
		echo "Data gagal diupdate";
	}
});

// delete data with eloquent
Route::get('/posts/deletepost', function(){
	// proses mencari data yang akan dihapus dan membuat instane
	// $post = Post::find(6);
	$post = Post::where('id', 6);

	// cara 1
	// $hapus = $post->delete();

	// cara 2
	$hapus = Post::destroy([9,10]);

	if ($hapus) {
	 	echo "data berhasil dihapus";
	 }else{
	 	echo "data gagal dihapus";
	 }
});

// method softdelete
Route::get('/posts/deletesoft', function(){
	Post::destroy(10);
});

// memunculkan data yang sudah di softdelete
Route::get('/posts/trash', function(){
	// $posts = Post::withTrashed()->get();
	// method withTrashed ini digunakan untuk memunculkans seluruh data
	// baik yang sudah dihapus secara softdelete maupun yg blm dihapus sema sekali
	$posts = Post::onlyTrashed()->get();
	// method onlyTrashed digunakan hanya untuk memunculkan data yang sudah 
	// dihapus secara softdelete
	return $posts;
});

// merestore data yang sudah dihapus secara softdelete
Route::get('/posts/restore', function(){
	$posts = Post::onlyTrashed()->restore();
	if ($posts) {
		// alert tidak berfungsi
	echo "<script>
		alert('data berhasil direstore');
	</script>";
	 "data berhasil di restore";
		return redirect('/posts');
	}else{
		return redirect('/trash');
	}
});

// delete permanen dari softdelete
Route::get('/posts/deleteforce', function(){
	$post = Post::onlyTrashed()->where('id',10)->forceDelete(); //selected delete
	// $post = Post::onlyTrashed()->forceDelete(); //delete all
	if ($post) {
		echo "data berhasil dihapus";
	}else{
		echo "data gagal dihapus";
	}
	// return dd($post);
});

// authentication
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user', function(){
	return Auth::user();
});

Route::get('/admin', function(){
	return 'Halaman admin';
})->middleware(['role', 'auth']);

Route::get('/member', function(){
	return 'Halaman member';
})->middleware('auth');
