<!-- Apple-style Validation Modal -->
<div id="validationErrorsModal" class="aw-modal" aria-hidden="true">
  <div class="aw-modal__backdrop"></div>
  <div class="aw-modal__sheet">
    <div class="aw-modal__icon">⚠️</div>
    <h3 class="aw-modal__title">Validation Errors</h3>
    <p class="aw-modal__text">Please fix the following before continuing:</p>
    <ul class="aw-modal__list">
        @foreach ($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
    <div class="aw-modal__actions">
      <button type="button" class="aw-btn aw-btn--primary" onclick="closeValidationModal()">
        OK
      </button>
    </div>
  </div>
</div>

<style>
  /* --- Apple modal styling --- */
  .aw-modal {
    position: fixed;
    inset: 0;
    display: none;
    z-index: 1055;
  }
  .aw-modal[aria-hidden="false"] {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .aw-modal__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.25);
    backdrop-filter: blur(6px);
  }
  .aw-modal__sheet {
    position: relative;
    background: #fff;
    border-radius: 18px;
    padding: 20px 22px;
    width: min(90%, 400px);
    box-shadow: 0 20px 40px rgba(0,0,0,.15);
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, sans-serif;
    z-index: 2;
    animation: scaleIn .2s ease;
  }
  @keyframes scaleIn {
    from { transform: scale(.96); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .aw-modal__icon {
    font-size: 30px;
    margin-bottom: 10px;
    text-align: center;
  }
  .aw-modal__title {
    font-weight: 800;
    font-size: 18px;
    margin: 0 0 6px;
    text-align: center;
    color: #111;
  }
  .aw-modal__text {
    font-size: 14px;
    text-align: center;
    color: #6b7280;
    margin-bottom: 12px;
  }
  .aw-modal__list {
    font-size: 14px;
    color: #b91c1c;
    margin: 0 0 16px;
    padding-left: 18px;
  }
  .aw-modal__actions {
    display: flex;
    justify-content: center;
  }
  .aw-btn {
    border-radius: 10px;
    padding: 8px 18px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    background: #111827;
    color: #fff;
    cursor: pointer;
    transition: background .2s ease;
  }
  .aw-btn:hover {
    background: #000;
  }
</style>

<script>
function closeValidationModal() {
    document.getElementById('validationErrorsModal').setAttribute('aria-hidden', 'true');
}

@if ($errors->any())
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('validationErrorsModal').setAttribute('aria-hidden', 'false');
});
@endif
</script>
