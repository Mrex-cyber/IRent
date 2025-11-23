Write-Host "Clearing Vite cache..." -ForegroundColor Yellow
Remove-Item -Recurse -Force node_modules\.vite -ErrorAction SilentlyContinue
Write-Host "Vite cache cleared!" -ForegroundColor Green
Write-Host ""
Write-Host "Please restart the dev server with: npm run dev" -ForegroundColor Cyan
Write-Host "Then hard refresh your browser with Ctrl+Shift+R or Ctrl+F5" -ForegroundColor Cyan

