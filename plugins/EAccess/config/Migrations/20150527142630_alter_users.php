<?php
use Phinx\Migration\AbstractMigration;

class AlterUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->removeColumn('updated')
            ->removeColumn('updated_by')
            ->removeColumn('is_deactivated')
            ->addColumn('modified_by', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
                'after' => 'created_by'
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
                'after' => 'created_by'
            ])
            ->addColumn('is_deactivated', 'boolean', [
                'default' => 0,
                'null' => true,
                'after' => 'ad_groups'
            ])
            ->update();

        $table = $this->table('groups');
        $table->removeColumn('updated')
            ->removeColumn('updated_by')
            ->addColumn('modified_by', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
                'after' => 'created_by'
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
                'after' => 'created_by'
            ])
            ->update();

        $table = $this->table('users_groups');
        $table->removeColumn('updated')
            ->removeColumn('updated_by')
            ->addColumn('modified_by', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
                'after' => 'created_by'
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => false,
                'after' => 'created_by'
            ])
            ->update();

        $this->execute("
          INSERT INTO groups (id, name_eng, name_fra, acronym, description_eng, description_fra, created, created_by, modified, modified_by)
          VALUES (1, 'Admin', 'Admin', 'ADMIN', 'Administrator users. (System generated group)', 'Utilisateur administrateur (Groupe ajouté par le system).', NOW(), 1, NOW(), 1)");
    }
}
