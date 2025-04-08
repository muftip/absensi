@if (Session::get('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
@else
    <div class="alert alert-danger">
        Anda tidak memiliki akses!
    </div>
@endif
