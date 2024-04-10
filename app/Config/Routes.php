<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'PdfController::index');

$routes->match(['get', 'post'], 'PdfController/htmlToPDF', 'PdfController::htmlToPDF');

$routes->get('/generate-pdfs', 'PdfController::generatePDFs');

$routes->get('/generate-qrcodes', 'QrCodeController::generateQrCode');
