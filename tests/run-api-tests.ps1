# Run from project root: .\tests\run-api-tests.ps1
# Optional: .\tests\run-api-tests.ps1 -BaseUrl "http://localhost/ivan-jwt-auth/api"

param(
    [string]$BaseUrl = "http://ivan-jwt-auth.api"
)

$reqDir = Join-Path $PSScriptRoot "requests"
$passed = 0
$failed = 0
$total = 0

function Invoke-Api {
    param(
        [string]$Method,
        [string]$Path,
        [string]$BodyFile = $null,
        [string]$BodyRaw = $null
    )

    $url = "$BaseUrl$Path"
    $curlArgs = @("-s", "-w", "`nHTTP:%{http_code}", "-X", $Method, $url)

    if ($Method -ne "GET") {
        $curlArgs += @("-H", "Content-Type: application/json")
    }

    if ($BodyFile) {
        $curlArgs += @("-d", "@$BodyFile")
    } elseif ($BodyRaw) {
        $curlArgs += @("-d", $BodyRaw)
    }

    $output = (& curl.exe @curlArgs 2>&1 | Out-String).Trim()
    $status = 0
    $body = $output

    if ($output -match 'HTTP:(\d+)$') {
        $status = [int]$Matches[1]
        $body = $output.Substring(0, $output.LastIndexOf("HTTP:")).Trim()
    }

    $json = $null
    if ($body -and $body.StartsWith("{")) {
        try { $json = $body | ConvertFrom-Json } catch { }
    }

    return @{ Status = $status; Body = $body; Json = $json }
}

function Assert-Test {
    param(
        [string]$Name,
        [bool]$Condition,
        [string]$Detail = ""
    )

    $script:total++
    if ($Condition) {
        $script:passed++
        Write-Host "  [PASS] $Name" -ForegroundColor Green
    } else {
        $script:failed++
        Write-Host "  [FAIL] $Name" -ForegroundColor Red
        if ($Detail) { Write-Host "         $Detail" -ForegroundColor DarkRed }
    }
}

Write-Host "`n========================================" -ForegroundColor Yellow
Write-Host "  API Test Suite" -ForegroundColor Yellow
Write-Host "  Base URL: $BaseUrl" -ForegroundColor Yellow
Write-Host "========================================`n" -ForegroundColor Yellow

# --- GET /show ---
Write-Host "--- GET /show (health) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method GET -Path "/show"
Write-Host $r.Body
Assert-Test "HTTP 200" ($r.Status -eq 200) "Got $($r.Status)"
Assert-Test "JSON status success" ($r.Json.status -eq "success") "status=$($r.Json.status)"
Assert-Test "data is array" ($null -ne $r.Json.data)
Assert-Test "meta has total" ($null -ne $r.Json.meta.total)

# --- POST /register (201 with unique email) ---
Write-Host "`n--- POST /register (new user) ---" -ForegroundColor Cyan
$uniqueEmail = "test.{0}@example.com" -f [guid]::NewGuid().ToString("N").Substring(0, 8)
$uniqueBody = (@{
    first_name = "Test"
    last_name  = "User"
    email      = $uniqueEmail
    password   = "secret12"
} | ConvertTo-Json -Compress)
$uniqueFile = Join-Path $env:TEMP "register-unique.json"
$utf8NoBom = New-Object System.Text.UTF8Encoding $false
[System.IO.File]::WriteAllText($uniqueFile, $uniqueBody, $utf8NoBom)

$r = Invoke-Api -Method POST -Path "/register" -BodyFile $uniqueFile
Write-Host $r.Body
Assert-Test "HTTP 201 Created" ($r.Status -eq 201) "Got $($r.Status)"
Assert-Test "status success" ($r.Json.status -eq "success")
Assert-Test "has uuid" ($null -ne $r.Json.data.uuid)
Assert-Test "has first_name" ($r.Json.data.first_name -eq "Test")
Assert-Test "no password in response" ($null -eq $r.Json.data.password)

Remove-Item $uniqueFile -ErrorAction SilentlyContinue

# --- POST /register (422 weak password) ---
Write-Host "`n--- POST /register (weak password) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method POST -Path "/register" -BodyFile (Join-Path $reqDir "register-weak.json")
Write-Host $r.Body
Assert-Test "HTTP 422" ($r.Status -eq 422) "Got $($r.Status)"
Assert-Test "password validation error" ($null -ne $r.Json.errors.password)

# --- POST /register (422 missing fields) ---
Write-Host "`n--- POST /register (missing fields) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method POST -Path "/register" -BodyFile (Join-Path $reqDir "register-missing.json")
Write-Host $r.Body
Assert-Test "HTTP 422" ($r.Status -eq 422) "Got $($r.Status)"
Assert-Test "missing array present" ($null -ne $r.Json.errors.missing)

# --- POST /register (400 invalid JSON) ---
Write-Host "`n--- POST /register (invalid JSON) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method POST -Path "/register" -BodyRaw "{bad json}"
Write-Host $r.Body
Assert-Test "HTTP 400" ($r.Status -eq 400) "Got $($r.Status)"

# --- GET /register (405) ---
Write-Host "`n--- GET /register (wrong method) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method GET -Path "/register"
Write-Host $r.Body
Assert-Test "HTTP 405" ($r.Status -eq 405) "Got $($r.Status)"

# --- POST /register (409 duplicate) ---
# Siempre: 1.er POST crea el usuario (201) o ya existe (409); 2.º POST debe ser 409.
Write-Host "`n--- POST /register (duplicate email) ---" -ForegroundColor Cyan
$dupFile = Join-Path $reqDir "register-duplicate.json"

$rSetup = Invoke-Api -Method POST -Path "/register" -BodyFile $dupFile
Write-Host "Setup: $($rSetup.Body)"
Assert-Test "Setup: 201 created or 409 already exists" (
    $rSetup.Status -eq 201 -or $rSetup.Status -eq 409
) "Got $($rSetup.Status)"

$r = Invoke-Api -Method POST -Path "/register" -BodyFile $dupFile
Write-Host "Duplicate attempt: $($r.Body)"
Assert-Test "HTTP 409 Conflict" ($r.Status -eq 409) "Got $($r.Status)"
Assert-Test "email exists message" ($r.Json.message -match "exists")

# --- POST /login (stub) ---
Write-Host "`n--- POST /login (stub, not implemented yet) ---" -ForegroundColor Cyan
$r = Invoke-Api -Method POST -Path "/login" -BodyFile (Join-Path $reqDir "login.json")
Write-Host $r.Body
Assert-Test "HTTP 200" ($r.Status -eq 200) "Got $($r.Status)"
Assert-Test "stub status false" ($r.Json.status -eq $false)
Assert-Test "returns JSON message" ($null -ne $r.Json.message)

# --- GET / (documentation UI) ---
Write-Host "`n--- GET / (documentation UI) ---" -ForegroundColor Cyan
$docStatus = curl.exe -s -o NUL -w "%{http_code}" "$BaseUrl/"
$contentType = curl.exe -s -I "$BaseUrl/" 2>&1 | Select-String -Pattern "Content-Type"
Write-Host "HTTP $docStatus | $contentType"
$script:total++
if ($docStatus -eq "200") {
    $script:passed++
    Write-Host "  [PASS] Documentation page loads" -ForegroundColor Green
} else {
    $script:failed++
    Write-Host "  [FAIL] Documentation page (expected 200, got $docStatus)" -ForegroundColor Red
}

# --- Summary ---
Write-Host "`n========================================" -ForegroundColor Yellow
$color = if ($failed -eq 0) { "Green" } else { "Red" }
Write-Host "  RESULT: $passed / $total passed | $failed failed" -ForegroundColor $color
Write-Host "========================================`n" -ForegroundColor Yellow

if ($failed -gt 0) { exit 1 }
exit 0
