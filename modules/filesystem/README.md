public function rules()
{
    return [
        [['images'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
    ];
}

<?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>


public function behaviors()
{
    return [
        'files' => [
            'class' => FilesBehavior::className(),
            'attributes' => [
                'image' => ['path' => '@webroot/uploads/products', 'fileName' => '{model.id}.{file.extension}'],
                'image' => ['path' => '@webroot/uploads/products', 'fileName' => function ($filePath, $model) {
                        return Inflector::slug($model->getTitle()) . '.{file.extension}';
                    }],
                'image' => ['path' => '@webroot/uploads/products', 'fileName' => '{model.slug}.{file.extension}'],
                'images' => ['multiple' => true, 'path' => '@webroot/uploads/files', 'fileName' => '{model.slug}-{file.index}.{file.extension}'],
                'images' => ['multiple' => true, 'filesystem' => 'localFs', 'fileName' => '{model.slug}-{file.index}.{file.extension}'],
                'images' => ['filesystem' => 'local', 'path' => '/products/images', 'fileName' => '{model.slug}-{file.index}.{file.extension}', 'multiple' => true],
                'video' => ['filesystem' => 'local', 'path' => '/products/videos', 'fileName' => '{model.slug}-{file.index}.{file.extension}'],
                'video' => ['filesystem' => 'dropbox', 'path' => '/uploads', 'fileName' => '{model.id}-{file.index}.{file.extension}']
            ]
        ]
    ];
}


https://github.com/trntv/yii2-file-kit


Flysystem public url
https://github.com/SmartestEdu/FlysystemPublicUrlPlugin
http://return-true.com/uploading-directly-to-amazon-s3-from-your-laravel-5-application/
http://code.htmlasks.com/laravel_5_use_storage_facade_to_get_file_path
http://laravelcoding.com/blog/laravel-5-beauty-upload-manager
http://cgit.drupalcode.org/flysystem_dropbox/tree/src/Flysystem/Dropbox.php?h=8.x-1.x