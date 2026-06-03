<div class="hidden" data-swal-messages='@json(__('sweetalert'))'></div>

@if (session('success'))
    <div class="hidden" data-swal-success="{{ session('success') }}"></div>
@endif
