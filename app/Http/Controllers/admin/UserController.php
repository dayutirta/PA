<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use Authenticatable;
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem',
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all();//ambil data level untuk filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'level'=> $level, 'activeMenu' => $activeMenu]);
    }
     // Ambil data user dalam bentuk json untuk datatables
     public function list(Request $request)
     {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');
        // ->join('m_level', 'm_user.level_id', '=', 'm_level.level_id')
        // ->get();
         
        //Filter data user berdasarkan level_id
        if($request->level_id){
            $users->where('level_id', $request->level_id);
        }
        $users = $users->get(); // Panggil get() setelah menerapkan filter
         return DataTables::of($users)
                 ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
                 
                 ->addColumn('aksi', function ($user) {
                     $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                     $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                     $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/'.$user->user_id).'">'
                             .csrf_field() .method_field('DELETE') . 
                             '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                     return $btn;
                 })
                 ->rawColumns(['aksi'])
                 ->make(true);
     }
    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah User Baru',
        ];

        $level = LevelModel::all();//ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page,'level'=>$level ,'activeMenu' => $activeMenu]);
    }

    //Menyimpan data baru
    Public function store(Request $request)
    {
        $request->validate([
            //username wajib diisi, string, minimal 3 char
            'username'  => 'required|string|min:3|unique:m_user,username',
            'nama'      => 'required|string|max:100',
            'password'  => 'required|min:5',
            'level_id'  => 'required|integer'
        ]);

        UserModel::create([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => bcrypt($request->password),  //mengekripsi sebelum disimpan
            'level_id'     => $request->level_id
        ]);
        return redirect('/user')->with('success','Data user berhasil disimpan');
    }

    //Menampilkan detail user
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User',
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page,'user' => $user,'activeMenu' => $activeMenu]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page,'user' => $user,'level' => $level,'activeMenu' => $activeMenu]);
    }

    //Menyimpan perubahan data user 
    public function update(Request $request, string $id)
    {
        $request->validate([
            //username wajib diisi, string, minimal 3 char
            'username'  => 'required|string|min:3|unique:m_user,username, '.$id.',user_id',
            'nama'      => 'required|string|max:100',
            'password'  => 'required|min:5',
            'level_id'  => 'required|integer'
        ]);

        UserModel::find($id)->update([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => $request->password ? bcrypt($request->password): UserModel::find($id)->password,  
            'level_id'     => $request->level_id
        ]);
        return redirect('/user')->with('success','Data user berhasil diubah');
    }
    //Menghapus data
    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if(!$check){
            return redirect('/user')->with('error','Data user tidak ditemukan');
        }

        try{
            UserModel::destroy($id);

            return redirect('/user')->with('succes','Data berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){
            //jika terjadi error, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error','Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}