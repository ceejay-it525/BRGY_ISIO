<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIndigentsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // ── Resident link ──────────────────────────────────────────────
            'resident_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],

            // ── Basic info ─────────────────────────────────────────────────
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            // ── Classification ─────────────────────────────────────────────
            'indigency_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'assistance_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'assistance_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
                'default'    => '0.00',
            ],

            // ── Status ─────────────────────────────────────────────────────
            // Values: Pending Assessment | Approved | Completed | Rejected
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
                'default'    => 'Pending Assessment',
            ],

            // ── Workflow timestamps ────────────────────────────────────────
            'date_assessed' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'rejected_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rejected_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'rejected_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'date_provided' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'completed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],

            // ── Meta / Document ────────────────────────────────────────────
            'purpose' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'remarks' => [
                'type'       => 'VARCHAR',
                'constraint' => 1000,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'certificate_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'issue_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            // ── Audit ──────────────────────────────────────────────────────
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('resident_id');
        $this->forge->addKey('status');
        $this->forge->addKey('indigency_category');
        $this->forge->addKey('date_assessed');
        $this->forge->addUniqueKey('certificate_no');

        $this->forge->createTable('indigents', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('indigents', true);
    }
}
