@extends('admin.templateAdmin.app')
@section('content')
    <div class="p-2">
        <nav class="navbar navbar-light bg-light rounded shadow-sm mb-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0">Book Management</span>
                <div class="d-flex align-items-center">
                    <span>Admin SkyMagz</span>
                </div>
            </div>
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Book</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="table-responsive">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <div class="d-flex justify-content-end mb-3 mt-4">
                <a href="{{ route('admin.books.trash', ['type' => 'buku']) }}" class="btn btn-secondary me-2">Data
                    Sampah</a>
                <a href="{{ route('admin.books.export', ['type' => 'buku']) }}" class="btn btn-secondary me-2">Export
                    (.xlsx)</a>
                <a href="{{ route('admin.books.create') }}" class="btn btn-success me-2">Tambah Data</a>
            </div>
            <table class="table table-bordered table-hover" id="magazinesTable">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($books as $key => $item)
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item['cover'])
                                    <img src="{{ asset('storage/' . $item['cover']) }}" width="120"
                                        class="img-thumbnail">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->author }}</td>
                            <td>{{ $item->publisher }}</td>
                            <td>Rp. {{ number_format($item['price'], 0, ',', ',') }}</td>
                            <td class="text-center">
                                {{-- onclick : menjalankan fungsi javascript ketika komponen di click --}}
                                <button class="btn btn-sm btn-secondary"
                                    onclick="showModal({{ $item }})">Detail</button>
                                <a href="{{ route('admin.books.edit', $item->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.books.delete', $item->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Buku</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modalDetailBody">
                            ...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showModal(item) {
            // console.log($item);

            //menghubungkan fungsi php asset, digabungkan dengan data yang diambil data
            let image = "{{ asset('storage/') }}" + "/" + item.cover;
            //format harga Intl.NumberFormat untuk format mata uang id-ID itu IDR untuk rupiah
            //minimumFractionDigits : jumlah digit dibelakang koma untuk pecahan desimal (0 = tidak ada)
            //format(item.price) : memformat angka (item.price) sesuai format diatas
            let price = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0
            }).format(item.price);
            //backtip (``) : membuat string yang bisa dienter
            let content =
                `
                <div class="d-flex mx-auto my-2 justify-content-center">
                    <img src="${image}" width="120" >
                </div>
                <ol>
                    <li>Judul : ${item.title}</li>
                    <li>Pembuat : ${item.author}</</li>
                    <li>Penerbit : ${item.publisher}</li>
                    <li>Harga : ${price}</li>
                    <li>Tanggal Dan Tahun Dibuat : ${item.release_date}</li>
                    <li>Sinopsis : ${item.description}</li>
                </ol>
            `;
            //memanggil variable pada tanda ``
            //memanggil element HTML yang akan disimpan konten diatas -> document.querySelector
            //innerHTML -> mengisi konten HTML
            document.querySelector("#modalDetailBody").innerHTML = content;
            //munculkan modal
            new bootstrap.Modal(document.querySelector("#modalDetail")).show();
        };

        $(function() {
            $('#magazinesTable').DataTable({
                processing: true,
                //data untuk datatable diproses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.magazines.datatables', 'buku') }}",
                //urutan column (td), pastikan urutan sesuai th
                //data: 'nama' -> nama diambil dari rawColumn jika addColumn, atau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cover_img',
                        name: 'cover_img',
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'author',
                        name: 'author',
                    },
                    {
                        data: 'publisher',
                        name: 'publisher',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        });
    </script>
@endpush
