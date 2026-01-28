@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto hide after 5 seconds
        setTimeout(function() {
            let alertElement = document.querySelector('.alert');
            if (alertElement) {
                alertElement.classList.remove('show');
                alertElement.classList.add('fade');
                setTimeout(function() {
                    alertElement.remove();
                }, 500); // Remove the element after fading out
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    });
</script>
