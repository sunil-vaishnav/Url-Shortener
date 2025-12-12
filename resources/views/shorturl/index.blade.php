<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Short Urls
        </h2>
    </x-slot>
    <br>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if(auth()->user()->userRole->name == 'Admin' || auth()->user()->userRole->name == 'Member')
                    <a href="{{ url('shorturls/add') }}" class="btn btn-primary float-sm-right float-end mb-5">+ Create Short Url</a>
                @endif
                
                <div class="mt-5">
                    @if(session('success')) 
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error')) 
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <table id="shorturls" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">S.No</th>
                                <th>Company</th>
                                <th>Created By</th>
                                <th>Original Url</th>
                                <th>Short Code</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot class="custom-header">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tfoot>
                            <tr>
                                <th width="5%">S.No</th>
                                <th>Company</th>
                                <th>Created By</th>
                                <th>Original Url</th>
                                <th>Short Code</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
            </div>
        </div>
    </div>

    {{-- DataTable Script --}}
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#shorturls').DataTable( {
                "pageLength": 10,
                "lengthMenu": [[-1 , 500, 100, 50, 25, 10, 5 , 1], ['All', 500, 100, 50, 25, 10, 5 , 1]],
                "processing": true,
                "serverSide": true,
                "dom": 'ltipr',
                "order": [[ 2, "desc" ]],
                "ajax": "{{ url('/shorturls') }}",
                "fnServerParams": function ( aoData ) {},
                "columns": [
                    { "data": "sno", "name": "S.No", "searchable": false, "orderable": false  },
                    { "data": "company", "name": "Company", "searchable": true, "orderable": true  },
                    { "data": "created_by", "name": "Created By", "searchable": true, "orderable": true  },
                    { "data": "original_url", "name": "Original Url", "searchable": true, "orderable": true  },
                    { "data": "short_code", "name": "Short Code", "searchable": true, "orderable": true  },
                    { "data": "created_at", "name": "Created At", "searchable": false, "orderable": false  },
                    { "data": "action", "name": "Action", "searchable": false, "orderable": false },
                ]
            } );

            $(".search_filter").change(function(e){
                e.preventDefault();
                table.columns().eq( 0 ).each( function ( colIdx) {
                    if($( 'input,select', table.column( colIdx ).footer().length ) && typeof($( 'input,select', table.column( colIdx ).footer() ).val()) != 'undefined' ){
                        table
                        .column( colIdx )
                        .search( $( 'input,select', table.column( colIdx ).footer() ).val());
                    } 
                });
                table.draw();
            });
        });  
    </script>

</x-app-layout>


