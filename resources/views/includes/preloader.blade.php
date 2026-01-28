<div id="preloader">
    <div id="status">
        <div class="apple-spinner"></div>
    </div>
</div>

<style>
/* Preloader full-screen overlay */
#preloader {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 99999;
    width: 100%;
    height: 100%;
    background-color: #fff; /* white background */
    display: flex;
    align-items: center;
    justify-content: center;
    /* transition: opacity 0.3s ease, visibility 0.3s ease; */
}

#preloader.hide {
    opacity: 0;
    visibility: hidden;
}

/* Simple Apple-like spinner */
.apple-spinner {
    border: 2px solid rgba(0, 0, 0, 0.1);   /* thinner border */
    border-top: 2px solid #282828;          /* dark top edge */
    border-radius: 50%;
    width: 32px;  /* smaller size (optional) */
    height: 32px; /* smaller size (optional) */
    animation: spin 1s linear infinite;
}


@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// Delay hide preloader by 3 seconds after page load
window.addEventListener("load", function(){
    setTimeout(function(){
        document.getElementById("preloader").classList.add("hide");
    }, 2000); // 3 seconds delay
});
</script>
