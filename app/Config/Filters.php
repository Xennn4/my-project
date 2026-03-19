<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

// Custom filters
use App\Filters\AuthFilter;
use App\Filters\StudentFilter;
use App\Filters\TeacherFilter;
use App\Filters\AdminFilter;

class Filters extends BaseFilters
{
    /**
     * Filter aliases.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Custom RBAC filters
        'auth'    => AuthFilter::class,
        'student' => StudentFilter::class,
        'teacher' => TeacherFilter::class,
        'admin'   => AdminFilter::class,
    ];

    /**
     * Required filters.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            // Uncomment if needed
            // 'forcehttps',
            // 'pagecache',
        ],
        'after' => [
            // Uncomment if needed
            // 'pagecache',
            // 'performance',
            'toolbar',
        ],
    ];

    /**
     * Global filters.
     *
     * @var array{
     *     before: array<string, array{except: list<string>|string}>|list<string>,
     *     after: array<string, array{except: list<string>|string}>|list<string>
     * }
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'secureheaders',
        ],
    ];

    /**
     * Method filters.
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * Pattern-based filters.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [];
}