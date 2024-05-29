<x-app-layout>
    <div x-data="members">

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Member') }}
                    </h2>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                        {{-- <a href="{{ route('roles.create') }}">{{ __('Add Role') }}</a> --}}
                    </h2>
                </div>
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm">
                    <div class="text-center p-6">
                        @foreach ($society as $company)
                            <h2><b>{{ $company->name }}</b></h2>
                            <h2>{{ $company->address1 }}</h>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table id="member-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SR. NO.</th>
                                    <th>Name</th>
                                    <th>Alias</th>
                                    <th>Parent</th>
                                    <th>Primary Group</th>
                                    <th>Balance</th>
                                    <th>Total Voucher</th>
                                    <th>First Entry</th>
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
            var table;
            
            $(document).ready(function() {
                table = $('#member-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('members.get-data') }}",
                        data: function(d) {
                            d.guid = "{{ $societyGuid }}";
                        }
                    },
                    columns: [
                        {data: 'id'},
                        {
                            data: 'name',
                            render: function(data, type, row, meta) {
                                var url = "{{ route('vouchers.index') }}?ledger_guid=" + row.guid;
                                return '<a href="' + url + '">' + data + '</a>';
                            }
                        },
                        {data: 'alias1'},
                        {data: 'parent'},
                        {data: 'primary_group'},
                        {data: 'this_year_balance'}, 
                        {data: 'vouchers_count'},
                        {data: 'first_voucher_date', name: 'first_voucher_date'},
                    ]
                });

                // $('#member-datatable tbody').on('click', 'td:nth-child(2)', function () {
                //     var data = table.row($(this).closest('tr')).data();
                //     var memberId = data['id']; // Assuming 'id' is the member ID column
                //     // Redirect to the vouchers page passing the member ID
                //     window.location.href = "{{ route('vouchers.index') }}?member_id=" + memberId;
                // });

                $('#clear-filters').click(function () {
                    $("#name").val('').trigger('change');
                    table.search('').draw();
                });
            });
        </script>

        @endpush

    </div>
</x-app-layout>
