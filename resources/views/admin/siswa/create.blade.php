<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
</head>
<body>
    <h1>Tambah Siswa</h1>
    <a href="{{ route('siswa.index') }}">Kembali</a> <br><br>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf 
        <h2>Akun Siswa</h2>
        <label for="">Nama Lengkap</label><br>
        <input type="text" id="name" name="name" value="{{ old('name') }}">
        <br><br>
        <label for="">Email Address</label><br>
        <input type="email" id="email" name="email" value="{{ old('email') }}">
        <br><br>
        <label for="">Password</label><br>
        <input type="password" id="password" name="password">
        <br><br>
        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
        <div class="col-md-6">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <br><br>

        <h2>Data Siswa</h2>
        <label for="">Foto Siswa</label><br>
        <input type="file" name="image" accept="image/*" required>
        <br><br>
        <label for="">NIS</label><br>
        <input type="text" name="nis" value="{{ old('nis') }}" required>
        <br><br>
        <label for="">Tingkatan</label><br>
        <select name="tingkatan" required>
            <option value="">Pilih Tingkatan</option>
            <option value="x">X</option>
            <option value="xi">XI</option>
            <option value="xii">XII</option>
        </select>
        <br><br>
        <label for="">Jurusan</label><br>
        <select name="jurusan" required>
            <option value="">Pilih Jurusan</option>
            <option value="pplg">PPLG</option>
            <option value="tjkt">TJKT</option>
            <option value="tbsm">TBSM</option>
            <option value="dkv">DKV</option>
            <option value="toi">TOI</option>
        </select>
        <br><br>

        <label for="">Kelas</label><br>
        <select name="kelas" required>
            <option value="">Pilih Kelas</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <br><br>

        <label for="">No HP</label><br>
        <input type="text" name="hp" value="{{ old('hp') }}" required>
        <br><br>

        <button type="submit">SIMPAN DATA</button>
        <button type="reset">RESET FORM</button>
    </form>
</body>
</html>