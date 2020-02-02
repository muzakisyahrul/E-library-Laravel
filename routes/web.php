<?php

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

Route::group(['middleware'=>'guest'], function() {
	Route::get('/', function () {
	    return view('auth.login');
	})->name('login');
	Route::post('/p_login', 'AuthController@p_login')->name('p_login');
});	



Route::group(['middleware'=>'auth'], function() {
	

	Route::get('logout','AuthController@logout')->name('logout');

	Route::group(['prefix'=>'home'], function() {
		Route::get('/dashboard', function () {
	    	return view('dashboard');
		})->name('dashboard');

		// start route prefix master
		Route::group(['prefix'=>'master'], function() {
			// Route Petugas
			Route::group(['prefix'=>'petugas'], function() {
				Route::get('data','Master\PetugasController@index')->name('petugas.data');
				Route::get('datatable','Master\PetugasController@datatable');
				Route::post('insert','Master\PetugasController@insert_data')->name('petugas.insert');
				Route::get('get_data/{id}','Master\PetugasController@get_data');
				Route::post('update/{id}','Master\PetugasController@update_data');
				Route::delete('delete/{id}','Master\PetugasController@delete_data');
			});

			// Route Kategori Buku
			Route::group(['prefix'=>'kategori_buku'], function() {
				Route::get('data','Master\KategoriBukuController@index')->name('kategori.data');
				Route::get('datatable','Master\KategoriBukuController@datatable');
				Route::post('insert','Master\KategoriBukuController@insert_data');
				Route::get('get_data/{id}','Master\KategoriBukuController@get_data');
				Route::put('update/{id}','Master\KategoriBukuController@update_data');
				Route::delete('delete/{id}','Master\KategoriBukuController@delete_data');
			});

			// Route Rak Buku
			Route::group(['prefix'=>'rak_buku'], function() {
				Route::get('data','Master\RakBukuController@index')->name('rak_buku.data');
				Route::get('datatable','Master\RakBukuController@datatable');
				Route::post('insert','Master\RakBukuController@insert_data');
				Route::get('get_data/{id}','Master\RakBukuController@get_data');
				Route::put('update/{id}','Master\RakBukuController@update_data');
				Route::delete('delete/{id}','Master\RakBukuController@delete_data');
			});

			// Route Penulis Buku
			Route::group(['prefix'=>'penulis_buku'], function() {
				Route::get('data','Master\PenulisBukuController@index')->name('penulis_buku.data');
				Route::get('datatable','Master\PenulisBukuController@datatable');
				Route::post('insert','Master\PenulisBukuController@insert_data');
				Route::get('get_data/{id}','Master\PenulisBukuController@get_data');
				Route::put('update/{id}','Master\PenulisBukuController@update_data');
				Route::delete('delete/{id}','Master\PenulisBukuController@delete_data');
			});

			// Route Buku
			Route::group(['prefix'=>'buku'], function() {
				Route::get('data','Master\BukuController@index')->name('buku.data');
				Route::get('datatable','Master\BukuController@datatable');
				Route::post('insert','Master\BukuController@insert_data');
				Route::get('get_kategori','Master\BukuController@get_kategori');
				Route::get('get_penulis','Master\BukuController@get_penulis');
				Route::get('get_rak','Master\BukuController@get_rak');
				Route::get('get_data/{id}','Master\BukuController@get_data');
				Route::put('update/{id}','Master\BukuController@update_data');
				Route::delete('delete/{id}','Master\BukuController@delete_data');
			});

			// Route Anggota
			Route::group(['prefix'=>'anggota'], function() {
				Route::get('data','Master\AnggotaController@index')->name('anggota.data');
				Route::get('datatable','Master\AnggotaController@datatable');
				Route::post('insert','Master\AnggotaController@insert_data');
				Route::get('get_data/{id}','Master\AnggotaController@get_data');
				Route::put('update/{id}','Master\AnggotaController@update_data');
				Route::delete('delete/{id}','Master\AnggotaController@delete_data');
			});

			// Route Stok
			Route::group(['prefix'=>'stok_buku'], function() {
				Route::get('data','Master\StokBukuController@index')->name('stok_buku.data');
				Route::get('datatable','Master\StokBukuController@datatable');
				Route::get('get_buku','Master\StokBukuController@get_buku');
				Route::post('insert','Master\StokBukuController@insert_data');
				Route::get('get_data/{id}','Master\StokBukuController@get_data');
				Route::put('update/{id}','Master\StokBukuController@update_data');
			});

			// Route Denda
			Route::group(['prefix'=>'denda'], function() {
				Route::get('data','Master\DendaController@index')->name('denda.data');
				Route::get('get_denda','Master\DendaController@get_denda');
				Route::get('get_denda/{id}','Master\DendaController@get_detail_denda');
				Route::post('insert','Master\DendaController@insert_data');
				Route::put('update/{id}','Master\DendaController@update_data');
				Route::delete('delete/{id}','Master\DendaController@delete_data');
			});
		});
		// end routes prefix master
		
		// start routes prefix peminjaman
		Route::group(['prefix'=>'peminjaman'], function() {
			Route::get('data','Peminjaman\PeminjamanController@index')->name('peminjaman.data');
			Route::get('datatable','Peminjaman\PeminjamanController@datatable');
			Route::get('form','Peminjaman\PeminjamanController@form')->name('peminjaman.form');
			Route::get('get_kode_peminjaman','Peminjaman\PeminjamanController@get_kode_peminjaman');
			Route::get('table_anggota','Peminjaman\PeminjamanController@table_anggota');
			Route::get('get_anggota/{id}','Peminjaman\PeminjamanController@get_anggota');
			Route::get('table_buku','Peminjaman\PeminjamanController@table_buku');
			Route::get('get_buku/{id}','Peminjaman\PeminjamanController@get_buku');
			Route::post('insert','Peminjaman\PeminjamanController@insert_data');
			Route::get('detail/{kode}','Peminjaman\PeminjamanController@form_detail')->name('peminjaman.detail');
			Route::get('get_pinjam/{kode}','Peminjaman\PeminjamanController@get_pinjam');
			Route::get('get_excel/detail/{kode}','Peminjaman\PeminjamanController@get_excel_detail')->name('peminjaman.detail.excel');
			Route::get('get_pdf/detail/{kode}','Peminjaman\PeminjamanController@get_pdf_detail')->name('peminjaman.detail.pdf');
			Route::get('get_denda','Peminjaman\PeminjamanController@get_denda');
			Route::put('update/{kode}','Peminjaman\PeminjamanController@update_data');
		});
		// end routes prefix peminjaman
	});

});
