@extends('layout')

@section('title', 'Bukti Pembayaran - Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">BUKTI PEMBAYARAN</h1>
            <p class="text-gray-600">Booking ID: #{{ $booking->id }}</p>
            <div class="mt-4">
                <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                    <i class="fas fa-check-circle"></i>
                    <span class="font-bold">Pembayaran Berhasil</span>
                </div>
            </div>
        </div>

        <!-- struk -->
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-8 mb-8" id="receipt">
            <div id="receipt-content">
                <div class="text-center mb-8 border-b pb-6">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-green-600 mb-2">PAYMENT SUCCESS</h2>
                    <p class="text-gray-500">Pembayaran telah berhasil diproses</p>
                    <p class="text-gray-400 text-sm mt-2">{{ now()->format('d F Y, H:i:s') }}</p>
                </div>

                <!-- Company Info -->
                <div class="text-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800">GorKita.ID</h3>
                    <p class="text-gray-600">Sport Booking Platform</p>
                    <p class="text-gray-500 text-sm">Kp Kedep RT 02/RW 22</p>
                    <p class="text-gray-500 text-sm">Telp: 088210017726</p>
                </div>

                <!-- Booking Info -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Booking ID</p>
                        <p class="font-bold text-gray-800 text-lg">#{{ $booking->id }}</p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Tanggal Booking</p>
                        <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-span-2 border p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Lapangan</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $booking->lapangan->name ?? 'Lapangan' }}</p>
                        <p class="text-gray-600 text-sm">{{ $booking->lapangan->type ?? 'Lapangan Olahraga' }}</p>
                    </div>
                </div>

                <!-- Time & Duration -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Waktu</p>
                        <p class="font-bold text-gray-800">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Durasi</p>
                        <p class="font-bold text-gray-800">{{ $booking->duration }} jam</p>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-gray-800 mb-3">Detail Pembayaran</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-bold">{{ $paymentData['bank'] ?? 'BCA Virtual Account' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Virtual Account</span>
                            <span class="font-bold font-mono">{{ $paymentData['va_number'] ?? '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waktu Pembayaran</span>
                            <span class="font-bold">{{ $paymentData['date'] ?? now()->format('d/m/Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <div class="text-center">
                        <p class="text-gray-600 mb-2">Total Pembayaran</p>
                        <p class="text-4xl font-bold text-green-600">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                        <p class="text-gray-500 text-sm mt-2">Sudah termasuk pajak dan biaya layanan</p>
                    </div>
                </div>

                <!-- Verifikasi-->
                <div class="border rounded-lg p-4 mb-6 text-center">
                    <p class="text-gray-600 mb-3 font-bold">Kode Verifikasi</p>
                    <div class="flex justify-center space-x-3 mb-3">
                        @php
                            $pin = $paymentData['pin'] ?? '123456';
                            $pinDigits = str_split($pin);
                        @endphp
                        @foreach($pinDigits as $digit)
                        <div class="w-10 h-10 bg-gray-800 text-white rounded-lg flex items-center justify-center font-bold text-xl">
                            {{ $digit }}
                        </div>
                        @endforeach
                    </div>
                    <p class="text-gray-500 text-sm">Simpan kode ini untuk verifikasi jika diperlukan</p>
                </div>

                <!-- Status -->
                <div class="border rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-gray-800 mb-2">Status Booking</h4>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-yellow-600 font-bold">Menunggu Verifikasi Admin</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-2">Estimasi waktu verifikasi: 15 menit</p>
                    <p class="text-gray-500 text-sm">Booking akan aktif setelah diverifikasi oleh admin</p>
                </div>

                <!-- Footer -->
                <div class="text-center border-t pt-6">
                    <p class="text-gray-500 mb-2">Terima kasih telah menggunakan layanan GorKita.ID</p>
                    <p class="text-gray-400 text-sm">Simpan bukti pembayaran ini sebagai referensi</p>
                    <p class="text-gray-400 text-sm">Untuk pertanyaan hubungi: support@gorkita.id</p>
                    
                    <!-- Watermark -->
                    <div class="mt-8 opacity-10">
                        <div class="text-6xl text-gray-300 font-bold text-center">PAID</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <button onclick="printReceipt()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-print"></i>
                    <span>Print Bukti</span>
                </button>
                
                <button onclick="downloadAsPDF()" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-file-pdf"></i>
                    <span>Download PDF</span>
                </button>
                
                <button onclick="downloadAsImage()" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-image"></i>
                    <span>Save as Image</span>
                </button>
                
                <a href="{{ route('booking.my-bookings') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fas fa-list"></i>
                    <span>Booking Saya</span>
                </a>
            </div>
            
            <!-- Screenshot -->
            <div class="mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-yellow-500 mt-1"></i>
                    <div>
                        <p class="font-bold text-yellow-700">Tips:</p>
                        <p class="text-yellow-600 text-sm">Anda dapat screenshot halaman ini, atau gunakan tombol di atas untuk print/download bukti pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include html2canvas and jsPDF libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Print Function
function printReceipt() {
    // Hide action buttons and other non-receipt elements
    const receipt = document.getElementById('receipt-content').cloneNode(true);
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Bukti Pembayaran #{{ $booking->id }} - GorKita.ID</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
                    background: white; 
                    color: #333; 
                    padding: 20px; 
                    max-width: 800px; 
                    margin: 0 auto;
                    line-height: 1.5;
                }
                
                .print-header {
                    text-align: center;
                    margin-bottom: 30px;
                    padding-bottom: 20px;
                    border-bottom: 2px dashed #ddd;
                }
                
                .print-title {
                    color: #059669;
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                
                .print-subtitle {
                    color: #6b7280;
                    font-size: 14px;
                }
                
                .print-section {
                    margin-bottom: 20px;
                }
                
                .print-label {
                    color: #6b7280;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 5px;
                }
                
                .print-value {
                    font-weight: 600;
                    font-size: 16px;
                    margin-bottom: 15px;
                }
                
                .print-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 15px;
                    margin-bottom: 20px;
                }
                
                .print-box {
                    border: 1px solid #e5e7eb;
                    padding: 15px;
                    border-radius: 8px;
                }
                
                .print-amount {
                    background: #f0fdf4;
                    border: 1px solid #86efac;
                    padding: 25px;
                    text-align: center;
                    border-radius: 10px;
                    margin: 20px 0;
                }
                
                .print-amount-value {
                    font-size: 32px;
                    font-weight: bold;
                    color: #059669;
                    margin: 10px 0;
                }
                
                .print-verification {
                    background: #1f2937;
                    color: white;
                    padding: 20px;
                    border-radius: 10px;
                    text-align: center;
                    margin: 20px 0;
                }
                
                .print-pin {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    margin: 15px 0;
                }
                
                .pin-digit {
                    width: 40px;
                    height: 40px;
                    background: white;
                    color: #1f2937;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 20px;
                    font-weight: bold;
                    border-radius: 6px;
                }
                
                .print-footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #e5e7eb;
                    color: #6b7280;
                    font-size: 12px;
                }
                
                .print-watermark {
                    opacity: 0.1;
                    font-size: 48px;
                    font-weight: bold;
                    color: #9ca3af;
                    text-align: center;
                    margin-top: 30px;
                }
                
                @media print {
                    @page { margin: 0.5in; }
                    body { padding: 0; }
                    .print-amount { page-break-inside: avoid; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                    <div style="width: 50px; height: 50px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <span style="color: white; font-size: 24px;">✓</span>
                    </div>
                    <div>
                        <div class="print-title">PAYMENT RECEIPT</div>
                        <div class="print-subtitle">GorKita.ID - {{ now()->format('d F Y, H:i:s') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-label">Booking Information</div>
                <div class="print-grid">
                    <div class="print-box">
                        <div class="print-label">Booking ID</div>
                        <div class="print-value">#{{ $booking->id }}</div>
                    </div>
                    <div class="print-box">
                        <div class="print-label">Booking Date</div>
                        <div class="print-value">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-box">
                    <div class="print-label">Field Name</div>
                    <div class="print-value" style="font-size: 18px;">{{ $booking->lapangan->name ?? 'Lapangan' }}</div>
                    <div style="color: #6b7280; font-size: 14px;">{{ $booking->lapangan->type ?? 'Lapangan Olahraga' }}</div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-grid">
                    <div class="print-box">
                        <div class="print-label">Time</div>
                        <div class="print-value">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</div>
                    </div>
                    <div class="print-box">
                        <div class="print-label">Duration</div>
                        <div class="print-value">{{ $booking->duration }} jam</div>
                    </div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-label">Payment Details</div>
                <div class="print-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Method</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; font-weight: 600;">{{ $paymentData['bank'] ?? 'BCA Virtual Account' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Virtual Account</td>
                            <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; font-family: monospace; font-weight: 600;">{{ $paymentData['va_number'] ?? '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;">Payment Time</td>
                            <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $paymentData['date'] ?? now()->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="print-amount">
                <div class="print-label">Total Payment</div>
                <div class="print-amount-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                <div style="color: #6b7280; font-size: 14px;">Including tax and service fee</div>
            </div>
            
            <div class="print-section">
                <div class="print-label">Verification Code</div>
                <div class="print-verification">
                    <div style="font-size: 14px; margin-bottom: 10px;">PIN VERIFICATION</div>
                    <div class="print-pin">
                        @php
                            $pin = $paymentData['pin'] ?? '123456';
                            $pinDigits = str_split($pin);
                        @endphp
                        @foreach($pinDigits as $digit)
                        <div class="pin-digit">{{ $digit }}</div>
                        @endforeach
                    </div>
                    <div style="font-size: 12px; opacity: 0.8;">Save this code for verification purposes</div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-label">Status</div>
                <div class="print-box">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%; margin-right: 10px; animation: pulse 1.5s infinite;"></div>
                        <span style="font-weight: 600; color: #f59e0b;">Pending Admin Verification</span>
                    </div>
                    <div style="color: #6b7280; font-size: 14px;">Estimated verification time: 15 minutes</div>
                    <div style="color: #6b7280; font-size: 14px;">Booking will be active after verification</div>
                </div>
            </div>
            
            <div class="print-footer">
                <div style="margin-bottom: 10px;">Thank you for using GorKita.ID services</div>
                <div style="font-size: 11px; color: #9ca3af;">
                    <div>Keep this receipt as reference</div>
                    <div>For questions contact: support@gorkita.id | Phone: (022) 1234-5678</div>
                    <div>GorKita.ID © {{ date('Y') }} - All rights reserved</div>
                </div>
            </div>
            
            <div class="print-watermark">PAID</div>
            
            <style>
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }
                
                @media print {
                    @page { 
                        margin: 20mm;
                        size: A4;
                    }
                    body { 
                        padding: 0;
                        font-size: 12pt;
                    }
                    .print-amount-value { font-size: 24pt; }
                    .print-title { font-size: 18pt; }
                }
            </style>
            
            <script>
                window.onload = function() {
                    // Auto print after 500ms
                    setTimeout(() => {
                        window.print();
                        // Close window after print
                        setTimeout(() => {
                            window.close();
                        }, 500);
                    }, 500);
                }
            <\/script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}

async function downloadAsPDF() {
    const btn = event.currentTarget;
    const originalHTML = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Membuat PDF...';
    btn.disabled = true;
    
    try {
        const { jsPDF } = window.jspdf;
        const receipt = document.getElementById('receipt-content');
        
        const canvas = await html2canvas(receipt, {
            scale: 2,
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff'
        });
        
        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        const pdf = new jsPDF('p', 'mm', 'a4');
        
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        
        pdf.addImage(imgData, 'JPEG', 10, 10, pageWidth - 20, pageHeight - 20);
        
        // watermark
        pdf.setTextColor(200, 200, 200);
        pdf.setFontSize(60);
        pdf.text('PAID', pageWidth / 2, pageHeight / 2, { align: 'center' });
        
        // pdf
        pdf.save(`bukti-pembayaran-{{ $booking->id }}-{{ date('YmdHis') }}.pdf`);
        
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        
    
        showNotification('PDF berhasil didownload!', 'success');
        
    } catch (error) {
        console.error('PDF generation error:', error);
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        showNotification('Gagal membuat PDF', 'error');
    }
}

// dwoanload sturk
async function downloadAsImage() {
    const btn = event.currentTarget;
    const originalHTML = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Membuat gambar...';
    btn.disabled = true;
    
    try {
        const receipt = document.getElementById('receipt-content');
        
        const canvas = await html2canvas(receipt, {
            scale: 2,
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff'
        });
        
        // Convert to image and download
        const link = document.createElement('a');
        link.download = `bukti-pembayaran-{{ $booking->id }}-{{ date('YmdHis') }}.jpg`;
        link.href = canvas.toDataURL('image/jpeg', 0.9);
        link.click();
        
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        
        showNotification('Gambar berhasil didownload!', 'success');
        
    } catch (error) {
        console.error('Image generation error:', error);
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        showNotification('Gagal membuat gambar', 'error');
    }
}

//notif
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}


const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-out {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    .animate-fade-out {
        animation: fade-out 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>

<style>

#receipt-content {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

@media print {
    body * {
        visibility: hidden !important;
    }
    
    #receipt, #receipt * {
        visibility: visible !important;
    }
    
    #receipt {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
        padding: 20px !important;
        background: white !important;
    }
    
    .no-print, 
    .bg-gray-100,
    .py-8,
    .mb-8,
    .shadow-lg,
    .rounded-2xl {
        display: none !important;
    }
}

/* For better print layout */
@page {
    margin: 20mm;
    size: A4;
}

/* Receipt print optimization */
.print-optimized {
    page-break-inside: avoid;
    break-inside: avoid;
}
</style>
@endsection