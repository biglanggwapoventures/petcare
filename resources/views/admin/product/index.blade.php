@extends('admin.layouts.main')

@section('body')
<div class="row mt-4">
    <div class="col">
        <h2>Products</h2>
    </div>
    <div class="col text-right">
        <a class="btn btn-info" href="{{ MyHelper::resource('create') }}">Create new product</a>
    </div>
</div>
<table class="table table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Image</th>
            <th>Code</th>
            <th>Name</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Stock on hand</th>
            <th>Reorder Level</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($resourceList as $row)
        <tr>
            <td><img src="{{ $row->photo_src }}" alt="" style="width:100px;height: 100px"></td>
            <td>{{ $row->code }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->category->description }}</td>
            <td>{{ $row->supplier->description }}</td>
            <td>{{ number_format($row->price, 2) }}</td>
            <td>
                {{ number_format($row->stock_on_hand) }} <br>
                 @if($row->stock_on_hand <= $row->reorder_level)
                    <span class="text-danger"><i class="fas fa-arrow-down"></i> LOW</span>
                @else
                    <span class="text-success"><i class="fas fa-arrow-up"></i> HIGH</span>
                @endif
                <br>
            </td>
            <td>
                {{ $row->reorder_level }}

            </td>
            <td>
                @if($row->product_status === 'inactive')
                    <span class="badge badge-warning badge-pill">INACTIVE</span>
                @else
                    <span class="badge badge-success badge-pill">ACTIVE</span>
                @endif
            </td>
            <td>
                 @includeWhen(Auth::user()->is('admin'), 'components.form.index-actions', ['id' => $row->id, 'hideRemove' => true])
                <a href="{{ route('admin.product.logs', ['product' => $row->id]) }}" class="btn btn-info">Logs</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-danger text-center">Emtpty table</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
