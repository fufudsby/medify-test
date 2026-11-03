<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <select name="categories[]" id="categories" class="form-control" multiple>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ $item->categories->contains('id', $category->id) ? 'selected' : '' }}
                >
                    {{ $category->kode }} - {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="foto">Foto Item</label>
        <div class="d-flex">
                <div class="mx-2">
                    <img
                        id="img_preview"
                        src="{{ !empty($item['foto']) ? asset('storage/' . $item['foto']) : '' }}"
                        alt="img"
                        width="200"
                        style="{{ !empty($item['foto']) ? '' : 'display: none;' }}; margin-top: 10px; object-fit: cover;"
                    >
                </div>
            <div class="flex-grow-1">
                <input
                    type="file"
                    name="foto"
                    id="foto"
                    class="form-control"
                    accept="image/*"
                >
            </div>
        </div>
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>

<script>
    document.getElementById('foto').addEventListener('change', function (event) {
        const preview_foto = document.getElementById('img_preview');
        const file = event.target.files[0];

        if (file) {
            preview_foto.src = URL.createObjectURL(file);
            preview_foto.style.display = 'block';
        }
    });
</script>