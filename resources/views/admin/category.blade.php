@extends('layouts.apps')

@section('content')
    <div class="row">
        <div class="col-12">

            <!-- Modal Tambah Kategori -->
            <div class="modal fade" id="modal-lg">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="category-form" method="POST" action="{{ route('categoryStore') }}">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal-title">Tambah Kategori</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nama Kategori</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" id="is_default" name="is_default">
                                    <label for="is_default">Set sebagai kategori default</label>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Kategori -->
            <button style="border-radius: 20px" type="button" class="btn btn-default" data-toggle="modal"
                data-target="#modal-lg">
                Tambah Category
            </button>

            <!-- DataTable Kategori -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">DataTable Category</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-warning btn-sm"data-toggle="modal"
                                            data-target="#editCategoryModal">Edit</button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('categoryDestroy', $category->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Edit Modal Category --}}
            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editUserForm"
                            method="POST" action="{{ isset($category) ? route('categoryUpdate', ['category' => $category->id]) : '' }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="editUserId" name="id">
                                <div class="form-group">
                                    <label for="editName">Category</label>
                                    <input type="text" class="form-control" id="editCategory" name="name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
