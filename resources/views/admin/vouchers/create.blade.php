@extends('layouts.admin.app')

@section('title', 'Add Voucher')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Add Voucher</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="code">Voucher Code</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="discount">Discount</label>
                    <input type="number" name="discount" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="type">Discount Type</label>
                    <select name="type" class="form-control" required>
                        <option value="" disabled selected>Select Menu</option>
                        <option value="percentage">Percentage</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="is_active">
                        <input type="checkbox" name="is_active" value="1" checked> Is Active
                    </label>
                </div>
                <div class="form-group">
                    <label for="usage_limit">Usage Limit (leave blank for unlimited)</label>
                    <input type="number" name="usage_limit" class="form-control" min="1">
                </div>
                <div class="form-group">
                    <label for="minimum_purchase">Minimum Purchase Amount</label>
                    <input type="number" name="minimum_purchase" class="form-control" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label for="max_discount">Maximum Discount Amount</label>
                    <input type="number" name="max_discount" class="form-control" step="0.01" min="0">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</main>
@endsection