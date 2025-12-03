<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoryExport;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    // ============================
    // INDEX (LIST CATEGORY)
    // ============================
    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC')->get();
        return view('admin.category.index', compact('categories'));
    }

    // ============================
    // CREATE
    // ============================
    public function create()
    {
        return view('admin.category.create');
    }

    // ============================
    // STORE
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            'gender' => 'required|max:255|unique:categories,gender',
            'description' => 'nullable|string',
        ], [
            'gender.unique' => 'Genre sudah ada, tambahkan genre baru.',
            'gender.required' => 'Genre wajib diisi.',

        ]);

        Category::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // ============================
    // EDIT
    // ============================
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    // ============================
    // UPDATE
    // ============================
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'gender' => 'required|max:255|unique:categories,gender,' . $category->id,
            'description' => 'nullable|string',
        ], [
            'gender.unique' => 'Genre sudah ada, tambahkan genre baru.',
            'gender.required' => 'Genre wajib diisi.',
        ]);


        $category->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // ============================
    // DELETE
    // ============================
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }

    // ============================
    // TRASH (SOFT DELETE)
    // ============================
    public function trash()
    {
        //ORM yang digunakan terkait softdelete
        //onlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        //restore() -> mengembalikan data yang sudah dihapus (menghapus nilai tanggal pada delete_at)
        //forceDelete() -> menghapus data secara permanen, data dihilangkan bahkan dari databasenya
        $categoryTrash = Category::onlyTrashed()->get();
        return view('admin.category.trash', compact('categoryTrash'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);
        $category->restore();
        return redirect()->route('admin.categories.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $category = Category::onlyTrashed()->find($id);
        $category->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

    // ============================
    // EXPORT EXCEL
    // ============================
    public function export()
    {
        $fileName = 'data-Kategori.xlsx';
        return Excel::download(new CategoryExport, $fileName);
    }

    // datatables
    public function datatables()
    {
        $categories = Category::query();
        return DataTables::of($categories)
        ->addIndexColumn()
        ->addColumn('action', function($category){
            $btnEdit =  '<a href="' . route('admin.categories.edit', $category->id) . '" class="btn btn-primary">Edit</a>';
            $btnDelete = ' <form action="' . route('admin.categories.delete', $category->id) . '" method="POST" style="display: inline-block;">
                            ' . csrf_field() . method_field('DELETE') .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';
            return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns(['name', 'gender', 'action'])
        ->make(true);
    }
}
