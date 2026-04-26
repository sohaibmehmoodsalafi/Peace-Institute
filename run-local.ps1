Param(
  [int]$Port = 8001
)

$ErrorActionPreference = 'Stop'

$projectRoot = $PSScriptRoot
if (-not $projectRoot) {
  $projectRoot = Split-Path -Parent $PSCommandPath
}

function Resolve-Php {
  $cmd = Get-Command php -ErrorAction SilentlyContinue
  if ($cmd) { return $cmd.Source }

  $candidates = @(
    (Join-Path $env:USERPROFILE 'scoop\apps\php\current\php.exe'),
    'C:\tools\php\php.exe',
    'C:\tools\php82\php.exe',
    'C:\tools\php83\php.exe',
    'C:\php\php.exe',
    'C:\Program Files\PHP\php.exe',
    'C:\Program Files (x86)\PHP\php.exe',
    'C:\ProgramData\chocolatey\lib\php\tools\php.exe',
    'C:\laragon\bin\php\php.exe',
    'C:\xampp\php\php.exe'
  )

  foreach ($p in $candidates) {
    if (Test-Path $p) { return $p }
  }

  # Try a shallow scan in a few common roots (kept intentionally small)
  $scanRoots = @('C:\laragon\bin\php', 'C:\xampp\php', 'C:\tools', 'C:\Program Files\PHP', 'C:\php')
  foreach ($root in $scanRoots) {
    if (Test-Path $root) {
      $found = Get-ChildItem -Path $root -Recurse -Filter php.exe -ErrorAction SilentlyContinue | Select-Object -First 1
      if ($found) { return $found.FullName }
    }
  }

  return $null
}

function Resolve-Composer([string]$phpPath) {
  $pharCandidates = @(
    'C:\Composer\composer.phar',
    'C:\ProgramData\ComposerSetup\bin\composer.phar'
  )

  foreach ($p in $pharCandidates) {
    if (Test-Path $p) {
      return @{ Mode = 'phar'; Path = $p; Php = $phpPath }
    }
  }

  $cmd = Get-Command composer -ErrorAction SilentlyContinue
  if ($cmd) {
    return @{ Mode = 'exe'; Path = $cmd.Source }
  }

  return $null
}

$php = Resolve-Php
if (-not $php) {
  Write-Host "PHP not found. Install PHP 8.2+ and/or add it to PATH." -ForegroundColor Red
  Write-Host "Tip: if you already installed PHP, paste the full path to php.exe here so we can wire it in." -ForegroundColor Yellow
  exit 1
}

$phpArgs = @()

function Ensure-PhpExtensions([string]$phpPath) {
  $localIni = Join-Path $projectRoot 'php-cli.ini'
  $extDir = Join-Path (Split-Path -Parent $phpPath) 'ext'

  # Always (re)write the local ini so it stays consistent.
  @(
    '; Auto-generated for local development (CLI only)',
    '; Enables required extensions without modifying system php.ini',
    ('extension_dir="' + $extDir + '"'),
    'extension=fileinfo',
    'extension=zip',
    'extension=openssl',
    'extension=mbstring',
    'extension=curl',
    'extension=pdo_sqlite',
    'extension=sqlite3'
  ) | Set-Content -Path $localIni -Encoding ASCII

  Write-Host "Using local PHP ini override: $localIni" -ForegroundColor Yellow
  return @('-c', $localIni)
}

$phpArgs = Ensure-PhpExtensions -phpPath $php

$composer = Resolve-Composer -phpPath $php
if (-not $composer) {
  Write-Host "Composer not found. Install Composer or place composer.phar at C:\Composer\composer.phar" -ForegroundColor Red
  exit 1
}

Set-Location $projectRoot

if (-not (Test-Path -Path '.env')) {
  if (Test-Path -Path '.env.example') {
    Copy-Item .env.example .env
  } else {
    Write-Host "No .env or .env.example found." -ForegroundColor Red
    exit 1
  }
}

if (-not (Test-Path -Path 'database/database.sqlite')) {
  New-Item -ItemType File -Path 'database/database.sqlite' -Force | Out-Null
}

$composerArgs = @('install')
if ($composer.Mode -eq 'exe') {
  & $composer.Path @composerArgs
} else {
  & $composer.Php @phpArgs $composer.Path @composerArgs
}
if ($LASTEXITCODE -ne 0) {
  Write-Host "Composer failed (exit code $LASTEXITCODE). Fix the error above and re-run." -ForegroundColor Red
  exit $LASTEXITCODE
}

& $php @phpArgs artisan key:generate
& $php @phpArgs artisan migrate --seed
& $php @phpArgs artisan storage:link

Write-Host "Starting server on http://127.0.0.1:$Port" -ForegroundColor Green

& $php @phpArgs artisan serve --host=127.0.0.1 --port=$Port
if ($LASTEXITCODE -ne 0) {
  Write-Host "artisan serve failed; falling back to PHP built-in server..." -ForegroundColor Yellow
  & $php @phpArgs -S "127.0.0.1:$Port" -t public server.php
}
