<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Short Url
        </h2>
    </x-slot>
    <br>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('shorturl.add') }}" method="POST">

                {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="company" class="form-label">Company</label>
                        <select class="form-control" disabled>
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        </select>
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <span class="error-info text-danger">{{ $errors->first('company_id') }}</span>
                    </div>

                    <div class="mb-3">
                        <label for="original_url" class="form-label">Original Url</label>
                        @php $original_url = old('original_url',"") @endphp
                        <input type="text" name="original_url" class="form-control" placeholder="Original Url" value="{{$original_url}}"required>
                        <span class="error-info text-danger">{{ $errors->first('original_url') }}</span>
                    </div>

                    <div class="mb-3">
                        <label for="short_code" class="form-label">Short Code</label>
                        @php $short_code = old('short_code',"") @endphp
                        <input type="text" name="short_code" class="form-control" placeholder="Short Code" value="{{$short_code}}"required>
                        <span class="error-info text-danger">{{ $errors->first('short_code') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success">Create</button>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>