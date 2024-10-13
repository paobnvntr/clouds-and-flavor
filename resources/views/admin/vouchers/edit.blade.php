@extends('layouts.admin.app')

@section('title', 'Edit Voucher')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-sm-6">
                        <h3>Edit Voucher</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <form action="{{ route('admin.vouchers.update', ['id' => $voucher->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="code">Voucher Code</label>
                        <input type="text" name="code" class="form-control" value="{{ $voucher->code }}" required>
                    </div>
                    <div class="form-group">
                        <label for="discount">Discount</label>
                        <input type="number" name="discount" class="form-control" step="0.01" min="0"
                            value="{{ $voucher->discount }}" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Discount Type</label>
                        <select name="type" class="form-control" required>
                            <option value="percentage" {{ $voucher->type == 'percentage' ? 'selected' : '' }}>Percentage
                            </option>
                            <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control"
                            value="{{ \Carbon\Carbon::parse($voucher->expiry_date)->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="is_active">
                            <input type="checkbox" name="is_active" value="1"
                                {{ $voucher->is_active ? 'checked' : '' }}> Is Active
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="usage_limit">Usage Limit (leave blank for unlimited)</label>
                        <input type="number" name="usage_limit" class="form-control" min="1"
                            value="{{ $voucher->usage_limit }}">
                    </div>
                    <div class="form-group">
                        <label for="minimum_purchase">Minimum Purchase Amount</label>
                        <input type="number" name="minimum_purchase" class="form-control" step="0.01" min="0"
                            value="{{ $voucher->minimum_purchase }}">
                    </div>
                    <div class="form-group">
                        <label for="max_discount">Maximum Discount Amount</label>
                        <input type="number" name="max_discount" class="form-control" step="0.01" min="0"
                            value="{{ $voucher->max_discount }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </main>
@endsection
