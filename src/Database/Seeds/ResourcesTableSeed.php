<?php

namespace Oka6\SulRadio\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Oka6\Admin\Models\Profile;
use Oka6\Admin\Models\Resource;
use Oka6\Admin\Models\Sequence;
use Oka6\Admin\Models\User;


class ResourcesTableSeed extends Seeder {
	public function run() {
		$resource = Resource::getResourceIdByRouteName('ticket.config.menu');
		$profile = Profile::where('id', User::PROFILE_ID_ROOT)->first();
		if(!$resource){
			$id = Sequence::getSequence('resource');
			Resource::insert([
				[
					'id' => $id,
					'name' => "Configurações Ticket",
					'menu' => 'Config. Ticket',
					'is_menu' => 1,
					'route_name' => 'ticket.config.menu',
					'icon' => 'fas fa-cogs',
					'controller_method' => '',
					'can_be_default' => 0,
					'parent_id' => 0,
					'order' => 1,
					'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
					'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
				]
			]);
			if (!count($profile->resources_allow)) {
				$profile->resources_allow = [$id];
				
			} else {
				$profile->resources_allow = array_merge($profile->resources_allow, [$id]);
				
			}
			$idAdmin = Sequence::getSequence('resource');
			Resource::insert([
				[
					'id' => $idAdmin,
					'name' => "Ticket Admin",
					'menu' => 'Ticket Admin',
					'is_menu' => 0,
					'route_name' => 'ticket.admin',
					'icon' => 'fas fa-cogs',
					'controller_method' => '',
					'can_be_default' => 0,
					'parent_id' => 0,
					'order' => 1,
					'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
					'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
				]
			]);
			if (!count($profile->resources_allow)) {
				$profile->resources_allow = [$idAdmin];
			} else {
				$profile->resources_allow = array_merge($profile->resources_allow, [$idAdmin]);
			}
			
			$profile->save();
		}
	}
}
