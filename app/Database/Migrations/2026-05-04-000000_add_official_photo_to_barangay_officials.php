<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOfficialPhotoToBarangayOfficials extends Migration
{
    public function up()
    {
        // Check if column already exists before adding
        $fields = $this->db->getFieldData('barangay_officials');
        $fieldNames = array_column($fields, 'name');
        
        if (!in_array('photo', $fieldNames)) {
            $columnsToAdd = [
                'photo' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                    'default' => null,
                ],
            ];

            $this->forge->addColumn('barangay_officials', $columnsToAdd);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('barangay_officials', 'photo');
    }
}
