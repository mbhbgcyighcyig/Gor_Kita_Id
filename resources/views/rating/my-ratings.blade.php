@extends('layout')

@section('title', 'My Ratings - GotKita.ID')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">My Ratings</h1>
            <p class="text-gray-600">Rating dan review yang telah Anda berikan</p>
        </div>

        @if($ratings->count() > 0)
            <div class="space-y-6">
                @foreach($ratings as $rating)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $rating->field->name }}</h3>
                            <div class="flex items-center mt-1">
                                <div class="text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-gray-600 text-sm ml-2">
                                    {{ $rating->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">
                            {{ $rating->rating }}/5
                        </span>
                    </div>

                    @if($rating->review)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-gray-700 leading-relaxed">{{ $rating->review }}</p>
                    </div>
                    @endif

                    <div class="text-sm text-gray-500">
                        Booking #{{ $rating->booking_id }} • {{ $rating->field->type }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $ratings->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Rating</h3>
                <p class="text-gray-500 mb-6">Anda belum memberikan rating untuk booking apapun.</p>
                <a href="{{ route('booking.my-bookings') }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Lihat Booking Saya
                </a>
            </div>
        @endif
    </div>
</div>
@endsection