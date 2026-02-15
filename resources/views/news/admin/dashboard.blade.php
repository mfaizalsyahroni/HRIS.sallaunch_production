@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/show_news.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <div class="container mt-4">
        <h3>Dashboard Admin</h3>

        <a href="{{ route('news.admin.create') }}" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i> Add News
        </a>

        {{-- Alert sukses --}}
        @if (session('message1'))
            <div class="notif-success" id="notification">
                {{ session('message1') }}
            </div>
            <script>
                setTimeout(() => document.getElementById('notification')?.remove(), 8000);
            </script>
        @endif

        <table class="visual">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($news as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td>
                            @if ($item->thumbnail)
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="Thumbnail"
                                    style="width:80px;height:60px;object-fit:cover;border-radius:6px;">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Edit --}}
                            <a href="{{ route('news.admin.edit', $item->id) }}" class="btn btn-secondary btn-sm"
                                title="Edit">
                                <i class="fa fa-pencil-alt"></i>
                            </a>

                            {{-- Tombol Delete --}}
                            <form action="{{ route('news.admin.delete', $item->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                    onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
