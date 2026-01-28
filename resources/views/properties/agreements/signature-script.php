<script>
(function() {
  const canvas = document.getElementById('sigCanvas');
  const hidden = document.getElementById('signatureData');
  const clearBtn = document.getElementById('clearSig');
  const undoBtn  = document.getElementById('undoSig');
  const ctx = canvas.getContext('2d');

  // Resize canvas to device pixels for crisp lines
  function resizeCanvas() {
    const ratio = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width  = rect.width  * ratio;
    canvas.height = Math.max(200, rect.height) * ratio;
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#111';
    redraw();
  }

  // Simple path history for undo
  let drawing = false;
  let paths = [];  // array of arrays of points: [{x,y},...]
  let current = [];

  function startDraw(x, y) {
    drawing = true;
    current = [{x, y}];
  }

  function moveDraw(x, y) {
    if (!drawing) return;
    current.push({x, y});
    redraw();
  }

  function endDraw() {
    if (!drawing) return;
    drawing = false;
    if (current.length > 0) {
      paths.push(current);
      current = [];
      updateHidden();
    }
  }

  function redraw() {
    // Clear visual canvas
    const rect = canvas.getBoundingClientRect();
    ctx.clearRect(0, 0, rect.width, rect.height);

    function drawPath(path) {
      if (path.length < 2) return;
      ctx.beginPath();
      ctx.moveTo(path[0].x, path[0].y);
      for (let i = 1; i < path.length; i++) ctx.lineTo(path[i].x, path[i].y);
      ctx.stroke();
    }

    paths.forEach(drawPath);
    if (current.length) drawPath(current);
  }

  function updateHidden() {
    // Only save if something drawn
    if (paths.length === 0) { hidden.value = ''; return; }
    // Export to PNG (base64)
    hidden.value = canvas.toDataURL('image/png');
  }

  // Mouse
  canvas.addEventListener('mousedown', e => startDraw(e.offsetX, e.offsetY));
  canvas.addEventListener('mousemove', e => moveDraw(e.offsetX, e.offsetY));
  canvas.addEventListener('mouseup', endDraw);
  canvas.addEventListener('mouseleave', endDraw);

  // Touch (mobile)
  canvas.addEventListener('touchstart', e => {
    e.preventDefault();
    const rect = canvas.getBoundingClientRect();
    const t = e.touches[0];
    startDraw(t.clientX - rect.left, t.clientY - rect.top);
  }, {passive:false});

  canvas.addEventListener('touchmove', e => {
    e.preventDefault();
    const rect = canvas.getBoundingClientRect();
    const t = e.touches[0];
    moveDraw(t.clientX - rect.left, t.clientY - rect.top);
  }, {passive:false});

  canvas.addEventListener('touchend', e => { e.preventDefault(); endDraw(); });

  // Controls
  clearBtn.addEventListener('click', () => {
    paths = []; current = []; redraw(); updateHidden();
  });

  undoBtn.addEventListener('click', () => {
    if (drawing) return;
    paths.pop(); redraw(); updateHidden();
  });

  // Keep data synced on submit too (in case user didn’t lift finger at end)
  const form = canvas.closest('form');
  form.addEventListener('submit', () => {
    if (drawing) endDraw();
    if (!hidden.value) {
      // Prevent blank signature
      alert('Please add your signature before submitting.');
      event.preventDefault();
    }
  });

  // Initialize
  window.addEventListener('resize', resizeCanvas);
  resizeCanvas();
})();
</script>
