<#
    deploy.ps1 – One-click Git sync (portable Git)
    Uses an explicit path to git.exe. Comments in English.
#>

# --- 1. Absolute path to portable git.exe --------------------------------
$GitExe = "C:\Users\fdela\Desktop\Lancelot\Programmation\Git\bin\git.exe"

function Git {
    param (
        [Parameter(ValueFromRemainingArguments = $true)]
        [string[]]$gitArgs
    )
    & $GitExe @gitArgs
}

# --- 2. Move to repository root (folder where this script lives) ---------
Set-Location -Path $PSScriptRoot

# --- 3. Detect current branch -------------------------------------------
$Branch = (Git symbolic-ref --short HEAD | Out-String).Trim()

# --- 4. Check for pending changes ---------------------------------------
Git diff --quiet HEAD
if ($LASTEXITCODE -eq 0) {
    Write-Host "Nothing to sync, repository is up to date." -ForegroundColor Yellow
    exit
}

# --- 5. Build timestamped commit message --------------------------------
$timestamp = Get-Date -Format "yyyy-MM-ddTHH-mm-ss"
$msg       = "Sync $timestamp"

# --- 6. Stage, commit, push ---------------------------------------------
try {
    Git add -A
    Git commit -m $msg
    Git push origin $Branch
    Write-Host "Synchronization completed" -ForegroundColor Green
}
catch {
    Write-Host "Deploy failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}