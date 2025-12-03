@extends('templateUser.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.promos.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Sampah Promo</h3>
        <table class="table my-3 table-bordered">
            <tr>
                <th></th>
                <th class="text-center">Kode Promo</th>
                <th class="text-center">Diskon</th>
                <th class="text-center">Tipe</th>
                <th class="text-center">Aksi</th>
            </tr>
            @foreach ($promoTrash as $key => $promo)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    {{-- ambil detail data relasi dari with() $item['namarelasi'] ['data'] --}}
                    <td>{{ $promo['promo_code'] }}</td>
                    <td>
                        @if ($promo['type'] == 'rupiah')
                            <span>Rp. {{ number_format($promo['discount'], 0, ',', ',') }}</span>
                        @else
                            <span>{{ $promo['discount'] }} %</span>
                        @endif
                    </td>
                    <td>{{ $promo['type'] }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <form action="{{ route('admin.promos.restore', $promo['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.promos.delete_permanent', $promo['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
