<?php
// app/Services/SettingsService.php
namespace App\Services;

use App\Models\AppSettings;

class SettingsService
{
    protected $cacheKey = 'app_settings';
    protected $cacheTTL = 3600; // 1 hour
    protected $cache;
    protected $model;

    public function __construct()
    {
        $this->cache = cache();
        $this->model = new AppSettings();
    }

    public function get($key, $default = null)
    {
        $settings = $this->getAll();
        return $settings[$key]['value'] ?? $default;
    }

    public function getAsObj($key, $default = null)
    {
        $settings = $this->getAll();
        return $settings[$key] ?? $default;
    }

    public function getAll(): float|object|array|bool|int|string
    {
        if ($cached = $this->cache->get($this->cacheKey)) {
            return $cached;
        }

        $allSettings = [];
        foreach ($this->model->findAll() as $row) {
            $allSettings[$row['key_name']] = [
                'value' => $row['value'],
                'comment' => $row['comment'],
            ];
        }

        $this->cache->save($this->cacheKey, $allSettings, $this->cacheTTL);
        return $allSettings;
    }

    public function set($key, $value): void
    {
        $existing = $this->model->where('key_name', $key)->first();
        if ($existing) {
            $this->model->update($existing['id'], ['value' => $value]);
        } else {
            $this->model->insert(['key_name' => $key, 'value' => $value]);
        }

        $this->refreshCache();
    }

    public function refreshCache(): void
    {
        $this->cache->delete($this->cacheKey);
        $this->getAll(); // repopulate
    }
}

