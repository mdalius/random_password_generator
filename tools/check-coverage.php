<?php
declare(strict_types=1);

$reportPath = $argv[1] ?? __DIR__ . '/../build/coverage.xml';
$configuredThreshold = getenv('CODE_COVERAGE_THRESHOLD');
$threshold = filter_var($configuredThreshold === false ? '90' : $configuredThreshold, FILTER_VALIDATE_FLOAT);

if ($threshold === false || $threshold < 0 || $threshold > 100) {
    fwrite(STDERR, "Coverage threshold must be a number between 0 and 100.\n");
    exit(1);
}

if (!is_file($reportPath)) {
    fwrite(STDERR, "Coverage report not found: $reportPath\n");
    exit(1);
}

$report = simplexml_load_file($reportPath);
if ($report === false || !isset($report->project->metrics)) {
    fwrite(STDERR, "Invalid Clover coverage report.\n");
    exit(1);
}

$metrics = $report->project->metrics;
$statements = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];
if ($statements <= 0 || $covered < 0 || $covered > $statements) {
    fwrite(STDERR, "Coverage report contains no executable statements or invalid counts.\n");
    exit(1);
}

$coverage = 100 * $covered / $statements;
printf("Line coverage: %.2f%% (required: %.2f%%)\n", $coverage, $threshold);
exit($coverage < $threshold ? 1 : 0);
