<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Company
        </h2>
    </x-slot>
    <br>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('companies.add') }}" method="POST">

                {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                        @php $name = old('name',"") @endphp
                        <input type="text" name="name" id="name" value="{{$name}}" placeholder="Name" class="form-control" required>
                        <span class="error-info text-danger">{{ $errors->first('name') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success">Create</button>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>