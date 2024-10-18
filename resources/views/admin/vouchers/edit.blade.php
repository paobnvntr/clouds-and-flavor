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
                    <h3 class="mb-0">Edit Voucher</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Vouchers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Voucher</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Voucher Details</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.vouchers.update', ['id' => $voucher->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="code" class="form-label">Voucher Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        value="{{ $voucher->code }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="discount"
                                        class="form-control @error('discount') is-invalid @enderror" step="0.01" min="0"
                                        value="{{ $voucher->discount }}" required>
                                    @error('discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Discount Type <span
                                            class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror"
                                        required>
                                        <option value="percentage" {{ $voucher->type == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                        <option value="fixed" {{ $voucher->type == 'fixed' ? 'selected' : '' }}>Fixed
                                            Amount</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date"
                                        class="form-control @error('expiry_date') is-invalid @enderror"
                                        value="{{ \Carbon\Carbon::parse($voucher->expiry_date)->format('Y-m-d') }}"
                                        required>
                                    @error('expiry_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $voucher->is_active ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label">Is Active</label>
                                </div>

                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">Usage Limit (leave blank for
                                        unlimited)</label>
                                    <input type="number" name="usage_limit"
                                        class="form-control @error('usage_limit') is-invalid @enderror" min="1"
                                        value="{{ $voucher->usage_limit }}">
                                    @error('usage_limit')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="minimum_purchase" class="form-label">Minimum Purchase Amount</label>
                                    <input type="number" name="minimum_purchase"
                                        class="form-control @error('minimum_purchase') is-invalid @enderror" step="0.01"
                                        min="0" value="{{ $voucher->minimum_purchase }}">
                                    @error('minimum_purchase')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="max_discount" class="form-label">Maximum Discount Amount</label>
                                    <input type="number" name="max_discount"
                                        class="form-control @error('max_discount') is-invalid @enderror" step="0.01"
                                        min="0" value="{{ $voucher->max_discount }}">
                                    @error('max_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-warning">Update Voucher</button>
                                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection