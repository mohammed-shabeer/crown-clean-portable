# Crown Clean Vendor Kit — INTERNAL, DO NOT SHIP TO CUSTOMER

This folder contains the vendor-side tools for managing customer activations
of the CrownCleanLaundry portable app. Keep it private.

## Contents

- `vendor-side/private.pem`  — RSA private key (NEVER give to the customer)
- `vendor-side/public.pem`    — matching public key (already embedded in the app)
- `vendor-side/make-activation-key.php` — generates activation codes
- `vendor-side/guard-template.stub` — template used to regenerate LicenseGuard.php
- `vendor-side/refresh-guard-key.php` — rewrites the app's LicenseGuard with the current public key
- `vendor-side/Launcher.cs` — source code for CrownCleanLaundry.exe launcher

## How customer activation works

1. Customer opens the app on their machine (double-click `CrownCleanLaundry.exe`).
2. On first run the app shows an **Activate Application** page with a
   **System ID** (32 hex chars) bound to that machine's Windows MachineGuid.
3. Customer sends you the System ID (phone/WhatsApp/email).
4. You run:

   ```
   php make-activation-key.php <SYSTEM_ID>
   ```

   (use any PHP 8.x; on this dev machine use the bundled one:
   `C:\CrownCleanLaundry\runtime\php\php.exe`)

5. Send the resulting activation code back to the customer.
6. Customer pastes it into the app → app unlocks **on that machine only**.

## Protection properties

- Copying the whole folder to another PC → app locks again (different
  MachineGuid → signature fails).
- Sharing an activation code → useless on any other machine.
- The app contains only the PUBLIC key; codes can only be issued by you.
- Deleting the license file (`app/storage/app/.ccl`) → app re-locks.

## Rebuilding the launcher (if you change Launcher.cs)

```
C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe /target:winexe /platform:anycpu /optimize+ ^
  /out:"C:\CrownCleanLaundry\CrownCleanLaundry.exe" ^
  /reference:System.dll /reference:System.Windows.Forms.dll /reference:System.Drawing.dll ^
  vendor-side\Launcher.cs
```

## MariaDB root password (bundled local DB)

`CrownLocal#2026!Db` — used by the launcher for graceful shutdown only.
The app itself connects as `laundry_user` / `Laundry@2024`.

## NOTE about the droplet

The live droplet (64.227.163.226) still contains the webshell backdoor at
`/var/www/laundry-box/storage/.cache.php` — delete it when doing the final
migration cleanup (we did not touch the live server per your instruction).
