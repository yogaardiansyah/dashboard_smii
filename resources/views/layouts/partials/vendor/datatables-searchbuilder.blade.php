@once
    @include('layouts.partials.vendor.datatables')
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/dataTables.dateTime-1.5.2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor-css/searchBuilder.dataTables-1.7.1.css') }}">
        <style>
            .dtsb-title {
                display: none !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/vendor-js/dataTables.dateTime-1.5.4.min.js') }}"></script>
        <script src="{{ asset('assets/vendor-js/dataTables.searchBuilder-1.8.1.js') }}"></script>
        <script src="{{ asset('assets/vendor-js/searchBuilder.dataTables-1.8.1.js') }}"></script>
    @endpush
@endonce
