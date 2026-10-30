@props([
    'type' => 'success',
    'message' => null,
])

<div {{ $attributes->class(['flash-message', 'flash-message--' . $type]) }} role="{{ $type === 'error' ? 'alert' : 'status' }}">
    @if ($message)
        {{ $message }}
    @else
        {{ $slot }}
    @endif
</div>
