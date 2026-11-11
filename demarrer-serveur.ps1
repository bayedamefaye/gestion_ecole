$ErrorActionPreference = 'Stop'

$project = $PSScriptRoot
$php = 'C:\xampp\php\php.exe'

if (-not (Test-Path -LiteralPath $php)) {
    throw "PHP introuvable ici : $php"
}

Set-Location -LiteralPath $project
$securePassword = Read-Host 'Entre le mot de passe du site' -AsSecureString
$passwordPointer = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($securePassword)
try {
    $password = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($passwordPointer)
    $env:SCHOOL_PASSWORD_TEMP = $password
    $env:SCHOOL_ADMIN_PASSWORD_HASH = (& $php -r 'echo password_hash(getenv($argv[1]), PASSWORD_DEFAULT);' SCHOOL_PASSWORD_TEMP)
} finally {
    Remove-Item Env:SCHOOL_PASSWORD_TEMP -ErrorAction SilentlyContinue
    [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($passwordPointer)
    Remove-Variable password -ErrorAction SilentlyContinue
}

if ($LASTEXITCODE -ne 0 -or [string]::IsNullOrWhiteSpace($env:SCHOOL_ADMIN_PASSWORD_HASH)) {
    throw 'PHP n''a pas pu creer le hash du mot de passe.'
}

Write-Host 'Serveur pret : http://localhost:8000/' -ForegroundColor Green
Write-Host 'Garde cette fenetre ouverte. Pour arreter le serveur, appuie sur Ctrl+C.'
& $php -S localhost:8000 -t $project
