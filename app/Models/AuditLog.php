<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'actor_name',
        'action',
        'entity',
        'change_summary',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(?User $user, string $action, string $entity, string $changeSummary): self
    {
        return self::create([
            'user_id' => $user?->id,
            'actor_name' => $user?->name ?? 'System',
            'action' => $action,
            'entity' => $entity,
            'change_summary' => $changeSummary,
        ]);
    }
}
