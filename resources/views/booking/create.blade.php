@extends('layout')

@section('title', 'Rate Booking - SportSpace')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-md mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 bg-gray-800/50 rounded-full px-6 py-3 border border-yellow-500/30 mb-6">
                <i class="fas fa-star text-yellow-400"></i>
                <span class="text-yellow-300 text-sm font-semibold">RATE YOUR EXPERIENCE</span>
            </div>
            
            <h1 class="text-3xl font-black text-white mb-4">
                Rate <span class="bg-gradient-to-r from-yellow-300 to-amber-400 bg-clip-text text-transparent">Booking</span>
            </h1>
            <p class="text-gray-400">Share your experience for booking #{{ $booking->id }}</p>
        </div>

        <!-- Booking Info -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 mb-6 border border-emerald-500/20">
            <h3 class="text-lg font-bold text-white mb-4">Booking Details</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-400">Lapangan:</span>
                    <span class="text-white font-semibold">{{ $booking->lapangan->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Date:</span>
                    <span class="text-emerald-300">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Time:</span>
                    <span class="text-cyan-300">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status:</span>
                    <span class="font-semibold {{ $booking->status == 'completed' ? 'text-green-400' : 'text-blue-400' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Rating Form -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-yellow-500/20">
            <form method="POST" action="{{ route('rating.store', $booking->id) }}">
                @csrf
                
                <!-- Star Rating -->
                <div class="mb-6">
                    <label class="block text-gray-300 mb-4 text-center text-lg font-semibold">
                        How was your experience?
                    </label>
                    <div class="flex justify-center space-x-2 mb-2" id="starRating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden" 
                                   {{ old('rating') == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" class="text-4xl cursor-pointer transition-all duration-200 
                                {{ old('rating') && old('rating') >= $i ? 'text-yellow-400' : 'text-gray-600' }} 
                                hover:text-yellow-300 hover:scale-110 star-label">
                                ★
                            </label>
                        @endfor
                    </div>
                    <div class="text-center">
                        <span id="ratingText" class="text-gray-400 text-sm">
                            {{ old('rating') ? old('rating') . ' stars' : 'Select a rating' }}
                        </span>
                        @error('rating')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Comment -->
                <div class="mb-6">
                    <label for="comment" class="block text-gray-300 mb-2">
                        <i class="fas fa-comment mr-2"></i>Comment (Optional)
                    </label>
                    <textarea id="comment" name="comment" rows="3" 
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-xl p-4 text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all duration-300"
                        placeholder="Share your experience...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('booking.my-bookings') }}" 
                       class="flex-1 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 rounded-xl text-center transition-all duration-300 transform hover:-translate-y-1 border border-gray-600/30">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-500 hover:to-amber-500 text-white font-bold py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-label');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingText = document.getElementById('ratingText');
    
    const ratingDescriptions = {
        1: 'Very Poor',
        2: 'Poor',
        3: 'Average',
        4: 'Good',
        5: 'Excellent'
    };
    
    // Set initial state
    ratingInputs.forEach(input => {
        if (input.checked) {
            updateStars(parseInt(input.value));
        }
    });
    
    // Star click event
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('for').replace('star', '');
            updateStars(parseInt(value));
        });
    });
    
    // Star hover event
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const value = this.getAttribute('for').replace('star', '');
            previewStars(parseInt(value));
        });
    });
    
    // Reset on mouse out
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            updateStars(parseInt(checkedInput.value));
        } else {
            resetStars();
        }
    });
    
    function updateStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('for').replace('star', ''));
            if (starValue <= value) {
                star.classList.remove('text-gray-600');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-600');
            }
        });
        
        ratingText.textContent = value + ' stars - ' + ratingDescriptions[value];
    }
    
    function previewStars(value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('for').replace('star', ''));
            if (starValue <= value) {
                star.classList.add('text-yellow-300');
            } else {
                star.classList.remove('text-yellow-300');
            }
        });
    }
    
    function resetStars() {
        stars.forEach(star => {
            star.classList.remove('text-yellow-300');
        });
        ratingText.textContent = 'Select a rating';
    }
});
</script>
@endsection