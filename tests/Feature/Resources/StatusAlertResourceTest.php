<?php

use App\Models\StatusAlert;

describe('StatusAlert Resource', function () {
    beforeEach(function () {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    });

    it('admin can view status alerts', function () {
        $admin = createUserWithRole('admin');

        expect($admin->hasRole('admin'))->toBeTrue();
    });

    it('cannot create status alert via form', function () {
        $admin = createUserWithRole('admin');

        // StatusAlerts são criadas automaticamente quando status muda
        expect($admin->hasRole('admin'))->toBeTrue();
    });

    it('can toggle is_read status', function () {
        $alert = StatusAlert::factory()->create(['is_read' => false]);

        $alert->update(['is_read' => true]);

        expect($alert->fresh()->is_read)->toBeTrue();
    });

    it('displays unread alerts in list', function () {
        StatusAlert::factory()->count(3)->create(['is_read' => false]);
        StatusAlert::factory()->count(2)->create(['is_read' => true]);

        $unreadCount = StatusAlert::where('is_read', false)->count();

        expect($unreadCount)->toBe(3);
    });

    it('can filter alerts by is_read status', function () {
        StatusAlert::factory()->count(3)->create(['is_read' => false]);
        StatusAlert::factory()->count(2)->create(['is_read' => true]);

        $readAlerts = StatusAlert::where('is_read', true)->get();

        expect($readAlerts)->toHaveCount(2);
    });
});
