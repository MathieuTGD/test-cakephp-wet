<?php
use Phinx\Migration\AbstractMigration;

class CreateUsersGroups extends AbstractMigration
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
        $table = $this->table('users_groups');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('group_id', 'integer', [
            'default' => null,
            'limit' => 11,
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
