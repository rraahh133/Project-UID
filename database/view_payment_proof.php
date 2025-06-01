<?php
include 'db_connect.php'; // your DB connection
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "No image specified.";
    exit;
}
$stmt = $conn->prepare("SELECT payment_proof FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $payment_proof = $row['payment_proof'];
    if (empty($payment_proof)) {
        echo "No payment proof found.";
        exit;
    }
} else {
    echo "Transaction not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8" />
<title>Bukti Pembayaran</title>
<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen select-none font-sans p-4">

  <div class="bg-white p-5 rounded-lg shadow-lg max-w-[95vw] max-h-[80vh] overflow-hidden relative flex justify-center items-center">
    <img
      id="proofImage"
      src="data:image/jpeg;base64,<?= htmlspecialchars($payment_proof) ?>"
      alt="Bukti Pembayaran"
      class="max-w-full max-h-full rounded-md cursor-grab transition-transform duration-300 ease-in-out select-none"
    />
  </div>

  <div class="mt-4 flex gap-4">
    <button
      id="zoomInBtn"
      aria-label="Zoom In"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition-colors"
    >➕ Zoom In</button>

    <button
      id="zoomOutBtn"
      aria-label="Zoom Out"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition-colors"
    >➖ Zoom Out</button>

    <button
      id="resetBtn"
      aria-label="Reset Zoom"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition-colors"
    >🔄 Reset</button>
  </div>

<script>
    const img = document.getElementById('proofImage');
    let scale = 1;
    const scaleStep = 0.1;
    const minScale = 0.5;
    const maxScale = 5;

    let isDragging = false;
    let startX, startY, currentX = 0, currentY = 0;

    function updateTransform() {
        img.style.transform = `scale(${scale}) translate(${currentX / scale}px, ${currentY / scale}px)`;
    }

    document.getElementById('zoomInBtn').addEventListener('click', () => {
        scale = Math.min(maxScale, scale + scaleStep);
        updateTransform();
    });

    document.getElementById('zoomOutBtn').addEventListener('click', () => {
        scale = Math.max(minScale, scale - scaleStep);
        if (scale === 1) {
            currentX = 0;
            currentY = 0;
        }
        updateTransform();
    });

    document.getElementById('resetBtn').addEventListener('click', () => {
        scale = 1;
        currentX = 0;
        currentY = 0;
        updateTransform();
    });

    img.addEventListener('mousedown', e => {
        if (scale > 1) {
            isDragging = true;
            startX = e.clientX - currentX;
            startY = e.clientY - currentY;
            img.style.cursor = 'grabbing';
            e.preventDefault();
        }
    });

    window.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            img.style.cursor = 'grab';
        }
    });

    img.addEventListener('mousemove', e => {
        if (!isDragging) return;
        e.preventDefault();
        currentX = e.clientX - startX;
        currentY = e.clientY - startY;
        updateTransform();
    });
</script>

</body>
</html>
