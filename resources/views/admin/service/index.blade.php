@extends('admin.layouts.main')


@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Services</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new service</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>Duration</th>
            <th class="text-right">Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->duration }} minutes</td>
            <td class="text-right">{{ number_format($row->price, 2) }}</td>
            <td>
                @if($row->service_status === 'inactive')
                    <span class="badge badge-warning badge-pill">INACTIVE</span>
                @else
                    <span class="badge badge-success badge-pill">ACTIVE</span>
                @endif
            </td>
            <td>
                @includeWhen(Auth::user()->is('admin'), 'components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-danger text-center">Emtpty table</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
