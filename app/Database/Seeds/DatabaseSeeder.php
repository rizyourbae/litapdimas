<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('AdminSeeder');
        $this->call('AnnouncementSeeder');
        $this->call('CreateTestDosen');
        $this->call('LandingPageSeeder');
        $this->call('MasterSeeder');
        $this->call('ProposalMasterSeeder');
    }
}
