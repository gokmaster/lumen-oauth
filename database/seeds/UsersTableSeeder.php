<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'Mark',
            'email' => 'mark@test.com',
            'password' => password_hash('mark1', PASSWORD_BCRYPT)
        ]);
        factory(App\User::class)->create([
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => password_hash('john', PASSWORD_BCRYPT)
        ]);
    }
}
