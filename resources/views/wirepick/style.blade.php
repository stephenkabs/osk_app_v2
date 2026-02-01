<style>
/* ===== Apple Layout ===== */
.invest-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 22px;
}

@media (min-width: 992px) {
    .invest-grid {
        grid-template-columns: 1.1fr 0.9fr;
        align-items: start;
    }
}

/* ===== Apple Card ===== */
.apple-card {
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid #e6e8ef;
    box-shadow: 0 18px 45px rgba(0,0,0,.08);
    padding: 26px;
}

/* Titles */
.apple-title {
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #1f2937;
}

/* Info Box */
.info-box {
    background: #f8f9fb;
    border-radius: 16px;
    padding: 14px 16px;
    border: 1px solid #e5e7eb;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 13px;
}

.info-row .label {
    color: #6b7280;
    font-weight: 600;
}

.info-row .value {
    font-weight: 800;
    color: #111827;
}

/* Mode Toggle */
.invest-mode-toggle {
    display: flex;
    background: #f1f3f6;
    border-radius: 999px;
    padding: 4px;
    gap: 4px;
}

.mode-btn {
    flex: 1;
    border: none;
    border-radius: 999px;
    padding: 8px 0;
    font-size: 13px;
    font-weight: 700;
    background: transparent;
    color: #6b7280;
    transition: 0.2s ease;
}

.mode-btn.active {
    background: #111;
    color: white;
}

/* Amount / Shares table */
.invest-table {
    background: #f9fafb;
    border-radius: 16px;
    padding: 14px;
    border: 1px solid #e5e7eb;
}

.row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.table-input {
    width: 120px;
    padding: 8px 10px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    font-weight: 700;
    background: #fff;
}

/* Payment options */
.payment-options {
    display: flex;
    justify-content: center;
    gap: 18px;
}

.pay-wrapper {
    text-align: center;
}

.pay-btn-option {
    width: 82px;
    height: 50px;
    border-radius: 14px;
    border: 2px solid transparent;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(0,0,0,.08);
    transition: 0.25s ease;
}

.pay-btn-option img {
    width: 58px;
}

.pay-btn-option.active {
    border-color: #111;
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(0,0,0,.18);
}

.pay-label {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    margin-top: 6px;
}

/* Pay button */
.pay-btn {
    background: linear-gradient(180deg, #1f2937, #000);
    border: none;
    padding: 14px;
    border-radius: 999px;
    font-weight: 700;
    color: white;
    width: 100%;
    box-shadow: 0 18px 35px rgba(0,0,0,.25);
    transition: 0.25s ease;
}

.pay-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 40px rgba(0,0,0,.35);
}
</style>

<style>
.apple-input {
  width: 100%;
  padding: 12px 14px;
  border-radius: 14px;
  border: 1px solid #d1d5db;
  background: #fafafa;
  font-size: 14px;
  transition: .2s ease;
}

.apple-input:focus {
  border-color: #007aff;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(0,122,255,.15);
  outline: none;
}

.form-group {
  margin-bottom: 14px;
}

.checkout-fields {
  animation: fadeUp .25s ease;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<style>
.payment-options {
  display: flex;
  justify-content: center;
  gap: 18px;
}

/* wrapper */
.pay-wrapper {
  text-align: center;
}

/* base button */
.pay-btn-option {
  width: 86px;
  height: 54px;
  border-radius: 16px;
  border: 2px solid transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all .25s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,.12);
  position: relative;
  overflow: hidden;
}

/* images */
.pay-btn-option img {
  width: 60px;
  filter: drop-shadow(0 1px 2px rgba(0,0,0,.25));
}

/* BRAND COLORS */
.pay-airtel {
  background: linear-gradient(180deg, #e60000, #b30000);
}

.pay-mtn {
  background: linear-gradient(180deg, #ffd400, #f0c000);
}

.pay-card {
  background: linear-gradient(180deg, #1a1a1a, #000000);
}

/* active (Apple focus ring style) */
.pay-btn-option.active {
  transform: translateY(-3px) scale(1.03);
  box-shadow: 0 18px 40px rgba(0,0,0,.25);
  border-color: rgba(255,255,255,.6);
}

/* hover */
.pay-btn-option:hover {
  transform: translateY(-2px);
}

/* label */
.pay-label {
  margin-top: 7px;
  font-size: 11px;
  font-weight: 700;
  color: #6b7280;
}
</style>
<style>
/* ===== Skeleton Base ===== */
@keyframes skeletonShimmer {
  0%   { background-position: -400px 0; }
  100% { background-position: 400px 0; }
}

.skeleton {
  background: linear-gradient(
    90deg,
    #eef1f5 25%,
    #e5e7eb 37%,
    #eef1f5 63%
  );
  background-size: 800px 100%;
  animation: skeletonShimmer 1.4s infinite linear;
  border-radius: 14px;
}

/* Skeleton layout */
.skeleton-card {
  padding: 26px;
  border-radius: 22px;
}

.skel-title {
  height: 20px;
  width: 70%;
  margin: 0 auto 18px;
}

.skel-row {
  height: 14px;
  margin-bottom: 10px;
}

.skel-box {
  height: 48px;
  border-radius: 14px;
}

.skel-toggle {
  height: 36px;
  border-radius: 999px;
}

.skel-button {
  height: 52px;
  border-radius: 999px;
  margin-top: 18px;
}
</style>
