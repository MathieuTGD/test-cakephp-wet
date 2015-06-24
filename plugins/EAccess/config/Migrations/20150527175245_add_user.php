<?php
use Phinx\Migration\AbstractMigration;

class AddUser extends AbstractMigration
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
        $this->execute("
          INSERT INTO users (id, first_name, last_name, email, username, ad_groups, is_deactivated, created, created_by, modified, modified_by)
          VALUES (1, 'system', 'system', 'xglf-aps-spa@dfo-mpo.gc.ca', 'system', null, 0, NOW(), 1, NOW(), 1)");
    }
}
