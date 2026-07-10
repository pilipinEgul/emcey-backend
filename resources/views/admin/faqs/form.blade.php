@extends('admin.layout')

@section('eyebrow', 'Support')
@section('title', $faq->exists ? 'Edit FAQ' : 'New FAQ')

@section('actions')
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">← Back</a>
@endsection

@section('content')
    @php $serviceOptions = $services->pluck('name', 'id')->all(); @endphp

    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="max-w-3xl space-y-5 card p-6 sm:p-7">
        @csrf
        @if($faq->exists) @method('PUT') @endif

        <x-admin.input name="question" label="Question" :value="$faq->question" required />
        <x-admin.textarea name="answer" label="Answer" :value="$faq->answer" rows="6" required />

        <div class="grid sm:grid-cols-3 gap-4">
            <x-admin.select name="service_id" label="Service (optional)" :value="$faq->service_id" :options="$serviceOptions" placeholder="— General —" />
            <x-admin.input name="category" label="Category tag" :value="$faq->category" placeholder="aftercare, pricing…" />
            <x-admin.input name="sort_order" label="Sort order" type="number" :value="$faq->sort_order ?? 0" />
        </div>

        <x-admin.checkbox name="is_active" label="Active" :checked="$faq->is_active ?? true" />

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="btn btn-primary">{{ $faq->exists ? 'Save changes' : 'Add FAQ' }}</button>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
@endsection
