<?php

namespace App\Support;

use Illuminate\Support\Collection;

class RolePermissionUi
{
    private const CRUD = ['create', 'read', 'update', 'delete'];

    /**
     * Mengelompokkan permission menjadi kategori navigasi untuk modal edit role.
     *
     * @param  Collection<int,\Spatie\Permission\Models\Permission>  $permissions
     * @param  array<int|string, int|string>  $rolePermissionIds
     * @return array{categories: array<int, array<string, mixed>>, total_checkboxes: int, selected_count: int}
     */
    public static function build(Collection $permissions, array $rolePermissionIds): array
    {
        $crudGrouped = [];

        foreach ($permissions as $permission) {
            $name = (string) $permission->name;
            $matched = preg_match('/^(.+)-(create|read|update|delete)$/u', $name, $matches);

            if (! $matched) {
                continue;
            }

            $resourceBase = $matches[1];
            $action = $matches[2];

            $crudGrouped[$resourceBase] ??= array_fill_keys(self::CRUD, null);
            $crudGrouped[$resourceBase][$action] = [
                'id' => $permission->id,
                'name' => $name,
            ];
        }

        /** @var array<string, mixed> */
        $config = config('permission-ui', []);
        /** @var array<string, string> */
        $categoryLabels = $config['category_labels'] ?? [];
        /** @var array<string, int> */
        $categoryOrderConfig = $config['category_order'] ?? [];
        /** @var array<string, string> */
        $actionLabels = $config['action_labels'] ?? [];

        $categoriesBySlug = [];

        foreach ($crudGrouped as $resourceBase => $actionsPayload) {
            $slug = self::inferCategorySlug($resourceBase);

            $categoriesBySlug[$slug] ??= [
                'slug' => $slug,
                'resources' => [],
            ];

            $resources = &$categoriesBySlug[$slug]['resources'];

            $actionsForView = [];

            foreach (self::CRUD as $crudKey) {
                $row = $actionsPayload[$crudKey] ?? null;
                if (! is_array($row)) {
                    continue;
                }

                $actionsForView[] = [
                    'key' => $crudKey,
                    'id' => $row['id'],
                    'short' => mb_strtoupper(mb_substr($crudKey, 0, 1)),
                    'label' => $actionLabels[$crudKey] ?? ucfirst($crudKey),
                ];
            }

            if ($actionsForView === []) {
                continue;
            }

            /** @var list<array<string, mixed>> */
            $actionRowsForSearch = array_map(
                static fn ($a) => (string) $a['label'],
                $actionsForView,
            );

            $resources[] = [
                'base' => $resourceBase,
                'title' => self::prettyResourceTitle($resourceBase),
                'search_blob' => self::normalizeSearchText(
                    strtolower($slug.' '.$resourceBase.' '.implode(' ', $actionRowsForSearch))
                ),
                'actions' => $actionsForView,
            ];
        }

        $orphans = [];

        foreach ($permissions as $permission) {
            $name = (string) $permission->name;
            if (! preg_match('/^(.+)-(create|read|update|delete)$/u', $name)) {
                $orphans[] = [
                    'id' => $permission->id,
                    'name' => $name,
                    'search_blob' => self::normalizeSearchText(strtolower($name)),
                ];
            }
        }

        foreach ($categoriesBySlug as $slug => $payload) {
            usort($payload['resources'], fn ($a, $b) => strcmp((string) $a['title'], (string) $b['title']));
            $categoriesBySlug[$slug] = $payload;
        }

        $categoriesFlat = [];

        foreach ($categoriesBySlug as $slug => $payload) {
            $label = $categoryLabels[$slug] ?? self::automaticCategoryLabel($slug);
            $sort = $categoryOrderConfig[$slug] ?? 900;

            $categoriesFlat[] = [
                'slug' => $slug,
                'label' => $label,
                'sort' => $sort,
                'resources' => $payload['resources'],
                '_resource_count' => count($payload['resources']),
            ];
        }

        usort($categoriesFlat, function ($a, $b) {
            if ($a['sort'] !== $b['sort']) {
                return $a['sort'] <=> $b['sort'];
            }

            return strcmp((string) $a['label'], (string) $b['label']);
        });

        if ($orphans !== []) {
            usort($orphans, fn ($left, $right) => strcmp((string) $left['name'], (string) $right['name']));
            $categoriesFlat[] = [
                'slug' => '_extras',
                'label' => $categoryLabels['_extras'] ?? 'Izin khusus',
                'sort' => $categoryOrderConfig['_extras'] ?? 850,
                'resources' => [],
                'orphans' => $orphans,
                '_resource_count' => 0,
                'extras_count' => count($orphans),
            ];
        }

        return [
            'categories' => $categoriesFlat,
            'total_checkboxes' => $permissions->count(),
            'selected_count' => count($rolePermissionIds),
        ];
    }

    private static function normalizeSearchText(string $flat): string
    {
        while (strpos($flat, '  ') !== false) {
            $flat = str_replace('  ', ' ', $flat);
        }

        return $flat;
    }

    private static function inferCategorySlug(string $resourceBase): string
    {
        if (! str_contains($resourceBase, '/')) {
            return '_core';
        }

        [$head] = explode('/', $resourceBase, 2);

        return strtolower($head) ?: '_core';
    }

    private static function prettyResourceTitle(string $resourceBase): string
    {
        if ($resourceBase === '') {
            return $resourceBase;
        }

        $last = basename(str_replace('\\', '/', $resourceBase));
        $last = str_replace(['_', '-'], ' ', $last);

        return ucwords(strtolower($last));
    }

    private static function automaticCategoryLabel(string $slug): string
    {
        if ($slug === '_core') {
            return config('permission-ui.category_labels._core', 'Dasar akses aplikasi');
        }

        $pretty = strtolower(str_replace(['_', '-'], ' ', $slug));

        return ucwords($pretty);
    }
}
