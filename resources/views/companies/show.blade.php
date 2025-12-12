@extends('layouts.adminApp')

{{-- Customize layout sections --}}

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('messages.show-company' )}}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home' )}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">{{ __('messages.companies' )}}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.show-company' )}}</li>
        </ol>
    </div>
@stop
@section('content_body')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <table id="employees" class="table table-bordered table-hover">
                        <tr>
                            <th width="15%">{{ __('messages.logo' )}}</th>
                            <td width="80%">
                                @if(!empty($company->name))
                                    @php $file_url = url('/'.$company->logo); @endphp
                                    <img src="{{$file_url}}" width="150" height="150" class="img-thumbnail"/>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th width="15%">{{ __('messages.name' )}}</th>
                            <td width="80%">{{ $company->name }}</td>
                        </tr>
                        
                        <tr>
                            <th width="15%">{{ __('messages.email' )}}</th>
                            <td width="80%">{{ $company->email }}</td>
                        </tr>
                        <tr>
                            <th width="15%">{{ __('messages.website' )}}</th>
                            <td width="80%">{{ $company->website }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
{{-- Push extra CSS --}}

@push('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush