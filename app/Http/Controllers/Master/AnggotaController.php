<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Anggota;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;

class AnggotaController extends Controller
{
    public function index(){
        $data['title'] = 'Data Anggota';
    	return view('master.anggota.index',$data);
    }

    public function datatable()
    {
        $data = Anggota::orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            $nama = "'".$data->nama."'";
            return '<button class="btn btn-info btn-sm" data-ng-click="showModal(edit,'.$id.')">
                    <i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-danger btn-sm" ng-click="ModalDelete('.$id.','.$nama.')">
                    <i class="fa fa-eraser"></i> Hapus</button>';
        })
        ->make();   
    }

    public function insert_data(Request $req){

        $this->validate($req,[
            'nomor_anggota'     => 'required|numeric|unique:anggota,nomor_anggota',
            'nama'              => 'required',
            'no_telepon'        => 'required|numeric|unique:anggota,no_telepon',
            'alamat'            => 'required',
        ]);

        try{
            DB::beginTransaction();
            $dat = new Anggota;
            $dat->nomor_anggota = $req->nomor_anggota;
            $dat->nama          = $req->nama;
            $dat->no_telepon    = $req->no_telepon;
            $dat->alamat        = $req->alamat;
            $dat->created_by    = Auth::user()->id;
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
          $data = Anggota::where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        $dat = Anggota::where('id',$id)->first();

        $this->validate($req,[
            'nomor_anggota'     => 'required|numeric|unique:anggota,nomor_anggota,'.$dat->id,
            'nama'              => 'required',
            'no_telepon'        => 'required|numeric|unique:anggota,no_telepon,'.$dat->id,
            'alamat'            => 'required',
        ]);

        try{            
            DB::beginTransaction();
            $dat->nomor_anggota = $req->nomor_anggota;
            $dat->nama          = $req->nama;
            $dat->no_telepon    = $req->no_telepon;
            $dat->alamat        = $req->alamat;
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
            $dat       = Anggota::where('id',$id)->first();
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
