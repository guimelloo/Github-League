# Production Troubleshooting Guide - Badges Not Displaying

## Quick Diagnosis 

Run this command in production to diagnose the issue:

```bash
php artisan diagnose:badges {username}
```

Example:
```bash
php artisan diagnose:badges guimelloo
```

This will show:
- Profile data (score, top language, language_scores)
- Raw database values
- All languages and their scores  
- Languages with 100k+ (should show ✓)
- JSON validity
- API response simulation

## Common Issues & Solutions

### Issue 1: language_scores appears but empty
**Cause**: Users weren't migrated with new scoring formula
**Solution**: Run the migration command
```bash
php artisan scores:migrate
```

### Issue 2: language_scores is NULL in database
**Cause**: Users never had their scores calculated
**Solution**: 
1. Trigger a GitHub refresh for the user
2. Or manually run the update command
```bash
php artisan score:update-daily
```

### Issue 3: language_scores shows but diagnose shows "No languages with 100k+"
**Cause**: User scores are too low
**Solution**: This is expected if user truly doesn't have 100k+ in any language. Badges appears only at 100k+.

### Issue 4: Diagnose shows 5 languages with 100k+ but frontend shows nothing
**Cause**: 
- Frontend assets not rebuilt
- Browser cache
- Inertia not serializing data correctly

**Solution**:
```bash
# 1. Rebuild frontend
npm ci
npm run build

# 2. Clear any caches
php artisan cache:clear
php artisan view:clear  

# 3. Restart app
# (depends on your deployment setup)
```

## Complete Production Fix Sequence

If badges aren't showing for users with 1M+ points:

```bash
# 1. Check if user data looks correct
php artisan diagnose:badges {username}

# 2. If language_scores is empty or showing low values
php artisan scores:migrate

# 3. If that didn't help, update all scores
php artisan score:update-daily

# 4. Rebuild frontend
npm ci
npm run build

# 5. Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 6. Test by visiting /dashboard and checking browser console
# (F12 to open console)
```

## What to Check

1. **Database**: 
```sql
SELECT github_username, score, top_language, language_scores 
FROM github_profiles 
WHERE github_username = 'username';
```

2. **Frontend**: Press F12 in browser and check:
   - Network tab: Is `/dashboard` returning correct data?
   - Console: Any JavaScript errors?
   - Application tab: Check React/Vue state if available

3. **Logs**: Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

## If All Else Fails

1. Manually check the raw SQL:
```sql
SELECT COUNT(*) FROM github_profiles 
WHERE JSON_EXTRACT(language_scores, '$') IS NOT NULL 
AND JSON_LENGTH(language_scores) > 0;
```

2. Check if language_scores is being cast correctly:
```bash
php artisan tinker
# Then in tinker:
> $p = App\Models\GithubProfile::first()
> var_dump($p->language_scores)
```

3. Verify JSON structure in database is valid:
```sql
SELECT github_username, JSON_VALID(language_scores) as is_valid 
FROM github_profiles;
```
