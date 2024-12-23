document.addEventListener('DOMContentLoaded', function () {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var captureButton = document.getElementById('capture');
    var formElement = document.getElementById('imageUploadForm');
    var isCapturing = false;

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            video.srcObject = stream;
            video.play();
        })
        .catch(function (error) {
            console.error("Không thể truy cập webcam", error);
            toastr.error('Không thể truy cập webcam. Vui lòng cho phép truy cập webcam');
        });

    captureButton.addEventListener('click', function () {
        if (!isCapturing) {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.style.display = 'block';
            video.style.display = 'none';

            canvas.toBlob(function(blob) {
                var formData = new FormData(formElement);
                formData.append('image', blob);

                fetch(formElement.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    toastr.success('Hình ảnh đã được lưu thành công! Nếu muốn thay đổi hãy bấm nút chụp lại.');
                    captureButton.textContent = 'Chụp lại';
                    captureButton.className = 'btn btn-warning mt-3';
                    isCapturing = true;
                    isUpdate = true; // Cập nhật trạng thái để lần sau gọi hàm update
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Không thể lưu hình ảnh!');
                });
            }, 'image/jpeg');
        } else {
            video.style.display = 'block';
            canvas.style.display = 'none';
            captureButton.textContent = 'Chụp hình';
            captureButton.className = 'btn btn-danger mt-3';
            isCapturing = false;
            isUpdate = false; // Đặt lại trạng thái update
        }
    });
});
