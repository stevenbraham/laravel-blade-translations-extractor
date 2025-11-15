<?php

namespace StevenBraham\LaravelBladeTranslationsExtractor\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class ExtractTranslations extends Command
{
    protected $signature = 'extract:translations {locale}';
    protected $description = 'Scan Blade & PHP files for __() strings and merge into JSON translation file (new ones as null)';

    public function handle()
    {
        $locale = $this->argument('locale');
        $paths = [
            resource_path('views'),
            app_path(),
        ];

        // recursively get all .php and .blade.php files
        $allFiles = collect($paths)->flatMap(function (string $path) {
            $files = [];

            if (!is_dir($path)) {
                return $files;
            }

            $directoryIterator = new RecursiveDirectoryIterator($path);
            $iterator = new RecursiveIteratorIterator($directoryIterator);
            $regexIterator = new RegexIterator($iterator, '/\.(php|blade\.php)$/i');

            foreach ($regexIterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $files[] = $fileInfo->getPathname();
                }
            }

            return $files;
        });

        $pattern = '/(?:__|@lang)\(\s*(["\'])(.*?)\1\s*[\),]/m';

        $found = [];

        foreach ($allFiles as $file) {
            $content = file_get_contents($file);

            if ($content === false) {
                continue;
            }

            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[2] as $str) {

                    $found[$str] = null; // new strings are added with null
                }
            }
        }

        $jsonPath = lang_path($locale . '.json');

        $existing = file_exists($jsonPath)
            ? json_decode(file_get_contents($jsonPath), true) ?? []
            : [];

        // keep existing keys, add new ones with null
        foreach ($found as $key => $value) {
            if (!array_key_exists($key, $existing)) {
                $existing[$key] = $value;
            }
        }

        ksort($existing);

        file_put_contents(
            $jsonPath,
            json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->info('Merged ' . count($found) . ' strings into ' . $jsonPath);
    }
}
