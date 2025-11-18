<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\StudioClass;
use App\Models\Schedule;
use App\Models\Membership;
use App\Models\UserMembership;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_non_member_is_redirected_to_payment()
    {
        $user = User::factory()->create();
        $class = StudioClass::factory()->create();
        $schedule = Schedule::factory()->create(['studio_class_id' => $class->id, 'price' => 50000]);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertRedirect(route('bayar_kelas', ['schedule' => $schedule->id]));
    }

    public function test_member_can_book_class_for_free()
    {
        $user = User::factory()->create();
        $membership = Membership::factory()->create(['price' => 100000]);
        UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addMonth(),
        ]);
        $class = StudioClass::factory()->create();
        $schedule = Schedule::factory()->create([
            'studio_class_id' => $class->id,
            'price' => 50000,
            'start_time' => Carbon::now()->addWeek(), // Ensure within membership
            'end_time' => Carbon::now()->addWeek()->addHours(2),
        ]);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'schedule_id' => $schedule->id,
        ])->dump();

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    public function test_member_cannot_book_class_outside_membership_period()
    {
        $user = User::factory()->create();
        $membership = Membership::factory()->create(['price' => 100000]);
        UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->subDay(), // Membership expired yesterday
        ]);
        $class = StudioClass::factory()->create();
        $schedule = Schedule::factory()->create([
            'studio_class_id' => $class->id,
            'price' => 50000,
            'start_time' => Carbon::now()->addWeek(), // Class is in the future
            'end_time' => Carbon::now()->addWeek()->addHours(2),
        ]);

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertSessionHas('error', 'The selected class date is outside your active membership period.');
        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
        ]);
    }

    public function test_user_cannot_book_the_same_class_twice()
    {
        $user = User::factory()->create();
        $membership = Membership::factory()->create(['price' => 100000]);
        UserMembership::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addMonth(),
        ]);
        $class = StudioClass::factory()->create();
        $schedule = Schedule::factory()->create(['studio_class_id' => $class->id, 'price' => 50000]);

        // First booking
        $this->actingAs($user)->post(route('booking.store'), [
            'schedule_id' => $schedule->id,
        ]);

        // Second booking attempt
        $response = $this->actingAs($user)->post(route('booking.store'), [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertSessionHas('error', 'You are already enrolled in this class.');
    }
}
