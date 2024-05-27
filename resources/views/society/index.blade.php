<x-app-layout>
    <div x-data="society">

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Society') }}
                    </h2>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                        {{-- <a href="{{ route('roles.create') }}">{{ __('Add Role') }}</a> --}}
                    </h2>
                </div>
            </div>
        </header>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">

                        <table id="society-datatable" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>SR. NO.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>website</th>
                                <th>Company Number</th>
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
                table = $('#society-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('society.get-data') }}",
                        data: function(d) {
                            
                        }
                    },
                    columns: [
                        {data: 'id'},
                        // {data: 'name'},
                        {
                            data: 'name',
                            render: function(data, type, row, meta) {
                                var url = "{{ route('members.index') }}?guid=" + row.guid;
                                return '<a href="' + url + '">' + data + '</a>';
                            }
                        },
                        {data: 'address1'},
                        {data: 'mobile_number'},
                        {data: 'website'},
                        {data: 'company_number'},
                    ]
                });

                // $('#society-datatable tbody').on('click', 'td:nth-child(2)', function () {
                //     var data = table.row($(this).closest('tr')).data();
                //     var societyGuid = data['guid']; // Assuming 'guid' is the society GUID column
                //     // Redirect to the member page passing the society GUID
                //     window.location.href = "{{ route('members.index') }}?society_guid=" + societyGuid;
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
