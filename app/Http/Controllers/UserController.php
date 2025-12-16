<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::orderBy('role', 'asc')->get();
        return view('admin.user.index', compact('users'));
        //compact -> argumen pada fungsi akakn sama dengan nama variabel yang akna dikirim ke blade
    }

    public function count()
    {
        $countUser = User::count();
        return view('admin.dashboard', compact('countUser'));
    }

    public function chartData()
    {
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $userCounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $monthlyUsers->where('month', $date->month)->where('year', $date->year)->first()->count ?? 0;
            $userCounts[] = $count;
        }
        return view('admin.dashboard', compact(
            'userCount',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi
        $request->validate([
            'name' => 'required|min:3',
            //email:dns => emailnya valid, @gmail @company.co dsb
            'email' => 'required|email:dns',
            'password' => 'required|min:8',
            'role' => 'required',
        ], [
            'name.required' => 'Nama belakang wajib diisi',
            'name.min' => 'Nama belakang minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib diisi',
        ]);

        //kirim data
        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            //pengguna tidak bisa memilih role {akses}, jadi manual ditambahkan 'user'
            'role' => $request->role
        ]);

        //redirect / perpindahan halaman
        if ($createUser) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil membuat data Pengguna!');
        } else {
            return redirect()->back()->with('failed', 'Gagal membuat data user');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validasi
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'nullable|min:8',
            'role' => 'required',
        ], [
            'name.required' => 'Nama belakang wajib diisi',
            'name.min' => 'Nama belakang minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib diisi',

        ]);

        //update data
        $updateUser = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        //redirect / perpindahan halaman
        if ($updateUser) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil mengupdate data Pengguna!');
        } else {
            return redirect()->back()->with('failed', 'Gagal mengupdate data Pengguna');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteData = User::where('id', $id)->delete();

        if ($deleteData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data pengguna!');
        } else {
            return redirect()->back()->with('failed', 'Gagal menghapus data pengguna!');
        }
    }

    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            //pesan error
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama depan minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email wajib diisi dengan data yang valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 Karakter',
        ]);

        //membuat data baru
        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            //Hash : enskripsi data (mengubah menjadi karakter acak) agar tidak ada yang bisa menebak isinya
            'password' => Hash::make($request->password),
            //pengguna tidak bisa memilih role {akses}, jadi manual ditambahkan 'user'
            'role' => 'user'
        ]);

        if ($createUser) {
            //redirec() : memindah halaman, route() : nama routing yang dituju
            //with() : mengirimkan session, biasanya untuk notifikasi
            return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silakan login');
        } else {
            //back() : kembali ke halaman sebelumnya
            return redirect()->back()->with('error', 'Akun gagal dibuat, silahkan coba lagi');
        }
    }

    public function loginAuth(Request $request)
    {
        //validasi data yang dikirim dari formulir
        $request->validate([
            //'name_input => 'tipe validasi'
            //required : harus diisi, min: minimal karakter (teks)
            'email' => 'required|email',
            'password' => 'required|min:8', // |max:20 untuk maksimal karakter
        ], [
            //pesan error custom
            //'name_input.validasi' => 'pesan'
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        //mengambil data yang akan di verif
        $data = $request->only('email', 'password');
        //Auth:: => class laravel untuk otentikasi
        //attempt() : method class auth untuk mencocokan email dan pw atau username dan kalau cocok akan disimpan datanya ke sesssion auth
        if (Auth::attempt($data)) {
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
            } else {
                return redirect()->route('home')->with('success', 'Berhasil login!');
            }
            //jika cocok
            //redirect ke halaman yang di tuju
        } else {
            //jika tidak cocok
            return redirect()->back()->with('error', 'Login gagal, email atau password tidak sesuai!');
        }
    }
    //jika cocok
    //redirect ke halaman yang di tuju

    public function logout()
    {
        //logout : menghapus session auth
        Auth::logout();
        //redirect ke halaman login
        return redirect()->route('home')->with('logout', 'Berhasil logout! Silahkan login kembali untuk akses lengkap');
    }

    public function export()
    {
        //nama file yang akan di unduh
        $fileName = 'data-Pengguna.xlsx';
        //proses unduh
        return Excel::download(new UserExport, $fileName);
    }

    //trash
    public function trash()
    {
        //ORM yang digunakan terkait softdelete
        //onlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        //restore() -> mengembalikan data yang sudah dihapus (menghapus nilai tanggal pada delete_at)
        //forceDelete() -> menghapus data secara permanen, data dihilangkan bahkan dari databasenya
        $userTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

    public function datatables()
    {
        $users = User::orderBy('role', 'asc');
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role', function ($user) {
                if ($user['role'] == 'admin') {
                    return '<span class="badge bg-success">admin</span>';
                } else {
                    return '<span class="badge bg-secondary">User</span>';
                }
            })
            ->addColumn('action', function ($user) {
                $btnEdit =  '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-primary">Edit</a>';
                $btnDelete = ' <form action="' . route('admin.users.delete', $user->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';
                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
    }
}
