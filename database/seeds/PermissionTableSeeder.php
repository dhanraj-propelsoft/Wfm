<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        App\Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('permissions')->insert([
            [
        		'name' => 'register-list',
        		'display_name' => 'Display User List',
        		'description' => 'Shows List of users',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' => 'register-create',
        		'display_name' => 'Create User',
        		'description' => 'Create New User',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' => 'register-edit',
        		'display_name' => 'Edit User',
        		'description' => 'Edit User',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
        	[
        		'name' => 'register-delete',
        		'display_name' => 'Delete User',
        		'description' => 'Delete User',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        	],
            [
                'name' => 'role-list',
                'display_name' => 'Role List',
                'description' => 'Shows List of roles',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'role-create',
                'display_name' => 'Create Role',
                'description' => 'Create New Role',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'role-edit',
                'display_name' => 'Edit Role',
                'description' => 'Edit Role',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Delete Role',
                'description' => 'Delete Role',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
			[
                'name' => 'module-settings',
                'display_name' => 'Enable Modules',
                'description' => 'Enable modules to the Organization',
        		'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'business-profile-show',
                'display_name' => 'Business Profile',
                'description' => 'See business profile',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'business-profile-create',
                'display_name' => 'Create Business Profile',
                'description' => 'Create New Business Profile',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'business-profile-edit',
                'display_name' => 'Edit Business Profile',
                'description' => 'Edit Business Profile',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'business-profile-delete',
                'display_name' => 'Delete Business Profile',
                'description' => 'Delete Business Profile',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'branches-list',
                'display_name' => 'Branch List',
                'description' => 'Shows List of branches',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'branches-create',
                'display_name' => 'Create Branch',
                'description' => 'Create New Branch',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'branches-edit',
                'display_name' => 'Edit Branch',
                'description' => 'Edit Branch',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'branches-delete',
                'display_name' => 'Delete Branch',
                'description' => 'Delete Branch',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'team-list',
                'display_name' => 'Team List',
                'description' => 'Shows List of Teams',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'team-create',
                'display_name' => 'Create Team',
                'description' => 'Create New Team',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'team-edit',
                'display_name' => 'Edit Team',
                'description' => 'Edit Team',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'team-delete',
                'display_name' => 'Delete Team',
                'description' => 'Delete Team',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'assign-roles',
                'display_name' => 'Assign Roles',
                'description' => 'Assign Roles',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'utility',
                'display_name' => 'Utility',
                'description' => 'Show utility tab',
                'module' => 'admin',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-group-list',
                'display_name' => 'Ledger Group List',
                'description' => 'Shows List of ledger groups',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-group-create',
                'display_name' => 'Create Ledger Group',
                'description' => 'Create New Ledger Group',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-group-edit',
                'display_name' => 'Edit Ledger Group',
                'description' => 'Edit Ledger Group',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-group-delete',
                'display_name' => 'Delete Ledger Group',
                'description' => 'Delete Ledger Group',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-list',
                'display_name' => 'Ledger List',
                'description' => 'Shows List of ledgers',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-create',
                'display_name' => 'Create Ledger',
                'description' => 'Create New Ledger',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-edit',
                'display_name' => 'Edit Ledger',
                'description' => 'Edit Ledger',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-delete',
                'display_name' => 'Delete Ledger',
                'description' => 'Delete Ledger',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'cheque-book-list',
                'display_name' => 'Cheque Book List',
                'description' => 'Shows List of cheque books',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'cheque-book-create',
                'display_name' => 'Create Cheque Book',
                'description' => 'Create New Cheque Book',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'cheque-book-edit',
                'display_name' => 'Edit Cheque Book',
                'description' => 'Edit Cheque Book',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'cheque-book-delete',
                'display_name' => 'Delete Cheque Book',
                'description' => 'Delete Cheque Book',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-master-list',
                'display_name' => 'Voucher Master',
                'description' => 'Shows List of voucher masters',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-master-create',
                'display_name' => 'Create Master Voucher',
                'description' => 'Create New Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-master-edit',
                'display_name' => 'Edit Master Voucher',
                'description' => 'Edit Master Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-master-delete',
                'display_name' => 'Delete Master Voucher',
                'description' => 'Delete Master Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-list',
                'display_name' => 'Voucher List',
                'description' => 'Shows List of vouchers',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-create',
                'display_name' => 'Create Voucher',
                'description' => 'Create New Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-edit',
                'display_name' => 'Edit Voucher',
                'description' => 'Edit Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-delete',
                'display_name' => 'Delete Voucher',
                'description' => 'Delete Voucher',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'transactions-list',
                'display_name' => 'Transactions',
                'description' => 'List of all transactions',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'general-ledger-list',
                'display_name' => 'Statement List',
                'description' => 'List of stetements',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'voucher-types-list',
                'display_name' => 'Voucher Type List',
                'description' => 'Shows List of voucher types',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-types-create',
                'display_name' => 'Create Voucher Type',
                'description' => 'Create New Voucher Type',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-types-edit',
                'display_name' => 'Edit Voucher Type',
                'description' => 'Edit Voucher Type',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-types-delete',
                'display_name' => 'Delete Voucher Type',
                'description' => 'Delete Voucher Type',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-format-list',
                'display_name' => 'Voucher Format List',
                'description' => 'Shows List of Voucher Format',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-format-create',
                'display_name' => 'Create Voucher Format',
                'description' => 'Create Voucher Format',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-format-edit',
                'display_name' => 'Edit Voucher Format',
                'description' => 'Edit Voucher Format',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-format-delete',
                'display_name' => 'Delete Voucher Format',
                'description' => 'Delete Voucher Format',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-print-list',
                'display_name' => 'Voucher Print List',
                'description' => 'Shows List of Voucher Print',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-print-create',
                'display_name' => 'Create Voucher Print',
                'description' => 'Create New Voucher Print',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-print-edit',
                'display_name' => 'Edit Voucher Print',
                'description' => 'Edit Voucher Print',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'voucher-print-delete',
                'display_name' => 'Delete Voucher Print',
                'description' => 'Delete Voucher Print',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'books',
                'display_name' => 'Accounts Menu',
                'description' => 'Show accounts menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-group-approval',
                'display_name' => 'Ledger Gr. Approval',
                'description' => 'Ledger Gr. Approval',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ledger-approval',
                'display_name' => 'Ledger Approval',
                'description' => 'Ledger Approval',
                'module' => 'accounts',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project',
                'display_name' => 'Project',
                'description' => 'Shows List of projects',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-list',
                'display_name' => 'Project List',
                'description' => 'Shows List of projects',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-create',
                'display_name' => 'Create Project',
                'description' => 'Create New Project',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-edit',
                'display_name' => 'Edit Project',
                'description' => 'Edit Project',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-delete',
                'display_name' => 'Delete Project',
                'description' => 'Delete Project',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'assignwork-list',
                'display_name' => 'Assign Work',
                'description' => 'Shows List of assigned works',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'assignwork-create',
                'display_name' => 'Create Work',
                'description' => 'Create New Work',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'assignwork-edit',
                'display_name' => 'Edit Work',
                'description' => 'Edit Work',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'assignwork-delete',
                'display_name' => 'Delete Work',
                'description' => 'Delete Work',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'worksheet-list',
                'display_name' => 'Worksheet',
                'description' => 'See user worksheet',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'worksheet-create',
                'display_name' => 'Create Worksheet',
                'description' => 'Create New Worksheet',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'worksheet-edit',
                'display_name' => 'Edit Worksheet',
                'description' => 'Edit Worksheet',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'worksheet-delete',
                'display_name' => 'Delete Worksheet',
                'description' => 'Delete Worksheet',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-list',
                'display_name' => 'Enquiries List',
                'description' => 'Shows List of enquiries',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-create',
                'display_name' => 'Create Enquiry',
                'description' => 'Create New Enquiry',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-edit',
                'display_name' => 'Edit Enquiry',
                'description' => 'Edit Enquiry',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-delete',
                'display_name' => 'Delete Enquiry',
                'description' => 'Delete Enquiry',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-categories-list',
                'display_name' => 'Project Category List',
                'description' => 'Shows List of project categories',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-categories-create',
                'display_name' => 'Create Project Category',
                'description' => 'Create Project Category',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-categories-edit',
                'display_name' => 'Edit Project Category',
                'description' => 'Edit Project Category',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'project-categories-delete',
                'display_name' => 'Delete Project Category',
                'description' => 'Delete Project Category',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-field-list',
                'display_name' => 'Job Field',
                'description' => 'Shows List of job fields',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-field-create',
                'display_name' => 'Create Job Field',
                'description' => 'Create New Job Field',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-field-edit',
                'display_name' => 'Edit Job Field',
                'description' => 'Edit Job Field',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-field-delete',
                'display_name' => 'Delete Job Field',
                'description' => 'Delete Job Field',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'department-list',
                'display_name' => 'Department List',
                'description' => 'Shows List of departments',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'department-create',
                'display_name' => 'Create Department',
                'description' => 'Create New Department',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'department-edit',
                'display_name' => 'Edit Department',
                'description' => 'Edit Department',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'department-delete',
                'display_name' => 'Delete Department',
                'description' => 'Delete Department',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'designation-list',
                'display_name' => 'Designation List',
                'description' => 'Shows List of designations',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'designation-create',
                'display_name' => 'Create Designation',
                'description' => 'Create New Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'designation-edit',
                'display_name' => 'Edit Designation',
                'description' => 'Edit Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'designation-delete',
                'display_name' => 'Delete Designation',
                'description' => 'Delete Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leave-types-list',
                'display_name' => 'Leave Types List',
                'description' => 'Shows List of leave types',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leave-types-create',
                'display_name' => 'Create Leave Type',
                'description' => 'Create New Leave Type',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leave-types-edit',
                'display_name' => 'Edit Leave Type',
                'description' => 'Edit Leave Type',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leave-types-delete',
                'display_name' => 'Delete Leave Type',
                'description' => 'Delete Leave Type',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leave-approval',
                'display_name' => 'Leave Approval',
                'description' => 'Leave Approval',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holidays-list',
                'display_name' => 'Holidays List',
                'description' => 'Shows List of holidays',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holidays-create',
                'display_name' => 'Create Holiday',
                'description' => 'Create New Holiday',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holidays-edit',
                'display_name' => 'Edit Holiday',
                'description' => 'Edit Holiday',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holidays-delete',
                'display_name' => 'Delete Holiday',
                'description' => 'Delete Holiday',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-types-list',
                'display_name' => 'Attendance Types List',
                'description' => 'Show List of Attendance Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-types-create',
                'display_name' => 'Attendance Types Create',
                'description' => 'Create Attendance Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-types-edit',
                'display_name' => 'Attendance Types Edit',
                'description' => 'Edit Attendance Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-types-delete',
                'display_name' => 'Attendance Types Delete',
                'description' => 'Delete Attendance Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'week-off-list',
                'display_name' => 'Week off List',
                'description' => 'Shows List of Week off',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'week-off-create',
                'display_name' => 'Create Week off',
                'description' => 'Create New Week off',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'week-off-edit',
                'display_name' => 'Edit Week off',
                'description' => 'Edit Week off',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'week-off-delete',
                'display_name' => 'Delete Week off',
                'description' => 'Delete Week off',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-break-list',
                'display_name' => 'Work Break List',
                'description' => 'Shows List of work breaks',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-break-create',
                'display_name' => 'Create Work Break',
                'description' => 'Create New Work Break',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-break-edit',
                'display_name' => 'Edit Work Break',
                'description' => 'Edit Work Break',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-break-delete',
                'display_name' => 'Delete Work Break',
                'description' => 'Delete Work Break',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-shift-list',
                'display_name' => 'Work Shift List',
                'description' => 'Shows List of work shifts',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-shift-create',
                'display_name' => 'Create Work Shift',
                'description' => 'Create New Work Shift',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-shift-edit',
                'display_name' => 'Edit Work Shift',
                'description' => 'Edit Work Shift',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'work-shift-delete',
                'display_name' => 'Delete Work Shift',
                'description' => 'Delete Work Shift',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-asset-category-list',
                'display_name' => 'Employee Asset Category List',
                'description' => 'Shows List of employees asset categories',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-asset-category-create',
                'display_name' => 'Create Employee Asset Category',
                'description' => 'Create New Employee Asset Category',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-asset-category-edit',
                'display_name' => 'Edit Employee Asset Category',
                'description' => 'Edit Employee Asset Category',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-asset-category-delete',
                'display_name' => 'Delete Employee Asset Category',
                'description' => 'Delete Employee Asset Category',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-inquiry-list',
                'display_name' => 'Inquiry List',
                'description' => 'Shows List of inquiry',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-inquiry-create',
                'display_name' => 'Create Inquiry',
                'description' => 'Create New Inquiry',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-inquiry-edit',
                'display_name' => 'Edit Inquiry',
                'description' => 'Edit Inquiry',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-inquiry-delete',
                'display_name' => 'Delete Inquiry',
                'description' => 'Delete Inquiry',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-interviews-list',
                'display_name' => 'Interview List',
                'description' => 'Shows List of interviews',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-interviews-create',
                'display_name' => 'Create Interview',
                'description' => 'Create New Interview',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-interviews-edit',
                'display_name' => 'Edit Interview',
                'description' => 'Edit Interview',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'job-interviews-delete',
                'display_name' => 'Delete Interview',
                'description' => 'Delete Interview',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-list',
                'display_name' => 'Employee List',
                'description' => 'Shows List of employees',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-create',
                'display_name' => 'Create Employee',
                'description' => 'Create New Employee',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-edit',
                'display_name' => 'Edit Employee',
                'description' => 'Edit Employee',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-delete',
                'display_name' => 'Delete Employee',
                'description' => 'Delete Employee',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-show',
                'display_name' => 'Show Employee',
                'description' => 'Show Employee Details',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-designation-list',
                'display_name' => 'Employee Designation List',
                'description' => 'Shows List of designations',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-designation-create',
                'display_name' => 'Create Employee Designation',
                'description' => 'Create New Employee Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-designation-edit',
                'display_name' => 'Edit Employee Designation',
                'description' => 'Edit Employee Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-designation-delete',
                'display_name' => 'Delete Employee Designation',
                'description' => 'Delete Employee Designation',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-assets-list',
                'display_name' => 'Employee Asset List',
                'description' => 'Shows List of assets',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-assets-create',
                'display_name' => 'Create Employee Asset',
                'description' => 'Create New Employee Asset',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-assets-edit',
                'display_name' => 'Edit Employee Asset',
                'description' => 'Edit Employee Asset',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-assets-delete',
                'display_name' => 'Delete Employee Asset',
                'description' => 'Delete Employee Asset',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-travel-list',
                'display_name' => 'Employee Travel List',
                'description' => 'Shows List of employee travel',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-travel-create',
                'display_name' => 'Create Employee Travel',
                'description' => 'Create New Employee Travel',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-travel-edit',
                'display_name' => 'Edit Employee Travel',
                'description' => 'Edit Employee Travel',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-travel-delete',
                'display_name' => 'Delete Employee Travel',
                'description' => 'Delete Employee Travel',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-achievement-list',
                'display_name' => 'Employee Achievement List',
                'description' => 'Shows List of employee achievements',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-achievement-create',
                'display_name' => 'Create Employee Achievement',
                'description' => 'Create New Employee Achievement',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-achievement-edit',
                'display_name' => 'Edit Employee Achievement',
                'description' => 'Edit Employee Achievement',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-achievement-delete',
                'display_name' => 'Delete Employee Achievement',
                'description' => 'Delete Employee Achievement',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allocatedleave-list',
                'display_name' => 'Employee Allocated Leave List',
                'description' => 'Shows List of allocated leaves to epmployees',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allocatedleave-create',
                'display_name' => 'Create Employee Allocated Leave',
                'description' => 'Create New Employee Allocated Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allocatedleave-edit',
                'display_name' => 'Edit Employee Allocated Leave',
                'description' => 'Edit Employee Allocated Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allocatedleave-delete',
                'display_name' => 'Delete Employee Allocated Leave',
                'description' => 'Delete Employee Allocated Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permissions-list',
                'display_name' => 'Permission List',
                'description' => 'Shows List of permissions',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permissions-create',
                'display_name' => 'Create Permission',
                'description' => 'Create New Permission',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permissions-edit',
                'display_name' => 'Edit Permission',
                'description' => 'Edit Permission',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permissions-delete',
                'display_name' => 'Delete Permission',
                'description' => 'Delete Permission',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permission-approval',
                'display_name' => 'Permission Approval',
                'description' => 'Permission Approval',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leaves-list',
                'display_name' => 'Leaves List',
                'description' => 'Shows List of leaves',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leaves-create',
                'display_name' => 'Create Leave',
                'description' => 'Create New Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leaves-edit',
                'display_name' => 'Edit Leave',
                'description' => 'Edit Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'leaves-delete',
                'display_name' => 'Delete Leave',
                'description' => 'Delete Leave',
        		'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-type-list',
                'display_name' => 'Attendance Type List',
                'description' => 'Shows List of Attendance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-type-create',
                'display_name' => 'Create Attendance Type',
                'description' => 'Create New Attendance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-type-edit',
                'display_name' => 'Edit Attendance Type',
                'description' => 'Edit Attendance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-type-delete',
                'display_name' => 'Delete Attendance Type',
                'description' => 'Delete Attendance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-list',
                'display_name' => 'Holiday List',
                'description' => 'Shows List of Holiday List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-list-create',
                'display_name' => 'Create Holiday List',
                'description' => 'Create New Holiday List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-list-edit',
                'display_name' => 'Edit Holiday List',
                'description' => 'Edit Holiday List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-list-delete',
                'display_name' => 'Delete Holiday List',
                'description' => 'Delete Holiday List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'transfer-list',
                'display_name' => 'Transfer List',
                'description' => 'Shows List of Transfer',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'transfer-create',
                'display_name' => 'Create Transfer',
                'description' => 'Create New Transfer',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'transfer-edit',
                'display_name' => 'Edit Transfer',
                'description' => 'Edit Transfer',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'transfer-delete',
                'display_name' => 'Delete Transfer',
                'description' => 'Delete Transfer',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'promotion-list',
                'display_name' => 'Promotion List',
                'description' => 'Shows List of Promotion',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'promotion-create',
                'display_name' => 'Create Promotion',
                'description' => 'Create New Promotion',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'promotion-edit',
                'display_name' => 'Edit Promotion',
                'description' => 'Edit Promotion',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'promotion-delete',
                'display_name' => 'Delete Promotion',
                'description' => 'Delete Promotion',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll-approval',
                'display_name' => 'Payroll Approval',
                'description' => 'Payroll Approval',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll-frequency-list',
                'display_name' => 'Payroll Frequency List',
                'description' => 'Shows List of Payroll Frequency',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll-frequency-create',
                'display_name' => 'Create Payroll Frequency',
                'description' => 'Create New Payroll Frequency',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll-frequency-edit',
                'display_name' => 'Edit Payroll Frequency',
                'description' => 'Edit Payroll Frequency',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payroll-frequency-delete',
                'display_name' => 'Delete Payroll Frequency',
                'description' => 'Delete Payroll Frequency',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'pay-head-list',
                'display_name' => 'Pay Head List',
                'description' => 'Shows List of Pay Head',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'pay-head-create',
                'display_name' => 'Create Pay Head',
                'description' => 'Create New Pay Head',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'pay-head-edit',
                'display_name' => 'Edit Pay Head',
                'description' => 'Edit Pay Head',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'pay-head-delete',
                'display_name' => 'Delete Pay Head',
                'description' => 'Delete Pay Head',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'salary-scale-list',
                'display_name' => 'Salary Scale List',
                'description' => 'Shows List of Salary Scale',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'salary-scale-create',
                'display_name' => 'Create Salary Scale',
                'description' => 'Create New Salary Scale',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'salary-scale-edit',
                'display_name' => 'Edit Salary Scale',
                'description' => 'Salary Scale Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'salary-scale-delete',
                'display_name' => 'Delete Salary Scale',
                'description' => 'Salary Scale Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'in-out-register-list',
                'display_name' => 'In Out Register List',
                'description' => 'Shows List of In Out Register',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'in-out-register-create',
                'display_name' => 'Create In Out Register',
                'description' => 'Create New In Out Register',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'in-out-register-edit',
                'display_name' => 'Edit In Out Register',
                'description' => 'In Out Register Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'in-out-register-delete',
                'display_name' => 'Delete In Out Register',
                'description' => 'In Out Register Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'person-types-list',
                'display_name' => 'Person Types List',
                'description' => 'Shows List of Person Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'person-types-create',
                'display_name' => 'Create Person Types',
                'description' => 'Create New Person Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'person-types-edit',
                'display_name' => 'Edit Person Types',
                'description' => 'Person Types Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'person-types-delete',
                'display_name' => 'Delete Person Types',
                'description' => 'Person Types Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ot-register-list',
                'display_name' => 'OT Register List',
                'description' => 'Shows List of OT Register',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ot-register-create',
                'display_name' => 'Create OT Register',
                'description' => 'Create New OT Register',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ot-register-edit',
                'display_name' => 'Edit OT Register',
                'description' => 'OT Register Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'ot-register-delete',
                'display_name' => 'Delete OT Register',
                'description' => 'OT Register Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'people-list',
                'display_name' => 'People List',
                'description' => 'Shows List of People',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'people-create',
                'display_name' => 'Create People',
                'description' => 'Create New People',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'people-edit',
                'display_name' => 'Edit People',
                'description' => 'Edit People',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'people-delete',
                'display_name' => 'Delete People',
                'description' => 'Delete People',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],            
            [
                'name' => 'vehicle-list',
                'display_name' => 'Vehicle List',
                'description' => 'Shows List of vehicles',
        		'module' => 'vehicle',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-create',
                'display_name' => 'Create Vehicle',
                'description' => 'Create New Vehicle',
        		'module' => 'vehicle',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-edit',
                'display_name' => 'Edit Vehicle',
                'description' => 'Edit Vehicle',
        		'module' => 'vehicle',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-delete',
                'display_name' => 'Delete Vehicle',
                'description' => 'Delete Vehicle',
        		'module' => 'vehicle',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-master-list',
                'display_name' => 'Enquiry Master List',
                'description' => 'Shows List of enquiry masters',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-master-create',
                'display_name' => 'Create Enquiry Master',
                'description' => 'Create New Enquiry Master',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-master-edit',
                'display_name' => 'Edit Enquiry Master',
                'description' => 'Edit Enquiry Master',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'enquiry-master-delete',
                'display_name' => 'Delete Enquiry Master',
                'description' => 'Delete Enquiry Master',
        		'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'projects',
                'display_name' => 'Project Menu',
                'description' => 'Show project menu',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm',
                'display_name' => 'HR Menu',
                'description' => 'Show hrm menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicles',
                'display_name' => 'Vehicles Menu',
                'description' => 'Show vehicles menu',
                'module' => 'vehicle',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-masters',
                'display_name' => 'HR Master Menu',
                'description' => 'Show hr master menu',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'amc-list',
                'display_name' => 'AMC List',
                'description' => 'Show list of AMC',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'amc-create',
                'display_name' => 'Create AMC',
                'description' => 'Create AMC',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'amc-edit',
                'display_name' => 'Edit AMC',
                'description' => 'Edit AMC',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'amc-delete',
                'display_name' => 'Delete AMC',
                'description' => 'Delete AMC',
                'module' => 'project',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'insurance-types-list',
                'display_name' => 'Show list of Insurance Types',
                'description' => 'AMC',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'insurance-types-create',
                'display_name' => 'Create Insurance Type',
                'description' => 'Create Insurance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'insurance-types-edit',
                'display_name' => 'Edit Insurance Type',
                'description' => 'Edit Insurance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'insurance-types-delete',
                'display_name' => 'Delete Insurance Type',
                'description' => 'Delete Insurance Type',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allowance-list',
                'display_name' => 'Allowance List',
                'description' => 'Show list of Allowances',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allowance-create',
                'display_name' => 'Create Allowance',
                'description' => 'Create Allowance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allowance-edit',
                'display_name' => 'Edit Allowance',
                'description' => 'Edit Allowance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-allowance-delete',
                'display_name' => 'Delete Allowance',
                'description' => 'Delete Allowance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-benefits-list',
                'display_name' => 'Employee Benefits',
                'description' => 'Show list of Employee Benefits',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-benefits-create',
                'display_name' => 'Create Employee Benefits',
                'description' => 'Create Employee Benefits',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-benefits-edit',
                'display_name' => 'Edit Employee Benefits',
                'description' => 'Edit Employee Benefits',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-benefits-delete',
                'display_name' => 'Delete Employee Benefits',
                'description' => 'Delete Employee Benefits',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'bonus-list',
                'display_name' => 'Bonus List',
                'description' => 'Show list of Bonus',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'bonus-create',
                'display_name' => 'Create Bonus',
                'description' => 'Create Bonus',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'bonus-edit',
                'display_name' => 'Edit Bonus',
                'description' => 'Edit Bonus',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'bonus-delete',
                'display_name' => 'Delete Bonus',
                'description' => 'Delete Bonus',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-relieve-list',
                'display_name' => 'Employee Relieve List',
                'description' => 'Show list of Employee Relieve',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-relieve-create',
                'display_name' => 'Create Employee Relieve',
                'description' => 'Employee Relieve Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-relieve-edit',
                'display_name' => 'Edit Employee Relieve',
                'description' => 'Employee Relieve Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'employee-relieve-delete',
                'display_name' => 'Delete Employee Relieve',
                'description' => 'Employee Relieve Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer Menu',
                'description' => 'Customer Menu',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-details-list',
                'display_name' => 'Customer Details List',
                'description' => 'Show List of Customer',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-details-create',
                'display_name' => 'Customer Details Create',
                'description' => 'Create Customer',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-details-edit',
                'display_name' => 'Customer Details Edit',
                'description' => 'Edit Customer customer',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-details-delete',
                'display_name' => 'Customer Details Delete',
                'description' => 'Delete Customer',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-group-list',
                'display_name' => 'Customer Group List',
                'description' => 'Show List of Customer Group',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-group-create',
                'display_name' => 'Customer Group Create',
                'description' => 'Create Customer Group',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-group-edit',
                'display_name' => 'Customer Group Edit',
                'description' => 'Edit Customer Group',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-group-delete',
                'display_name' => 'Customer Group Delete',
                'description' => 'Delete Customer Group',
                'module' => 'customer',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-types-list',
                'display_name' => 'Holiday Types List',
                'description' => 'Show List of Holiday Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-types-create',
                'display_name' => 'Holiday Types Create',
                'description' => 'Create Holiday Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-types-edit',
                'display_name' => 'Holiday Types Edit',
                'description' => 'Edit Holiday Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'holiday-types-delete',
                'display_name' => 'Holiday Types Delete',
                'description' => 'Delete Holiday Types',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-list',
                'display_name' => 'Attendance List',
                'description' => 'Show List of Attendance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-create',
                'display_name' => 'Attendance Create',
                'description' => 'Create Attendance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-edit',
                'display_name' => 'Attendance Edit',
                'description' => 'Edit Attendance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-delete',
                'display_name' => 'Attendance Delete',
                'description' => 'Delete Attendance',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-setting-list',
                'display_name' => 'Attendance Setting List',
                'description' => 'Show List of Attendance Setting',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-setting-create',
                'display_name' => 'Attendance Setting Create',
                'description' => 'Create Attendance Setting',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-setting-edit',
                'display_name' => 'Attendance Setting Edit',
                'description' => 'Edit Attendance Setting',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'attendance-setting-delete',
                'display_name' => 'Attendance Setting Delete',
                'description' => 'Delete Attendance Setting',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'inventory',
                'display_name' => 'Inventory Menu',
                'description' => 'Inventory Menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'discount-list',
                'display_name' => 'Discount List',
                'description' => 'Show List of Discount',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'discount-create',
                'display_name' => 'Discount Create',
                'description' => 'Create Discount',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'discount-edit',
                'display_name' => 'Discount Edit',
                'description' => 'Edit Discount',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'discount-delete',
                'display_name' => 'Discount Delete',
                'description' => 'Delete Discount',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-make-list',
                'display_name' => 'Item Make List',
                'description' => 'Shows List of Item Make',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-make-create',
                'display_name' => 'Create Item Make',
                'description' => 'Create New Item Make',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-make-edit',
                'display_name' => 'Edit Item Make',
                'description' => 'Edit Item Make',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-make-delete',
                'display_name' => 'Delete Item Make',
                'description' => 'Delete Item Make',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'item-model-list',
                'display_name' => 'Item Model List',
                'description' => 'Shows List of Item Model',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'item-model-create',
                'display_name' => 'Create Item Model',
                'description' => 'Create New Item Model',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-model-edit',
                'display_name' => 'Edit Item Model',
                'description' => 'Edit Item Model',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-model-delete',
                'display_name' => 'Delete Item Model',
                'description' => 'Delete Item Model',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-specification-list',
                'display_name' => 'Item Specification List',
                'description' => 'Shows List of Item Specification',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'item-specification-create',
                'display_name' => 'Create Item Specification',
                'description' => 'Create New Item Specification',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-specification-edit',
                'display_name' => 'Edit Item Specification',
                'description' => 'Edit Item Specification',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-specification-delete',
                'display_name' => 'Delete Item Specification',
                'description' => 'Delete Item Specification',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'adjustment-list',
                'display_name' => 'Item Adjustment List',
                'description' => 'Shows List of Adjustment',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'adjustment-create',
                'display_name' => 'Create Adjustment',
                'description' => 'Create New Adjustment',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'adjustment-edit',
                'display_name' => 'Edit Adjustment',
                'description' => 'Edit Adjustment',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'adjustment-delete',
                'display_name' => 'Delete Adjustment',
                'description' => 'Delete Adjustment',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'internal-consumption-list',
                'display_name' => 'Item Internal Consumption List',
                'description' => 'Shows List of Internal Consumption',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'internal-consumption-create',
                'display_name' => 'Create Internal Consumption',
                'description' => 'Create New Internal Consumption',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'internal-consumption-edit',
                'display_name' => 'Edit Internal Consumption',
                'description' => 'Edit Internal Consumption',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'internal-consumption-delete',
                'display_name' => 'Delete Internal Consumption',
                'description' => 'Delete Internal Consumption',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'shipment-mode-list',
                'display_name' => 'Shipment Mode List',
                'description' => 'Show List of Shipment Mode',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'shipment-mode-create',
                'display_name' => 'Shipment Mode Create',
                'description' => 'Create Shipment Mode',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'shipment-mode-edit',
                'display_name' => 'Shipment Mode Edit',
                'description' => 'Edit Shipment Mode',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'shipment-mode-delete',
                'display_name' => 'Shipment Mode Delete',
                'description' => 'Delete Shipment Mode',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-list',
                'display_name' => 'Warehouse List',
                'description' => 'Show List of Warehouse',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-create',
                'display_name' => 'Warehouse Create',
                'description' => 'Create Warehouse',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-edit',
                'display_name' => 'Warehouse Edit',
                'description' => 'Edit Warehouse',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-delete',
                'display_name' => 'Warehouse Delete',
                'description' => 'Delete Warehouse',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'stores-list',
                'display_name' => 'Stores List',
                'description' => 'Show List of Stores',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'stores-create',
                'display_name' => 'Stores Create',
                'description' => 'Create Stores',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'stores-edit',
                'display_name' => 'Stores Edit',
                'description' => 'Edit Stores',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'stores-delete',
                'display_name' => 'Stores Delete',
                'description' => 'Delete Stores',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'rack-list',
                'display_name' => 'Rack List',
                'description' => 'Show List of Rack',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'rack-create',
                'display_name' => 'Rack Create',
                'description' => 'Create Rack',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'rack-edit',
                'display_name' => 'Rack Edit',
                'description' => 'Edit Rack',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'rack-delete',
                'display_name' => 'Rack Delete',
                'description' => 'Delete Rack',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'nature-list',
                'display_name' => 'Nature List',
                'description' => 'Show List of Nature',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'nature-create',
                'display_name' => 'Nature Create',
                'description' => 'Create Nature',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'nature-edit',
                'display_name' => 'Nature Edit',
                'description' => 'Edit Nature',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'nature-delete',
                'display_name' => 'Nature Delete',
                'description' => 'Delete Nature',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-category-list',
                'display_name' => 'Item Category List',
                'description' => 'Show List of Item Category',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-category-create',
                'display_name' => 'Item Category Create',
                'description' => 'Create Item Category',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-category-edit',
                'display_name' => 'Item Category Edit',
                'description' => 'Edit Item Category',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-category-delete',
                'display_name' => 'Item Category Delete',
                'description' => 'Delete Item Category',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-list',
                'display_name' => 'Item List',
                'description' => 'Show List of Item',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-create',
                'display_name' => 'Item Create',
                'description' => 'Create Item',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-edit',
                'display_name' => 'Item Edit',
                'description' => 'Edit Item',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'item-delete',
                'display_name' => 'Item Delete',
                'description' => 'Delete Item',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],          
                        
            [
                'name' => 'trade',
                'display_name' => 'Trade Menu',
                'description' => 'Trade Menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'unit-list',
                'display_name' => 'Unit List',
                'description' => 'Show List of Unit',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'unit-create',
                'display_name' => 'Unit Create',
                'description' => 'Create Unit',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'unit-edit',
                'display_name' => 'Unit Edit',
                'description' => 'Edit Unit',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'unit-delete',
                'display_name' => 'Unit Delete',
                'description' => 'Delete Unit',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-status-list',
                'display_name' => 'Lead Status List',
                'description' => 'Show List of Lead Status',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-status-create',
                'display_name' => 'Lead Status Create',
                'description' => 'Create Lead Status',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-status-edit',
                'display_name' => 'Lead Status Edit',
                'description' => 'Edit Lead Status',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-status-delete',
                'display_name' => 'Lead Status Delete',
                'description' => 'Delete Lead Status',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-source-list',
                'display_name' => 'Lead Source List',
                'description' => 'Show List of Lead Source',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-source-create',
                'display_name' => 'Lead Source Create',
                'description' => 'Create Lead Source',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-source-edit',
                'display_name' => 'Lead Source Edit',
                'description' => 'Edit Lead Source',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'lead-source-delete',
                'display_name' => 'Lead Source Delete',
                'description' => 'Delete Lead Source',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'estimate-list',
                'display_name' => 'Estimate List',
                'description' => 'List of Estimate',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'estimate-create',
                'display_name' => 'Estimate Create',
                'description' => 'Create Estimate',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'estimate-edit',
                'display_name' => 'Estimate Edit',
                'description' => 'Edit Estimate',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'estimate-delete',
                'display_name' => 'Estimate Delete',
                'description' => 'Delete Estimate',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'sale-order-list',
                'display_name' => 'Sale Order List',
                'description' => 'List of Sale Order',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sale-order-create',
                'display_name' => 'Sale Order Create',
                'description' => 'Create Sale Order',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sale-order-edit',
                'display_name' => 'Sale Order Edit',
                'description' => 'Edit Sale Order',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sale-order-delete',
                'display_name' => 'Sale Order Delete',
                'description' => 'Delete Sale Order',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'sales-list',
                'display_name' => 'Sales List',
                'description' => 'List of Sales',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-create',
                'display_name' => 'Sales Create',
                'description' => 'Create Sales',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-edit',
                'display_name' => 'Sales Edit',
                'description' => 'Edit Sales',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-delete',
                'display_name' => 'Sales Delete',
                'description' => 'Delete Sales',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'delivery-challan-list',
                'display_name' => 'Delivery Challan List',
                'description' => 'List of Delivery Challan',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'delivery-challan-create',
                'display_name' => 'Delivery Challan Create',
                'description' => 'Create Delivery Challan',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'delivery-challan-edit',
                'display_name' => 'Delivery Challan Edit',
                'description' => 'Edit Delivery Challan',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'delivery-challan-delete',
                'display_name' => 'Delivery Challan Delete',
                'description' => 'Delete Delivery Challan',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'sales-return-list',
                'display_name' => 'Sales Return List',
                'description' => 'List of Sales Return',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-return-create',
                'display_name' => 'Sales Return Create',
                'description' => 'Create Sales Return',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-return-edit',
                'display_name' => 'Sales Return Edit',
                'description' => 'Edit Sales Return',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'sales-return-delete',
                'display_name' => 'Sales Return Delete',
                'description' => 'Delete Sales Return',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'warehouse-summary-list',
                'display_name' => 'Warehouse Summary List',
                'description' => 'List of Warehouse Summary',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-summary-create',
                'display_name' => 'Warehouse Summary Create',
                'description' => 'Create Warehouse Summary',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-summary-edit',
                'display_name' => 'Warehouse Summary Edit',
                'description' => 'Edit Warehouse Summary',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'warehouse-summary-delete',
                'display_name' => 'Warehouse Summary Delete',
                'description' => 'Delete Warehouse Summary',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'receivables-list',
                'display_name' => 'Receivables List',
                'description' => 'List of Receivables',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'receivables-create',
                'display_name' => 'Receivables Create',
                'description' => 'Create Receivables',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'receivables-edit',
                'display_name' => 'Receivables Edit',
                'description' => 'Edit Receivables',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'receivables-delete',
                'display_name' => 'Receivables Delete',
                'description' => 'Delete Receivables',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'gst-report-list',
                'display_name' => 'GST Report List',
                'description' => 'List of GST Report',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'purchase-order-list',
                'display_name' => 'Purchase Order List',
                'description' => 'List of Purchase Order',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-order-create',
                'display_name' => 'Purchase Order Create',
                'description' => 'Create Purchase Order',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-order-edit',
                'display_name' => 'Purchase Order Edit',
                'description' => 'Edit Purchase Order',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-order-delete',
                'display_name' => 'Purchase Order Delete',
                'description' => 'Delete Purchase Order',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'goods-receipt-note-list',
                'display_name' => 'Goods Receipt Note List',
                'description' => 'List of Goods Receipt Note',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'goods-receipt-note-create',
                'display_name' => 'Goods Receipt Note Create',
                'description' => 'Create Goods Receipt Note',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'goods-receipt-note-edit',
                'display_name' => 'Goods Receipt Note Edit',
                'description' => 'Edit Goods Receipt Note',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'goods-receipt-note-delete',
                'display_name' => 'Goods Receipt Note Delete',
                'description' => 'Delete Goods Receipt Note',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'purchase-return-list',
                'display_name' => 'Purchase Return List',
                'description' => 'List of Purchase Return',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-return-create',
                'display_name' => 'Purchase Return Create',
                'description' => 'Create Purchase Return',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-return-edit',
                'display_name' => 'Purchase Return Edit',
                'description' => 'Edit Purchase Return',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-return-delete',
                'display_name' => 'Purchase Return Delete',
                'description' => 'Delete Purchase Return',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'payables-list',
                'display_name' => 'Payables List',
                'description' => 'List of Payables',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payables-create',
                'display_name' => 'Payables Create',
                'description' => 'Create Payables',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payables-edit',
                'display_name' => 'Payables Edit',
                'description' => 'Edit Payables',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'payables-delete',
                'display_name' => 'Payables Delete',
                'description' => 'Delete Payables',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'material-receipt-list',
                'display_name' => 'Material Receipt List',
                'description' => 'List of Material Receipt',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'material-receipt-create',
                'display_name' => 'Material Receipt Create',
                'description' => 'Create Material Receipt',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'material-receipt-edit',
                'display_name' => 'Material Receipt Edit',
                'description' => 'Edit Material Receipt',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'material-receipt-delete',
                'display_name' => 'Material Receipt Delete',
                'description' => 'Delete Material Receipt',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'low-stock-report-list',
                'display_name' => 'Low Stock Report List',
                'description' => 'List of Low Stock Report',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'low-stock-report-create',
                'display_name' => 'Low Stock Report Create',
                'description' => 'Create Low Stock Report',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'low-stock-report-edit',
                'display_name' => 'Low Stock Report Edit',
                'description' => 'Edit Low Stock Report',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'low-stock-report-delete',
                'display_name' => 'Low Stock Report Delete',
                'description' => 'Delete Low Stock Report',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'purchase-list',
                'display_name' => 'Purchase List',
                'description' => 'List of Purchase',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-create',
                'display_name' => 'Purchase Create',
                'description' => 'Create Purchase',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-edit',
                'display_name' => 'Purchase Edit',
                'description' => 'Edit Purchase',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'purchase-delete',
                'display_name' => 'Purchase Delete',
                'description' => 'Delete Purchase',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'tax-list',
                'display_name' => 'Tax List',
                'description' => 'List of Tax',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'tax-create',
                'display_name' => 'Tax Create',
                'description' => 'Create Tax',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'tax-edit',
                'display_name' => 'Tax Edit',
                'description' => 'Edit Tax',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'tax-delete',
                'display_name' => 'Tax Delete',
                'description' => 'Delete Tax',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'customer-info-list',
                'display_name' => 'Customer List',
                'description' => 'List of Customer',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-info-create',
                'display_name' => 'Customer Create',
                'description' => 'Create Customer',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-info-edit',
                'display_name' => 'Customer Edit',
                'description' => 'Edit Customer',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'customer-info-delete',
                'display_name' => 'Customer Delete',
                'description' => 'Delete Customer',
                'module' => 'trade',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],

            [
                'name' => 'supplier-info-list',
                'display_name' => 'Supplier List',
                'description' => 'List of Supplier',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'supplier-info-create',
                'display_name' => 'Supplier Create',
                'description' => 'Create Supplier',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'supplier-info-edit',
                'display_name' => 'Supplier Edit',
                'description' => 'Edit Supplier',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'supplier-info-delete',
                'display_name' => 'Supplier Delete',
                'description' => 'Delete Supplier',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'trade-wms',
                'display_name' => 'WMS Menu',
                'description' => 'WMS Menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],           
            [
                'name' => 'wfm',
                'display_name' => 'WFM Menu',
                'description' => 'WFM Menu',
                'module' => 'permission',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],  
            [
                'name' => 'wfm-add-project-menu',
                'display_name' => 'Add WFM project',
                'description' => 'Add WFM project',
                'module' => 'wfm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'wfm-master-dataset-menu',
                'display_name' => 'WFM master dataset menu',
                'description' => 'WFM master dataset menu',
                'module' => 'wfm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'wfm-manage-projects-menu',
                'display_name' => 'WFM manage projects menu',
                'description' => 'WFM manage projects menu',
                'module' => 'wfm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'wfm-chart-view-menu',
                'display_name' => 'WFM Chart view menu',
                'description' => 'WFM Chart view menu',
                'module' => 'wfm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'wfm-project-list-menu',
                'display_name' => 'WFM Project List Menu',
                'description' => 'WFM Project List Menu',
                'module' => 'wfm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           
            [
                'name' => ' wms-jobcard-list',
                'display_name' => 'Jobcard List',
                'description' => 'Jobcard List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            
           
            [
                'name' => ' wms-estimation-list',
                'display_name' => 'Estimation List',
                'description' => 'Estimation List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           
           
           
             [
                'name' => '  wms-deliverynote-list',
                'display_name' => 'Delivery Note List',
                'description' => 'Delivery Note List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             
            
           
            [
                'name' => 'wms-job-invoice-list',
                'display_name' => 'Job Invoice List',
                'description' => 'Job Invoice List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           
           
            [
                'name' => ' wms-job-status-list',
                'display_name' => 'Job Status List',
                'description' => 'Job Status List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           [
                'name' => 'wms-receipt-list',
                'display_name' => 'Job Receipt List',
                'description' => 'Job Receipt List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'today_summary',
                'display_name' => 'Today Summary',
                'description' => 'Today Summary',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           
           
            [
                'name' => 'hrm-appraisal-kpi-list',
                'display_name' => 'Appraisal KPI List',
                'description' => 'Appraisal KPI List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-kpi-create',
                'display_name' => 'Appraisal KPI Create',
                'description' => 'Appraisal KPI Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-kpi-edit',
                'display_name' => 'Appraisal KPI Edit',
                'description' => 'Appraisal KPI Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-kpi-delete',
                'display_name' => 'Appraisal KPI Delete',
                'description' => 'Appraisal KPI Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-vacancy-list',
                'display_name' => 'Vacancy List',
                'description' => 'Vacancy List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-vacancy-create',
                'display_name' => 'Vacancy Create',
                'description' => 'Vacancy Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-vacancy-edit',
                'display_name' => 'Vacancy Edit',
                'description' => 'Vacancy Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-vacancy-delete',
                'display_name' => 'Vacancy Delete',
                'description' => 'Vacancy Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-candidate-list',
                'display_name' => 'Candidate List',
                'description' => 'Candidate List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-candidate-create',
                'display_name' => 'Candidate Create',
                'description' => 'Candidate Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-candidate-edit',
                'display_name' => 'Candidate Edit',
                'description' => 'Candidate Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-candidate-delete',
                'display_name' => 'Candidate Delete',
                'description' => 'Candidate Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-document-list',
                'display_name' => 'Document List',
                'description' => 'Document List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-document-create',
                'display_name' => 'Document Create',
                'description' => 'Document Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-document-edit',
                'display_name' => 'Document Edit',
                'description' => 'Document Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-document-delete',
                'display_name' => 'Document Delete',
                'description' => 'Document Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-list',
                'display_name' => 'Appraisal List',
                'description' => 'Appraisal List',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-create',
                'display_name' => 'Appraisal Create',
                'description' => 'Appraisal Create',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-edit',
                'display_name' => 'Appraisal Edit',
                'description' => 'Appraisal Edit',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'hrm-appraisal-delete',
                'display_name' => 'Appraisal Delete',
                'description' => 'Appraisal Delete',
                'module' => 'hrm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'service-type-list',
                'display_name' => 'Service Type List',
                'description' => 'Service Type List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-category-list',
                'display_name' => 'Vehicle Category List',
                'description' => 'Vehicle Category List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-make-list',
                'display_name' => 'Vehicle Make List',
                'description' => 'Vehicle Make List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-model-list',
                'display_name' => 'Vehicle Model List',
                'description' => 'Vehicle Model List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'variant-list',
                'display_name' => 'Vehicle Variant List',
                'description' => 'Vehicle Variant List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'variant-create',
                'display_name' => 'Variant Create',
                'description' => 'Variant Create',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'variant-edit',
                'display_name' => 'Variant Edit',
                'description' => 'Variant Edit',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'variant-delete',
                'display_name' => 'Variant Delete',
                'description' => 'Variant Delete',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'readingfactor-list',
                'display_name' => 'Reading Factor List',
                'description' => 'Reading Factor List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'readingfactor-create',
                'display_name' => 'Reading Factor Create',
                'description' => 'Reading Factor Create',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'readingfactor-edit',
                'display_name' => 'Reading Factor Edit',
                'description' => 'Reading Factor Edit',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'readingfactor-delete',
                'display_name' => 'Reading Factor Delete',
                'description' => 'Reading Factor Delete',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'checklist-list',
                'display_name' => 'Check List ',
                'description' => 'Check List ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'checklist-create',
                'display_name' => 'CheckList Create ',
                'description' => 'CheckList Create ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'checklist-edit',
                'display_name' => 'CheckList Edit ',
                'description' => 'CheckList Edit ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'checklist-delete',
                'display_name' => 'CheckList Delete ',
                'description' => 'CheckList Delete ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permit-type-list',
                'display_name' => 'Permit Type List ',
                'description' => 'Permit Type List ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permit-type-create',
                'display_name' => 'Permit Type Create ',
                'description' => 'Permit Type Create ',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permit-type-edit',
                'display_name' => 'Permit Type Edit',
                'description' => 'Permit Type Edit',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'permit-type-delete',
                'display_name' => 'Permit Type Delete',
                'description' => 'Permit Type Delete',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'segment-list',
                'display_name' => 'Segment List',
                'description' => 'Segment List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'segment-create',
                'display_name' => 'Segment Create ',
                'description' => 'Segment Create',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'segment-create',
                'display_name' => 'Segment Edit',
                'description' => 'Segment Edit',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'segment-create',
                'display_name' => 'Segment Delete',
                'description' => 'Segment Delete',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-register',
                'display_name' => 'Vehicle Register',
                'description' => 'Vehicle Register',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'price-list',
                'display_name' => 'Price List',
                'description' => 'Price List',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'segment-details',
                'display_name' => 'Segment Details',
                'description' => 'Segment Details',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            
              [
                'name' => 'customer-grouping',
                'display_name' => 'Customer Grouping',
                'description' => 'Customer Grouping',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
           
              [
                'name' => 'gst-report',
                'display_name' => 'GST Report',
                'description' => 'GST Report',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'vehicle-report',
                'display_name' => 'Vehicle Report',
                'description' => 'Vehicle Report',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'service-history-report',
                'display_name' => 'Service History Report',
                'description' => 'Service History Report',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'specifiaction-master',
                'display_name' => 'Specification Master',
                'description' => 'Specification Master',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'vehicle-specifications',
                'display_name' => 'Vehicle Specifications',
                'description' => 'Vehicle Specifications',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'specification-values',
                'display_name' => 'Specification Values',
                'description' => 'Specification Values',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'specification-master-create',
                'display_name' => 'Specification Master Create',
                'description' => 'Specification Master Create',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'specification-values-create',
                'display_name' => 'Specification Values Create',
                'description' => 'Specification Values Create',
                'module' => 'trade_wms',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'age-of-products',
                'display_name' => 'Age of products',
                'description' => 'Age of products',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
             [
                'name' => 'jc-stock-report',
                'display_name' => 'Job Card Stock Report',
                'description' => 'Job Card Stock Report',
                'module' => 'inventory',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            
            
        ]);
    }
}
