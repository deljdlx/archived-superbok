(function( $ ) {
    $.fn.focusPointPicker = function(callback, options) {

        var cropperSettings = {
            "aspectRatio": null,
            "autoCropArea": 1,
            "strict": true,
            "guides": false,
            "rotatable": false,
            "cropBoxResizable": true,
            "center": false,
            "dragCrop": true
        };

        var dragdrop = {
            "element": null,
            "isDragging": false,
            "currentPosition": {"x": 0, "y": 0},
            "image": null
        };

        var returnCallback = callback;

        var focusPointPickerCursorOffsetX = 22;
        var focusPointPickerCursorOffsetY = 22;

        var updateResult = function(image) {
            var canvasData = $(image).cropper('getCanvasData');
            var data = $(image).cropper('getData');
            returnCallback({
                "focusPoint": {
                    'x': Math.abs(Math.round((($(image).data('focusPoint').offsetLeft + focusPointPickerCursorOffsetX) - canvasData.left) / canvasData.width * 100)),
                    'y': Math.abs(Math.round((($(image).data('focusPoint').offsetTop + focusPointPickerCursorOffsetY) - canvasData.top) / canvasData.height * 100))
                },
                "cropZone": {
                    "x": Math.round(data.x, 0),
                    "y": Math.round(data.y, 0),
                    "w": Math.round(data.width, 0),
                    "h": Math.round(data.height, 0)
                }
            });
        };

        var init = function(img) {

            // remove precedent picker
            if ($(img).next().hasClass('focusPointPickerContainer')) {
                $(img).next().remove();
                $(img).show();
            }

            // Container
            var container = document.createElement('DIV');
            $(container).addClass('focusPointPickerContainer');
            container.style.width = img.offsetWidth + 'px';
            container.style.height = img.offsetHeight + 'px';
            container.style.position = 'relative';

            // Image
            var image = document.createElement("IMG");
            image.style.width = img.offsetWidth + 'px';
            image.style.height = img.offsetHeight + 'px';
            image.src = img.src;
            $(container).append(image);

            // FocusPoint
            var focusPoint = document.createElement("DIV");
            $(image).data('focusPoint', focusPoint);
            $(focusPoint).addClass('focusPointPickerCrosshair');
            focusPoint.style.left = (img.offsetWidth / 2 - focusPointPickerCursorOffsetX) + 'px';
            focusPoint.style.top = (img.offsetHeight / 2 - focusPointPickerCursorOffsetY) + 'px';
            $(container).append(focusPoint);

            // Commit in DOM
            $(img).after(container);
            $(img).hide();
            $(image).cropper(cropperSettings);

            // FocusPoint drag n drop management
            $(focusPoint).on('mousedown', function(event) {
                dragdrop.isDragging = true;
                dragdrop.element = event.target;
                dragdrop.currentPosition.x = event.clientX;
                dragdrop.currentPosition.y = event.clientY;
                dragdrop.image = image;
                event.preventDefault();
            });
            $(container).on('mousemove', function(event) {
                if (dragdrop.isDragging) {
                    var newLeft = dragdrop.element.offsetLeft + event.clientX - dragdrop.currentPosition.x;
                    var newTop = dragdrop.element.offsetTop + event.clientY - dragdrop.currentPosition.y;
                    if (
                        newLeft < -focusPointPickerCursorOffsetX || newLeft > (container.offsetWidth - focusPointPickerCursorOffsetX)
                        || newTop < -focusPointPickerCursorOffsetY || newTop > (container.offsetHeight -focusPointPickerCursorOffsetY)
                    ) {
                        event.preventDefault();
                        return;
                    }
                    dragdrop.element.style.left = (dragdrop.element.offsetLeft + event.clientX - dragdrop.currentPosition.x) + 'px';
                    dragdrop.element.style.top = (dragdrop.element.offsetTop + event.clientY - dragdrop.currentPosition.y) + 'px';
                    dragdrop.currentPosition.x = event.clientX;
                    dragdrop.currentPosition.y = event.clientY;
                    event.preventDefault();
                }
            });
            $(container).on('mouseup', function(event) {
                if (dragdrop.isDragging) {
                    event.preventDefault();
                    updateResult(dragdrop.image);
                }
                dragdrop.isDragging = false;
            });

            // Crop modification management
            $(image).on('cropend.cropper', function (event) {updateResult(this);});
            $(image).on('zoom.cropper', function (event) {updateResult(this);});

            $(image).on('built.cropper', function (event) {updateResult(this);});

            return img;
        };

        return this.filter("img").each(function() {

            if (!this.complete) {
                $(this).load(function() {
                    init(this);
                });
            } else {
                init(this);
            }
            return this;
        });



    };
}( jQuery ));
