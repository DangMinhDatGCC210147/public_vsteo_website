import AudioMotionAnalyzer from 'https://cdn.skypack.dev/audiomotion-analyzer?min';

let recorder; // Global scope recorder
let audioMotion, audioContext, gainNode;

export function startRecording(duration, questionElement, speakingPart, testId) {
    const questionId = questionElement.getAttribute('data-question-id');
    const startButton = questionElement.querySelector('.startRecording');
    const stopButton = questionElement.querySelector('.stopRecording');
    const audioPlayback = questionElement.querySelector('.audioPlayback');
    const skillId = questionElement.getAttribute('data-skill-id');

    console.log("Start Recording for Question ID: " + questionId + ", Skill ID: " + skillId);
    console.log("Start Recording for Speaking Part: " + speakingPart);
    console.log("Start Recording for Test ID: " + testId);

    navigator.mediaDevices.getUserMedia({ audio: true })
    .then(stream => {
        initializeAudioMotion(stream);  // Khởi tạo AudioMotion mới

        const options = {
            mimeType: 'audio/mp3',
            recorderType: RecordRTC.StereoAudioRecorder,
            desiredSampRate: 16000
        };
        recorder = new RecordRTC(stream, options);
        recorder.startRecording();

        startButton.disabled = true;
        stopButton.disabled = false;

        setTimeout(() => {
            recorder.stopRecording(() => {
                const audioBlob = recorder.getBlob();
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayback.src = audioUrl;
                audioPlayback.hidden = true;

                let formData = new FormData();
                formData.append('recording', new File([audioBlob], "recording.mp3", { type: 'audio/mp3' }));
                formData.append('skill_id', skillId);
                formData.append('question_id', questionId);
                formData.append('test_id', testId);
                fetch('/saveRecording', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    if (speakingPart === 5) {
                        var testResultUrl = `/students/tests/${testId}/results`;
                        endSession(testId);
                        Swal.fire({
                            html: `
                                <h3>Bạn đã hoàn thành bài kiểm tra</h3>
                                <h4>Hệ thống sẽ nộp bài tự động</h4>
                            `,
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#save-btn").click();
                                localStorage.clear();
                                location.reload();
                                setTimeout(function() {
                                    window.location.href = testResultUrl;
                                }, 500); // Chờ 500 ms
                            }
                        });
                    }
                })
                .catch(error => console.error('Error:', error));

                cleanupResources(); // Clean up resources
            });
            stopButton.disabled = true;
        }, duration * 1000);
    })
    .catch(error => console.error('Error:', error));
}
function endSession(testId) {
    const url = `/students/tests/${testId}/session/end`;
    fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content')
            }
        })
        .then(response => response.json())
        .then(data => console.log("Session ended:", data))
        .catch(error => console.error("Error ending session:", error));
}

function initializeAudioMotion(stream) {
    const audioMotionContainer = document.getElementById('audioMotion');
    if (audioMotionContainer.firstChild) {
        audioMotionContainer.removeChild(audioMotionContainer.firstChild); // Loại bỏ element cũ nếu có
    }

    // Đảm bảo rằng audioContext và gainNode được khởi tạo lại mỗi khi ghi âm mới
    audioContext = new AudioContext();
    gainNode = audioContext.createGain();
    gainNode.gain.setValueAtTime(0, audioContext.currentTime); // Tắt âm lượng

    const source = audioContext.createMediaStreamSource(stream);
    source.connect(gainNode); // Kết nối source đến gainNode
    // Không kết nối gainNode đến audioContext.destination

    audioMotion = new AudioMotionAnalyzer(audioMotionContainer, {
        audioCtx: audioContext,
        source: source,
        mode: 'classic', // Use 'classic' preset mode
        gradient: 'prism', // Use a gradient similar to your image
        height: 250,
        showScaleY: true,
        barSpace: 0.05, // Adjust bar spacing
        barWidth: 10, // Adjust bar width
        fftSize: 2048, // Use larger FFT size for higher resolution
        minFreq: 30, // Minimum frequency
        maxFreq: 20000, // Maximum frequency
        lumiBars: true, // Use luminous bars
        showBgColor: true, // Background color for bars
        showScaleX: true, // Show X-axis scale
        showScaleY: true, // Show Y-axis scale
        showPeaks: true, // Show peaks
        isBandsMode: true,
    });

    audioMotion.disconnectOutput(); // Ngắt kết nối đầu ra để không phát âm thanh trực tiếp
}

export function cleanupResources() {
    if (audioMotion) {
        audioMotion.disconnectOutput(); // Ngắt kết nối đầu ra nếu chưa thực hiện
    }
    if (audioContext) {
        audioContext.close().then(() => {
            console.log("AudioContext closed.");
            const audioMotionElement = document.getElementById('audioMotion');
            if (audioMotionElement) {
                audioMotionElement.innerHTML = ''; // Xóa nội dung phần tử để reset
            }

            audioMotion = null; // Clear the reference only after AudioContext is closed
            audioContext = null; // Clear audioContext reference
            gainNode = null; // Clear gainNode reference
        });
    }
}

window.startRecording = startRecording;
window.cleanupResources = cleanupResources;
