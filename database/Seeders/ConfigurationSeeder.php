<?php

namespace Database\Seeders;

use App\Domains\Configuration\Model\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        $configurations = [
            ['key' => 'general.debug', 'value' => 'true'],
            ['key' => 'general.url', 'value' => 'http://127.0.0.1:8000'],
            ['key' => 'general.locale', 'value' => 'en'],
            ['key' => 'general.timezone', 'value' => 'UTC'],
            ['key' => 'general.version', 'value' => '2.17.0'],
            ['key' => 'user.default_locale', 'value' => 'en'],
            ['key' => 'user.default_timezone', 'value' => 'UTC'],
            ['key' => 'email.subject', 'value' => '[GPS-Tracker]'],
            ['key' => 'email.from_address', 'value' => 'no-reply@localhost'],
            ['key' => 'email.from_name', 'value' => 'GPS-Tracker'],
            ['key' => 'email.host', 'value' => null],
            ['key' => 'email.port', 'value' => null],
            ['key' => 'email.encryption', 'value' => null],
            ['key' => 'email.username', 'value' => null],
            ['key' => 'email.password', 'value' => null],
            ['key' => 'email.address', 'value' => null],
        ];

        foreach ($configurations as $configuration) {
            Configuration::updateOrCreate(
                ['key' => $configuration['key']],
                $configuration
            );
        }
    }
}