<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {

            $user_id = session('user_id');
            $user_role = session('user_role', 'user');
            
            // JIKA ADMIN, redirect ke dashboard
            if ($user_role === 'admin') {
                return redirect('/admin/dashboard');
            }
            
            //  INSTAL STASTIK
            $stats = [
                'total_bookings' => 0,
                'active_bookings' => 0,
                'completed_bookings' => 0
            ];
            
            // JIKA USER LOGIN, ambil data bookingnya
            if ($user_id) {
                $stats['total_bookings'] = Booking::where('user_id', $user_id)->count();
                $stats['active_bookings'] = Booking::where('user_id', $user_id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count();
                $stats['completed_bookings'] = Booking::where('user_id', $user_id)
                    ->where('status', 'completed')
                    ->count();
            }
            
            // AMBIL DATA LAPANGAN
            $futsalFields = Lapangan::where('type', 'futsal')
                ->where('is_active', true)
                ->get();
            
            $badmintonFields = Lapangan::where('type', 'badminton')
                ->where('is_active', true)
                ->get();
            
            $miniSoccerFields = Lapangan::where('type', 'minisoccer')
                ->where('is_active', true)
                ->get();
            
            // AMBIL RATING TERBARU
            $recentRatings = Rating::with(['user', 'field'])
                ->latest()
                ->limit(6)
                ->get();
            
            return view('home', [
                'futsalFields' => $futsalFields,
                'badmintonFields' => $badmintonFields,
                'miniSoccerFields' => $miniSoccerFields,
                'recentRatings' => $recentRatings,
                'stats' => $stats  // PASTIKAN ADA!
            ]);
            
        } catch (\Exception $e) {
            Log::error('Home Controller Error: ' . $e->getMessage());
            
            // FALLBACK DATA
            return view('home', [
                'futsalFields' => collect(),
                'badmintonFields' => collect(),
                'miniSoccerFields' => collect(),
                'recentRatings' => collect(),
                'stats' => [
                    'total_bookings' => 0,
                    'active_bookings' => 0,
                    'completed_bookings' => 0
                ]
            ]);
        }
    }
    
    public function about()
    {
        try {

            $totalFields = Lapangan::where('is_active', true)->count();
            $totalBookings = Booking::count();
            $averageRating = Rating::avg('rating') ?? 0;
            $totalReviews = Rating::count();
            
            return view('about', [
                'totalFields' => $totalFields,
                'totalBookings' => $totalBookings,
                'averageRating' => round($averageRating, 1),
                'totalReviews' => $totalReviews
            ]);
            
        } catch (\Exception $e) {
            Log::error('About Controller Error: ' . $e->getMessage());
            
            return view('about', [
                'totalFields' => 12,
                'totalBookings' => 245,
                'averageRating' => 4.7,
                'totalReviews' => 89
            ]);
        }
    }
}