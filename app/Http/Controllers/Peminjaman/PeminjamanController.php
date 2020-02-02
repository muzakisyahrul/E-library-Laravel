<?php

namespace App\Http\Controllers\Peminjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Peminjaman;
use App\Model\PeminjamanDetail;
use App\Model\Anggota;
use App\Model\Buku;
use App\Model\StokBuku;
use App\Model\Denda;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use DB,Excel,File,Response,PDF;

class PeminjamanController extends Controller
{
    public function index(){
        $data['title'] = 'Data Peminjaman';
    	return view('peminjaman.data',$data);
    }

    public function datatable()
    {
        $data = Peminjaman::with('anggota')->orderBy('created_at','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($data) {
            $id = $data->kode;
            $urldetail = Route('peminjaman.detail',['kode' => $data->kode]);
            return '<a href="'.$urldetail.'" class="btn btn-info btn-sm">
                    <i class="fa fa-eye"></i> Detail</a>';
        })
        ->addColumn('proses', function($data) {
            $id = "'".$data->kode."'";
            $kembali = $nama = "'kembali'";
            if($data->status == 1){
                $button = '<button class="btn btn-success btn-sm" data-ng-click="showModal('.$kembali.','.$id.')">Dikembalikan</button>';
            }else{
                 $button = '<span class="text-success"><i class="fa fa-check"></i></span>';
            }
            return $button;
        })
        ->rawColumns(['aksi','proses'])
        ->make();   
    }

    public function form(){
        $data['title'] = 'Form Peminjaman';
        return view('peminjaman.form',$data);
    }

    public function get_kode_peminjaman(){
        try{
            $max    = Peminjaman::all()->max('kode');
            $noUrut = (int) substr($max,2,6);
            $noUrut++;
            $char   = "PM";
            $code   = $char.sprintf("%06s", $noUrut);
            return $code; 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function table_anggota(){
        $data = Anggota::orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            return '<button class="btn btn-info btn-sm" data-ng-click="getAnggota('.$id.')">
                    <i class="fa fa-hand-pointer-o"></i> Pilih</button>';
        })
        ->make();  
    }

    public function table_buku(){
        $data = Buku::with('stok')->select('id','kode','judul')->orderBy('id','DESC')->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) {
            $id = $data->id;
            return '<button class="btn btn-info btn-sm" data-ng-click="getBuku('.$id.')">
                    <i class="fa fa-hand-pointer-o"></i> Pilih</button>';
        })
        ->make();  
    }

    public function get_anggota($id){
        try{
          $data = Anggota::select('id','nomor_anggota','nama','alamat','no_telepon')->where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_buku($id){
        try{
          $buku     = Buku::with('stok')->select('id','kode','judul')->where('id',$id)->first();
          $buku_id  = $buku->id;
          $max      = $buku->stok->stok_qty;
          $table_row =  '<tr class="tr_append">'.
                            '<td>'.$buku->kode.'<input type="text" name="buku_id[]" data-ng-model="buku_id['.$buku_id.']" data-ng-hide="true"></td>'.
                            '<td>'.$buku->judul.'</td>'.
                            '<td class="text-center">'.$max.'</td>'.
                            '<td><input type="number" name="qty[]" min="1" max="'.$max.'" data-ng-model="qty['.$buku_id.']" class="form-control" placeholder="Jumlah Pinjam" /></td>'.
                            '<td class="text-center"><button id="remove_tr'.$buku_id.'" class="btn btn-sm btn-danger" ng-click="RemoveTr('.$buku_id.')"><i class="fa fa-close"></i> </button></td>'.
                        '</tr>';
          return response()->json(['buku' => $buku,'table_row' => $table_row],200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function custom_alert(){
        return $data =  [
                            'id_anggota.required' => 'Anda belum memilih peminjam.',
                            'tanggal_pinjam.required' => 'Tanggal peminjaman harus diisi.',
                            'tanggal_pinjam.date_format' => 'Tanggal peminjaman harus diisi dengan format Y-m-d.',
                            'tanggal_kembali.required' => 'Tanggal kembali harus diisi.',
                            'tanggal_kembali.date_format' => 'Tanggal kembali harus diisi dengan format Y-m-d.',
                        ];
    }
    public function insert_data(Request $req){
        // return response($req->all());
        $custom_alert = $this->custom_alert();
        $this->validate($req,[
            'id_anggota'        => 'required',
            'tanggal_pinjam'    => 'required|date_format:Y-m-d',
            'tanggal_kembali'   => 'required|date_format:Y-m-d',
        ],$custom_alert);

        if($req->has('buku_id')){
            for($i=0;$i < count($req->buku_id);$i++){
                $id = $req->buku_id[$i];
                $buku = Buku::select('judul')->where('id',$id)->first();
                $stok = StokBuku::select('stok_qty')->where('buku_id',$id)->first();
                $nama_buku = $buku->judul;
                $max       = $stok->stok_qty;

                $this->validate($req,[
                    'qty.'.$i => 'required|numeric|min:1|max:'.$max,
                ],[
                    'qty.'.$i.'.required'  => 'Input Jumlah Pinjam Buku '.$nama_buku.' Harus Diisi.',
                    'qty.'.$i.'.numeric'  => 'Input Jumlah Pinjam Buku '.$nama_buku.' Harus Berupa Angka.',
                    'qty.'.$i.'.min'  => 'Input Jumlah Buku '.$nama_buku.' Minimal Harus 1',
                    'qty.'.$i.'.max'  => 'Input Jumlah Buku '.$nama_buku.' Melebihi Batas Stok Yaitu '.$max,
                ]);

            }
        }else{
            $data['message'] = 'Anda belum memilih buku yang akan dipinjam.';
            $data['status']  = 'no_buku';
            return response()->json($data,422);
        }
        // $data = ['status' => 'success', 'message' => 'Success! Data berhasil ditambahkan.'];
        // return response()->json($data,200);

        try{
            DB::beginTransaction();
            $kode = $this->get_kode_peminjaman();
            $dat = new Peminjaman;
            $dat->kode              = $kode;
            $dat->anggota_id        = $req->id_anggota;
            $dat->tgl_pinjam        = $req->tanggal_pinjam;
            $dat->tgl_kembali       = $req->tanggal_kembali;
            $dat->status            = 1;
            $dat->status_txt        = 'Dipinjam';
            $dat->created_by        = Auth::user()->id;
            $saved = $dat->save();
            $saveLastCode = $dat->kode;

                $dataBuku = [];
                for($i=0;$i < count($req->buku_id);$i++){
                    $buku_id = $req->buku_id[$i];
                    $qty     = $req->qty[$i];
                    $row = [
                        'peminjaman_kode'   => $saveLastCode,
                        'buku_id'           => $buku_id,
                        'qty'               => $qty,
                        'created_at'        => Carbon::now('Asia/Jakarta')->format('Y-m-d h:i:s'),
                    ];
                    $transaction  = ['type'=> 2, 'text' => 'Peminjaman'];
                    app('App\Http\Controllers\Master\StokBukuController')
                    ->change_stock($buku_id, $transaction, $saveLastCode, '-'.$qty);
                    array_push($dataBuku,$row);
                }
                $saved_detail = PeminjamanDetail::insert($dataBuku);

            if($saved && $saved_detail){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil disimpan.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal disimpan.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_denda(){
        try{
          $data = Denda::select('nominal')->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req,$kode){
        // return response()->json($req->all());
        $this->validate($req,[
            'tanggal_kembali'   => 'required|date_format:Y-m-d',
        ]);

        try{
            DB::beginTransaction();
            $data = [
                        'tgl_dikembalikan'  => $req->tanggal_kembali,
                        'denda'             => $req->denda,
                        'status'            => 2,
                        'status_txt'        => 'Dikembalikan',
                        'updated_at'        => Carbon::now('Asia/Jakarta')->format('Y-m-d h:i:s'),
                    ];
            $saved = Peminjaman::where('kode',$kode)->update($data);

            $detail = PeminjamanDetail::where('peminjaman_kode',$kode)->get();
            $transaction  = ['type'=> 3, 'text' => 'Pengembalian'];
            foreach ($detail as $key => $value) {
                $buku_id = $value->buku_id;
                $qty     = $value->qty;
                $tambah_stok = app('App\Http\Controllers\Master\StokBukuController')
                    ->change_stock($buku_id, $transaction, $kode, $qty);
            }

            if($saved && $tambah_stok){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil disimpan.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal disimpan.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function form_detail($kode){
        $query = Peminjaman::where('kode',$kode)->first();
        if($query){
            $title  = 'Detail Peminjaman';
            $data = ['title' => $title, 'kode' => $kode];
            return view('peminjaman.detail',$data);
        }else{
            return redirect()->route('peminjaman.data');
        }
    }

    public function get_pinjam($kode){
        $pinjam = Peminjaman::with('anggota')->where('kode',$kode)->first();
        $detail = PeminjamanDetail::with('buku')->where('peminjaman_kode',$kode)->get();

        $data = ['pinjam' => $pinjam, 'detail' => $detail];
        return response()->json($data);
    }

    public function get_excel_detail($kode){
        //cleaning excel file 
        File::deleteDirectory(public_path('/excel/peminjaman/'));

        //query for blade excel views
        $query = Peminjaman::where('kode', $kode)->with(['anggota','details'])->get();
            foreach ($query as $key => $value) {
                foreach ($value->details as $k => $v) {
                   $buku = Buku::select('judul')->where('id',$v->buku_id)->first();
                   $v->judul = $buku->judul;
                }
            } 
        $file_name = 'Detail_Peminjaman_'.$kode;
        Excel::create($file_name, function($excel) use(&$query, &$file_name) {

            $excel->sheet('Sheet 1', function($sheet) use(&$query, &$file_name) {
                $sheet->setFitToPage(true);
                $sheet->setHorizontalCentered(true);
                $sheet->setPageMargin(0.25);
                $sheet->setWidth(array(
                            'A'     =>  19.29,
                            'B'     =>  22.14,
                            'C'     =>  22.71,
                            'D'     =>  29.14,
                ));

                $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
                
                $sheet->loadView('peminjaman.detail_excel',['data'=>$query, 'today' => $today]);
            });

        })->store('xlsx', public_path('excel/peminjaman/'));

        $file_path = public_path('excel/peminjaman/').$file_name.'.xlsx';

        if(File::exists($file_path)){
            return Response::download($file_path,$file_name.'.xlsx');
        }
    }

    public function get_pdf_detail($kode){
        $query = Peminjaman::where('kode', $kode)->with(['anggota','details'])->get();
            foreach ($query as $key => $value) {
                foreach ($value->details as $k => $v) {
                   $buku = Buku::select('judul')->where('id',$v->buku_id)->first();
                   $v->judul = $buku->judul;
                }
            }
        $file_name = 'Detail_Peminjaman_'.$kode;
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $pdf = PDF::loadView('peminjaman.detail_pdf', ['data'=>$query,'today' => $today])
                    ->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

}
