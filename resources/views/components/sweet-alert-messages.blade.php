<div class="hidden" data-swal-messages='@json(__('sweetalert'))'></div>

@if (session('success'))
    <div class="hidden" data-swal-success="{{ session('success') }}"></div>
@endif

@if (session('error'))
    <div class="hidden" data-swal-error="{{ session('error') }}"></div>
@endif

@if ($errors->any())
    <div class="hidden" data-swal-error="{{ $errors->first() }}"></div>
@endif
