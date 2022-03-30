<?php
use TianRosandhy\Autocrud\Http\Controllers\ComponentController;

Route::post('file-manager', [ComponentController::class, 'fileManager'])->name('autocrud.filemanager');
Route::post('media-upload', [ComponentController::class, 'mediaUpload'])->name('autocrud.media');
Route::post('remove-media/{id?}', [ComponentController::class, 'removeMedia'])->name('autocrud.media.remove');
Route::match(['get', 'post'], 'media/get-image-url', [ComponentController::class, 'getImageUrl'])->name('autocrud.media.get-image-url');
Route::post('post-document', [ComponentController::class, 'postDocument'])->name('autocrud.post-document');
Route::post('delete-document', [ComponentController::class, 'deleteDocument'])->name('autocrud.delete-document');
