@extends('layouts.ideastaff')

{{-- @php
    use Illuminate\Support\Facades\Storage;
@endphp --}}

@section('content')
    <div class="container py-4">

        <h2 class="mb-4 fw-bold">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>
            Staff Idea
        </h2>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- ALERT VOTE --}}
        @if (session('vote'))
            <div class="alert alert-success" id="notif-vote">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('vote') }}
            </div>
            <script>
                setTimeout(() => document.getElementById('notif-vote')?.remove(), 8000)
            </script>
        @endif

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif



        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif


        {{--  SUBMIT IDEA  --}}
        <div class="card shadow-sm mb-5 border-2">
            <div class="card-header bg-primary text-white">
                <strong>
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Submit New Idea
                </strong>
            </div>
            <div class="card-body">

                <form action="{{ route('idea.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Title
                        </label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-exclamation-diamond me-1 text-danger"></i> Problem
                        </label>
                        <textarea name="problem" class="form-control summernote" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tools me-1 text-success"></i> Solution
                        </label>
                        <textarea name="solution" class="form-control summernote" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-graph-up-arrow me-1 text-info"></i> Impact (Optional)
                        </label>
                        <textarea name="impact" class="form-control summernote" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-paperclip me-1"></i> Attachment (Optional)
                        </label>
                        <input type="file" name="attachment" class="form-control">
                        <small class="text-muted">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            PDF, DOC, PPT (Max 50MB)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-camera-video me-1"></i> Demo Video (Optional)
                        </label>
                        <input type="file" name="demo_video" class="form-control">
                        <small class="text-muted">
                            <i class="bi bi-film me-1"></i>
                            MP4, MOV, AVI (Max 200MB)
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send-fill me-1"></i>
                        Submit Idea
                    </button>
                </form>
            </div>
        </div>



        {{-- MY IDEAS --}}
        <div class="card shadow-sm mb-5 border-2">
            <div class="card-header bg-info text-white">
                <strong>My Ideas</strong>
            </div>
            <div class="card-body">

                @forelse($myIdeas as $idea)
                    <div class="border rounded p-3 mb-3">

                        <h5>{{ $idea->title }}</h5>

                        <div>{!! $idea->problem !!}</div>
                        <div>{!! $idea->solution !!}</div>

                        @if ($idea->impact)
                            <div>{!! $idea->impact !!}</div>
                        @endif

                        <div class="mb-2">
                            <small class="text-muted">
                                Submitted {{ $idea->created_at->format('d M Y H:i A') }}
                            </small>
                        </div>

                        <button type="button" onclick="location.href='{{ route('idea.result', $idea) }}'"
                            class="btn btn-sm btn-info">
                            View Result
                        </button>

                        <div class="d-flex flex-wrap mt-2" style="gap: 16px;">

                            {{-- ATTACHMENT --}}
                            @if ($idea->attachment)
                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                    data-target="#attachmentModal{{ $idea->id }}">
                                    <i class="bi bi-eye me-1"></i>
                                    View Attachment
                                </button>

                                <a href="{{ route('idea.download', [$idea, 'download']) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download me-1"></i>
                                    Download
                                </a>

                                {{-- MODAL ATTACHMENT --}}
                                <div class="modal fade" id="attachmentModal{{ $idea->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="attachmentModalLabel{{ $idea->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="attachmentModalLabel{{ $idea->id }}">
                                                    Attachment Preview
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @php
                                                    $fileUrl = Storage::url($idea->attachment);
                                                    $ext = strtolower(pathinfo($idea->attachment, PATHINFO_EXTENSION));
                                                @endphp

                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <img src="{{ $fileUrl }}" class="img-fluid rounded">
                                                @elseif($ext === 'pdf')
                                                    <iframe src="{{ $fileUrl }}" width="100%" height="600"
                                                        style="border:none;"></iframe>
                                                @elseif(in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']))
                                                    <p class="text-muted">
                                                        Dokumen {{ strtoupper($ext) }} tidak bisa di-preview di localhost.
                                                    </p>
                                                    <a href="{{ $fileUrl }}" class="btn btn-primary" download>
                                                        <i class="bi bi-download"></i> Download untuk Melihat
                                                    </a>
                                                    <hr>
                                                    <small class="text-muted">Preview online (HTTPS server publik):</small>
                                                    <iframe
                                                        src="https://view.officeapps.live.com/op/view.aspx?src={{ urlencode(asset('storage/' . $idea->attachment)) }}"
                                                        width="100%" height="400" frameborder="0"></iframe>
                                                @else
                                                    <p class="text-warning">Format file tidak didukung.</p>
                                                    <a href="{{ $fileUrl }}" class="btn btn-warning" download>
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- DEMO VIDEO --}}
                            @if ($idea->demo_video)
                                <a href="{{ asset('storage/' . $idea->demo_video) }}" target="_blank"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-play-circle me-1"></i> Demo Video
                                </a>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="alert alert-light">No ideas yet</div>
                @endforelse

            </div>
        </div>


        {{-- VOTING IDEAS --}}
        <div class="card shadow-sm border-2">
            <div class="card-header bg-warning">
                <strong>Ideas in Voting Phase</strong>
            </div>
            <div class="card-body">

                @forelse($votingIdeas as $idea)
                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between">

                            <h6>{{ $idea->title }}</h6>

                            <form action="{{ route('idea.vote', $idea->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-warning btn-sm">Vote</button>
                            </form>

                        </div>

                        <div>{!! $idea->problem !!}</div>
                        <div>{!! $idea->solution !!}</div>

                        @if ($idea->impact)
                            <div>{!! $idea->impact !!}</div>
                        @endif

                        <small>Total Votes: {{ $idea->votes_count }}</small>

                        {{-- my button preview result --}}
                        <div class="d-flex flex-wrap mt-3" style="gap: 16px;">

                            {{-- @if ($idea->attachment)
                                <a href="{{ route('idea.download', $idea) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye mr-1"></i>
                                    View Attachment
                                </a>

                                <a href="{{ route('idea.download', [$idea, 'download']) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download mr-1"></i>
                                    Download
                                </a>
                            @endif --}}

                            @if ($idea->attachment)
                                @php
                                    $fileUrl = Storage::url($idea->attachment);
                                    $ext = strtolower(pathinfo($idea->attachment, PATHINFO_EXTENSION));
                                @endphp

                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                    data-target="#attachmentModal{{ $idea->id }}">
                                    <i class="bi bi-eye me-1"></i> View Attachment
                                </button>

                                <div class="modal fade" id="attachmentModal{{ $idea->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="attachmentModalLabel{{ $idea->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Attachment Preview</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="carousel{{ $idea->id }}" class="carousel slide"
                                                    data-ride="carousel">
                                                    <div class="carousel-inner text-center">
                                                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                            <div class="carousel-item active">
                                                                <img src="{{ $fileUrl }}"
                                                                    class="img-fluid rounded mx-auto d-block">
                                                            </div>
                                                        @elseif($ext === 'pdf')
                                                            {{-- PDF hanya tampil 1 halaman, carousel bisa dikembangkan pakai PDF.js --}}
                                                            <div class="carousel-item active">
                                                                <iframe src="{{ $fileUrl }}" width="100%"
                                                                    height="600" style="border:none;"></iframe>
                                                            </div>
                                                        @else
                                                            <div class="carousel-item active">
                                                                <p class="text-muted">Preview hanya tersedia untuk gambar &
                                                                    PDF.</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <a class="carousel-control-prev"
                                                            href="#carousel{{ $idea->id }}" role="button"
                                                            data-slide="prev">
                                                            <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next"
                                                            href="#carousel{{ $idea->id }}" role="button"
                                                            data-slide="next">
                                                            <span class="carousel-control-next-icon"
                                                                aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($idea->demo_video)
                                <a href="{{ asset('storage/' . $idea->demo_video) }}" target="_blank"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-play-circle mr-1"></i>
                                    Demo Video
                                </a>
                            @endif

                        </div>

                    </div>
                @empty
                    <div class="alert alert-light">No voting ideas</div>
                @endforelse

            </div>
        </div>


        <div class="text-center my-3">
            <form action="{{ route('idea.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection
