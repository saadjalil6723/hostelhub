<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Room;
use App\Models\RoomAllocation;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Populates the app with realistic-looking sample data for local visual
 * testing — rooms, residents, allocations, payments, services, gallery
 * images, and contact messages.
 *
 * Placeholder images for services/gallery are generated LOCALLY as SVG
 * files (no external HTTP calls) so they always render, regardless of
 * firewalls, antivirus, or network policies on your machine blocking
 * outbound requests to image-placeholder services.
 *
 * Requires `php artisan storage:link` to have been run at least once, same
 * as any real admin-uploaded image.
 *
 * Intentionally NOT included in DatabaseSeeder's default run, so a real
 * deployment's `php artisan migrate --seed` only ever creates the admin
 * account. Run this one explicitly:
 *
 *   php artisan db:seed --class=DemoDataSeeder
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $rooms = $this->seedRooms();
            $residents = $this->seedResidents();
            $this->seedAllocationsAndPayments($residents, $rooms);
            $this->seedServices();
            $this->seedGallery();
            $this->seedContactMessages();
        });

        $this->command?->info('Demo data seeded: rooms, residents, allocations, payments, services, gallery, and contact messages.');
    }

    /**
     * Generates a simple SVG placeholder image, writes it to the public
     * storage disk, and returns the relative path to store in an `image`
     * column — exactly the same shape as a real admin file upload, so no
     * changes are needed anywhere else in the app to display it.
     */
    private function placeholderImage(string $label, string $bgHex, string $fgHex = 'F2ECDF', int $width = 600, int $height = 400): string
    {
        $safeLabel = htmlspecialchars($label, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $fontSize = (int) round($width / 14);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="#{$bgHex}"/>
    <text x="50%" y="50%" font-family="Arial, Helvetica, sans-serif" font-size="{$fontSize}" fill="#{$fgHex}" text-anchor="middle" dominant-baseline="middle">{$safeLabel}</text>
</svg>
SVG;

        $filename = 'placeholders/'.Str::slug($label).'-'.Str::random(6).'.svg';

        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }

    private function seedRooms(): \Illuminate\Support\Collection
    {
        $definitions = [
            ['room_number' => 'A101', 'floor' => 'Ground', 'room_type' => 'Single', 'capacity' => 1, 'price' => 18000, 'facilities' => 'AC, Attached Bath, Wi-Fi'],
            ['room_number' => 'A102', 'floor' => 'Ground', 'room_type' => 'Single', 'capacity' => 1, 'price' => 18000, 'facilities' => 'AC, Attached Bath, Wi-Fi'],
            ['room_number' => 'A103', 'floor' => 'Ground', 'room_type' => 'Double Sharing', 'capacity' => 2, 'price' => 13000, 'facilities' => 'Fan, Shared Bath, Wi-Fi'],
            ['room_number' => 'A104', 'floor' => 'Ground', 'room_type' => 'Double Sharing', 'capacity' => 2, 'price' => 13000, 'facilities' => 'Fan, Shared Bath, Wi-Fi'],
            ['room_number' => 'B201', 'floor' => '1st', 'room_type' => 'Triple Sharing', 'capacity' => 3, 'price' => 9500, 'facilities' => 'Fan, Shared Bath'],
            ['room_number' => 'B202', 'floor' => '1st', 'room_type' => 'Triple Sharing', 'capacity' => 3, 'price' => 9500, 'facilities' => 'Fan, Shared Bath'],
            ['room_number' => 'B203', 'floor' => '1st', 'room_type' => 'Double Sharing', 'capacity' => 2, 'price' => 14000, 'facilities' => 'AC, Attached Bath, Wi-Fi'],
            ['room_number' => 'B204', 'floor' => '1st', 'room_type' => 'Single', 'capacity' => 1, 'price' => 19500, 'facilities' => 'AC, Attached Bath, Wi-Fi, Balcony'],
            ['room_number' => 'C301', 'floor' => '2nd', 'room_type' => 'Quad Sharing', 'capacity' => 4, 'price' => 8000, 'facilities' => 'Fan, Shared Bath'],
            ['room_number' => 'C302', 'floor' => '2nd', 'room_type' => 'Quad Sharing', 'capacity' => 4, 'price' => 8000, 'facilities' => 'Fan, Shared Bath'],
            ['room_number' => 'C303', 'floor' => '2nd', 'room_type' => 'Double Sharing', 'capacity' => 2, 'price' => 13500, 'facilities' => 'AC, Shared Bath, Wi-Fi'],
            ['room_number' => 'C304', 'floor' => '2nd', 'room_type' => 'Single', 'capacity' => 1, 'price' => 20000, 'facilities' => 'AC, Attached Bath, Wi-Fi'],
        ];

        $rooms = collect($definitions)->map(fn ($def) => Room::create([
            ...$def,
            'current_occupancy' => 0,
            'status' => 'available',
            'description' => 'Comfortable, well-ventilated room with regular housekeeping.',
        ]));

        // Mark one room as maintenance explicitly (illustrative empty/unavailable state).
        $rooms->last()->update(['status' => 'maintenance']);

        return $rooms;
    }

    private function seedResidents(): \Illuminate\Support\Collection
    {
        $named = collect([
            ['name' => 'Ahmed Raza', 'guardian_name' => 'Muhammad Raza', 'id_type' => 'cnic', 'identification_number' => '35201-1234567-1', 'phone' => '03001234567', 'email' => 'ahmed.raza@example.com'],
            ['name' => 'Bilal Hussain', 'guardian_name' => 'Iftikhar Hussain', 'id_type' => 'cnic', 'identification_number' => '35202-2345678-2', 'phone' => '03011234567', 'email' => 'bilal.hussain@example.com'],
            ['name' => 'Zainab Fatima', 'guardian_name' => 'Tariq Mehmood', 'id_type' => 'cnic', 'identification_number' => '35203-3456789-3', 'phone' => '03021234567', 'email' => 'zainab.fatima@example.com'],
            ['name' => 'Hassan Ali', 'guardian_name' => 'Anwar Ali', 'id_type' => 'cnic', 'identification_number' => '35204-4567890-4', 'phone' => '03031234567', 'email' => 'hassan.ali@example.com'],
            ['name' => 'Sara Khan', 'guardian_name' => 'Imran Khan', 'id_type' => 'cnic', 'identification_number' => '35205-5678901-5', 'phone' => '03041234567', 'email' => 'sara.khan@example.com'],
            ['name' => 'John Miller', 'guardian_name' => null, 'id_type' => 'passport', 'identification_number' => 'US4487213', 'phone' => '03051234567', 'email' => 'john.miller@example.com'],
            ['name' => 'Ayesha Siddiqui', 'guardian_name' => 'Rashid Siddiqui', 'id_type' => 'cnic', 'identification_number' => '35206-6789012-6', 'phone' => '03061234567', 'email' => 'ayesha.siddiqui@example.com'],
            ['name' => 'Umer Farooq', 'guardian_name' => 'Farooq Ahmed', 'id_type' => 'cnic', 'identification_number' => '35207-7890123-7', 'phone' => '03071234567', 'email' => 'umer.farooq@example.com'],
        ])->map(fn ($r) => [
            ...$r,
            'address' => 'Lahore, Punjab, Pakistan',
            'emergency_contact' => '0311'.rand(1000000, 9999999),
            'check_in_date' => now()->subMonths(rand(1, 6)),
            'status' => 'active',
        ]);

        $residents = $named->map(fn ($r) => Resident::create($r));

        // Bulk filler residents via factory, for pagination testing (index paginates 15/page).
        $filler = Resident::factory()->count(14)->create();

        // A few checked-out / inactive residents to exercise those states + history guards.
        $checkedOut = Resident::factory()->count(3)->create([
            'status' => 'checked_out',
            'check_in_date' => now()->subMonths(8),
            'check_out_date' => now()->subDays(rand(5, 40)),
        ]);

        return $residents->concat($filler)->concat($checkedOut);
    }

    private function seedAllocationsAndPayments($residents, $rooms): void
    {
        $activeResidents = $residents->where('status', 'active')->values();
        $checkedOutResidents = $residents->where('status', 'checked_out')->values();
        $availableRooms = $rooms->where('status', '!=', 'maintenance')->values();

        // Leave the last couple of active residents unassigned, to show that
        // "no current allocation" empty state on the resident detail page too.
        $toAllocate = $activeResidents->slice(0, max(0, $activeResidents->count() - 2));

        foreach ($toAllocate as $resident) {
            $room = $availableRooms->first(fn (Room $r) => $r->activeAllocations()->count() < $r->capacity);

            if (! $room) {
                break; // every room is full — stop rather than silently overfilling one.
            }

            $allocation = RoomAllocation::create([
                'resident_id' => $resident->id,
                'room_id' => $room->id,
                'bed_number' => chr(65 + $room->activeAllocations()->count()),
                'allocation_date' => $resident->check_in_date ?? now()->subMonths(2),
                'status' => 'active',
            ]);

            $room->syncOccupancy();

            // Payment history: 1-3 past months plus this month, tied to the room's price.
            $monthsBack = rand(1, 3);
            for ($m = $monthsBack; $m >= 0; $m--) {
                Payment::create([
                    'resident_id' => $resident->id,
                    'room_allocation_id' => $allocation->id,
                    'amount' => $room->price,
                    'payment_date' => now()->subMonths($m)->startOfMonth()->addDays(rand(0, 4)),
                    'for_month' => now()->subMonths($m)->format('Y-m'),
                    'method' => collect(['cash', 'bank_transfer', 'mobile_wallet'])->random(),
                    'reference_number' => rand(0, 1) ? strtoupper(uniqid()) : null,
                ]);
            }
        }

        // Historical (ended) allocations for the checked-out residents.
        foreach ($checkedOutResidents as $i => $resident) {
            $room = $availableRooms[$i % $availableRooms->count()];

            RoomAllocation::create([
                'resident_id' => $resident->id,
                'room_id' => $room->id,
                'bed_number' => 'A',
                'allocation_date' => $resident->check_in_date,
                'checkout_date' => $resident->check_out_date,
                'status' => 'ended',
            ]);
            // No syncOccupancy needed — an ended allocation doesn't count toward capacity.
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['title' => 'High-Speed Wi-Fi', 'description' => 'Fibre-backed wireless internet available throughout the building, including all common areas.', 'color' => '1E3E37'],
            ['title' => 'Laundry Service', 'description' => 'Twice-weekly laundry pickup and delivery included in every room package.', 'color' => 'C79A46'],
            ['title' => '24/7 Security', 'description' => 'CCTV coverage and on-site security staff around the clock.', 'color' => '15302B'],
            ['title' => 'Dining Hall', 'description' => 'Three meals a day, with a rotating weekly menu and dietary options.', 'color' => '6F9C76'],
            ['title' => 'Study Lounge', 'description' => 'A quiet, well-lit common area for studying or working, open late.', 'color' => '4E655F'],
            ['title' => 'Housekeeping', 'description' => 'Regular room cleaning and common-area upkeep, scheduled weekly.', 'color' => 'A97F35'],
        ];

        foreach ($services as $s) {
            Service::create([
                'title' => $s['title'],
                'description' => $s['description'],
                'image' => $this->placeholderImage($s['title'], $s['color']),
                'status' => true,
            ]);
        }
    }

    private function seedGallery(): void
    {
        $titles = [
            'Common Room', 'Study Lounge', 'Room Interior', 'Dining Hall',
            'Rooftop Seating', 'Reception', 'Hallway', 'Laundry Area',
        ];

        foreach ($titles as $title) {
            GalleryImage::create([
                'title' => $title,
                'image' => $this->placeholderImage($title, '1E3E37', 'F2ECDF', 600, 450),
                'description' => null,
            ]);
        }
    }

    private function seedContactMessages(): void
    {
        $messages = [
            ['name' => 'Fahad Iqbal', 'email' => 'fahad.iqbal@example.com', 'subject' => 'Room availability', 'message' => 'Hi, do you have any single rooms available for next month? I need a move-in date around the 5th.', 'status' => 'unread'],
            ['name' => 'Mariam Yousaf', 'email' => 'mariam.y@example.com', 'subject' => 'Visiting hours', 'message' => 'Could you let me know your visiting hours policy for family members?', 'status' => 'unread'],
            ['name' => 'Kamran Sheikh', 'email' => 'kamran.sheikh@example.com', 'phone' => '03211234567', 'subject' => 'Pricing for triple sharing', 'message' => 'What is the monthly rate for a triple sharing room, and is a security deposit required?', 'status' => 'read'],
            ['name' => 'Nadia Chaudhry', 'email' => 'nadia.c@example.com', 'subject' => 'Wi-Fi speed', 'message' => 'What internet speed can residents expect? I work remotely and need something reliable.', 'status' => 'read'],
            ['name' => 'Talha Aslam', 'email' => null, 'phone' => '03451234567', 'subject' => null, 'message' => 'Is there parking available for residents with motorbikes?', 'status' => 'unread'],
        ];

        foreach ($messages as $m) {
            ContactMessage::create($m);
        }
    }
}