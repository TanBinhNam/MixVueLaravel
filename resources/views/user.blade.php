@extends('layout')
@push('css')    
<style>
    .title{
        font-weight: bold;
        
    }
    .icon{
        border: 1px solid white;
        
    }
    .VuePagination__count {
       font-size: 0;
    }

</style>
@endpush

@section('content')
@include('layout.menu')
<div class="container-fluid p-0" id="app">

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
        editor.ui.view.editable.element.style.height = '300px';
        } )
        .catch( error => {
            console.error( error );
        } );
</script>
@endpush
