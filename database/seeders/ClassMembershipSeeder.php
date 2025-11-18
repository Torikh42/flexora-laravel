<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudioClass;
use App\Models\Membership;

class ClassMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing studio classes and memberships
        $classes = StudioClass::all();
        $memberships = Membership::all();

        // Attach all memberships to all classes via pivot table
        foreach ($memberships as $membership) {
            foreach ($classes as $class) {
                // Sync to avoid duplicate attachments
                $membership->studioClasses()->syncWithoutDetaching($class->id);
            }
        }
    }
}
