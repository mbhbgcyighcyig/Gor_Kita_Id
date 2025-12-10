@extends('layout')

@section('title', 'Pembayaran - Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-3 bg-white/5 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">PAYMENT REQUIRED</span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Finalize Your <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Booking</span> ⚡
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Lengkapi pembayaran untuk mengamankan slot premium Anda</p>
        </div>

        @if($remainingTime && $remainingTime != 'EXPIRED')
        <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-3xl p-6 mb-8 border border-yellow-500/30 backdrop-blur-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center border border-yellow-400/30">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white mb-1">Waktu Tersisa</h3>
                        <p class="text-yellow-300 text-2xl font-bold">{{ $remainingTime }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-400">Selesaikan dalam</p>
                    <p class="text-white font-bold">15 Menit</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm">
                    <div class="mb-8">
                        <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <span>Booking Summary</span>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 text-sm mb-2">ID Booking</p>
                                <p class="text-2xl font-black text-white">#{{ $booking->id }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 text-sm mb-2">Lapangan</p>
                                <p class="text-2xl font-black text-white">{{ $booking->lapangan->nama_lapangan }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 text-sm mb-2">Tanggal & Waktu</p>
                                <p class="text-xl font-bold text-white">{{ $booking->formatted_date }}</p>
                                <p class="text-lg text-emerald-300">{{ $booking->formatted_time }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 text-sm mb-2">Total Pembayaran</p>
                                <p class="text-3xl font-black text-emerald-300">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('booking.payment.process', $booking->id) }}" method="POST" id="paymentForm">
                        @csrf
                        
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center border border-blue-400/30">
                                    <i class="fas fa-university text-white"></i>
                                </div>
                                <span>Pilih Bank</span>
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                @foreach($vaNumbers as $bank => $va)
                                <div class="relative">
                                    <input type="radio" name="bank" value="{{ $bank }}" 
                                           id="bank_{{ $bank }}" class="hidden peer" required>
                                    <label for="bank_{{ $bank }}" 
                                           class="block cursor-pointer group">
                                        <div class="bg-white/5 border-2 border-white/10 rounded-2xl p-6 text-center transition-all duration-300 
                                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 peer-checked:scale-105
                                                    hover:border-emerald-500/50 hover:bg-white/10">
                                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl 
                                                        flex items-center justify-center border border-blue-400/30 group-hover:scale-110 transition-transform">
                                                @if($bank == 'BCA')
                                                <i class="fas fa-building text-blue-400 text-2xl"></i>
                                                @elseif($bank == 'BRI')
                                                <i class="fas fa-university text-green-400 text-2xl"></i>
                                                @elseif($bank == 'Mandiri')
                                                <i class="fas fa-landmark text-yellow-400 text-2xl"></i>
                                                @elseif($bank == 'BNI')
                                                <i class="fas fa-bank text-red-400 text-2xl"></i>
                                                @else
                                                <i class="fas fa-credit-card text-purple-400 text-2xl"></i>
                                                @endif
                                            </div>
                                            <p class="font-bold text-white">{{ $bank }}</p>
                                            <div class="mt-2 text-xs text-gray-400 va-display" style="display: none;">
                                                {{ $va }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center border border-red-400/30">
                                    <i class="fas fa-lock text-white"></i>
                                </div>
                                <span>Security PIN</span>
                            </h2>
                            <div class="bg-white/5 rounded-2xl p-8 border border-white/10">
                                <p class="text-gray-400 mb-6 text-center">Masukkan 6 digit PIN untuk mengkonfirmasi pembayaran</p>
                                
                                <div class="flex justify-center space-x-4 mb-8">
                                    @for($i = 1; $i <= 6; $i++)
                                    <input type="password" 
                                           class="pin-box w-16 h-16 bg-white/10 border-2 border-white/20 rounded-2xl text-center text-3xl font-black text-white focus:outline-none focus:border-emerald-500 transition-all"
                                           maxlength="1" 
                                           data-index="{{ $i }}"
                                           oninput="moveToNext(this)">
                                    @endfor
                                </div>
                                <input type="hidden" name="pin" id="pinHidden">
                                
                                <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-2xl p-4 mb-6 border border-yellow-500/30">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-info-circle text-yellow-400 text-xl"></i>
                                        <div>
                                            <p class="text-yellow-300 font-bold">DEMO MODE</p>
                                            <p class="text-gray-300 text-sm">Gunakan PIN: <code class="bg-black/50 px-2 py-1 rounded">123456</code> untuk simulasi pembayaran</p>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(session('error'))
                                <div class="bg-gradient-to-r from-red-500/10 to-pink-500/10 rounded-2xl p-4 mb-6 border border-red-500/30">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                                        <p class="text-red-300 font-bold">{{ session('error') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" 
                                    id="submitBtn"
                                    class="flex-1 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-6 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3 group disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-lock group-hover:scale-110 transition-transform"></i>
                                <span id="btnText">KONFIRMASI PEMBAYARAN</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            
                            <a href="{{ route('booking.show', $booking->id) }}" 
                               class="bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-6 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-600/30 flex items-center justify-center space-x-3">
                                <i class="fas fa-times"></i>
                                <span>BATAL</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div>
                <div class="sticky top-8">
                    <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl p-8 border border-blue-500/20 backdrop-blur-sm mb-6">
                        <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center border border-blue-400/30">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <span>Cara Bayar</span>
                        </h2>
                        <ol class="space-y-4">
                            <li class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                    <span class="text-blue-400 font-bold">1</span>
                                </div>
                                <p class="text-gray-300">Pilih bank transfer</p>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                    <span class="text-blue-400 font-bold">2</span>
                                </div>
                                <p class="text-gray-300">Transfer ke Virtual Account</p>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                    <span class="text-blue-400 font-bold">3</span>
                                </div>
                                <p class="text-gray-300">Masukkan PIN konfirmasi</p>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                    <span class="text-blue-400 font-bold">4</span>
                                </div>
                                <p class="text-gray-300">Tunggu verifikasi admin (1x24 jam)</p>
                            </li>
                        </ol>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm">
                        <h2 class="text-2xl font-black text-white mb-6">Total Tagihan</h2>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Harga per jam</span>
                                <span class="text-white font-bold">
                                    Rp {{ number_format($booking->lapangan->price_per_hour, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Durasi</span>
                                <span class="text-white font-bold">{{ $booking->duration }} jam</span>
                            </div>
                            <div class="border-t border-gray-700 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-black text-white">Total</span>
                                    <span class="text-3xl font-black text-emerald-300">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                            <p class="text-gray-400 text-sm mb-1">Status Booking</p>
                            <p class="text-xl font-bold text-yellow-400">MENUNGGU PEMBAYARAN</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinBoxes = document.querySelectorAll('.pin-box');
    const pinHidden = document.getElementById('pinHidden');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    let selectedBank = null;
     
    document.querySelectorAll('input[name="bank"]').forEach(radio => {
        radio.addEventListener('change', function() {

            selectedBank = this.value;
            document.querySelectorAll('.va-display').forEach(el => el.style.display = 'none');
            const vaDisplay = this.closest('label').querySelector('.va-display');
            if (vaDisplay) vaDisplay.style.display = 'block';
            checkForm();
        });
    });
    
    pinBoxes.forEach(box => {
        box.addEventListener('input', function(e) {
            const index = parseInt(this.dataset.index);
            const value = this.value;
            
            if (!/^\d$/.test(value)) {
                this.value = '';
                return;
            }
            
            this.classList.add('filled');
            this.style.borderColor = '#10b981';
            this.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
           
            if (value.length === 1 && index < 6) {
                pinBoxes[index].focus();
            }
            
            updateHiddenPin();
            checkForm();
        });
        
        box.addEventListener('keydown', function(e) {
            const index = parseInt(this.dataset.index);
            
            if (e.key === 'Backspace') {
                if (this.value === '' && index > 1) {
              
                    pinBoxes[index - 2].focus();
                    pinBoxes[index - 2].value = '';
                    pinBoxes[index - 2].classList.remove('filled');
                    pinBoxes[index - 2].style.borderColor = '';
                    pinBoxes[index - 2].style.backgroundColor = '';
                } else {
                    this.value = '';
                    this.classList.remove('filled');
                    this.style.borderColor = '';
                    this.style.backgroundColor = '';
                }
                updateHiddenPin();
                checkForm();
                e.preventDefault();
            }
        });
        
   
        box.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = e.clipboardData.getData('text');
            if (/^\d{6}$/.test(pasted)) {
                for (let i = 0; i < 6; i++) {
                    pinBoxes[i].value = pasted[i];
                    pinBoxes[i].classList.add('filled');
                    pinBoxes[i].style.borderColor = '#10b981';
                    pinBoxes[i].style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                }
                updateHiddenPin();
                checkForm();
            }
        });
    });
    
    function updateHiddenPin() {
        let pin = '';
        pinBoxes.forEach(box => {
            pin += box.value;
        });
        pinHidden.value = pin;
    }
    
    function checkForm() {
        const pin = pinHidden.value;
        const bankSelected = selectedBank !== null;
        
        if (pin.length === 6 && bankSelected) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
            btnText.textContent = 'KONFIRMASI PEMBAYARAN';
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
            
            if (!bankSelected && pin.length < 6) {
                btnText.textContent = 'PILIH BANK & MASUKKAN PIN';
            } else if (!bankSelected) {
                btnText.textContent = 'PILIH BANK TERLEBIH DAHULU';
            } else if (pin.length < 6) {
                btnText.textContent = 'MASUKKAN 6 DIGIT PIN';
            }
        }
    }
    
    setTimeout(() => {
        if (pinBoxes[0]) pinBoxes[0].focus();
    }, 500);
    
  
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>MEMPROSES...';
    });
});

function moveToNext(input) {
    const index = parseInt(input.dataset.index);
    if (input.value.length === 1 && index < 6) {
        const next = document.querySelector(`.pin-box[data-index="${index + 1}"]`);
        if (next) next.focus();
    }
}
</script>

<style>
.pin-box:focus {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.pin-box.filled {
    border-color: #10b981 !important;
    background-color: rgba(16, 185, 129, 0.1) !important;
}

input[type="radio"]:checked + label > div {
    border-color: #10b981 !important;
    background-color: rgba(16, 185, 129, 0.1) !important;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
}

.sticky {
    position: sticky;
    top: 2rem;
}
</style>
@endsection