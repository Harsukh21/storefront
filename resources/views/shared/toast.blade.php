@php
    $toastQueue = collect();

    if ($sessionToasts = session('toast')) {
        foreach (\Illuminate\Support\Arr::wrap($sessionToasts) as $toast) {
            if (is_array($toast) && isset($toast['message'])) {
                $toastQueue->push([
                    'type' => $toast['type'] ?? 'info',
                    'message' => $toast['message'],
                ]);
            } elseif (is_string($toast)) {
                $toastQueue->push(['type' => 'info', 'message' => $toast]);
            }
        }
    }

    if (session('status')) {
        $toastQueue->push(['type' => 'success', 'message' => session('status')]);
    }

    if (session('warning')) {
        $toastQueue->push(['type' => 'warning', 'message' => session('warning')]);
    }

    if (session('error')) {
        $toastQueue->push(['type' => 'danger', 'message' => session('error')]);
    }

    if (session('info')) {
        $toastQueue->push(['type' => 'info', 'message' => session('info')]);
    }
@endphp

<div class="toast-container pointer-events-none" data-toast-container></div>

<template id="toast-template">
    <div class="toast" data-toast>
        <div class="toast-accent" data-toast-accent></div>
        <div class="toast-body">
            <span data-toast-message></span>
            <button type="button" class="toast-dismiss" data-toast-dismiss aria-label="Dismiss">
                &times;
            </button>
        </div>
    </div>
</template>

@foreach ($toastQueue as $toast)
    <div data-flash-toast data-toast-type="{{ $toast['type'] }}" data-toast-message="{{ $toast['message'] }}"></div>
@endforeach

