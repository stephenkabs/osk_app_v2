<!-- Apple-like Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content apple-success-modal">
      <button type="button" class="btn-close btn-close-white ms-auto me-2 mt-2" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body text-center pt-2">
        <!-- Animated Check -->
        <div class="success-circle">
          <svg viewBox="0 0 72 72" class="success-svg">
            <circle class="ring" cx="36" cy="36" r="30"></circle>
            <path class="check" d="M20 37 L31 48 L52 26"></path>
          </svg>
        </div>

        <h5 id="successTitle" class="mt-2">Done</h5>
        <p id="successMessage" class="mb-0 text-muted">Your action completed successfully.</p>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-dark w-100" style="border-radius:12px;" data-bs-dismiss="modal">
          Great
        </button>
      </div>
    </div>
  </div>
</div>

<style>
    .apple-success-modal{
  border-radius: 18px;
  background: rgba(255,255,255,0.78);
  backdrop-filter: blur(18px) saturate(180%);
  -webkit-backdrop-filter: blur(18px) saturate(180%);
  border: 1px solid rgba(255,255,255,0.45);
  box-shadow: 0 20px 60px rgba(0,0,0,0.18);
  padding-bottom: 6px;
  font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.success-circle{
  width: 96px; height: 96px; margin: 6px auto 8px;
  border-radius: 50%;
  background: linear-gradient(180deg, #f6fff8, #e9fff0);
  display: grid; place-items: center;
  box-shadow: inset 0 0 0 1px rgba(16,185,129,.18), 0 8px 20px rgba(16,185,129,.15);
}

.success-svg{ width: 74px; height: 74px; }

.success-svg .ring{
  fill: none;
  stroke: rgba(16,185,129,.25);
  stroke-width: 6;
  transform-origin: 36px 36px;
  animation: ring-pop .44s ease-out both;
}

.success-svg .check{
  fill: none;
  stroke: #16a34a;           /* emerald-ish */
  stroke-width: 6;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 60;
  stroke-dashoffset: 60;
  animation: draw .5s .12s ease forwards;
}

#successTitle{
  font-weight: 800;
  letter-spacing: -0.02em;
  margin-top: 8px;
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
  // Simple wrapper to show the modal with custom text and optional auto-hide
  // Usage: window.showSuccess({ title: 'Saved', message: 'Expense created.' , autoHideMs: 2000 })
  window.showSuccess = function ({ title='Done', message='Your action completed successfully.', autoHideMs=null, onHidden=null } = {}) {
    const modalEl = document.getElementById('successModal');
    if (!modalEl) return;

    // Update text
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = message;

    // Restart animations by replacing the SVG (so it replays every time)
    const circle = modalEl.querySelector('.success-circle');
    const svg = circle.querySelector('.success-svg');
    if (svg) svg.remove();
    circle.insertAdjacentHTML('beforeend', `
      <svg viewBox="0 0 72 72" class="success-svg">
        <circle class="ring" cx="36" cy="36" r="30"></circle>
        <path class="check" d="M20 37 L31 48 L52 26"></path>
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

  // Auto-trigger if you’re using Laravel flash session('success')
  @if(session('success'))
    document.addEventListener('DOMContentLoaded', function(){
      window.showSuccess({
        title: 'Success',
        message: @json(session('success')),
        autoHideMs: 3200
      });
    });
  @endif
</script>
