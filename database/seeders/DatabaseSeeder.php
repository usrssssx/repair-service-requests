<?php

namespace Database\Seeders;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dispatcher = User::create([
            'name' => 'Диспетчер',
            'email' => 'dispatcher@example.com',
            'password' => bcrypt('password'),
            'role' => 'dispatcher',
        ]);

        $masterOne = User::create([
            'name' => 'Мастер Иван',
            'email' => 'master1@example.com',
            'password' => bcrypt('password'),
            'role' => 'master',
        ]);

        $masterTwo = User::create([
            'name' => 'Мастер Анна',
            'email' => 'master2@example.com',
            'password' => bcrypt('password'),
            'role' => 'master',
        ]);

        RepairRequest::create([
            'client_name' => 'Иван Петров',
            'phone' => '+7 900 111-22-33',
            'address' => 'ул. Лесная, 10',
            'problem_text' => 'Не работает розетка в комнате.',
            'status' => RequestStatus::New,
            'assigned_to' => null,
        ]);

        RepairRequest::create([
            'client_name' => 'Мария Смирнова',
            'phone' => '+7 900 222-33-44',
            'address' => 'пр. Мира, 5',
            'problem_text' => 'Течёт кран на кухне.',
            'status' => RequestStatus::Assigned,
            'assigned_to' => $masterOne->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Алексей Кузнецов',
            'phone' => '+7 900 333-44-55',
            'address' => 'ул. Центральная, 25',
            'problem_text' => 'Не включается свет в коридоре.',
            'status' => RequestStatus::InProgress,
            'assigned_to' => $masterOne->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Ольга Николаева',
            'phone' => '+7 900 444-55-66',
            'address' => 'ул. Школьная, 3',
            'problem_text' => 'Сломался замок входной двери.',
            'status' => RequestStatus::Done,
            'assigned_to' => $masterTwo->id,
        ]);

        RepairRequest::create([
            'client_name' => 'Павел Орлов',
            'phone' => '+7 900 555-66-77',
            'address' => 'ул. Набережная, 18',
            'problem_text' => 'Не греет батарея.',
            'status' => RequestStatus::Canceled,
            'assigned_to' => null,
        ]);
    }
}
