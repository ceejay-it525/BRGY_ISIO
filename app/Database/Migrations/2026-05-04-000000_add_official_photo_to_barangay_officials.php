<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOfficialPhotoToBarangayOfficials extends Migration
{
    public function up()
    {
        $fields = [
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
            ],
        ];

        $this->forge->addColumn('barangay_officials', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('barangay_officials', 'photo');
    }
}
