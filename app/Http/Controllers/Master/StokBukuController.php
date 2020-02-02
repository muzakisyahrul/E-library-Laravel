<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\StokBuku;
use App\Model\StokBukuDetail;
use App\Model\Buku;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;

class StokBukuController extends Controller
{
    public function index(){
        $data['title'] = 'Data Stok Buku';
    	return view('master.stok_buku.index',$data);
    }

    public function datatable()
    {
        $data = StokBuku::with('buku')->orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            $buku_id = $data->buku_id;
            $nama = "'".$data->buku->judul."'";
            return '<button class="btn btn-info btn-sm" data-ng-click="showModal(edit,'.$id.','.$buku_id.')">
                    <i class="fa fa-edit"></i> Edit</button>';
        })
        ->make();   
    }

    public function get_buku(){
        try{
          $data = Buku::select('id','judul')->orderBy('judul','ASC')->get();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function insert_data(Request $req){

        $this->validate($req,[
            'buku'      => 'required|unique:stok_buku,buku_id',
            'qty'       => 'required|numeric',
        ]);

        try{
            $buku_id     = $req->buku;
            $qty         = $req->qty;
            $transaction = ['type'=> 0, 'text' => 'Saldo Awal Stok'];
            $insert_data = $this->change_stock($buku_id, $transaction,null,$qty);

            if($insert_data){
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil ditambahkan.'];
            }else{
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal ditambahkan.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_data($id){
        try{
          $data = StokBuku::where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        if(!empty($req->opsi_qty)){
            $this->validate($req,[
                'new_qty'       => 'required|numeric',
            ]);
        }

        try{
            $buku_id = $req->buku;
            if($req->opsi_qty == 1){
                $status = 'Penambahan';
                $qty    = $req->new_qty;
            }else{
                $status = 'Pengurangan';
                $qty    = '-'.$req->new_qty;
            }
            
            $transaction  = ['type'=> 1, 'text' => 'Perbaruan Stok ('.$status.')'];
            $updated_data = $this->change_stock($buku_id, $transaction,null,$qty);

            if($updated_data){
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

    public function change_stock($buku_id, $transaction, $transaction_code, $qty)
    {
        try{
            DB::beginTransaction();
            $stock_buku = StokBuku::where('buku_id',$buku_id)->first();
            if(empty($stock_buku)){
                $stock_buku = new StokBuku;
                $stock_buku->created_by = Auth::user()->id;
            }else{
                 $stock_buku->updated_by = Auth::user()->id;
            }
            $buku = Buku::where('id',$buku_id)->first();
            $stock_buku->buku_id    = $buku->id;
            $stock_buku->stok_qty   = $stock_buku->stok_qty + $qty;
            $saved_stock_buku = $stock_buku->save();

            $dat = new StokBukuDetail;
            $dat->stok_buku_id          = $stock_buku->id;
            $dat->tipe_transaksi        = $transaction['type'];
            $dat->tipe_transaksi_txt    = $transaction['text'];
            $dat->kode_transaksi        = $transaction_code;      
            $dat->qty                   = $qty;
            $dat->created_by            = Auth::user()->id;
            $saved_stock_detail = $dat->save();

            if($saved_stock_buku && $saved_stock_detail){
                DB::commit();
                return true;
            }else{
                DB::rollback();
                return false;
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return false;
        }
    }
}
