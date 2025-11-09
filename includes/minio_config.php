<?php
// MinIO settings stub. This file is updated by the Admin panel (MinIO Settings).
// You can also set values via environment variables (see MINIO_SETUP.md).
// If $inc array is not initialized yet, create it.
if (!isset($inc) || !is_array($inc)) { $inc = []; }

// Status: '1' to enable MinIO as active provider
$inc['minio_status'] = '0';

// Connection details
$inc['minio_bucket'] = '';
$inc['minio_region'] = 'us-east-1';
$inc['minio_key'] = '';
$inc['minio_secret_key'] = '';
$inc['minio_endpoint'] = '';

// Optional public base URL (defaults to ENDPOINT/BUCKET/ if empty)
$inc['minio_public_base'] = '';

// Flags
$inc['minio_path_style'] = '1';
$inc['minio_ssl_verify'] = '1';

