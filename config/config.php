<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'sistema_dashboard_salud';
const DB_USER = 'root';
const DB_PASS = '';

const APP_NAME = 'Panel de Salud';
const BASE_URL = '/';
const UPLOAD_DIR = __DIR__ . '/../uploads';

const ALLOWED_EXTENSIONS = ['xlsx', 'xls', 'xlsm'];
const MAX_UPLOAD_SIZE = 20 * 1024 * 1024; // 20 MB
