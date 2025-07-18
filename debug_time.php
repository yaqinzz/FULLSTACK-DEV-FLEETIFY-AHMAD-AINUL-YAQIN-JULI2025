<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;

// Simulate the exact same logic as in controller
$clockInStr = '2025-07-17 23:01:48';
$maxClockInStr = '22:00:00';

$clockInTime = Carbon::parse($clockInStr);
echo "Clock In Time: " . $clockInTime->format('Y-m-d H:i:s') . "\n";

// Add seconds if missing
if (strlen($maxClockInStr) === 5) {
    $maxClockInStr .= ':00';
}
echo "Max Clock In String: " . $maxClockInStr . "\n";

// Create max clock in time for same date
$maxClockIn = Carbon::createFromFormat('Y-m-d H:i:s', $clockInTime->format('Y-m-d') . ' ' . $maxClockInStr);
echo "Max Clock In Time: " . $maxClockIn->format('Y-m-d H:i:s') . "\n";

// Compare
$isLate = $clockInTime->gt($maxClockIn);
echo "Is Late?: " . ($isLate ? 'YES' : 'NO') . "\n";

if ($clockInTime->lte($maxClockIn)) {
    $status = 'On Time';
    $late_minutes = 0;
} else {
    $status = 'Late';
    $late_minutes = abs($clockInTime->diffInMinutes($maxClockIn));
}

echo "Status: " . $status . "\n";
echo "Late Minutes: " . $late_minutes . "\n";
