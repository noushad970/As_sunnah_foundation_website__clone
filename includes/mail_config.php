<?php
// SMTP configuration via environment variables for security.
// Set these in your environment or Apache config. No hardcoded credentials.
$SMTP_HOST = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
$SMTP_PORT = (int)(getenv('SMTP_PORT') ?: 587);
$SMTP_USER = getenv('SMTP_USER') ?: 'bmdnoushad@gmail.com';
$SMTP_PASS = getenv('SMTP_PASS') ?: 'kaxymkmdbykbugqe';
$SMTP_FROM = getenv('SMTP_FROM') ?: $SMTP_USER;
$SMTP_FROM_NAME = getenv('SMTP_FROM_NAME') ?: 'My Website';



// SMTP_HOST/PORT: From your email provider’s docs. Gmail: smtp.gmail.com, 587 (TLS) or 465 (SSL).
// SMTP_USER: Your full email address (e.g., you@gmail.com).
// SMTP_PASS: An App Password (not your login password).
// Gmail: Google Account → Security → 2‑Step Verification ON → App passwords → Generate 16‑char password.
// SMTP_FROM: Usually same as SMTP_USER.
// SMTP_FROM_NAME: The display name you want (e.g., As-Sunnah Foundation).
// Set these as environment variables, then restart Apache.