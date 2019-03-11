<?php


use Phinx\Migration\AbstractMigration;

class Tables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function change()
    {
        $table = $this->table('gallery', ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('name', 'string', ['limit' => 150, 'null' => true, 'default' => NULL])

            ->create();

        $table = $this->table('image', ['id' => false, 'primary_key' => 'id']);
        $table
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('gallery_id', 'biginteger', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 150, 'null' => true, 'default' => NULL])

            ->addIndex(['gallery_id'])

            ->addForeignKey('gallery_id', 'gallery', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'))

            ->create();
    }
}
