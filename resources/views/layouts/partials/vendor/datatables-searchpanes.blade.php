@once
    @include('layouts.partials.vendor.datatables')
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/select.dataTables-2.1.0.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/select.jqueryui-2.0.3.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/searchPanes.dataTables-2.3.3.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/searchPanes.jqueryui-2.3.1.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/vendor-js/dataTables.select-2.1.0.js') }}"></script>
        <script src="{{ asset('assets/vendor-js/select.jqueryui-2.1.0.js') }}"></script>
        <script src="{{ asset('assets/vendor-js/dataTables.searchPanes-2.3.3.js') }}"></script>
        <script src="{{ asset('assets/vendor-js/searchPanes.jqueryui-2.3.3.js') }}"></script>
    @endpush
@endonce
