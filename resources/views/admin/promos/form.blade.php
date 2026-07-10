@extends('admin.layout')

@section('eyebrow', 'Marketing')
@section('title', $promo->exists ? 'Edit promo' : 'New promo')

@section('actions')
    <a href="{{ route('admin.promos.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    <form method="POST" action="{{ $promo->exists ? route('admin.promos.update', $promo) : route('admin.promos.store') }}" class="max-w-3xl space-y-5 card p-6 sm:p-7">
        @csrf
        @if($promo->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <x-admin.input name="code" label="Promo code" :value="$promo->code" required placeholder="GLOW10" />
            <x-admin.input name="title" label="Title" :value="$promo->title" required />
        </div>

        <x-admin.textarea name="description" label="Description" :value="$promo->description" rows="3" />

        <div class="grid sm:grid-cols-3 gap-4">
            <x-admin.select name="type" label="Discount type" :value="$promo->type ?? 'percentage'" :options="['percentage' => 'Percentage (%)', 'fixed' => 'Fixed amount (₱)']" required />
            <x-admin.input name="value" label="Discount value" type="number" :value="$promo->value" required />
            <x-admin.input name="minimum_amount" label="Minimum spend (₱)" type="number" :value="$promo->minimum_amount" />
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <x-admin.input name="usage_limit" label="Usage limit" type="number" :value="$promo->usage_limit" placeholder="Unlimited" />
            <x-admin.input name="starts_at" label="Starts at" type="datetime-local" :value="$promo->starts_at?->format('Y-m-d\TH:i')" />
            <x-admin.input name="ends_at" label="Ends at" type="datetime-local" :value="$promo->ends_at?->format('Y-m-d\TH:i')" />
        </div>

        <x-admin.input name="cover_image" label="Cover image URL" :value="$promo->cover_image" placeholder="https://…" />

        <div class="grid sm:grid-cols-2 gap-3">
            <x-admin.checkbox name="is_active" label="Active" :checked="$promo->is_active ?? true" />
            <x-admin.checkbox name="is_featured" label="Featured (homepage)" :checked="$promo->is_featured ?? false" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">{{ $promo->exists ? 'Save changes' : 'Create promo' }}</button>
            <a href="{{ route('admin.promos.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
@endsection
