<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use DB;
use Intervention\Image\Facades\Image;
use File;

class PetugasController extends Controller
{
    public function index(){
        $data['title'] = 'Data Petugas';
    	return view('master.data_petugas.index',$data);
    }

    public function datatable()
    {
        $data = User::where('id_hak_akses',2)->orderBy('id','DESC')->get();
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
        // return response($req->file('photo')->getClientOriginalName());
        // exit();
        $this->validate($req,[
            'nip'       => 'required|unique:users,nip',
            'nama'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required',
        ]);

        if($req->hasFile('photo')){
            $this->validate($req,[
                'photo'     => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        try{
            DB::beginTransaction();
            $users = new User;
            $users->nip              = $req->nip;
            $users->nama             = $req->nama;
            $users->email            = $req->email;
            $users->password         = bcrypt($req->password);
            $users->id_hak_akses     = 2;
            $users->created_by       = Auth::user()->id;
            $saved = $users->save();
            $saved_id           = $users->id;
            if ($req->hasFile('photo')) {
                $file               = $req->file('photo');
                $fileName           = time().'.'.$file->getClientOriginalExtension();
                $path_name          = '/user/'.$saved_id.'/photo_profile/';
                $destinationPath    = public_path('/images'.$path_name);
                $file->move($destinationPath, $fileName);
                $img                = Image::make($destinationPath."/".$fileName)->resize(240, 320)->save();
            }else{
                $fileName           = 'default-profile.png';
                $path_name          = '/';
            }
            $uploaded = User::where('id',$saved_id)->update(['photo' => $path_name.$fileName]);


            if($saved && $uploaded){
                DB::commit();
                $data = ['status' => 'success', 'message' => 'Success! Data berhasil ditambahkan.'];
            }else{
                DB::rollback();
                $data = ['status' => 'fail', 'message' => 'Warning! Data gagal ditambahkan.'];
            }
            return response()->json($data,200);
        }catch (\Exception $e) {
            // DB::rollback();
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function get_data($id){
        try{
          $data = User::where('id',$id)->first();
          return response()->json($data,200); 
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return response($msg,500);
        }
    }

    public function update_data(Request $req, $id){
        // return response()->json(['id'=>$id]);
        // exit();
        $user = User::where('id',$id)->first();
        $this->validate($req,[
            'nip'       => 'required|unique:users,nip,'.$user->id,
            'nama'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$user->id,
        ]);

        if($req->hasFile('photo')){
            $this->validate($req,[
                'photo'     => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        try{
            DB::beginTransaction();
            $user->nip              = $req->nip;
            $user->nama             = $req->nama;
            $user->email            = $req->email;
            if($req->has('password')){
                $user->password     = bcrypt($req->password);
            }
            $user->id_hak_akses     = 2;
            if ($req->hasFile('photo')) {
                // // delete gambar yang sudah ada
                if($user->photo != '/default-profile.png'){
                    $path_deleted = public_path('/images'.$user->photo);
                    File::delete($path_deleted);
                }
                // upload gambar baru
                $file               = $req->file('photo');
                $fileName           = time().'.'.$file->getClientOriginalExtension();
                $path_name          = '/user/'.$user->id.'/photo_profile/';
                $destinationPath    = public_path('/images'.$path_name);
                $file->move($destinationPath, $fileName);
                $img                = Image::make($destinationPath."/".$fileName)->resize(240, 320)->save();
            $user->photo = $path_name.$fileName ;
            }
            $user->updated_by = Auth::user()->id;
            $saved = $user->save();

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
            $user       = User::where('id',$id)->first();
            $deleted    = $user->delete();
            $deleted_folder = true;
            // if not softdelete then remove folder image
            if($user->photo != '/default-profile.png'){
                $deleted_folder = File::deleteDirectory(public_path('/images/user/'.$id));
            }

            if($deleted && $deleted_folder){
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
