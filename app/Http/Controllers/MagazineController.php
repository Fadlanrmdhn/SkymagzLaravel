<?php

namespace App\Http\Controllers;

use App\Models\Magazine;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MagazineExport;
use App\Exports\BookExport;
use Yajra\DataTables\Facades\DataTables;

class MagazineController extends Controller
{
    // ============================
    // ADMIN DASHBOARD
    // ============================

    public function index()
    {
        $magazines = Magazine::with('categories')->get();

        return view('admin.magazine.index', compact('magazines'));
    }


    public function bookIndex()
    {
        $books = Magazine::with('categories')
            ->where('type', 'buku')
            ->get();

        return view('admin.book.index', compact('books'));
    }


    // ============================
    // HALAMAN DEPAN (USER)
    // ============================

    public function home()
    {
        $magazines = Magazine::where('type', 'majalah')->latest()->limit(15)->get();
        $books = Magazine::where('type', 'buku')->latest()->limit(15)->get();
        $categories = Category::latest()->get();

        return view('home', compact('magazines', 'books', 'categories'));
    }


    public function homeAllMagazines()
    {
        $magazines = Magazine::where('type', 'majalah')->latest()->get();
        return view('home_magazines', compact('magazines'));
    }

    public function homeAllBooks()
    {
        $books = Magazine::where('type', 'buku')->latest()->get();
        return view('home_books', compact('books'));
    }

    /**
     * Show books filtered by category (public page)
     */
    public function booksByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $books = Magazine::where('type', 'buku')
            ->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            })->latest()->get();

        return view('books.by_category', compact('books', 'category'));
    }

    /**
     * Show all books (public page)
     */
    public function allBooks()
    {
        $books = Magazine::where('type', 'buku')->latest()->get();

        return view('allbook', compact('books'));
    }

    /**
     * Show all magazines (public page)
     */
    public function allMagazines()
    {
        $magazines = Magazine::where('type', 'majalah')->latest()->get();

        return view('allmagazine', compact('magazines'));
    }

    public function show($id)
    {
        $magazine = Magazine::with('categories')->find($id);

        if ($magazine->type === 'majalah') {
            return view('detail.detail-magazine', compact('magazine'))->with('majalah', $magazine);
        } else {
            return view('detail.detail-book', compact('magazine'))->with('buku', $magazine);
        }
    }


    // ============================
    // CRUD (ADMIN)
    // ============================

    public function create(Request $request)
    {
        $type = $request->input('type');
        $categories = Category::all();

        if ($type === 'majalah') {
            return view('admin.magazine.create', compact('categories'));
        } else {
            return view('admin.book.create', compact('categories'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'author'        => 'required',
            'publisher'     => 'required',
            'type'          => 'required|in:majalah,buku',
            'cover'         => 'required|mimes:jpg,jpeg,png,webp,svg',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'release_date'  => 'required|date',
            'categories'    => 'nullable|array',
        ]);

        // upload cover
        $gambar = $request->file('cover');
        $namaGambar = Str::random(5) . "-cover." . $gambar->getClientOriginalExtension();
        $path = $gambar->storeAs('cover', $namaGambar, 'public');

        // buat data magazine
        $create = Magazine::create([
            'title'         => $request->title,
            'author'        => $request->author,
            'publisher'     => $request->publisher,
            'type'          => $request->type,
            'cover'         => $path,
            'description'   => $request->description,
            'price'         => $request->price,
            'release_date'  => $request->release_date,
        ]);

        // relasi kategori
        if ($request->has('categories')) {
            $create->categories()->sync($request->categories);
        }

        return redirect()->route($request->type === 'majalah' ? 'admin.magazines.index' : 'admin.books.index')->with('success', 'Berhasil membuat data!');
    }

    public function edit($id)
    {
        $magazine = Magazine::find($id);
        $categories = Category::all();

        if ($magazine->type === 'majalah') {
            return view('admin.magazine.edit', compact('magazine', 'categories'));
        } else {
            return view('admin.book.edit', compact('magazine', 'categories'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required',
            'author'        => 'required',
            'publisher'     => 'required',
            'type'          => 'required|in:majalah,buku',
            'cover'         => 'nullable|mimes:jpg,jpeg,png,webp,svg',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'release_date'  => 'required|date',
            'categories'    => 'nullable|array',
        ]);

        $magazine = Magazine::find($id);

        // jika ada cover baru, hapus yang lama
        if ($request->file('cover')) {
            $fileSebelumnya = storage_path('app/public/' . $magazine['cover']);
            if (file_exists($fileSebelumnya)) {
                unlink($fileSebelumnya);
            }

            $gambar = $request->file('cover');
            $namaGambar = Str::random(5) . "-cover." . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('cover', $namaGambar, 'public');
        }

        $magazine->update([
            'title'         => $request->title,
            'author'        => $request->author,
            'publisher'     => $request->publisher,
            'type'          => $request->type,
            'cover'         => $path ?? $magazine['cover'],
            'description'   => $request->description,
            'price'         => $request->price,
            'release_date'  => $request->release_date,
        ]);

        // update kategori
        if ($request->has('categories')) {
            $magazine->categories()->sync($request->categories);
        } else {
            $magazine->categories()->detach();
        }

        $route = $request->type === 'majalah' ? 'admin.magazines.index' : 'admin.books.index';
        return redirect()->route($route)->with('success', 'Berhasil memperbarui data!');
    }

    public function destroy($id)
    {
        $magazine = Magazine::find($id);
        $type = $magazine->type;

        // hapus cover jika ada
        if ($magazine->cover && Storage::disk('public')->exists($magazine->cover)) {
            Storage::disk('public')->delete($magazine->cover);
        }

        // hapus relasi pivot
        $magazine->categories()->detach();
        $magazine->delete();

        return redirect()
            ->route($type === 'majalah' ? 'admin.magazines.index' : 'admin.books.index')
            ->with('success', 'Berhasil menghapus data!');
    }

    // ============================
    // EXPORT
    // ============================

    public function export(Request $request)
    {
        $type = $request->input('type');

        if ($type === 'majalah') {
            return Excel::download(new MagazineExport, 'data-Majalah.xlsx');
        } elseif ($type === 'buku') {
            return Excel::download(new BookExport, 'data-Buku.xlsx');
        } else {
            return back()->with('error', 'Tipe tidak diketahui!');
        }
    }

    // ============================
    // TRASH / RESTORE
    // ============================

    public function trash()
    {
        $type = request()->input('type');

        if ($type === 'buku') {
            $bookTrash = Magazine::onlyTrashed()->where('type', 'buku')->get();
            return view('admin.book.trash', compact('bookTrash'));
        } else {
            $magazineTrash = Magazine::onlyTrashed()->where('type', 'majalah')->get();
            return view('admin.magazine.trash', compact('magazineTrash'));
        }
    }

    public function restore($id)
    {
        $item = Magazine::onlyTrashed()->find($id);
        $item->restore();

        $route = $item->type === 'majalah' ? 'admin.magazines.index' : 'admin.books.index';
        return redirect()->route($route)->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $item = Magazine::onlyTrashed()->find($id);
        $type = $item->type;
        $item->forceDelete();

        $route = $type === 'majalah' ? 'admin.magazines.index' : 'admin.books.index';
        return redirect()->route($route)->with('success', 'Berhasil menghapus data secara permanen!');
    }

    //datatables
    public function datatables($type)
    {
        $query = Magazine::query()->where('type', $type);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('cover_img', function ($magazine) {
                $url = asset('storage/' . $magazine->cover);
                return '<img src="' . $url . '" width="70">';
            })
            ->addColumn('price', function ($magazine) {
                return 'Rp. ' . number_format($magazine->price, 0, ',', '.');
            })
            ->addColumn('action', function ($magazine) {
                $btnDetail = '<button type="button" class="btn btn-secondary" onclick=\'showModal(' . json_encode($magazine) . ')\'>Detail</button>';
                $btnEdit = '<a href="' . route('admin.magazines.edit', $magazine->id) . '" class="btn btn-primary">Edit</a>';
                $btnDelete = '<form action="' . route('admin.magazines.delete', $magazine->id) . '" method="POST" style="display:inline-block">'
                    . csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger">Hapus</button></form>';

                return '<div class="d-flex gap-2">' . $btnDetail . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['cover_img', 'action'])
            ->make(true);
    }
}
