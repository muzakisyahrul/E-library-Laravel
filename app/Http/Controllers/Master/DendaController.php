<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Denda;
use DB;

class DendaController extends Controller
{
    public function index(){
        $data['title'] = 'Data Denda Keterlambatan Per Hari';
    	return view('master.denda.index',$data);
    }

    public function get_denda()
    {
        try{
          $data = Denda::orderBy('id','DESC')->get();
          foreach ($data as $key => $value) {
              $value->nominal = 'Rp. '.number_format($value->nominal);
          }
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }

    }

    public function insert_data(Request $req){

        $this->validate($req,[
            'nominal'      => 'required|numeric',
        ]);

        try{
            DB::beginTransaction();
            $dat = new Denda;
            $dat->nominal    = $req->nominal;
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

    public function get_detail_denda($id){
        try{
          $data = Denda::where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        $this->validate($req,[
            'nominal'      => 'required|numeric',
        ]);

        try{
            DB::beginTransaction();
            $dat = Denda::where('id',$id)->first();
            $dat->nominal = $req->nominal;
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
            $dat       = Denda::where('id',$id)->first();
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
