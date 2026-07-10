@props(['action', 'confirm' => 'Delete this item? This cannot be undone.', 'label' => 'Delete'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $confirm }}')" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">{{ $label }}</button>
</form>
