<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invite Admin
        </h2>
    </x-slot>
    <br>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('invite.admin') }}" method="POST">

                {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="company" class="form-label">Company</label>
                        @php $company_id = old('company_id',"") @endphp
                        <select name="company_id" class="form-control" required>
                            <option value="">Select Company</option>
                            @if(!empty($companies))
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" @if($company_id == $company->id) {{ "selected" }} @endif >{{ $company->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="error-info text-danger">{{ $errors->first('company_id') }}</span>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Admin Name</label>
                        @php $name = old('name',"") @endphp
                        <input type="text" name="name" class="form-control" placeholder="Admin Name" value="{{$name}}"required>
                        <span class="error-info text-danger">{{ $errors->first('name') }}</span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Admin Email</label>
                        @php $email = old('email',"") @endphp
                        <input type="email" name="email" class="form-control" placeholder="Admin Email" value="{{$email}}"required>
                        <span class="error-info text-danger">{{ $errors->first('email') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success">Invite Admin</button>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>