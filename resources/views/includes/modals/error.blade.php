<!-- Apple-like Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content apple-error-modal">
      <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body text-center pt-2">
        <!-- Animated Cross -->
        <div class="error-circle">
          <svg viewBox="0 0 72 72" class="error-svg">
            <circle class="ring" cx="36" cy="36" r="30"></circle>
            <path class="cross" d="M24 24 L48 48 M48 24 L24 48"></path>
          </svg>
        </div>

        <h5 id="errorTitle" class="mt-2">Error</h5>
        <p id="errorMessage" class="mb-0 text-muted">Something went wrong.</p>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-dark w-100" style="border-radius:12px;" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<style>
.apple-error-modal{
  border-radius: 18px;
  background: rgba(255,255,255,0.78);
  backdrop-filter: blur(18px) saturate(180%);
  -webkit-backdrop-filter: blur(18px) saturate(180%);
  border: 1px solid rgba(255,255,255,0.45);
  box-shadow: 0 20px 60px rgba(0,0,0,0.18);
  padding-bottom: 6px;
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.error-circle{
  width: 96px; height: 96px; margin: 6px auto 8px;
  border-radius: 50%;
  background: linear-gradient(180deg, #fff5f5, #ffeaea);
  display: grid; place-items: center;
  box-shadow: inset 0 0 0 1px rgba(220,38,38,.18), 0 8px 20px rgba(220,38,38,.15);
}

.error-svg{ width: 74px; height: 74px; }

.error-svg .ring{
  fill: none;
  stroke: rgba(220,38,38,.25);
  stroke-width: 6;
  transform-origin: 36px 36px;
  animation: ring-pop .44s ease-out both;
}

.error-svg .cross{
  fill: none;
  stroke: #dc2626;   /* red */
  stroke-width: 6;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 60;
  stroke-dashoffset: 60;
  animation: draw .5s .12s ease forwards;
}

#errorTitle{
  font-weight: 800;
  letter-spacing: -0.02em;
  margin-top: 8px;
  color: #b91c1c;
}

@keyframes draw {
  to { stroke-dashoffset: 0; }
}
@keyframes ring-pop {
  0% { transform: scale(.9); opacity:.6; }
  100% { transform: scale(1); opacity:1; }
}
</style>

<script>
  // Wrapper for error modal
  window.showError = function ({ title='Error', message='Something went wrong.', autoHideMs=null, onHidden=null } = {}) {
    const modalEl = document.getElementById('errorModal');
    if (!modalEl) return;

    // Update text
    document.getElementById('errorTitle').textContent = title;
    document.getElementById('errorMessage').textContent = message;

    // Restart SVG animation
    const circle = modalEl.querySelector('.error-circle');
    const svg = circle.querySelector('.error-svg');
    if (svg) svg.remove();
    circle.insertAdjacentHTML('beforeend', `
      <svg viewBox="0 0 72 72" class="error-svg">
        <circle class="ring" cx="36" cy="36" r="30"></circle>
        <path class="cross" d="M24 24 L48 48 M48 24 L24 48"></path>
      </svg>
    `);

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: 'static', keyboard: true });
    if (onHidden) {
      const once = () => { modalEl.removeEventListener('hidden.bs.modal', once); onHidden(); };
      modalEl.addEventListener('hidden.bs.modal', once);
    }
    modal.show();

    if (autoHideMs && Number(autoHideMs) > 0) {
      setTimeout(() => modal.hide(), Number(autoHideMs));
    }
  };

  // Auto-trigger if Laravel has flash errors
  @if($errors->any())
    document.addEventListener('DOMContentLoaded', function(){
      window.showError({
        title: 'Error',
        message: @json($errors->first()), // show the first validation error
        autoHideMs: 6000
      });
    });
  @endif
</script>
