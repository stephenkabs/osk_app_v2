<style>
/* ================= PROCESSING MODAL ================= */
.processing-modal {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: none;
}

.processing-modal[aria-hidden="false"] {
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Backdrop */
.processing-backdrop {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.35);
  backdrop-filter: blur(12px);
}

/* Card */
.processing-card {
  position: relative;
  background: rgba(255,255,255,0.96);
  border-radius: 24px;
  padding: 30px 32px;
  width: min(90vw, 360px);
  text-align: center;
  box-shadow: 0 30px 70px rgba(0,0,0,0.28);
  animation: modalFadeUp 0.25s ease-out;
  z-index: 1;
}

/* Apple Spinner */
.apple-spinner {
  width: 44px;
  height: 44px;
  margin: 0 auto 18px;
  border-radius: 50%;
  border: 3px solid rgba(0,0,0,0.12);
  border-top-color: #111;
  animation: appleSpin 0.8s linear infinite;
}

/* Text */
.processing-title {
  font-weight: 800;
  font-size: 16px;
  margin-bottom: 6px;
  letter-spacing: -0.01em;
}

.processing-text {
  font-size: 13px;
  color: #6b7280;
  line-height: 1.6;
}

/* Animations */
@keyframes appleSpin {
  to { transform: rotate(360deg); }
}

@keyframes modalFadeUp {
  from {
    opacity: 0;
    transform: translateY(18px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
<div id="processingModal" class="processing-modal" aria-hidden="true">
  <div class="processing-backdrop"></div>

  <div class="processing-card">
    <div class="apple-spinner"></div>

    <div class="processing-title">Processing Payment</div>
    <div class="processing-text">
      Please confirm the payment on your phone.<br>
      Do not refresh or close this page.
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {

  const form  = document.getElementById("checkoutForm");
  const modal = document.getElementById("processingModal");

  if (!form || !modal) return;

  form.addEventListener("submit", function () {

    // Show modal
    modal.setAttribute("aria-hidden", "false");

    // Lock scrolling
    document.body.style.overflow = "hidden";

    // Disable submit button safely
    const btn = form.querySelector("button[type=submit]");
    if (btn) {
      btn.disabled = true;
      btn.textContent = "Processing…";
    }

    // Let form submit normally
  });

});
</script>
