<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('admin.promo.index', compact('promos'));
        //compact -> argumen pada fungsi akakn sama dengan nama variabel yang akna dikirim ke blade
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos',
            'discount' => 'required|numeric',
            'type' => 'required|in:percent,rupiah',
        ], [
            'promo_code.required' => 'Kode promo harus diisi.',
            'promo_code.unique'   => 'Kode promo sudah digunakan, silakan pakai kode lain.',
            'discount.required'   => 'Diskon harus diisi.',
            'discount.numeric'    => 'Diskon harus berupa angka.',
            'type.required'       => 'Jenis diskon harus dipilih.',
            'type.in'             => 'Jenis diskon hanya boleh percent atau rupiah.',
        ]);

        //kirim data
        $createPromo = Promo::create([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'activated' => 1
        ]);

        //redirect / perpindahan halaman
        if ($createPromo) {
            return redirect()->route('admin.promos.index')->with('success', 'Berhasil membuat data promo!');
        } else {
            return redirect()->back()->with('failed', 'Gagal membuat data promo');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promo = Promo::find($id);
        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos',
            'discount' => 'required|numeric',
            'type' => 'required|in:percent,rupiah',
        ], [
            'promo_code.required' => 'Kode promo harus diisi.',
            'promo_code.unique'   => 'Kode promo sudah digunakan, silakan pakai kode lain.',
            'discount.required'   => 'Diskon harus diisi.',
            'discount.numeric'    => 'Diskon harus berupa angka.',
            'type.required'       => 'Jenis diskon harus dipilih.',
            'type.in'             => 'Jenis diskon hanya boleh percent atau rupiah.',
        ]);

        //kirim data
        $updatePromo = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'discount' => $request->discount,
            'type' => $request->type,
            'activated' => 1
        ]);

        //redirect / perpindahan halaman
        if ($updatePromo) {
            return redirect()->route('admin.promos.index')->with('success', 'Berhasil mengedit data promo!');
        } else {
            return redirect()->back()->with('failed', 'Gagal mengedit data promo');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $deleteData = Promo::where('id', $id)->delete();
        if ($deleteData) {
            return redirect()->route('admin.promos.index')->with('success', 'Berhasil menghapus data promo!');
        } else {
            return redirect()->back()->with('failed', 'Gagal menghapus data promo!');
        }
    }

    public function activated($id)
    {
        $promo = Promo::find($id);
        if (!$promo) {
            return redirect()->back()->with('error', 'Data film tidak ditemukan');
        }

        $promo->activated = !$promo->activated;
        $promo->save();

        $statusBaru = $promo->activated ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.promos.index')->with('success', "Film berhasil $statusBaru.");
    }

    public function export()
    {
        $fileName = 'data-promo.xlsx';
        return Excel::download(new PromoExport, $fileName);
    }

    public function trash()
    {
        //ORM yang digunakan terkait softdelete
        //onlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        //restore() -> mengembalikan data yang sudah dihapus (menghapus nilai tanggal pada delete_at)
        //forceDelete() -> menghapus data secara permanen, data dihilangkan bahkan dari databasenya
        $promoTrash = Promo::onlyTrashed()->get();
        return view('admin.promo.trash', compact('promoTrash'));
    }

    public function restore($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->restore();
        return redirect()->route('admin.promos.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

        //datatables
    public function datatables()
    {
        $promos = Promo::query();
        return DataTables::of($promos)
        ->addIndexColumn()
        ->addColumn('discount', function($promo){
            if ($promo['type'] == 'rupiah') {
                return '<span>Rp. ' . number_format($promo['discount'], 0, ',', ',') . '</span>';
            } else {
                return '<span>' . $promo['discount'] . ' %</span>';
            }
        })
        ->addColumn('type', function($promo){
            $type = $promo->type;
            return '<td>' . $type . '</td>';
        })
        ->addColumn('activated', function($promo){
            if($promo->activated){
                return '<span class="badge bg-success">Aktif</span>';
            } else{
                return '<span class="badge bg-danger">Non-Aktif</span>';
            }
        })

        ->addColumn('action', function($promo){
            $btnEdit =  '<a href="' . route('admin.promos.edit', $promo->id) . '" class="btn btn-primary">Edit</a>';
            $btnDelete = ' <form action="' . route('admin.promos.delete', $promo->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . method_field('DELETE') .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';

            $btnNonAktif = '';
            if($promo->activated){
                $btnNonAktif = ' <form action="' . route('admin.promos.activated', $promo->id) . '"  method="POST" style="display: inline-block;">
                            ' . csrf_field() . method_field('PUT') .'
                            <button type="submit" class="btn btn-warning me-2">Non-Aktif</button>
                        </form>';
            }
            return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . $btnNonAktif . '</div>';
        })
        ->rawColumns(['discount', 'type', 'activated' , 'action'])
        ->make(true);
    }
}
