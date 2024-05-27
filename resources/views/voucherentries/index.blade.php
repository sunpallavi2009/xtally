<x-app-layout>
    <div x-data="voucherentries">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Voucher Entry Details') }}
                </h2>
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">
                        <table id="voucherEntry-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SR. NO.</th>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            $(document).ready(function() {
                var table = $('#voucherEntry-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('voucherEntry.get-data') }}",
                        data: function(d) {
                            d.voucher_id = "{{ request()->query('voucher_id') }}";
                        }
                    },
                    columns: [
                        {data: 'id'},
                        {data: 'ledger'},
                        {data: 'amount'},
                    ]
                });
            });
        </script>
        @endpush
    </div>
</x-app-layout>
