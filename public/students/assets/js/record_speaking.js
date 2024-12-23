// Ngăn chặn các tổ hợp phím phổ biến mở Developer Tools
// document.addEventListener('keydown', function (event) {
//     if (event.keyCode == 123) { // F12
//         event.preventDefault();
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Ctrl+Shift+I
//         event.preventDefault();
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) { // Ctrl+Shift+J
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 85) { // Ctrl+U
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 83) { // Ctrl+S
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 80) { // Ctrl+P
//         event.preventDefault();
//     }
// });
//Not allow to hightlight
document.addEventListener('selectstart', function (e) {
    if (e.target.classList.contains('body')) {
        e.preventDefault(); // Ngăn chặn việc chọn văn bản cho các phần tử có class 'no-select'
    }
});

// Ngăn chặn chuột phải
document.addEventListener('contextmenu', function (event) {
    event.preventDefault();
});

document.querySelectorAll('textarea').forEach(textarea => {
    // Prevent text selection
    textarea.addEventListener('selectstart', function (e) {
        e.preventDefault();
    });
    // Prevent double-click text selection
    // textarea.addEventListener('mousedown', function (e) {
    //     e.preventDefault();
    // });
});

// Ngăn chặn các hành động copy, cut, paste
document.addEventListener('copy', function (event) {
    event.preventDefault();
});

document.addEventListener('cut', function (event) {
    event.preventDefault();
});

document.addEventListener('paste', function (event) {
    event.preventDefault();
});

// Ngăn chặn chọn văn bản
document.addEventListener('selectstart', function (event) {
    event.preventDefault();
});

// Phương pháp phát hiện Developer Tools
function detectDevTools() {
    const element = new Image();
    Object.defineProperty(element, 'id', {
        get: function () {
            alert('Developer Tools are not allowed.');
            window.location.reload();
        }
    });
    // console.log(element);
}

setInterval(detectDevTools, 1000);

// Ngăn chặn menu chuột phải bổ sung
document.addEventListener('mousedown', function (event) {
    if (event.button === 2 || event.button === 3) {
        event.preventDefault();
    }
});

// Disable text selection CSS
document.documentElement.style.userSelect = 'none';
document.documentElement.style.msUserSelect = 'none';
document.documentElement.style.mozUserSelect = 'none';



