@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <link rel="stylesheet" href="{{ asset('css/list_news1.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />



    <div class="container mt-4">
        <h2 class="text-center mb-4">All News</h2>

        <div class="news-grid">
            @foreach ($news as $item)
                <a href="#" class="news-card" data-title="{{ $item->title }}" data-content="{{ $item->content }}"
                    data-thumb="{{ asset('storage/' . $item->thumbnail) }}">

                    <div class="thumb-wrapper">
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}">
                    </div>

                    <div class="news-content">
                        <h4 class="news-title">
                            {{ $item->title }}
                        </h4>

                        <p class="news-excerpt">
                            {{ Str::limit(strip_tags($item->content), 200) }}
                        </p>
                        <span class="read-more-hover">Read More →</span>
                    </div>

                </a>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $news->links() }}
        </div>
    </div>


    <!-- ==============================MODAL BOOTSTRAP=============================== -->
    <div class="modal fade" id="readMoreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-dialog-a4">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="readMoreModalLabel"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modalContent"></div>

            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('news.logout') }}" class="btn-soft mt-3">
            <i class="bi bi-house-down"></i> Home
        </a>
    </div>


    <!-- ==============================SCRIPT POPUP=============================== -->
    <script>
        document.querySelectorAll('.news-card').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();

                const thumb = this.getAttribute('data-thumb');
                const title = this.getAttribute('data-title');
                const content = this.getAttribute('data-content');

                document.getElementById('readMoreModalLabel').textContent = title;
                document.getElementById('readMoreModalLabel').style.color = '#073362';


                document.getElementById('modalContent').innerHTML = `
                    <img src="${thumb}" class="img-fluid mb-3 rounded">
                ${content}
                `;

                const modal = new bootstrap.Modal(document.getElementById('readMoreModal'));
                modal.show();
            });
        });
    </script>
@endsection
