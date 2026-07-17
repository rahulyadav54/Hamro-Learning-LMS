@php
    $toastrMessages = collect(session()->get('toastr::messages', []))
        ->reject(function ($message) {
            return ($message['type'] ?? null) === 'error';
        })
        ->values();
@endphp

<script type="text/javascript">
    if (window.toastr && typeof window.toastr.error === 'function') {
        window.toastr.error = function (message, title, options) {
            return window.toastr.warning(message, title, options);
        };
    }
</script>

@if($toastrMessages->isNotEmpty())
<script type="text/javascript">
    @foreach($toastrMessages as $message)
        @php
            $options = (array) config('toastr.options');

            if (!empty($message['options'])) {
                $options = array_merge($options, $message['options']);
            }

            $type = $message['type'] ?? 'info';
            $title = $message['title'] ?? null;
            $body = $message['message'] ?? '';
        @endphp

        toastr.options = {!! json_encode($options) !!};
        toastr.{{ $type }}({!! json_encode($body) !!}, {!! json_encode($title) !!});
    @endforeach
</script>
@endif
