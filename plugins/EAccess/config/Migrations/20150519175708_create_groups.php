<?php
use Phinx\Migration\AbstractMigration;

class CreateGroups extends AbstractMigration
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
        $table = $this->table('groups');
        $table->addColumn('name_eng', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('name_fra', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('acronym', 'string', [
            'default' => null,
            'limit' => 25,
            'null' => false,
        ]);
        $table->addColumn('description_eng', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('description_fra', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('is_removed', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created_by', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('updated', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('updated_by', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
        ]);
        $table->create();

    }
}
