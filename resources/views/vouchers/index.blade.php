<x-app-layout>
    <div x-data="vouchers">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Voucher Details') }}
                </h2>
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">

                        {{-- @foreach ($society as $company)
                            <h2>{{ $company->name }}</h2>
                            <h4>{{ $company->address1 }}</h4>
                        @endforeach --}}

                        <div>
                            <h2>{{ $society->name }}</h2>
                            <h2>{{ $society->address1 }}</h2>
                        </div>
                        

                    </div>
                        @foreach ($members as $ledger)
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-start">
                            {{ $ledger->name }}
                        </h2>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                            {{ $ledger->alias1 }}
                        </h2>
                        @endforeach
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">
                        <table id="voucher-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SR. NO.</th>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Voucher Type</th>
                                    <th>Voucher Number</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
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
                var table = $('#voucher-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('vouchers.get-data') }}",
                        data: function(d) {
                            d.ledger_guid = "{{ request()->query('ledger_guid') }}";
                        }
                    },
                    columns: [
                        {data: 'id'},
                        {data: 'voucher_date'},
                        {
                            data: 'account_link',
                            name: 'account_link'
                        },
                        {data: 'type'},
                        {data: 'voucher_number'},
                        {data: 'debit'},
                        {data: 'credit'},
                        {data: 'balance_amount'},
                    ]
                });
            });
        </script>
        @endpush
    </div>
</x-app-layout>
