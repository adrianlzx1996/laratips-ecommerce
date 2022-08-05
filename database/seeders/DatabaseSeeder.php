<?php

    namespace Database\Seeders;

    // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use App\Models\User;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run ()
        {
            // \App\Models\User::factory(10)->create();

            User::factory()->create([
                                        'name'     => 'Admin',
                                        'email'    => 'admin@admin.com',
                                        'password' => bcrypt('password'),
                                    ]);

            User::factory()->create([
                                        'name'     => 'Editor',
                                        'email'    => 'editor@editor.com',
                                        'password' => bcrypt('password'),
                                    ]);

            $this->call(RolesSeeder::class);
        }
    }
