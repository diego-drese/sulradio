<?php

if (!function_exists('get_notification_ticket')) {
	function get_notification_ticket() {
		$user = \Illuminate\Support\Facades\Auth::user();
		$hasTicket = \Oka6\Admin\Http\Library\ResourceAdmin::hasResourceByRouteName('ticket.index', [1]);
		return $hasTicket ? \Oka6\SulRadio\Models\SystemLog::getLastNotificationsTicket($user->id) : null;
	}
}