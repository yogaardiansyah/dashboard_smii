@once
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor_components/datatables/datatables.min.css">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/dataTables.dataTables-2.1.8.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/dataTables.jqueryui-2.0.8.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/vendor-js/jquery.dataTables-1.13.4.min.js') }}"></script>
        <script src="{{ asset('assets') }}/vendor_components/datatables/datatables.min.js"></script>
        <script src="{{ asset('assets/vendor-js/dataTables.jqueryui-2.1.8.js') }}"></script>
    @endpush
@endonce
