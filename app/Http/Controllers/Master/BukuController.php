<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Buku;
use App\Model\KategoriBuku;
use App\Model\PenulisBuku;
use App\Model\RakBuku;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;

class BukuController extends Controller
{
    public function index(){
        $data['title'] = 'Data Buku';
    	return view('master.data_buku.index',$data);
    }

    public function datatable()
    {
        $data = Buku::with('stok')->orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            $nama = "'".$data->judul."'";
            $edit = "'edit'";
            $kategori_id = !empty($data->kategori_id)?$data->kategori_id:0;
            $penulis_id = !empty($data->penulis_id)?$data->penulis_id:0;
            $rak_id = !empty($data->rak_id)?$data->rak_id:0;
            return '<button class="btn btn-info btn-sm" data-ng-click="showModal('.$edit.','.$id.','.$kategori_id.','.$penulis_id.','.$rak_id.')">
                    <i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm" ng-click="ModalDelete('.$id.','.$nama.')">
                    <i class="fa fa-eraser"></i> Hapus</button>';
        })
        ->make();   
    }

    public function get_kategori(){
        try{
          $data = KategoriBuku::select('id','nama_kategori')->orderBy('nama_kategori','ASC')->get();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_penulis(){
        try{
          $data = PenulisBuku::select('id','nama')->orderBy('nama','ASC')->get();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_rak(){
        try{
          $data = RakBuku::select('id','nama_rak')->orderBy('nama_rak','ASC')->get();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function insert_data(Request $req){
        // return response($req->all());
        
        $this->validate($req,[
            'kode'         => 'required|unique:buku,kode',
            'judul'         => 'required',
        ]);

        try{
            $penerbit       = !empty($req->penerbit)?$req->penerbit:'-';
            $tahun_terbit   = !empty($req->tahun_terbit)?$req->tahun_terbit:'-';
            $isbn           = !empty($req->isbn)?$req->isbn:'-';

            DB::beginTransaction();
            $dat = new Buku;
            $dat->kode          = $req->kode;
            $dat->judul         = $req->judul;
            $dat->penerbit      = $penerbit;
            $dat->tahun_terbit  = $tahun_terbit;
            $dat->isbn          = $isbn;
            $dat->halaman       = $req->jumlah_halaman;
            $dat->kategori_id   = $req->kategori;
            $dat->penulis_id    = $req->penulis;
            $dat->rak_id        = $req->rak;
            $dat->created_by    = Auth::user()->id;
            $saved = $dat->save();

            $buku_id = $dat->id;
            $qty     = !empty($req->stok)?$req->stok:0;
            $transaction = ['type'=> 0, 'text' => 'Saldo Awal Stok'];
            $insert_data = app('App\Http\Controllers\Master\StokBukuController')->change_stock($buku_id, $transaction,null,$qty);

            if($saved && $insert_data){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil ditambahkan.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal ditambahkan.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_data($id){
        try{
          $data = Buku::with('stok')->where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        $dat = Buku::where('id',$id)->first();
        $this->validate($req,[
            'kode'         => 'required|unique:buku,kode,'.$dat->id,
            'judul'         => 'required',
        ]);

        try{
            $penerbit       = !empty($req->penerbit)?$req->penerbit:'-';
            $tahun_terbit   = !empty($req->tahun_terbit)?$req->tahun_terbit:'-';
            $isbn           = !empty($req->isbn)?$req->isbn:'-';
            
            DB::beginTransaction();
            $dat->kode          = $req->kode;
            $dat->judul         = $req->judul;
            $dat->penerbit      = $penerbit;
            $dat->tahun_terbit  = $tahun_terbit;
            $dat->isbn          = $isbn;
            $dat->halaman       = $req->jumlah_halaman;
            $dat->kategori_id   = $req->kategori;
            $dat->penulis_id    = $req->penulis;
            $dat->rak_id        = $req->rak;
            $dat->updated_by    = Auth::user()->id;
            $saved = $dat->save();

            if($saved){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil diperbarui.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal diperbarui.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function delete_data($id){
        // return response()->json(['id'=>$id]);
        try{
            DB::beginTransaction();
            $dat       = Buku::where('id',$id)->first();
            $deleted    = $dat->delete();

            if($deleted){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil dihapus.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal dihapus.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }
}
