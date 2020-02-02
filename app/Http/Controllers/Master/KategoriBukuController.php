<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\KategoriBuku;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;

class KategoriBukuController extends Controller
{
    public function index(){
        $data['title'] = 'Data Kategori Buku';
    	return view('master.kategori_buku.index',$data);
    }

    public function datatable()
    {
        $data = KategoriBuku::orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            $nama = "'".$data->nama_kategori."'";
            return '<button class="btn btn-info btn-sm" data-ng-click="showModal(edit,'.$id.')">
                    <i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm" ng-click="ModalDelete('.$id.','.$nama.')">
                    <i class="fa fa-eraser"></i> Hapus</button>';
        })
        ->make();   
    }

    public function insert_data(Request $req){

        $this->validate($req,[
            'nama_kategori'      => 'required',
        ]);

        try{
            DB::beginTransaction();
            $dat = new KategoriBuku;
            $dat->nama_kategori    = $req->nama_kategori;
            $dat->created_by       = Auth::user()->id;
            $saved = $dat->save();

            if($saved){
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
          $data = KategoriBuku::where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        $this->validate($req,[
            'nama_kategori'      => 'required',
        ]);

        try{
            DB::beginTransaction();
            $dat = KategoriBuku::where('id',$id)->first();
            $dat->nama_kategori = $req->nama_kategori;
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
            $dat       = KategoriBuku::where('id',$id)->first();
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
