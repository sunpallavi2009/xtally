<x-app-layout>
    <div x-data="roles">

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Roles') }}
                    </h2>

                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right">
                        <a href="{{ route('roles.create') }}">{{ __('Add Role') }}</a>
                    </h2>
                </div>
            </div>
        </header>



        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">

                        <table id="roles-datatable" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>SR. NO.</th>
                                <th>Name</th>
                                {{-- <th>Action</th> --}}
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
                table = $('#roles-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('roles.get-data') }}",
                        data: function(d) {

                        }
                    },
                    columns: [
                        {data: 'id'},
                        {data: 'name'},

                        // {data: 'actions', "render": function ( data, type, row ) {

                        //     var output = '<div>';

                        //      output +=  '<a href="/roles/'+row.id+'+/destroy" class="pr-2">Delete</a>';

                        //      output += '<a href="#" @click.prevent="editRole('+row.id+')">Edit</a>';

                        //      output += '<div>';

                        //         return output;


                        // }}

                    ]
                });


                $('#clear-filters').click(function () {
                    $("#name").val('').trigger('change');
                    table.search('').draw();
                });


            });
        </script>


        @endpush

    </div>

</x-app-layout>
