<?php

namespace App\Enums\Kanban;

class JobStatus
{
    public const ON_HOLD = 'on_hold';
    public const NEED_REVIEW = 'need_review';
    public const SCHEDULED = 'scheduled';
    public const PREPARATION = 'preparation';
    public const ON_GOING = 'on_going';
    public const COMPLETED = 'completed';
    public const CLOSED = 'closed';
    public const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::ON_HOLD,
            self::NEED_REVIEW,
            self::SCHEDULED,
            self::PREPARATION,
            self::ON_GOING,
            self::COMPLETED,
            self::CLOSED,
            self::CANCELLED,
        ];
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::ON_HOLD => 'On Hold',
            self::NEED_REVIEW => 'Need Review',
            self::SCHEDULED => 'Scheduled',
            self::PREPARATION => 'Preparation',
            self::ON_GOING => 'On Going',
            self::COMPLETED => 'Completed',
            self::CLOSED => 'Closed',
            self::CANCELLED => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function badgeClass(string $status): string
    {
        return match ($status) {
            self::ON_HOLD => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700',
            self::NEED_REVIEW => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-700',
            self::SCHEDULED => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700',
            self::PREPARATION => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-900/50 dark:text-indigo-300 dark:border-indigo-700',
            self::ON_GOING => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700',
            self::COMPLETED => 'bg-teal-100 text-teal-800 border-teal-300 dark:bg-teal-900/50 dark:text-teal-300 dark:border-teal-700',
            self::CLOSED => 'bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600',
            self::CANCELLED => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-900/50 dark:text-rose-300 dark:border-rose-700',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
