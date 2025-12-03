@extends('layout')

@section('title', 'Ulasan & Rating - GotKita.ID')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-2 bg-gray-800/50 rounded-full px-6 py-3 border border-emerald-500/30 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-emerald-300 text-sm font-semibold">ULASAN KOMUNITAS</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">
                Ulasan & <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Rating</span>
            </h1>
            <p class="text-xl text-gray-400">Pengalaman nyata dari komunitas olahraga kami</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-emerald-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-emerald-300 mb-2">
                    {{ number_format(\App\Models\Rating::avg('rating'), 1) }}
                </div>
                <div class="flex justify-center text-yellow-400 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor(\App\Models\Rating::avg('rating')))
                            <i class="fas fa-star text-sm"></i>
                        @elseif($i - 0.5 <= \App\Models\Rating::avg('rating'))
                            <i class="fas fa-star-half-alt text-sm"></i>
                        @else
                            <i class="far fa-star text-sm"></i>
                        @endif
                    @endfor
                </div>
                <div class="text-gray-400 text-sm font-semibold">Rata-rata Rating</div>
            </div>

            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-cyan-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-cyan-300 mb-2">
                    {{ \App\Models\Rating::count() }}
                </div>
                <i class="fas fa-comment-dots text-cyan-400 text-xl mb-3"></i>
                <div class="text-gray-400 text-sm font-semibold">Total Ulasan</div>
            </div>

            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-green-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-green-300 mb-2">
                    {{ \App\Models\Rating::where('rating', '>=', 4)->count() }}
                </div>
                <i class="fas fa-thumbs-up text-green-400 text-xl mb-3"></i>
                <div class="text-gray-400 text-sm font-semibold">Ulasan Positif</div>
            </div>

            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-purple-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-purple-300 mb-2">
                    {{ \App\Models\User::whereHas('ratings')->count() }}
                </div>
                <i class="fas fa-users text-purple-400 text-xl mb-3"></i>
                <div class="text-gray-400 text-sm font-semibold">Jumlah Pengulas Unik</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm sticky top-6">
                    <h2 class="text-xl font-black text-white mb-6 flex items-center">
                        <i class="fas fa-chart-pie text-emerald-400 mr-3"></i>
                        Distribusi Rating
                    </h2>
                    
                    @php
                        $totalRatings = \App\Models\Rating::count();
                    @endphp
                    
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = \App\Models\Rating::where('rating', $i)->count();
                            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;
                        @endphp
                        <div class="flex items-center mb-4 group cursor-pointer" onclick="filterRatings('{{ $i }}')">
                            <div class="w-12 text-right mr-3">
                                <span class="font-black text-white text-lg">{{ $i }}</span>
                                <i class="fas fa-star text-yellow-400 text-sm ml-1"></i>
                            </div>
                            <div class="flex-1">
                                <div class="bg-white/10 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-full rounded-full transition-all duration-500 ease-out" 
                                         style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="w-16 text-left ml-3">
                                <span class="text-gray-300 text-sm group-hover:text-white transition">{{ $count }}</span>
                            </div>
                        </div>
                    @endfor

                    <div class="mt-8 pt-6 border-t border-gray-700/50">
                        <h3 class="text-lg font-black text-white mb-4">Filter Ulasan</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="filterRatings('all')" class="filter-btn active bg-emerald-500 text-white border border-emerald-400">
                                <i class="fas fa-list mr-1"></i> Semua
                            </button>
                            <button onclick="filterRatings('5')" class="filter-btn bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                <i class="fas fa-star mr-1"></i> 5★
                            </button>
                            <button onclick="filterRatings('4')" class="filter-btn bg-green-500/20 text-green-300 border border-green-500/30">
                                <i class="fas fa-star mr-1"></i> 4★
                            </button>
                            <button onclick="filterRatings('3')" class="filter-btn bg-orange-500/20 text-orange-300 border border-orange-500/30">
                                <i class="fas fa-star mr-1"></i> 3★
                            </button>
                            <button onclick="filterRatings('2')" class="filter-btn bg-red-500/20 text-red-300 border border-red-500/30">
                                <i class="fas fa-star mr-1"></i> 2★
                            </button>
                            <button onclick="filterRatings('1')" class="filter-btn bg-red-600/20 text-red-400 border border-red-600/30">
                                <i class="fas fa-star mr-1"></i> 1★
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-2">
                @if($ratings->count() > 0)
                    <div class="space-y-6" id="reviewsContainer">
                        @foreach($ratings as $rating)
                        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm transition-all duration-300 hover:transform hover:-translate-y-1 hover:border-emerald-500/40" 
                             data-rating="{{ $rating->rating }}">
                            
                            <!-- Review Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <!-- User Avatar -->
                                    <div class="relative">
                                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center text-white text-xl font-bold border border-emerald-400/30">
                                            {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                        </div>
                                        @if($rating->rating >= 4)
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center border border-white">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- User Info -->
                                    <div>
                                        <h3 class="text-lg font-black text-white">{{ $rating->user->name }}</h3>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->rating)
                                                        <i class="fas fa-star text-sm"></i>
                                                    @else
                                                        <i class="far fa-star text-sm"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-gray-400 text-sm">•</span>
                                            <span class="text-gray-400 text-sm">{{ $rating->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rating Badge -->
                                <div class="text-center">
                                    <div class="px-3 py-2 rounded-xl font-black text-white bg-gradient-to-br 
                                        @if($rating->rating >= 4) from-emerald-500 to-green-500
                                        @elseif($rating->rating >= 3) from-yellow-500 to-orange-500
                                        @else from-red-500 to-pink-500 @endif
                                        border border-white/20">
                                        <span class="text-xl">{{ $rating->rating }}</span>
                                        <i class="fas fa-star text-yellow-300 ml-1"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Text -->
                            @if($rating->review)
                            <div class="bg-white/5 rounded-xl p-4 border-l-4 border-emerald-500 mb-4">
                                <p class="text-gray-200 leading-relaxed">{{ $rating->review }}</p>
                            </div>
                            @endif

                            <!-- Additional Info -->
                            <div class="flex items-center justify-between text-sm text-gray-400 pt-4 border-t border-gray-700/50">
                                <div class="flex items-center space-x-4">
                                    @php
                                        $fieldIcon = 'fa-running';
                                        $fieldName = 'Lapangan tidak ditemukan';
                                        
                                        if ($rating->field) {
                                            $fieldName = $rating->field->name;
                                            if ($rating->field->type == 'futsal' || $rating->field->type == 'minisoccer') {
                                                $fieldIcon = 'fa-futbol';
                                            } elseif ($rating->field->type == 'badminton') {
                                                $fieldIcon = 'fa-table-tennis-paddle-ball';
                                            }
                                        }
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        <i class="fas {{ $fieldIcon }} mr-2 text-xs"></i>
                                        {{ $fieldName }}
                                    </span>
                                </div>
                                <div class="text-gray-400">
                                    #{{ $rating->id }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $ratings->links() }}
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-12 text-center border border-emerald-500/20 backdrop-blur-sm">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-700/40 to-gray-800/40 rounded-3xl flex items-center justify-center border border-gray-600/50">
                            <i class="fas fa-star text-gray-400 text-4xl"></i>
                        </div>
                        <h3 class="text-3xl font-black text-white mb-4">Belum Ada Ulasan</h3>
                        <p class="text-gray-400 text-lg mb-8">Jadilah yang pertama berbagi pengalaman!</p>
                        <a href="{{ route('booking.my-bookings') }}" 
                           class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 inline-flex items-center space-x-3">
                            <i class="fas fa-pen"></i>
                            <span>Tulis Ulasan Anda</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<script>
function filterRatings(rating) {
    const cards = document.querySelectorAll('[data-rating]');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button styles
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-emerald-500', 'text-white', 'border-emerald-400');
        btn.classList.add('bg-opacity-20', 'border-opacity-30');
    });
    
    event.target.classList.add('active', 'bg-emerald-500', 'text-white', 'border-emerald-400');
    event.target.classList.remove('bg-opacity-20', 'border-opacity-30');
    
    // Filter cards
    cards.forEach(card => {
        if (rating === 'all') {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        } else {
            if (card.dataset.rating === rating) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('[data-rating]');
    cards.forEach(card => {
        card.style.transition = 'all 0.3s ease';
    });
});
</script>

<style>
.filter-btn {
    @apply px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-300;
}

.filter-btn.active {
    @apply bg-emerald-500 text-white border-emerald-400;
}
</style>
@endsection