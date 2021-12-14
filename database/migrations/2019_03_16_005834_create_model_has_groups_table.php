<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Junges\ACL\AclRegistrar;

class CreateModelHasGroupsTable extends Migration
{
    public function up()
    {
        $columnNames = config('acl.column_names');

        $userHasGroupsTable = config('acl.tables.user_has_groups', 'user_has_groups');
        $usersTable = config('acl.tables.users', 'users');
        $groupsTable = config('acl.tables.groups', 'groups');

        Schema::create($userHasGroupsTable, function (Blueprint $table) use ($usersTable, $groupsTable, $columnNames) {
            $table->unsignedBigInteger(AclRegistrar::$pivotGroup);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_groups_model_id_model_type_index');

            $table->foreign(AclRegistrar::$pivotGroup)
                ->references('id')
                ->on($groupsTable)
                ->cascadeOnDelete();

            $table->primary([AclRegistrar::$pivotGroup, $columnNames['model_morph_key'], 'model_type'],
                'model_has_groups_group_model_type_primary');
        });
    }

    public function down()
    {
        $userHasGroupsTable = config('acl.tables.user_has_groups', 'user_has_groups');
        Schema::dropIfExists($userHasGroupsTable);
    }
}
