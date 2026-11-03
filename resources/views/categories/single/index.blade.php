 @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('categories') }}" class="btn btn-secondary">
                    Kembali ke Daftar Kategori
                </a>
            </div>

            <div class="card">
                <div class="card-header">Kategori</div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="100">Kode</th>
                            <td width="10">:</td>
                            <td>{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h5 class="mb-3">Master Items</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga Beli</th>
                                    <th>Supplier</th>
                                    <th>Jenis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data->masterItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>{{ $item->supplier }}</td>
                                        <td>{{ $item->jenis }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Belum ada Master Item pada kategori ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <a class="btn btn-info" href="{{ url('categories/form/edit/' . $data->id) }}">
                        Edit
                    </a>

                    <a class="btn btn-danger"
                       href="{{ url('categories/delete/' . $data->id) }}"
                       onclick="return confirm('Are you sure you want to delete this item?');">
                        Delete
                    </a>
                    <a class="btn btn-success"
                    href="{{ route('categories.pdf', $data->kode) }}">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection