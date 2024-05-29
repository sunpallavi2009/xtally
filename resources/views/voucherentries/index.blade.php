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
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="text-center p-6">
                        @if($vouchers->isNotEmpty())
                            @foreach ($vouchers as $voucher)
                                <h2><b>{{ $voucher->type }}</b></h2>
                            @endforeach
                        @else
                            <h2>No Vouchers Found</h2>
                        @endif

                        @if($society)
                            <h2><b>{{ $society->name }}</b></h2>
                            <h2>{{ $society->address1 }}</h2>
                        @else
                            <h2>No Society Information Found</h2>
                        @endif

                        @if($vouchers->isNotEmpty())
                            @foreach ($vouchers as $voucher)
                                <h2>{{ $voucher->credit_ledger }}</h2>
                            @endforeach
                        @endif

                        @if($members)
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-start">
                                    {{ $members->name }}
                                </h2>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                                    {{ $members->alias1 }}
                                </h2>
                            </div>
                        @else
                            <h2>No Member Information Found</h2>
                        @endif
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
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
