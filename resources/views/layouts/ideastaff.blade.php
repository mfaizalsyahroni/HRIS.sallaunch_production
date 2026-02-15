<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .note-editor .dropdown-menu {
            z-index: 9999 !important;
        }

        .note-modal {
            z-index: 9999 !important;
        }

        .note-popover {
            z-index: 9999 !important;
        }

        .note-editor.note-frame {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .note-toolbar {
            background: #f8f9fa;
        }

        .note-editable {
            font-family: Times New Roman, Calibri, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            padding: 20px;
        }
    </style>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Summernote CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
    @stack('styles')
</head>

<body>

    <div class="container py-4">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

    {{-- jQuery --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    {{-- Bootstrap JS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

    {{-- Summernote --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script> --}}

    {{-- Global Summernote Init --}}
    <script>
        //FUNCTION PER-UPLOADAN
        function uploadFile(file, type) {

            let data = new FormData();
            data.append("file", file);
            data.append("type", type);

            $.ajax({
                url: "/editor/upload",
                method: "POST",
                data: data,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(response) {

                    if (type === 'image') {
                        $('.summernote').summernote('insertImage', response.url);
                    }

                    if (type === 'video') {
                        let videoHtml = `
                        <video controls width="100%">
                            <source src="${response.url}" type="video/mp4">
                        </video>
                    `;
                        $('.summernote').summernote('pasteHTML', videoHtml);
                    }

                },

                error: function(err) {
                    alert("Upload gagal!");
                    console.log(err);
                }
            });
        }
        //FUNCTION PER-UPLOADAN


        //Custom Button Upload
        $.summernote.plugins = $.summernote.plugins || {};

        $.extend($.summernote.plugins, {
            'customVideo': function(context) {
                var ui = $.summernote.ui;

                context.memo('button.customVideo', function() {
                    return ui.button({
                        contents: '<i class="fa fa-film"/>',
                        tooltip: 'Upload Video',
                        click: function() {

                            let input = $(
                                '<input type="file" accept="video/mp4,video/webm">');
                            input.click();

                            input.on('change', function() {
                                let file = this.files[0];
                                uploadFile(file, 'video');
                            });

                        }
                    }).render();
                });
            }
        });

        //Custom Button Upload


        $(function() {
            $('.summernote').summernote({

                height: 150,
                minHeight: 150,
                maxHeight: null,
                focus: false,
                tabsize: 2,
                dialogsInBody: true,

                placeholder: 'Write your idea in detail here...',

                toolbar: [
                    ['history', ['undo', 'redo']],
                    ['style', ['style']],
                    ['font', [
                        'bold', 'italic', 'underline', 'strikethrough',
                        'superscript', 'subscript', 'clear'
                    ]],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', [
                        'ul', 'ol', 'paragraph',
                        'height'
                    ]],
                    ['insert', [
                        'link', 'picture', 'video',
                        'table', 'hr'
                    ]],
                    ['view', [
                        'fullscreen', 'codeview', 'help'
                    ]]
                ],

                fontNames: [
                    'Arial',
                    'Arial Black',
                    'Calibri',
                    'Cambria',
                    'Comic Sans MS',
                    'Courier New',
                    'Georgia',
                    'Helvetica',
                    'Impact',
                    'Tahoma',
                    'Times New Roman',
                    'Trebuchet MS',
                    'Verdana'
                ],

                fontSizes: [
                    '8', '9', '10', '11', '12', '14', '16', '18',
                    '20', '22', '24', '26', '28', '32', '36', '48', '72'
                ],

                styleTags: [
                    'p',
                    {
                        title: 'Heading 1',
                        tag: 'h1',
                        className: 'fw-bold'
                    },
                    {
                        title: 'Heading 2',
                        tag: 'h2',
                        className: 'fw-bold'
                    },
                    {
                        title: 'Heading 3',
                        tag: 'h3',
                        className: 'fw-bold'
                    },
                    {
                        title: 'Heading 4',
                        tag: 'h4'
                    },
                    {
                        title: 'Blockquote',
                        tag: 'blockquote',
                        className: 'blockquote'
                    },
                    {
                        title: 'Code Block',
                        tag: 'pre',
                        className: 'bg-light p-2 rounded'
                    }
                ],

                popover: {
                    image: [
                        ['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    table: [
                        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
                    ]
                },

                callbacks: {

                    onInit: function() {
                        console.log('Summernote loaded successfully');
                    },

                    onChange: function(contents) {
                        console.log('Content changed');
                    },

                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadFile(files[i], 'image');
                        }
                    },

                    onMediaDelete: function(target) {
                        console.log('Media deleted:', target[0].src);
                        // Bisa tambahkan ajax hapus file di server jika perlu
                    }
                }

            });
        });
    </script>

    @stack('scripts')
</body>

</html>
