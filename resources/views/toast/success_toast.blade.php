<style>
    .toast {
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
        padding: 15px; /* Add padding for better appearance */
    }
    .toast-header {
        background-color: #28a745; /* Change header background color */
        color: white; /* Text color for header */
    }
    .toast-body {
        background-color: #f8f9fa; /* Body background color */
        color: black; /* Text color for body */
    }
</style>

    <!-- Success Toast -->
    <div aria-live="polite" aria-atomic="true" style="position: relative;">
        <div class="toast bg-success text-white" id="successToast" style="position: absolute; top: 20px; right: 20px; z-index: 1050;" data-delay="3000">
            <div class="toast-header">
                <strong class="mr-auto">Success</strong>
                {{-- <small>Just now</small>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="toast-body">
                Your success message here.
            </div>
        </div>
    </div>



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
