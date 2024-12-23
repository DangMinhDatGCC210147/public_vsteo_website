document.addEventListener('DOMContentLoaded', function() {
    let recordButton = document.getElementById('recordButton');
    let playbackButton = document.getElementById('playbackButton');
    let audioPlayback = document.getElementById('audioPlayback');

    let mediaRecorder;
    let audioChunks = [];

    // Kiểm tra xem trình duyệt có hỗ trợ MediaRecorder không
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert("Trình duyệt của bạn không hỗ trợ thu âm!");
        return;
    }

    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            mediaRecorder = new MediaRecorder(stream);

            // Thu âm khi nhấn nút "Thu âm"
            recordButton.addEventListener('click', () => {
                if (mediaRecorder.state === "recording") {
                    mediaRecorder.stop();
                    recordButton.textContent = 'Thu âm lại';
                    recordButton.className = 'btn btn-warning mt-3 m-1'
                } else {
                    mediaRecorder.start();
                    recordButton.textContent = 'Dừng lại';
                    playbackButton.disabled = true;
                }
            });

            mediaRecorder.ondataavailable = event => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayback.src = audioUrl;
                playbackButton.disabled = false;
                audioChunks = [];
            };
        })
        .catch(error => {
            console.error("Không thể truy cập microphone: ", error);
            alert("Không thể truy cập microphone, vui lòng kiểm tra cài đặt của bạn!");
        });

    // Phát lại âm thanh khi nhấn nút "Nghe lại"
    playbackButton.addEventListener('click', () => {
        audioPlayback.play();
    });
});
