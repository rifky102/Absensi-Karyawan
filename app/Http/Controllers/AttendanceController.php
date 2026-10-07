<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show attendance page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get location untuk unit user
        $location = Location::where('unit_id', $user->unit_id)
            ->where('is_active', true)
            ->first();

        $today = Carbon::today();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        return view('attendance.index', compact('location', 'todayAttendance'));
    }

    /**
     * Handle check-in with geofencing
     */
    public function checkIn(Request $request)
    {
        \Log::info('CheckIn payload', $request->all());
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string', // Base64 encoded photo
        ]);

        \Log::info('CheckIn Request', ['photo' => !empty($validated['photo']) ? 'present' : 'empty']);

        $user = Auth::user();
        $location = Location::where('unit_id', $user->unit_id)
            ->where('is_active', true)
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi gedung unit Anda belum dikonfigurasi',
            ], 422);
        }

        $distance = $this->calculateDistance(
            $location->latitude,
            $location->longitude,
            $validated['latitude'],
            $validated['longitude']
        );

        $today = Carbon::today();
        
        // Check if user is guru and outside radius
        if ($user->role === 'guru' && $distance > $location->radius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius. Jarak saat ini: " . round($distance, 2) . "m dari gedung.",
            ], 403);
        }

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'attendance_date' => $today,
            ],
            [
                'location_id' => $location->id,
                'check_in_time' => Carbon::now(),
                'check_in_latitude' => $validated['latitude'],
                'check_in_longitude' => $validated['longitude'],
                'status' => 'present',
            ]
        );

        if ($attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini',
            ], 422);
        }

        $attendance->update([
            'location_id' => $location->id,
            'check_in_time' => Carbon::now(),
            'check_in_latitude' => $validated['latitude'],
            'check_in_longitude' => $validated['longitude'],
            'status' => 'present',
        ]);

        // Handle photo upload
        $photoPath = null;
        \Log::info('Photo check', ['photo_value' => $validated['photo'], 'photo_length' => strlen($validated['photo'] ?? '')]);
        
        if (!empty($validated['photo'])) {
            $photoPath = $this->saveBase64Photo($validated['photo'], 'checkin');
            if ($photoPath) {
                $attendance->update(['checkin_photo' => $photoPath]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!' . ($distance > 0 ? ' (Jarak: ' . round($distance, 2) . 'm)' : ''),
            'data' => $attendance,
            'distance' => round($distance, 2),
        ]);
    }

    /**
     * Handle check-out with geofencing
     */
    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string', // Base64 encoded photo
        ]);

        $user = Auth::user();
        $location = Location::where('unit_id', $user->unit_id)
            ->where('is_active', true)
            ->first();

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi gedung unit Anda belum dikonfigurasi',
            ], 422);
        }

        $distance = $this->calculateDistance(
            $location->latitude,
            $location->longitude,
            $validated['latitude'],
            $validated['longitude']
        );

        // Check if user is guru and outside radius
        if ($user->role === 'guru' && $distance > $location->radius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius. Jarak saat ini: " . round($distance, 2) . "m dari gedung.",
            ], 403);
        }

        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini',
            ], 422);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini',
            ], 422);
        }

        $attendance->update([
            'check_out_time' => Carbon::now(),
            'check_out_latitude' => $validated['latitude'],
            'check_out_longitude' => $validated['longitude'],
        ]);

        // Handle photo upload
        $photoPath = null;
        if (!empty($validated['photo'])) {
            $photoPath = $this->saveBase64Photo($validated['photo'], 'checkout');
            if ($photoPath) {
                $attendance->update(['checkout_photo' => $photoPath]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil!' . ($distance > 0 ? ' (Jarak: ' . round($distance, 2) . 'm)' : ''),
            'data' => $attendance,
            'distance' => round($distance, 2),
        ]);
    }

    /**
     * Show attendance history
     */
    public function history()
    {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
            ->orderByDesc('attendance_date')
            ->paginate(20);

        return view('attendance.history', compact('attendances'));
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(sin($latDelta / 2) ** 2 +
            cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2));

        return $angle * $earthRadius; // Distance in meters
    }

    /**
     * Save base64 encoded photo from camera
     */
    private function saveBase64Photo($base64Data, $type = 'checkin')
    {
        try {
            \Log::info('Starting saveBase64Photo', ['type' => $type, 'data_length' => strlen($base64Data)]);
            
            // Remove data:image/png;base64, prefix
            if (strpos($base64Data, 'data:image') === 0) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            }

            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                \Log::error('Base64 decode failed');
                return null;
            }

            \Log::info('Base64 decoded', ['imageData_length' => strlen($imageData)]);

            $fileName = 'attendance-' . $type . '-' . time() . '.jpg';
            $path = 'attendance_photos/' . date('Y-m-d');
            
            $fullPath = $path . '/' . $fileName;
            
            \Illuminate\Support\Facades\Storage::disk('public')->put($fullPath, $imageData);

            \Log::info('Photo saved successfully', ['path' => $fullPath]);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Error saving base64 photo: ' . $e->getMessage());
            return null;
        }
    }
}
