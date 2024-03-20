jQuery(document).ready(function($){

    // Добавление нового изображения в галерею
    $('#additional-images').on('click', function(e){
        e.preventDefault();

        var customUploader = wp.media({
            title: 'Выберите изображение',
            button: {
                text: 'Загрузить',
            },
            multiple: true // Установите в false, чтобы разрешить только одно изображение
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').toJSON();
            var galleryContainer = $('#realty-gallery-preview');
            var galleryInput = $('#realty-gallery');

            galleryContainer.empty(); // Очищаем контейнер перед добавлением новых изображений

            // Перебираем выбранные изображения и добавляем их в галерею
            $.each(attachment, function(index, image) {
                galleryContainer.append('<li>'+image.title+'<br>'+image.filename+'</li>');

                // Добавляем ID изображения в поле input
                if(galleryInput.val() !== '') {
                    galleryInput.val(galleryInput.val()+','+image.id);
                } else {
                    galleryInput.val(image.id);
                }
            });
        }).open();
 
    });

});