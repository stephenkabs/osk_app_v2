<script>
    $(document).ready(function() {
        @if(session('success'))
            $('#successToast .toast-body').text('{{ session('success') }}');
            $('#successToast').toast('show');
        @endif

        @if(session('error'))
            $('#errorToast .toast-body').text('{{ session('error') }}');
            $('#errorToast').toast('show');
        @endif
    });
    </script>
