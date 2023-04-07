@extends('dashboard.layouts.main')


@section('head-script')
    <!-- Text Editor -->
    <script src="https://cdn.tiny.cloud/1/u5yv80sn31alf3o4asjhm5d8zpe5dgof1hastir594bi2xes/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
@endsection


@section('style')
    <style>
        #cover-preview-update {
            max-width: 60%;
            width: 60%;
            height: auto;
            object-fit: cover;
            aspect-ratio: 16 / 9;
            cursor: pointer;
        }

        #cover-preview-update.dragging {
            background-color: #0c9ce9;
        }

        @media (max-width: 767.98px) {
            #cover-preview-update {
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
@endsection


@section('main')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3>Edit Courses</h3>
        <div class="d-grid gap-2 d-flex">
            <a href="{{ route('courses.index') }}" class="btn btn-sm btn-warning">
                <i class="ti ti-arrow-back-up"></i> Back
            </a>
            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-sm btn-primary">
                <i class="ti ti-eye"></i> Preview
            </a>
            <form action="{{ route('courses.destroy', $course->slug) }}" method="post">
                @csrf
                @method('delete')
                <button id="delete" class="btn btn-sm btn-danger delete-course-btn">
                    <i class="ti ti-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
    <form action="{{ route('courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="mt-2">
            <label for="title" class="form-label">Title</label>
            <input value="{{ old('title', $course->title) }}" type="text"
                class="form-control @error('title') is-invalid @enderror" name="title" id="title"
                placeholder="Course apa yang ingin anda tambahkan?" required autofocus>
        </div>
        @error('title')
            <div class="text-danger text-start" style="font-size: 14px">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-4">
            <label class="form-label">Slug</label>
            <input value="{{ old('slug', $course->slug) }}" type="text"
                class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug"
                placeholder="Slug akan terisi otomatis sesuai judul course yang anda masukan." readonly>
        </div>
        @error('slug')
            <div class="text-danger text-start" style="font-size: 14px">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-4">
            <label for="desc" class="form-label">Description</label>
            <textarea rows="8" class="form-control @error('desc') is-invalid @enderror" name="desc" id="desc"
                placeholder="Apa yang akan dipelajari di course ini?" required>{{ old('desc', $course->desc) }}</textarea>
        </div>
        @error('desc')
            <div class="text-danger text-start" style="font-size: 14px">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-4">
            <label for="cover" class="form-label d-block">Cover</label>
            <img id="cover-preview-update" class="mb-2 img-thumbnail img-fluid" src="{{ $course->cover }}" old-src="{{ $course->cover }}" alt="cover preview">
            <p> Ukuran file maksimal <span class="badge text-bg-dark">5Mb</span>
                dan format gambar yang didukung:
                <span class="badge text-bg-primary">PNG</span>
                <span class="badge text-bg-secondary">JPG</span>
                <span class="badge text-bg-success">JPEG</span>
                <span class="badge text-bg-danger">GIF</span>
                <span class="badge text-bg-warning">JFIF</span>
                <span class="badge text-bg-info">WEBP</span>
            </p>
            <input id="cover-input" type="file" class="form-control @error('cover') is-invalid @enderror"
                name="cover" id="cover" accept="image/*">
        </div>
        @error('cover')
            <div class="text-danger text-start" style="font-size: 14px">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-4">
            <label for="body" class="form-label">Body</label>
            <textarea name="body" id="tinymce">{{ old('body', $course->body) }}</textarea>
        </div>
        @error('body')
            <div class="text-danger text-start" style="font-size: 14px">
                {{ $message }}
            </div>
        @enderror

        <div class="d-grid gap-2 d-flex justify-content-end mt-3">
            <button id="update-btn" class="btn btn-primary" type="submit" name="submit" value="done">Update Course</button>
            <button id="draft-btn" class="btn btn-warning" type="submit" name="submit" value="draft">Simpan Draft</button>
            <a href="{{ route('courses.index') }}" class="btn btn-danger">Cancel</a>
        </div>

    </form>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '#tinymce',
                height: 700,
                plugins: 'fullscreen anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tableofcontents footnotes autocorrect typography inlinecss preview insertdatetime',
                toolbar: 'fullscreen preview undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                file_picker_types: 'image media',
                image_caption: true,
                file_picker_callback: function(cb, value, meta) {
                    const input = $('<input/>')
                        .attr('type', 'file')
                        .attr('accept', 'image/*')
                        .on('change', function(e) {
                            const file = e.target.files[0];
                            const reader = new FileReader();
                            reader.onload = function() {
                                const id = 'blobid' + (new Date()).getTime();
                                const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                const base64 = reader.result.split(',')[1];
                                const blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                cb(blobInfo.blobUri(), {
                                    title: file.name
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    input.trigger('click');
                },
            });
            $('#title').on('change', function() {
                const title = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '/dashboard/courses/getSlug?title=' + title,
                    success: function(response) {
                        $('#slug').val(response);
                    }
                });
            });
        });
    </script>
@endsection
