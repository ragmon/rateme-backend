<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\Photo;
use App\Models\Rate;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->has(
            Photo::factory()->sequence(fn () => [
                'is_main' => true
            ])
        )->has(
            Phone::factory()->count(2)
        )->has(
            Skill::factory()->count(5)
        )->has(
            Rate::factory()->count(10)
        )->has(
            Contact::factory()
                ->has(Phone::factory()->count(2))
                ->has(Photo::factory())
                ->count(5)
        )->count(5)->create();

        $this->createGeneralContacts($users);
    }

    private function createGeneralContacts(Collection $users)
    {
        foreach ($users as $user) {
            /** @var User $user */
            foreach ($user->contacts as $contact) {
                do {
                    $secondUser = $users->random();
                } while ($secondUser == $user);
                $phone = Phone::factory()->sequence(fn() => [
                    'phone' => $secondUser->phones->random()->phone,
                ])->create();

                /** @var Contact $contact */
                $contact->phones()->save($phone);
            }
        }
    }
}
