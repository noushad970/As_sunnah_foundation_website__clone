<?php
// SMTP configuration via environment variables for security.
// Set these in your environment or Apache config. No hardcoded credentials.
$SMTP_HOST = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
$SMTP_PORT = (int)(getenv('SMTP_PORT') ?: 587);
$SMTP_USER = getenv('SMTP_USER') ?: 'bmdnoushad@gmail.com';
$SMTP_PASS = getenv('SMTP_PASS') ?: 'kaxymkmdbykbugqe';
$SMTP_FROM = getenv('SMTP_FROM') ?: $SMTP_USER;
$SMTP_FROM_NAME = getenv('SMTP_FROM_NAME') ?: 'My Website';
