//  Ensure proper printing for the e-ticket 
// Place in public/js folder
document.addEventListener('DOMContentLoaded', function() {
    // Enhance print functionality
    const printBtn = document.getElementById('printButton');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            preparePrint();
        });
    }
    
    // Enhance QR code loading
    const qrImg = document.getElementById('qrcode-image');
    if (qrImg) {
        qrImg.addEventListener('load', function() {
            document.getElementById('qr-loading-indicator')?.classList.add('d-none');
        });
        
        qrImg.addEventListener('error', function() {
            handleQRError();
        });
    }
    
    function preparePrint() {
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'alert alert-info print-message';
        loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyiapkan cetak...';
        document.querySelector('.e-tiket-container')?.prepend(loadingMsg);
        
        setTimeout(function() {
            loadingMsg.remove();
            window.print();
        }, 800);
    }
    
    function handleQRError() {
        // Try to generate a text-based QR code as fallback
        const qrContainer = qrImg.parentElement;
        const errorMsg = document.createElement('div');
        errorMsg.className = 'alert alert-warning mt-2 small';
        errorMsg.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> QR Code gagal dimuat, gunakan kode: <strong>' + 
            (window.ticketCode || 'KODE TIKET') + '</strong>';
        qrContainer.appendChild(errorMsg);
        
        // Create a simple fallback visual element
        const fallbackQR = document.createElement('div');
        fallbackQR.className = 'border rounded p-2 bg-white mx-auto mt-2';
        fallbackQR.style.width = '150px';
        fallbackQR.style.height = '150px';
        fallbackQR.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100">' + 
            '<span class="text-primary fw-bold">' + (window.ticketCode || 'TIKET') + '</span></div>';
        qrImg.style.display = 'none';
        qrContainer.insertBefore(fallbackQR, qrImg);
    }
});
