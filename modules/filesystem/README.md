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