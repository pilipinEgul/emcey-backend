@extends('admin.layout')

@section('eyebrow', 'Catalog')
@section('title', $service->exists ? 'Edit service' : 'New service')

@section('actions')
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    @php
        $catOptions = $categories->pluck('name', 'id')->all();
        $listify = fn ($arr) => is_array($arr) ? implode("\n", $arr) : '';
    @endphp

    <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        @if($service->exists) @method('PUT') @endif

        <div class="lg:col-span-2 space-y-5 card p-6 sm:p-7">
            <x-admin.input name="name" label="Name" :value="$service->name" required />
            <x-admin.input name="slug" label="Slug" :value="$service->slug" placeholder="Auto-generated from name if left blank" />
            <x-admin.textarea name="short_description" label="Short description" :value="$service->short_description" rows="2" help="Shown in service cards on the public site (max 255 chars)." />
            <x-admin.textarea name="description" label="Full description" :value="$service->description" rows="6" />

            <div class="grid sm:grid-cols-2 gap-4">
                <x-admin.textarea name="benefits" label="Benefits (one per line)" :value="$listify($service->benefits)" rows="4" />
                <x-admin.textarea name="process_steps" label="Process steps (one per line)" :value="$listify($service->process_steps)" rows="4" />
            </div>
            <x-admin.textarea name="aftercare" label="Aftercare tips (one per line)" :value="$listify($service->aftercare)" rows="4" />

            <details class="card p-4">
                <summary class="cursor-pointer text-sm font-medium text-ink-700">SEO metadata</summary>
                <div class="mt-4 space-y-4">
                    <x-admin.input name="meta_title" label="Meta title" :value="$service->meta_title" />
                    <x-admin.textarea name="meta_description" label="Meta description" :value="$service->meta_description" rows="2" />
                    <x-admin.input name="meta_keywords" label="Meta keywords" :value="$service->meta_keywords" />
                </div>
            </details>
        </div>

        <aside class="space-y-5">
            <div class="card p-6 space-y-5">
                <x-admin.select name="service_category_id" label="Category" :value="$service->service_category_id" :options="$catOptions" placeholder="— None —" />

                <div class="grid grid-cols-2 gap-3">
                    <x-admin.input name="price" label="Original price (₱)" type="number" :value="$service->price" />
                    <x-admin.input name="promo_price" label="Promo price (₱)" type="number" :value="$service->promo_price" />
                </div>

                <details class="card p-4">
                    <summary class="cursor-pointer text-sm font-medium text-ink-700">Artist tier pricing (PMU)</summary>
                    <div class="mt-4 space-y-4">
                        <p class="text-xs text-ink-500">For semi-permanent makeup services with Sr Artist and Master Artist rates across two sessions.</p>
                        <div class="grid grid-cols-2 gap-3">
                            <x-admin.input name="sr_artist_first_session" label="Sr Artist · 1st (₱)" type="number" :value="$service->sr_artist_first_session" />
                            <x-admin.input name="master_artist_first_session" label="Master Artist · 1st (₱)" type="number" :value="$service->master_artist_first_session" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <x-admin.input name="sr_artist_second_session" label="Sr Artist · 2nd (₱)" type="number" :value="$service->sr_artist_second_session" />
                            <x-admin.input name="master_artist_second_session" label="Master Artist · 2nd (₱)" type="number" :value="$service->master_artist_second_session" />
                        </div>
                    </div>
                </details>

                <x-admin.input name="duration_minutes" label="Duration (min)" type="number" :value="$service->duration_minutes" />
                <x-admin.input name="cover_image" label="Cover image URL" :value="$service->cover_image" placeholder="https://…" />
                <x-admin.input name="sort_order" label="Sort order" type="number" :value="$service->sort_order ?? 0" />
            </div>

            <div class="card p-6 space-y-3">
                <x-admin.checkbox name="is_active" label="Active" description="Show on the public site." :checked="$service->is_active ?? true" />
                <x-admin.checkbox name="is_featured" label="Featured" description="Highlight on the homepage." :checked="$service->is_featured ?? false" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn btn-primary w-full">{{ $service->exists ? 'Save changes' : 'Create service' }}</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary w-full">Cancel</a>
            </div>
        </aside>
    </form>
@endsection
