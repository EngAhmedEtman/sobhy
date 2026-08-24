<?php
$dir = dirname(__DIR__) . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        $newContent = str_replace(
            'flex items-center justify-center min-h-screen',
            'flex items-start justify-center min-h-screen pt-10 sm:pt-16',
            $content
        );

        $newContent = str_replace(
            'flex items-center justify-center p-4 min-h-screen',
            'flex items-start justify-center p-4 pt-10 sm:pt-16 min-h-screen',
            $newContent
        );

        if ($content !== $newContent) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
