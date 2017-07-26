<?php

use Illuminate\Database\Migrations\Migration;
use Konekt\Acl\Models\RoleProxy;
use Konekt\Acl\PermissionRegistrar;
use Konekt\AppShell\Acl\ResourcePermissions;

class CreateStiftPermissions extends Migration
{
    protected $resources = ['project', 'issue_type', 'severity', 'issue'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminRole = RoleProxy::where(['name' => 'admin'])->first();

        $permissions = ResourcePermissions::createPermissionsForResource($this->resources);

        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ResourcePermissions::deletePermissionsForResource($this->resources);
    }
}
