<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Increase memory limit for seeding
        ini_set('memory_limit', '1G');
        // Disable query log to improve performance
        DB::disableQueryLog();

        // Start a transaction to improve performance
        DB::beginTransaction();

        try {
            // Use Faker to generate fake data for addresses
            $faker = Faker::create();

            // Batch size for seeding to avoid memory overload
            $batchSize = 5000;
            $totalUsers = 1000000; // You can also dynamically determine this number

            for ($i = 0; $i < $totalUsers; $i += $batchSize) {
                // Create the users in batches
                $users = [];
                $addresses = [];

                for ($j = 0; $j < $batchSize; $j++) {
                    $userData = [
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'email' => $faker->unique()->safeEmail,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $users[] = $userData;

                    // Generate address data for the user
                    $addresses[] = [
                        'user_id' => null,  // This will be updated later
                        'country' => $faker->country,
                        'city' => $faker->city,
                        'street' => $faker->streetAddress,
                        'postcode' => $faker->postcode,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert the users in one batch and retrieve the inserted IDs
                DB::table('users')->insert($users);

                // Get the last inserted user IDs using a query
                $userIds = DB::table('users')
                    ->orderBy('id', 'desc')
                    ->take($batchSize)
                    ->pluck('id')
                    ->toArray();  // Retrieve the user ids for this batch

                // Update the user_id for the addresses
                foreach ($addresses as $index => $address) {
                    $addresses[$index]['user_id'] = $userIds[$index];
                }

                // Insert the addresses in one batch
                DB::table('addresses')->insert($addresses);
            }

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback transaction if something fails
            DB::rollBack();
            \Log::error('Seeding error: ' . $e->getMessage());
            throw $e;  // Rethrow the error after rollback
        }
    }
}
